<template>
    <div class="layout">
        <nav class="nav">
            <div class="nav__inner">
                <router-link to="/" class="nav__brand">AI REVIEWER</router-link>

                <div class="nav__links">
                    <router-link to="/" class="nav__link">Главная</router-link>
                    <router-link v-if="isAuthenticated" to="/profile" class="nav__link">Профиль</router-link>
                    <router-link v-if="isAuthenticated" to="/code-submission" class="nav__link">AI анализ</router-link>
                </div>

                <div class="nav__actions">
                    <router-link v-if="!isAuthenticated" to="/login" class="nav__link nav__link--button">
                        Вход
                    </router-link>
                    <router-link v-if="!isAuthenticated" to="/register" class="nav__link nav__link--button nav__link--ghost">
                        Регистрация
                    </router-link>

                    <button v-else class="nav__button" @click="logout">
                        Выйти
                    </button>
                </div>
            </div>
        </nav>

        <main class="content">
            <router-view @auth-changed="checkAuth" />
        </main>
    </div>
</template>

<script>
import http from '@/http';

export default {
    data: () => ({
        isAuthenticated: false,
        user: null,
    }),
    async created() {
        await this.checkAuth();
    },
    methods: {
        async checkAuth() {
            try {
                const { data } = await http.get('/api/profile');
                this.user = data.user ?? data;
                this.isAuthenticated = true;
            } catch {
                this.isAuthenticated = false;
                this.user = null;
            }
        },
        async logout() {
            try {
                await http.post('/api/auth/logout', null, {
                    headers: { Accept: 'application/json' },
                });
                this.isAuthenticated = false;
                this.user = null;
                await this.checkAuth();
                this.$router.push('/login');
            } catch (error) {
                console.error('Logout failed', error.response?.data ?? error);
            }
        },
    },
};
</script>
