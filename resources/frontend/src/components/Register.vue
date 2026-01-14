<template>
    <section class="auth-card">
        <header class="auth-header">
            <h1 class="auth-title">Регистрация</h1>
            <p class="auth-subtitle">Создайте аккаунт, чтобы отправлять код на ревью.</p>
        </header>

        <form class="auth-form" @submit.prevent="register">
            <div class="auth-field">
                <label class="auth-label" for="name">Имя</label>
                <input
                    id="name"
                    class="auth-input"
                    type="text"
                    v-model="name"
                    placeholder="Иван"
                    autocomplete="name"
                    required
                >
            </div>

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
                    autocomplete="new-password"
                    required
                >
            </div>

            <div class="auth-field">
                <label class="auth-label" for="password_confirmation">Подтверждение пароля</label>
                <input
                    id="password_confirmation"
                    class="auth-input"
                    type="password"
                    v-model="passwordConfirmation"
                    placeholder="••••••••"
                    autocomplete="new-password"
                    required
                >
            </div>

            <div v-if="errorMessage" class="auth-alert auth-alert--error">
                {{ errorMessage }}
            </div>

            <div class="auth-actions">
                <button class="auth-btn" type="submit" :disabled="isLoading">
                    <span v-if="isLoading" class="auth-spinner" aria-hidden="true"></span>
                    {{ isLoading ? 'Создаём…' : 'Зарегистрироваться' }}
                </button>
                <router-link class="auth-link" to="/login">
                    Уже есть аккаунт? Вход
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
            name: '',
            email: '',
            password: '',
            passwordConfirmation: '',
            isLoading: false,
            errorMessage: null,
        }
    },
    methods: {
        async register() {
            this.isLoading = true;
            this.errorMessage = null;

            if (this.password !== this.passwordConfirmation) {
                this.isLoading = false;
                this.errorMessage = 'Пароли не совпадают';
                return;
            }

            try {
                await http.post('/api/auth/register', {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.passwordConfirmation,
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
                    'Ошибка регистрации';
                this.errorMessage = msg;
                console.error('Register failed', error.response?.data ?? error);
            } finally {
                this.isLoading = false;
            }
        }
    }
}
</script>

<style scoped src="@/assets/components/auth.css"></style>
