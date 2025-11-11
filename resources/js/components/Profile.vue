<template>
    <div class="profile">
        <h1>Профиль пользователя</h1>
        <div v-if="user">
            <p><strong>Имя:</strong> {{ user.name }}</p>
            <p><strong>Email:</strong> {{ user.email }}</p>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            user: null
        }
    },
    async mounted() {
        // Заглушка - в реальном приложении брать ID из авторизации
        const response = await fetch('/api/user/1');
        const data = await response.json();
        this.user = data.user;
    }
}
</script>
