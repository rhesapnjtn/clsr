<template>
    <div>
        <div class="mb-6">
            <h1 class="text-2xl font-extrabold text-slate-900">Dashboard</h1>
            <p class="mt-1 text-sm text-slate-500">Ringkasan pendaftaran pelatihan CLSR.</p>
        </div>

        <div v-if="!stats" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div v-for="i in 4" :key="i" class="h-28 animate-pulse rounded-2xl bg-slate-200"></div>
        </div>

        <template v-else>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Pendaftar</p>
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-sky-100 text-sky-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </span>
                    </div>
                    <p class="mt-3 text-3xl font-extrabold text-slate-900">{{ stats.total }}</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Undangan Terkirim</p>
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-violet-100 text-violet-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                    </div>
                    <p class="mt-3 text-3xl font-extrabold text-slate-900">{{ stats.invited + stats.confirmed }}</p>
                    <p class="mt-1 text-xs text-slate-400">{{ stats.invited }} undangan baru</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Konfirmasi Hadir</p>
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                    </div>
                    <p class="mt-3 text-3xl font-extrabold text-slate-900">{{ stats.confirmed + stats.attended }}</p>
                    <p class="mt-1 text-xs text-slate-400">{{ stats.attended }} hadir</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Batal / Tidak Hadir</p>
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-rose-100 text-rose-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                    </div>
                    <p class="mt-3 text-3xl font-extrabold text-slate-900">{{ stats.cancelled + stats.no_show }}</p>
                    <p class="mt-1 text-xs text-slate-400">{{ stats.cancelled }} batal</p>
                </div>
            </div>

            <!-- Batch quota overview -->
            <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">Kuota Batch</h2>
                        <p class="text-sm text-slate-500">Pengisian kuota 30 peserta per batch.</p>
                    </div>
                    <button class="text-sm font-bold text-sky-600 hover:text-sky-700" @click="$emit('navigate', 'batches')">
                        Kelola Batch →
                    </button>
                </div>
                <div class="space-y-4">
                    <div v-for="b in stats.by_batch" :key="b.id" class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ b.code }} — {{ b.title }}</p>
                                <p class="text-xs text-slate-500">{{ b.training_date_label }}</p>
                            </div>
                            <span
                                class="rounded-full px-2.5 py-1 text-[11px] font-bold"
                                :class="b.is_full ? 'bg-rose-100 text-rose-700' : 'bg-sky-100 text-sky-700'"
                            >
                                {{ b.is_full ? 'PENUH / TERKUNCI' : 'TERBUKA' }}
                            </span>
                        </div>
                        <div class="mt-3 h-2.5 overflow-hidden rounded-full bg-slate-200">
                            <div
                                class="h-full rounded-full transition-all duration-500"
                                :class="b.is_full ? 'bg-rose-500' : 'bg-gradient-to-r from-sky-500 to-emerald-500'"
                                :style="{ width: Math.min(100, (b.active_count / b.max_participants) * 100) + '%' }"
                            ></div>
                        </div>
                        <p class="mt-1.5 text-xs font-medium text-slate-500">{{ b.active_count }} / {{ b.max_participants }} peserta</p>
                    </div>
                    <p v-if="!stats.by_batch.length" class="py-6 text-center text-sm text-slate-400">Belum ada batch.</p>
                </div>
            </div>
        </template>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

defineEmits(['navigate']);
const stats = ref(null);

onMounted(async () => {
    const { data } = await axios.get('/admin/stats');
    stats.value = data.stats;
    stats.value.by_batch = data.by_batch;
});
</script>