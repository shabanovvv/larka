<template>
    <div class="register">
        <h1>Регистрация</h1>
        <form @submit.prevent="register">
            <input type="text" v-model="name" placeholder="Имя" required>
            <input type="email" v-model="email" placeholder="Email" required>
            <input type="password" v-model="password" placeholder="Пароль" required>
            <button type="submit">Зарегистрироваться</button>
        </form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            name: '',
            email: '',
            password: ''
        }
    },
    methods: {
        register() {
            alert('Форма регистрации (прототип)');
        }
    }
}
</script>
