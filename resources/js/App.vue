<template>
    <div>
        <nav class="p-4 border-b flex gap-4">
            <router-link to="/">Главная</router-link>
            <router-link to="/login">Вход</router-link>
            <router-link to="/register">Регистрация</router-link>
            <router-link to="/profile">Профиль</router-link>
            <router-link to="/about">О нас</router-link>
        </nav>
        <main class="p-4">
            <router-view />
        </main>
    </div>
</template>

<script>
export default {}
</script>
