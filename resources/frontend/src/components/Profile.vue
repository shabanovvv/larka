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
import http from '@/http';

export default {
    data() {
        return {
            user: null
        }
    },
    async mounted() {
        try {
            const { data } = await http.get('/api/profile');
            this.user = data.user ?? data;
        } catch (error) {
            if (error.response?.status === 401) {
                this.$router.push('/login');
            } else {
                this.error = 'Не удалось загрузить профиль';
            }
        }
    }
}
</script>
