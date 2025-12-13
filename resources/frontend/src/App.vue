<template>
    <div>
        <nav>
            <router-link to="/">Главная</router-link>
            <router-link v-if="!isAuthenticated" to="/login">Вход</router-link>
            <router-link v-if="!isAuthenticated" to="/register">Регистрация</router-link>

            <template v-else>
                <router-link to="/profile">Профиль</router-link>
                <button @click="logout">Выйти</button>
            </template>
        </nav>

        <main class="p-4">
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
