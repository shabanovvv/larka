<template>
    <div class="login">
        <h1>Вход в систему</h1>
        <form @submit.prevent="login">
            <input type="email" v-model="email" placeholder="Email" required>
            <input type="password" v-model="password" placeholder="Пароль" required>
            <button type="submit">Войти</button>
        </form>
    </div>
</template>

<script>
import http from '@/http';

export default {
    data() {
        return {
            email: '',
            password: ''
        }
    },
    methods: {
        async login() {
            try {
                await http.post('/api/auth/login', {
                    email: this.email,
                    password: this.password,
                }, {
                    headers: { Accept: 'application/json' }
                });
                this.$emit('auth-changed');
                this.$router.push('/profile');
            } catch (error) {
                console.error('Login failed', error.response?.data ?? error);
            }
        }
    }
}
</script>
