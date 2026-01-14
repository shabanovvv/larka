<script>
    import http from "@/http.js";

    const POLL_INTERVAL_MS = 5000;

    export default {
        data() {
            return {
                technologies: [],
                form: {
                    technologyIds: [],
                    code: '',
                },
                ui: {
                    isSubmitting: false,
                    error: null,
                    success: null,
                    submissionId: null,
                    status: null,
                    lastCheckedAt: null, // timestamp (ms)
                },
                poll: {
                    timer: null,
                },
            }
        },
        computed: {
            selectedTechnologies() {
                const ids = new Set(this.form.technologyIds ?? []);
                return (this.technologies ?? []).filter(t => ids.has(t.id));
            },
            codeChars() {
                return (this.form.code ?? '').length;
            },
            lastCheckedLabel() {
                if (!this.ui.lastCheckedAt) return null;
                try {
                    return new Date(this.ui.lastCheckedAt).toLocaleTimeString();
                } catch {
                    return null;
                }
            },
        },
        methods: {
            async fetchAllTechnologies() {
                http.get('/api/technologies')
                    .then(response => {
                        this.technologies = response.data;
                    })
                    .catch(error => {
                        console.error('Ошибка загрузки технологий:', error);
                    });
            },

            stopPolling() {
                if (this.poll.timer) {
                    clearTimeout(this.poll.timer);
                    this.poll.timer = null;
                }
            },

            scheduleNextPoll() {
                this.stopPolling();
                this.poll.timer = setTimeout(() => {
                    this.checkResultAiAnalysis();
                }, POLL_INTERVAL_MS);
            },

            async submitForm(event) {
                event.preventDefault();
                this.ui.isSubmitting = true;
                this.ui.error = null;
                this.ui.success = null;
                this.ui.submissionId = null;
                this.ui.status = null;
                this.ui.lastCheckedAt = null;
                this.stopPolling();

                // лёгкая валидация на клиенте (чтобы UI был дружелюбнее)
                if (!Array.isArray(this.form.technologyIds) || this.form.technologyIds.length === 0) {
                    this.ui.error = new Error('Выберите хотя бы одну технологию');
                    this.ui.isSubmitting = false;
                    return;
                }
                if (!this.form.code || this.form.code.trim().length < 3) {
                    this.ui.error = new Error('Код слишком короткий');
                    this.ui.isSubmitting = false;
                    return;
                }

                try {
                    const response = await http.post('/api/code-submission', {
                        technologyId: this.form.technologyIds,
                        code: this.form.code
                    });

                    // очищаем форму, но оставляем submissionId для просмотра результата
                    this.ui.submissionId = response?.data?.id ?? null;
                    this.form.technologyIds = [];
                    this.form.code = '';

                    if (!this.ui.submissionId) {
                        this.ui.success = 'Отправлено';
                        return;
                    }

                    // Сразу показываем "в процессе" и делаем первый запрос без ожидания 5 секунд.
                    this.ui.status = 'ai_processing';
                    this.ui.success = 'AI в обработке ревью №' + this.ui.submissionId;
                    await this.checkResultAiAnalysis();
                } catch (error) {
                    this.ui.error = error;
                    console.error('Ошибка отправки формы ревью кода:', error);
                } finally {
                    this.ui.isSubmitting = false;
                }
            },

            async checkResultAiAnalysis() {
                try {
                    if (!this.ui.submissionId) return;

                    const response = await http.get('/api/code-submission/'+this.ui.submissionId+'/status');
                    this.ui.lastCheckedAt = Date.now();
                    this.ui.status = response?.data?.status ?? null;
                    if (response.data.status === 'ai_ready') {
                        this.ui.success = 'AI успешно обработал ревью №' + this.ui.submissionId;
                        this.stopPolling();
                    } else if (response.data.status === 'ai_processing') {
                        this.ui.success = 'AI в обработке ревью №' + this.ui.submissionId;
                        this.scheduleNextPoll();
                    } else if (response.data.status === 'ai_failed') {
                        this.ui.error = new Error('AI не смог обработать ревью №' + this.ui.submissionId);
                        this.stopPolling();
                    }
                    console.log(response);
                } catch (error) {
                    this.ui.error = error;
                    this.ui.lastCheckedAt = Date.now();
                    this.stopPolling();
                }
            }
        },
        mounted() {
            this.fetchAllTechnologies();
        },
        beforeUnmount() {
            this.stopPolling();
        }
    }
</script>

<template>
    <section class="cs-card">
        <header class="cs-header">
            <div>
                <h2 class="cs-title">Отправка кода на AI‑ревью</h2>
                <p class="cs-subtitle">
                    Выберите технологии и вставьте код. После отправки анализ выполнится в фоне.
                </p>
            </div>
        </header>

        <form class="cs-form" method="post" @submit="submitForm">
            <div class="cs-field">
                <label class="cs-label">Технологии</label>
                <div class="cs-help">Можно выбрать несколько.</div>

                <select class="cs-select" v-model="form.technologyIds" multiple size="6">
                    <option v-if="technologies.length === 0" disabled>Загрузка...</option>
                    <option v-for="technology in technologies"
                            :key="technology.id"
                            :value="technology.id">
                        {{ technology.name }}
                    </option>
                </select>

                <div v-if="selectedTechnologies.length" class="cs-chips" aria-label="Выбранные технологии">
                    <span class="cs-chip" v-for="t in selectedTechnologies" :key="t.id">
                        {{ t.name }}
                    </span>
                </div>
            </div>

            <div class="cs-field">
                <div class="cs-row">
                    <label class="cs-label">Код</label>
                    <div class="cs-meta">{{ codeChars }} символов</div>
                </div>
                <textarea
                    class="cs-textarea"
                    v-model="form.code"
                    rows="12"
                    placeholder="Вставьте сюда код..."
                    spellcheck="false"
                    autocapitalize="off"
                    autocomplete="off"
                    autocorrect="off"
                />
            </div>

            <div v-if="ui.error" class="cs-alert cs-alert--error">
                {{ ui.error?.response?.data?.message ?? ui.error?.message ?? 'Ошибка отправки' }}
            </div>

            <div v-if="ui.success" class="cs-alert cs-alert--success">
                {{ ui.success }}
                <div v-if="ui.submissionId && ui.status === 'ai_processing'" class="cs-poll">
                    <span class="cs-poll__dot" aria-hidden="true"></span>
                    <span class="cs-poll__text">
                        обновляем каждые 5 сек
                        <span v-if="lastCheckedLabel"> • последняя проверка {{ lastCheckedLabel }}</span>
                    </span>
                </div>
            </div>

            <div class="cs-actions">
                <button class="cs-btn" type="submit" :disabled="ui.isSubmitting">
                    <span v-if="ui.isSubmitting" class="cs-spinner" aria-hidden="true"></span>
                    {{ ui.isSubmitting ? 'Отправка…' : 'Отправить на ревью' }}
                </button>
                <button
                    class="cs-btn cs-btn--ghost"
                    type="button"
                    :disabled="ui.isSubmitting"
                    @click="form.technologyIds = []; form.code = ''; ui.error = null; ui.success = null; ui.submissionId = null;"
                >
                    Очистить
                </button>
            </div>
        </form>
    </section>
</template>

<style scoped src="@/assets/components/code-submission-form.css"></style>
