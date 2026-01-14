import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home.vue';
import About from '../components/About.vue';
import Login from '../components/Login.vue';
import Profile from '../components/Profile.vue';
import Register from '../components/Register.vue';
import CodeSubmissionForm from '../components/CodeSubmissionForm.vue';

const routes = [
    {path: '/', component: Home},
    {path: '/about', component: About},
    {path: '/login', component: Login},
    {path: '/profile', component: Profile},
    {path: '/register', component: Register},
    {path: '/code-submission', component: CodeSubmissionForm},
];

export default createRouter({
    history: createWebHistory(),
    routes,
});
