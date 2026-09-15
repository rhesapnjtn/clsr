import { createRouter, createWebHistory } from 'vue-router';
import RegisterView from '../views/RegisterView.vue';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        { path: '/', name: 'register', component: RegisterView },
        { path: '/:pathMatch(.*)*', redirect: '/' },
    ],
});

export default router;