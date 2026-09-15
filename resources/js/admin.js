import { createApp } from 'vue';
import { createPinia } from 'pinia';
import './bootstrap';
import AdminShell from './views/admin/AdminShell.vue';

const app = createApp(AdminShell);
app.use(createPinia());
app.mount('#admin-app');