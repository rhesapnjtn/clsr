<template>
    <div>
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900">Kelola Batch</h1>
                <p class="mt-1 text-sm text-slate-500">Buat dan atur kelas pelatihan CLSR.</p>
            </div>
            <button
                @click="showCreate = true"
                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-sky-600 to-blue-700 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-sky-600/20 transition hover:from-sky-500 hover:to-blue-600"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Buat Batch Baru
            </button>
        </div>

        <div class="space-y-4">
            <div v-for="b in batches" :key="b.id" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-base font-extrabold text-slate-900">{{ b.code }}</h3>
                            <span
                                class="rounded-full px-2.5 py-0.5 text-[11px] font-bold"
                                :class="{
                                    'bg-emerald-100 text-emerald-700': b.status === 'open',
                                    'bg-amber-100 text-amber-700': b.status === 'locked',
                                    'bg-slate-200 text-slate-600': b.status === 'completed',
                                }"
                            >{{ statusLabel(b.status) }}</span>
                        </div>
                        <p class="text-sm text-slate-600">{{ b.title }}</p>
                        <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-500">
                            <span><strong class="text-slate-700">Tanggal:</strong> {{ b.training_date_label }}</span>
                            <span v-if="b.start_time"><strong class="text-slate-700">Waktu:</strong> {{ b.start_time }} - {{ b.end_time }}</span>
                            <span v-if="b.location"><strong class="text-slate-700">Lokasi:</strong> {{ b.location }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col items-end gap-2">
                        <div class="h-2.5 w-44 overflow-hidden rounded-full bg-slate-200">
                            <div
                                class="h-full rounded-full transition-all duration-500"
                                :class="b.is_full || b.status === 'completed' ? 'bg-rose-500' : 'bg-gradient-to-r from-sky-500 to-emerald-500'"
                                :style="{ width: Math.min(100, (b.active_count / b.max_participants) * 100) + '%' }"
                            ></div>
                        </div>
                        <p class="text-xs font-semibold text-slate-600">{{ b.active_count }} / {{ b.max_participants }} peserta</p>
                        <div class="mt-1 flex flex-wrap justify-end gap-1.5">
                            <button
                                class="rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50"
                                :disabled="busy === b.id"
                                @click="toggleLock(b)"
                            >
                                {{ b.is_locked || b.status === 'locked' ? '🔓 Buka' : '🔒 Kunci' }}
                            </button>
                            <button
                                class="rounded-lg border border-amber-200 bg-amber-50 px-2.5 py-1.5 text-xs font-semibold text-amber-700 transition hover:bg-amber-100"
                                @click="resetBatch(b)"
                            >
                                Reset
                            </button>
                            <button
                                class="rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100"
                                :disabled="b.status === 'completed'"
                                @click="completeBatch(b)"
                            >
                                Selesai
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <p v-if="!batches.length" class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-sm text-slate-400">
                Belum ada batch. Klik "Buat Batch Baru" untuk memulai.
            </p>
        </div>

        <Modal :open="showCreate" title="Buat Batch Baru" @close="showCreate = false">
            <form id="create-batch-form" @submit.prevent="createBatch" class="space-y-4">
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Judul / Nama Batch <span class="text-rose-500">*</span></label>
                    <input v-model="batchForm.title" type="text" class="input" placeholder="cth: Pelatihan CLSR Batch September" />
                    <p v-if="createErrors.title" class="mt-1 text-xs font-medium text-rose-600">{{ createErrors.title }}</p>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Tanggal Pelaksanaan <span class="text-rose-500">*</span></label>
                        <input v-model="batchForm.training_date" type="date" class="input" min="today" />
                        <p v-if="createErrors.training_date" class="mt-1 text-xs font-medium text-rose-600">{{ createErrors.training_date }}</p>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Maks. Peserta <span class="text-rose-500">*</span></label>
                        <input v-model.number="batchForm.max_participants" type="number" min="1" max="500" class="input" />
                        <p v-if="createErrors.max_participants" class="mt-1 text-xs font-medium text-rose-600">{{ createErrors.max_participants }}</p>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Waktu Mulai</label>
                        <input v-model="batchForm.start_time" type="time" class="input" />
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Waktu Selesai</label>
                        <input v-model="batchForm.end_time" type="time" class="input" />
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Lokasi</label>
                    <input v-model="batchForm.location" type="text" class="input" placeholder="cth: Demo Room, Gedung Training" />
                </div>
            </form>

            <template #footer>
                <button type="button" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50" @click="showCreate = false">
                    Batal
                </button>
                <button type="submit" form="create-batch-form" :disabled="creating" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-sky-600 to-blue-700 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-sky-600/20 hover:from-sky-500 hover:to-blue-600 disabled:opacity-60">
                    <svg v-if="creating" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    Simpan Batch
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

const toast = useToastStore();
const batches = ref([]);
const busy = ref(null);
const showCreate = ref(false);
const creating = ref(false);
const createErrors = ref({});

const batchForm = reactive({
    title: '',
    training_date: '',
    start_time: '08:00',
    end_time: '16:00',
    location: 'Demo Room, Gedung Training',
    max_participants: 30,
});

function statusLabel(s) {
    return { open: 'Terbuka', locked: 'Terkunci', completed: 'Selesai' }[s] || s;
}

async function load() {
    const { data } = await axios.get('/admin/batches');
    batches.value = data.batches;
}

async function toggleLock(b) {
    busy.value = b.id;
    try {
        const { data } = await axios.patch(`/admin/batches/${b.id}/toggle-lock`);
        toast.info(data.batch.is_locked ? `Batch ${data.batch.code} dikunci.` : `Batch ${data.batch.code} dibuka.`);
        await load();
    } catch {
        toast.error('Gagal mengubah status batch.');
    } finally {
        busy.value = null;
    }
}

async function resetBatch(b) {
    try {
        await axios.patch(`/admin/batches/${b.id}/reset`);
        toast.success(`Batch ${b.code} dibuka kembali.`);
        await load();
    } catch {
        toast.error('Gagal reset batch.');
    }
}

async function completeBatch(b) {
    try {
        await axios.patch(`/admin/batches/${b.id}/complete`);
        toast.success(`Batch ${b.code} ditandai selesai.`);
        await load();
    } catch {
        toast.error('Gagal menyimpan status.');
    }
}

async function createBatch() {
    creating.value = true;
    createErrors.value = {};
    try {
        const { data } = await axios.post('/admin/batches', batchForm);
        toast.success(`Batch ${data.batch.code} berhasil dibuat.`);
        showCreate.value = false;
        Object.assign(batchForm, {
            title: '',
            training_date: '',
            start_time: '08:00',
            end_time: '16:00',
            location: 'Demo Room, Gedung Training',
            max_participants: 30,
        });
        await load();
    } catch (e) {
        createErrors.value = e.response?.data?.errors || {};
        toast.error('Periksa kembali data batch.');
    } finally {
        creating.value = false;
    }
}

onMounted(load);
</script>

<style scoped>
@reference '../../../css/app.css';

.input {
    @apply w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10;
}
</style>