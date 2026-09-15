<template>
    <div>
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Data Peserta</h1>
                <p class="mt-1 text-sm text-slate-500">Kelola pendaftar, kirim undangan, dan export data.</p>
            </div>
            <button
                @click="exportExcel"
                class="inline-flex items-center gap-2 rounded-xl border border-emerald-300 bg-emerald-50 px-4 py-2.5 text-sm font-bold text-emerald-700 shadow-sm transition hover:bg-emerald-100"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export Excel
            </button>
        </div>

        <!-- Filters -->
        <div class="mb-5 grid gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-4">
            <div class="relative">
                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input
                    v-model="filters.search"
                    type="text"
                    placeholder="Cari nama, perusahaan..."
                    class="w-full rounded-xl border border-slate-300 bg-white py-2.5 pl-9 pr-3 text-sm outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10"
                    @keyup.enter="load(1)"
                />
            </div>
            <select v-model="filters.batch_id" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10" @change="load(1)">
                <option value="">Semua Batch</option>
                <option v-for="b in batches" :key="b.id" :value="b.id">{{ b.code }} — {{ b.title }}</option>
            </select>
            <select v-model="filters.status" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10" @change="load(1)">
                <option value="">Semua Status</option>
                <option v-for="s in REGISTRATION_STATUSES" :key="s.value" :value="s.value">{{ s.label }}</option>
            </select>
            <button
                @click="load(1)"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-slate-700"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Terapkan Filter
            </button>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400">Pendaftar</th>
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400">Batch</th>
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400">Perusahaan / Jabatan</th>
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400">Status</th>
                            <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-400">Daftar</th>
                            <th class="px-4 py-3 text-right text-[11px] font-bold uppercase tracking-wider text-slate-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="r in rows" :key="r.id" class="transition hover:bg-sky-50/40">
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-sky-100 to-blue-100 text-sm font-bold text-sky-700">
                                        {{ initials(r.name) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="truncate font-semibold text-slate-900">{{ r.name }}</p>
                                        <p class="truncate text-xs text-slate-400">{{ r.email_private }}</p>
                                        <p class="text-[11px] text-slate-300">KTP: {{ r.ktp_masked }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5">
                                <p class="font-semibold text-slate-700">{{ r.batch?.code || '-' }}</p>
                                <p class="text-[11px] text-slate-400">{{ r.batch?.training_date_label }}</p>
                            </td>
                            <td class="px-4 py-3.5">
                                <p class="text-slate-700">{{ r.company_name }}</p>
                                <p class="text-xs text-slate-400">{{ r.position }} · {{ r.function }}</p>
                                <p class="text-[11px] text-slate-400">{{ r.employment_status }} · {{ r.training_status_label }}</p>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[11px] font-bold" :class="statusColor(r.status)">
                                    <template v-if="r.status === 'invited'">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </template>
                                    {{ r.status_label }}
                                </span>
                                <p v-if="r.cancel_reason" class="mt-1 max-w-40 text-[11px] text-rose-500" :title="r.cancel_reason">{{ r.cancel_reason }}</p>
                            </td>
                            <td class="px-4 py-3.5 text-xs text-slate-500">
                                {{ r.created_at ? new Date(r.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) : '-' }}
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button
                                        v-if="['registered', 'cancelled'].includes(r.status) === false && r.status !== 'cancelled'"
                                        class="rounded-lg border border-slate-200 p-1.5 text-slate-500 transition hover:bg-sky-50 hover:text-sky-600"
                                        :title="'Kirim ulang undangan'"
                                        @click="resendInvite(r)"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    </button>
                                    <button
                                        v-if="canInvite(r)"
                                        class="inline-flex items-center gap-1 rounded-lg bg-violet-600 px-2.5 py-1.5 text-xs font-bold text-white shadow-sm transition hover:bg-violet-700"
                                        title="Kirim undangan email"
                                        @click="invite(r)"
                                    >
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        Kirim
                                    </button>
                                    <button
                                        v-if="canAct(r) && ['registered', 'invited'].includes(r.status)"
                                        class="rounded-lg border border-emerald-200 bg-emerald-50 p-1.5 text-emerald-600 transition hover:bg-emerald-100"
                                        title="Konfirmasi hadir"
                                        @click="setStatus(r, 'confirm')"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </button>
                                    <button
                                        v-if="['confirmed', 'invited', 'registered'].includes(r.status)"
                                        class="rounded-lg border border-teal-200 bg-teal-50 p-1.5 text-teal-600 transition hover:bg-teal-100"
                                        title="Tandai hadir"
                                        @click="setStatus(r, 'attend')"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                    <button
                                        v-if="['confirmed', 'invited', 'registered'].includes(r.status)"
                                        class="rounded-lg border border-amber-200 bg-amber-50 p-1.5 text-amber-600 transition hover:bg-amber-100"
                                        title="Tidak hadir"
                                        @click="setStatus(r, 'no_show')"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                    <button
                                        v-if="['registered', 'invited', 'confirmed'].includes(r.status)"
                                        class="rounded-lg border border-rose-200 bg-rose-50 p-1.5 text-rose-600 transition hover:bg-rose-100"
                                        title="Batalkan (berhalangan)"
                                        @click="openCancel(r)"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!rows.length">
                            <td colspan="6" class="px-4 py-14 text-center text-sm text-slate-400">Tidak ada data peserta.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="meta.last_page > 1" class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 px-4 py-3">
                <p class="text-xs text-slate-500">
                    Menampilkan {{ meta.total ? (meta.current_page - 1) * meta.per_page + 1 : 0 }}–{{ Math.min(meta.current_page * meta.per_page, meta.total) }} dari {{ meta.total }}
                </p>
                <div class="flex items-center gap-1.5">
                    <button class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 disabled:opacity-40" :disabled="meta.current_page <= 1" @click="load(meta.current_page - 1)">
                        ‹ Sebelumnya
                    </button>
                    <span class="px-2 text-xs font-semibold text-slate-600">{{ meta.current_page }} / {{ meta.last_page }}</span>
                    <button class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 disabled:opacity-40" :disabled="meta.current_page >= meta.last_page" @click="load(meta.current_page + 1)">
                        Berikutnya ›
                    </button>
                </div>
            </div>
        </div>

        <!-- Cancel modal -->
        <Modal :open="!!cancelTarget" title="Batalkan Pendaftaran Peserta" @close="cancelTarget = null">
            <div v-if="cancelTarget">
                <p class="text-sm text-slate-600">
                    Batalkan pendaftaran <strong class="text-slate-900">{{ cancelTarget.name }}</strong>? Kuota batch akan dikembalikan sehingga form pendaftaran terbuka kembali.
                </p>
                <label class="mt-4 mb-1.5 block text-sm font-semibold text-slate-700">Alasan berhalangan <span class="text-rose-500">*</span></label>
                <textarea
                    v-model="cancelReason"
                    rows="3"
                    class="mt-4 w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm outline-none transition focus:border-rose-400 focus:ring-4 focus:ring-rose-500/10"
                    placeholder="cth: berhalangan hadir karena sakit / tugas dinas"
                ></textarea>
            </div>

            <template #footer>
                <button type="button" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50" @click="cancelTarget = null">
                    Batal
                </button>
                <button type="button" :disabled="!cancelReason.trim() || cancelling" class="rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-rose-600/20 hover:bg-rose-700 disabled:opacity-50" @click="doCancel">
                    {{ cancelling ? 'Membatalkan...' : 'Batalkan Pendaftaran' }}
                </button>
            </template>
        </Modal>
    </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue';
import axios from 'axios';
import Modal from '../../components/Modal.vue';
import { useToastStore } from '../../stores/toast';
import { REGISTRATION_STATUSES } from '../../lib/constants';

const toast = useToastStore();
const rows = ref([]);
const meta = ref({ current_page: 1, last_page: 1, per_page: 15, total: 0 });
const batches = ref([]);
const busyId = ref(null);

const filters = reactive({ batch_id: '', status: '', search: '' });

const cancelTarget = ref(null);
const cancelReason = ref('');
const cancelling = ref(false);

function initials(name) {
    return name.split(' ').filter(Boolean).slice(0, 2).map((w) => w[0]).join('').toUpperCase();
}

function statusColor(status) {
    return {
        registered: 'bg-sky-100 text-sky-700',
        invited: 'bg-violet-100 text-violet-700',
        confirmed: 'bg-emerald-100 text-emerald-700',
        attended: 'bg-teal-600 text-white',
        cancelled: 'bg-rose-100 text-rose-700',
        no_show: 'bg-amber-100 text-amber-700',
    }[status] || 'bg-slate-100 text-slate-700';
}

function canInvite(r) {
    return ['registered'].includes(r.status);
}

function canAct(r) {
    return r.status !== 'cancelled' && r.status !== 'attended' && r.status !== 'no_show';
}

async function load(page = 1) {
    const params = { page, per_page: 15 };
    if (filters.batch_id) params.batch_id = filters.batch_id;
    if (filters.status) params.status = filters.status;
    if (filters.search.trim()) params.search = filters.search.trim();

    try {
        const { data } = await axios.get('/admin/registrations', { params });
        rows.value = data.registrations;
        meta.value = data.meta;
    } catch (e) {
        toast.error('Gagal memuat data peserta.');
    }
}

async function loadBatches() {
    const { data } = await axios.get('/admin/batches');
    batches.value = data.batches;
}

async function invite(r) {
    if (!confirm(`Kirim undangan ke ${r.name} (${r.email_private})?`)) return;
    busyId.value = r.id;
    try {
        const { data } = await axios.post(`/admin/registrations/${r.id}/invite`);
        toast.success(data.message);
        await load(meta.value.current_page);
    } catch (e) {
        toast.error(e.response?.data?.message || 'Gagal mengirim undangan.');
    } finally {
        busyId.value = null;
    }
}

async function resendInvite(r) {
    busyId.value = r.id;
    try {
        const { data } = await axios.post(`/admin/registrations/${r.id}/invite`);
        toast.success('Undangan dikirim ulang.');
        await load(meta.value.current_page);
    } catch (e) {
        toast.error(e.response?.data?.message || 'Gagal mengirim ulang undangan.');
    } finally {
        busyId.value = null;
    }
}

async function setStatus(r, action) {
    const endpoints = { confirm: 'confirm', attend: 'attend', no_show: 'no-show' };
    const labels = { confirm: 'dikonfirmasi hadir', attend: 'ditandai hadir', no_show: 'ditandai tidak hadir' };
    if (!confirm(`Tandai ${r.name} sebagai ${labels[action]}?`)) return;
    try {
        await axios.post(`/admin/registrations/${r.id}/${endpoints[action]}`);
        toast.success(`Peserta ${labels[action]}.`);
        await load(meta.value.current_page);
    } catch (e) {
        toast.error('Gagal memperbarui status.');
    }
}

function openCancel(r) {
    cancelTarget.value = r;
    cancelReason.value = '';
}

async function doCancel() {
    cancelling.value = true;
    try {
        await axios.delete(`/admin/registrations/${cancelTarget.value.id}`, { data: { reason: cancelReason.value.trim() } });
        toast.success('Pendaftaran dibatalkan, kuota dikembalikan.');
        cancelTarget.value = null;
        await load(meta.value.current_page);
        await loadBatches();
    } catch (e) {
        toast.error('Gagal membatalkan pendaftaran.');
    } finally {
        cancelling.value = false;
    }
}

function exportExcel() {
    const params = new URLSearchParams();
    if (filters.batch_id) params.set('batch_id', filters.batch_id);
    if (filters.status) params.set('status', filters.status);

    const link = document.createElement('a');
    link.href = `/api/admin/registrations/export?${params.toString()}`;
    link.download = '';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

onMounted(() => {
    load();
    loadBatches();
});
</script>