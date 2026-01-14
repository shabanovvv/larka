<template>
    <section class="auth-card">
        <header class="auth-header">
            <h1 class="auth-title">Вход</h1>
            <p class="auth-subtitle">Введите email и пароль, чтобы продолжить.</p>
        </header>

        <form class="auth-form" @submit.prevent="login">
            <div class="auth-field">
                <label class="auth-label" for="email">Email</label>
                <input
                    id="email"
                    class="auth-input"
                    type="email"
                    v-model="email"
                    placeholder="name@example.com"
                    autocomplete="email"
                    required
                >
            </div>

            <div class="auth-field">
                <label class="auth-label" for="password">Пароль</label>
                <input
                    id="password"
                    class="auth-input"
                    type="password"
                    v-model="password"
                    placeholder="••••••••"
                    autocomplete="current-password"
                    required
                >
            </div>

            <div v-if="errorMessage" class="auth-alert auth-alert--error">
                {{ errorMessage }}
            </div>

            <div class="auth-actions">
                <button class="auth-btn" type="submit" :disabled="isLoading">
                    <span v-if="isLoading" class="auth-spinner" aria-hidden="true"></span>
                    {{ isLoading ? 'Входим…' : 'Войти' }}
                </button>
                <router-link class="auth-link" to="/register">
                    Нет аккаунта? Регистрация
                </router-link>
            </div>
        </form>
    </section>
</template>

<script>
import http from '@/http';

export default {
    data() {
        return {
            email: '',
            password: '',
            isLoading: false,
            errorMessage: null,
        }
    },
    methods: {
        async login() {
            try {
                this.isLoading = true;
                this.errorMessage = null;

                await http.post('/api/auth/login', {
                    email: this.email,
                    password: this.password,
                }, {
                    headers: { Accept: 'application/json' }
                });
                this.$emit('auth-changed');
                this.$router.push('/profile');
            } catch (error) {
                const msg =
                    error?.response?.data?.message ||
                    (typeof error?.response?.data === 'string' ? error.response.data : null) ||
                    error?.message ||
                    'Ошибка входа';
                this.errorMessage = msg;
                console.error('Login failed', error.response?.data ?? error);
            } finally {
                this.isLoading = false;
            }
        }
    }
}
</script>

<style scoped src="@/assets/components/auth.css"></style>
