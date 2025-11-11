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
export default {
    data() {
        return {
            email: '',
            password: ''
        }
    },
    methods: {
        login() {
            // Заглушка для демонстрации
            alert('Форма входа (будет реализовано позже)');
        }
    }
}
</script>
