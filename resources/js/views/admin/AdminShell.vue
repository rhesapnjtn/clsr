<template>
    <div v-if="loading" class="flex min-h-screen items-center justify-center bg-slate-100">
        <div class="flex flex-col items-center gap-3 text-slate-500">
            <svg class="h-8 w-8 animate-spin text-sky-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
            <p class="text-sm font-medium">Memuat data...</p>
        </div>
    </div>

    <div v-else class="flex min-h-screen bg-slate-100">
        <!-- Sidebar -->
        <aside class="hidden w-64 shrink-0 border-r border-slate-200 bg-white md:flex md:flex-col">
            <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-sky-500 to-blue-700 text-lg font-extrabold text-white shadow-lg shadow-sky-500/30">
                    C
                </div>
                <div>
                    <p class="text-sm font-extrabold leading-tight text-slate-900">CLSR Academy</p>
                    <p class="text-[11px] font-medium text-slate-500">Admin Panel</p>
                </div>
            </div>

            <nav class="mt-4 flex-1 space-y-1 px-3">
                <button
                    @click="section = 'dashboard'"
                    class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition"
                    :class="section === 'dashboard' ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    Dashboard
                </button>
                <button
                    @click="section = 'batches'"
                    class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition"
                    :class="section === 'batches' ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Batch
                </button>
                <button
                    @click="section = 'registrations'"
                    class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition"
                    :class="section === 'registrations' ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Peserta
                </button>
            </nav>

            <div class="border-t border-slate-200 px-3 py-3">
                <p class="truncate px-3 text-xs text-slate-400">{{ user?.email }}</p>
                <a
                    href="/admin"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="mt-2 flex w-full items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold text-slate-500 hover:bg-rose-50 hover:text-rose-600 transition"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar
                </a>
                <form id="logout-form" method="POST" action="/admin/logout" class="hidden">
                    <input type="hidden" name="_token" :value="csrfToken">
                </form>
            </div>
        </aside>

        <!-- Mobile header -->
        <div class="flex flex-1 flex-col md:overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-200 bg-white px-4 py-3 md:hidden">
                <div class="flex items-center gap-2">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-sky-500 to-blue-700 text-sm font-extrabold text-white">C</div>
                    <span class="text-sm font-bold text-slate-900">Admin</span>
                </div>
                <div class="flex items-center gap-1">
                    <button @click="section = 'dashboard'" class="rounded-lg p-2 text-slate-500 transition" :class="section === 'dashboard' ? 'bg-sky-50 text-sky-600' : ''">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"/></svg>
                    </button>
                    <button @click="section = 'batches'" class="rounded-lg p-2 text-slate-500 transition" :class="section === 'batches' ? 'bg-sky-50 text-sky-600' : ''">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </button>
                    <button @click="section = 'registrations'" class="rounded-lg p-2 text-slate-500 transition" :class="section === 'registrations' ? 'bg-sky-50 text-sky-600' : ''">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </button>
                    <a href="/admin" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="rounded-lg p-2 text-slate-500 hover:text-rose-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </a>
                </div>
            </div>

            <main class="flex-1 overflow-y-auto p-5 md:p-8">
                <AdminDashboardView v-if="section === 'dashboard'" @navigate="section = $event" />
                <AdminBatchesView v-if="section === 'batches'" />
                <AdminRegistrationsView v-if="section === 'registrations'" />
            </main>
        </div>
    </div>

    <ToastHost />
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AdminDashboardView from './AdminDashboardView.vue';
import AdminBatchesView from './AdminBatchesView.vue';
import AdminRegistrationsView from './AdminRegistrationsView.vue';
import ToastHost from '../../components/Toast.vue';

const user = ref(null);
const loading = ref(true);
const section = ref('dashboard');
const csrfToken = ref(window.csrfToken || '');

onMounted(async () => {
    try {
        const { data } = await axios.get('/me');
        user.value = data.user;
    } catch {
        window.location.href = '/admin';
    } finally {
        loading.value = false;
    }
});
</script>