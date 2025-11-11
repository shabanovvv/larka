<template>
    <div class="home">
        <h1>Главная - Мероприятия</h1>
        <div v-for="event in events" :key="event.id" class="event-card">
            <h3>{{ event.title }}</h3>
            <p>📅 {{ event.date }}</p>
            <p>📍 {{ event.location }}</p>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            events: []
        }
    },
    async mounted() {
        const response = await fetch('/api/events');
        const data = await response.json();
        this.events = data.events;
    }
}
</script>
