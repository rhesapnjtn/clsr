<template>
    <div class="min-h-screen bg-slate-100">
        <!-- Navbar -->
        <nav class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/80 backdrop-blur">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-sky-500 to-blue-700 text-lg font-extrabold text-white shadow-lg shadow-sky-500/30">
                        C
                    </div>
                    <div>
                        <p class="text-sm font-extrabold leading-tight text-slate-900">CLSR Academy</p>
                        <p class="text-[11px] font-medium text-slate-500">Registrasi Center Pelatihan</p>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero -->
        <header class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-blue-900 to-sky-800">
            <div class="pointer-events-none absolute -right-20 -top-20 h-80 w-80 rounded-full bg-sky-400/20 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 -left-10 h-72 w-72 rounded-full bg-blue-400/10 blur-3xl"></div>

            <div class="relative mx-auto max-w-6xl px-4 py-14 text-center sm:py-20">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-1.5 text-xs font-semibold text-sky-100 backdrop-blur">
                    <span class="h-2 w-2 animate-pulse rounded-full bg-emerald-400"></span>
                    COMPETENT LAWS &amp; SAFETY REGULATIONS
                </span>
                <h1 class="mx-auto mt-5 max-w-3xl text-3xl font-extrabold leading-tight text-white sm:text-5xl">
                    Pendaftaran Pelatihan <span class="bg-gradient-to-r from-sky-300 to-emerald-300 bg-clip-text text-transparent">CLSR</span>
                </h1>
                <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-sky-100/80 sm:text-base">
                    Daftarkan diri Anda untuk mengikuti pelatihan CLSR. Kuota terbatas masing-masing kelas maksimal
                    <strong class="text-white">30 peserta</strong>, dan pendaftaran otomatis terkunci saat kuota terpenuhi.
                </p>

                <div class="mt-8 flex flex-wrap items-center justify-center gap-2.5 text-xs sm:text-sm">
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-white/10 px-3 py-1.5 font-medium text-white/90 backdrop-blur">
                        <svg class="h-4 w-4 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        KTP hanya dapat daftar 1x
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-white/10 px-3 py-1.5 font-medium text-white/90 backdrop-blur">
                        <svg class="h-4 w-4 text-sky-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Invitation via email
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-white/10 px-3 py-1.5 font-medium text-white/90 backdrop-blur">
                        <svg class="h-4 w-4 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Data terenkripsi &amp; aman
                    </span>
                </div>
            </div>

            <svg class="block w-full text-slate-100" viewBox="0 0 1440 60" fill="currentColor" preserveAspectRatio="none">
                <path d="M0,32L60,37.3C120,43,240,53,360,53.3C480,53,600,43,720,37.3C840,32,960,32,1080,37.3C1200,43,1320,53,1380,58.7L1440,64L1440,64L1380,64C1320,64,1200,64,1080,64C960,64,840,64,720,64C600,64,480,64,360,64C240,64,120,64,60,64L0,64Z"></path>
            </svg>
        </header>

        <main class="mx-auto max-w-6xl px-4 pb-24">
            <!-- Batch Selection -->
            <section class="-mt-8 relative z-10" v-if="batches.length && !success">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xl shadow-slate-900/5 sm:p-7">
                    <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900">1. Pilih Kelas / Batch CLSR</h2>
                            <p class="text-sm text-slate-500">Pilih tanggal pelaksanaan yang tersedia. Kuota diperbarui secara real-time.</p>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <button
                            v-for="batch in batches"
                            :key="batch.id"
                            type="button"
                            class="group relative rounded-xl border-2 p-4 text-left transition"
                            :class="[
                                selectedBatch?.id === batch.id
                                    ? 'border-sky-500 bg-sky-50 shadow-lg shadow-sky-500/10'
                                    : 'border-slate-200 bg-white hover:border-sky-300 hover:bg-sky-50/40',
                                batch.is_full || batch.is_locked ? 'opacity-70' : '',
                            ]"
                            @click="selectBatch(batch)"
                        >
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="text-[11px] font-bold uppercase tracking-wider text-sky-600">{{ batch.code }}</p>
                                    <p class="mt-0.5 text-sm font-bold text-slate-900">{{ batch.title }}</p>
                                </div>
                                <span
                                    v-if="selectedBatch?.id === batch.id"
                                    class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-sky-500 text-white"
                                >
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </span>
                            </div>

                            <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-500">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ batch.training_date_label }}
                                </span>
                                <span v-if="batch.start_time" class="inline-flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ batch.start_time }} - {{ batch.end_time }}
                                </span>
                            </div>
                            <p v-if="batch.location" class="mt-1.5 truncate text-xs text-slate-400">{{ batch.location }}</p>

                            <div class="mt-4">
                                <div class="flex items-center justify-between text-xs font-semibold">
                                    <span class="text-slate-500">Kuota</span>
                                    <span :class="batch.is_full ? 'text-rose-600' : 'text-sky-600'">
                                        {{ batch.active_count }} / {{ batch.max_participants }} peserta
                                    </span>
                                </div>
                                <div class="mt-1.5 h-2 overflow-hidden rounded-full bg-slate-100">
                                    <div
                                        class="h-full rounded-full transition-all duration-500"
                                        :class="batch.is_full ? 'bg-rose-500' : batch.active_count / batch.max_participants >= 0.75 ? 'bg-amber-500' : 'bg-gradient-to-r from-sky-500 to-emerald-500'"
                                        :style="{ width: (batch.active_count / batch.max_participants) * 100 + '%' }"
                                    ></div>
                                </div>
                                <p class="mt-1.5 text-[11px] font-medium" :class="batch.is_full ? 'text-rose-600' : 'text-emerald-600'">
                                    <template v-if="batch.is_full">Kuota penuh — class terkunci</template>
                                    <template v-else-if="batch.slots_left <= 5">Sisa {{ batch.slots_left }} slot — segera daftar!</template>
                                    <template v-else>{{ batch.slots_left }} slot tersedia</template>
                                </p>
                            </div>
                        </button>
                    </div>
                </div>
            </section>

            <!-- Locked banner when no batch selected / all locked -->
            <section v-if="!batches.length && loaded" class="relative z-10 -mt-8">
                <div class="rounded-2xl border border-rose-200 bg-rose-50 p-8 text-center shadow-xl">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-rose-100">
                        <svg class="h-7 w-7 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <h2 class="mt-4 text-lg font-extrabold text-slate-900">Belum ada kelas yang dibuka</h2>
                    <p class="mt-1 text-sm text-slate-600">Silakan kembali lagi nanti. Admin akan membuka pendaftaran untuk batch berikutnya.</p>
                </div>
            </section>

            <!-- Registration Form -->
            <section id="form" v-if="selectedBatch && !selectedBatch.is_full" class="mt-8">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-xl shadow-slate-900/5 sm:p-8">
                    <div class="mb-6 flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-sky-100 text-sky-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </span>
                        <div>
                            <h2 class="text-lg font-extrabold text-slate-900">2. Lengkapi Data Pendaftaran</h2>
                            <p class="text-xs text-slate-500">
                                Batch <strong class="text-sky-600">{{ selectedBatch.code }}</strong> ·
                                {{ selectedBatch.training_date_label }}
                            </p>
                        </div>
                    </div>

                    <form @submit.prevent="submit" novalidate>
                        <div class="grid gap-5 sm:grid-cols-2">
                            <!-- Nama -->
                            <div class="sm:col-span-2">
                                <FormField
                                    id="name"
                                    label="Nama Lengkap"
                                    required
                                    :error="errors.name"
                                    placeholder="Nama sesuai KTP"
                                >
                                    <input
                                        id="name" v-model="form.name" type="text"
                                        class="input" placeholder="Nama sesuai KTP"
                                        :class="{ 'input-error': errors.name }"
                                    />
                                </FormField>
                            </div>

                            <!-- KTP -->
                            <div>
                                <FormField
                                    id="ktp"
                                    label="No. KTP"
                                    required
                                    :error="errors.ktp"
                                    hint="16 digit, hanya dapat digunakan 1x pendaftaran"
                                >
                                    <div class="relative">
                                        <input
                                            id="ktp" v-model="form.ktp" type="text" inputmode="numeric"
                                            maxlength="16" class="input pr-11" placeholder="Contoh: 3273010101900001"
                                            :class="{ 'input-error': errors.ktp, 'input-success': ktpCheck === 'ok' }"
                                            @input="onKtpInput" @blur="checkKtp"
                                        />
                                        <span v-if="ktpChecking" class="absolute right-3 top-1/2 -translate-y-1/2">
                                            <svg class="h-5 w-5 animate-spin text-slate-400" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                        </span>
                                        <span v-else-if="ktpCheck === 'ok'" class="absolute right-3 top-1/2 -translate-y-1/2 text-emerald-500">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </span>
                                    </div>
                                </FormField>
                            </div>

                            <!-- Worker number -->
                            <div>
                                <FormField
                                    id="worker_number"
                                    label="No. Pekerja / GGI"
                                    :error="errors.worker_number"
                                    hint="Khusus karyawan/vendor terdaftar PHM"
                                >
                                    <input id="worker_number" v-model="form.worker_number" type="text" class="input" placeholder="cth: 8800123 / GGI-0123" />
                                </FormField>
                            </div>

                            <!-- Private email -->
                            <div>
                                <FormField
                                    id="email_private"
                                    label="Email Pribadi"
                                    required
                                    :error="errors.email_private"
                                    hint="Digunakan untuk menerima invitation ujian CLSR"
                                >
                                    <input id="email_private" v-model="form.email_private" type="email" class="input" placeholder="nama@gmail.com" />
                                </FormField>
                            </div>

                            <!-- User email -->
                            <div>
                                <FormField
                                    id="email_user"
                                    label="Email User / Atasan"
                                    :error="errors.email_user"
                                    hint="Contoh: nama@pertamina.com (penanggung jawab pendaftar)"
                                >
                                    <input id="email_user" v-model="form.email_user" type="email" class="input" placeholder="nama@pertamina.com" />
                                </FormField>
                            </div>

                            <!-- Company -->
                            <div>
                                <FormField id="company_name" label="Nama Perusahaan" required :error="errors.company_name">
                                    <input id="company_name" v-model="form.company_name" type="text" class="input" placeholder="Nama perusahaan Anda" />
                                </FormField>
                            </div>

                            <!-- Function -->
                            <div>
                                <FormField id="function" label="Fungsi" required :error="errors.function">
                                    <select id="function" v-model="form.function" class="input">
                                        <option value="" disabled>Pilih Fungsi</option>
                                        <option v-for="f in FUNCTIONS" :key="f" :value="f">{{ f }}</option>
                                    </select>
                                </FormField>
                            </div>

                            <!-- Position -->
                            <div>
                                <FormField id="position" label="Posisi / Jabatan" required :error="errors.position">
                                    <input id="position" v-model="form.position" type="text" class="input" placeholder="cth: Supervisor HSSE" />
                                </FormField>
                            </div>

                            <!-- Employment status -->
                            <div>
                                <FormField id="employment_status" label="Status Pekerjaan" required :error="errors.employment_status">
                                    <select id="employment_status" v-model="form.employment_status" class="input">
                                        <option value="" disabled>Pilih status pekerjaan</option>
                                        <option v-for="s in EMPLOYMENT_STATUSES" :key="s" :value="s">{{ s }}</option>
                                    </select>
                                </FormField>
                            </div>

                            <!-- Training status -->
                            <div class="sm:col-span-2">
                                <p class="mb-2 text-sm font-semibold text-slate-700">
                                    Status Pelatihan <span class="text-rose-500">*</span>
                                </p>
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <label
                                        v-for="t in TRAINING_STATUSES"
                                        :key="t.value"
                                        class="flex cursor-pointer items-center gap-3 rounded-xl border-2 p-3.5 transition"
                                        :class="form.training_status === t.value
                                            ? 'border-sky-500 bg-sky-50'
                                            : 'border-slate-200 hover:border-sky-200'"
                                    >
                                        <input type="radio" name="training_status" :value="t.value" v-model="form.training_status" class="h-4 w-4 accent-sky-600" />
                                        <span class="text-sm font-medium text-slate-700">{{ t.label }}</span>
                                    </label>
                                </div>
                                <p v-if="errors.training_status" class="mt-1 text-xs font-medium text-rose-600">{{ errors.training_status }}</p>
                            </div>
                        </div>

                        <!-- Summary + submit -->
                        <div class="mt-7 rounded-xl border border-slate-200 bg-slate-50 p-4 sm:p-5">
                            <div v-if="errors.batch_id" class="mb-4 flex items-start gap-2 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
                                <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                {{ errors.batch_id }}
                            </div>
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="text-sm text-slate-600">
                                    <p>
                                        <span class="font-semibold text-slate-800">Tanggal Pelaksanaan:</span>
                                        {{ selectedBatch.training_date_label }}
                                        <span v-if="selectedBatch.start_time"> ({{ selectedBatch.start_time }} - {{ selectedBatch.end_time }})</span>
                                    </p>
                                    <p class="mt-0.5 text-xs text-slate-400">
                                        Dengan mendaftar, Anda menyetujui data diproses untuk keperluan pelatihan CLSR.
                                    </p>
                                </div>
                                <button
                                    type="submit"
                                    :disabled="submitting"
                                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-sky-600 to-blue-700 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-sky-600/30 transition hover:from-sky-500 hover:to-blue-600 disabled:cursor-not-allowed disabled:opacity-60"
                                >
                                    <svg v-if="submitting" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                    {{ submitting ? 'Mendaftarkan...' : 'Daftar Sekarang' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </section>

            <!-- Locked view for full selected batch -->
            <section v-else-if="selectedBatch && selectedBatch.is_full" id="form" class="mt-8">
                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-10 text-center shadow-xl">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-amber-100">
                        <svg class="h-8 w-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <h2 class="mt-4 text-xl font-extrabold text-slate-900">Kuota {{ selectedBatch.code }} Sudah Penuh</h2>
                    <p class="mx-auto mt-2 max-w-md text-sm text-slate-600">
                        Seluruh <strong>30 slot</strong> pada batch ini telah terisi sehingga pendaftaran otomatis terkunci.
                        Silakan pilih batch lain yang masih tersedia.
                    </p>
                    <button
                        class="mt-5 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700"
                        @click="selectedBatch = null; scrollToTop()"
                    >
                        Pilih Batch Lain
                    </button>
                </div>
            </section>

            <!-- Success screen -->
            <section v-else-if="success" id="form" class="mt-8">
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl shadow-slate-900/5">
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-8 text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-white/20 backdrop-blur">
                            <svg class="h-9 w-9 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h2 class="mt-3 text-2xl font-extrabold text-white">Pendaftaran Berhasil!</h2>
                        <p class="mt-1 text-sm text-emerald-100">Data Anda telah tercatat. Silakan cek email untuk undangan.</p>
                    </div>
                    <div class="p-6 text-center sm:p-8" v-if="successData">
                        <p class="text-xs font-bold uppercase tracking-widest text-sky-600">Kode Pendaftaran</p>
                        <p class="mt-1 font-mono text-2xl font-extrabold tracking-wider text-slate-900">{{ successData.code }}</p>
                        <div class="mx-auto mt-5 grid max-w-md gap-3 text-left text-sm sm:grid-cols-2">
                            <div class="rounded-lg bg-slate-50 p-3">
                                <p class="text-xs text-slate-400">Nama</p>
                                <p class="font-semibold text-slate-800">{{ successData.name }}</p>
                            </div>
                            <div class="rounded-lg bg-slate-50 p-3">
                                <p class="text-xs text-slate-400">Batch</p>
                                <p class="font-semibold text-slate-800">{{ successData.batch.code }}</p>
                            </div>
                            <div class="rounded-lg bg-slate-50 p-3">
                                <p class="text-xs text-slate-400">Tanggal Pelaksanaan</p>
                                <p class="font-semibold text-slate-800">{{ successData.batch.training_date_label }}</p>
                            </div>
                            <div class="rounded-lg bg-slate-50 p-3">
                                <p class="text-xs text-slate-400">Lokasi</p>
                                <p class="font-semibold text-slate-800">{{ successData.batch.location || 'Informasi menyusul' }}</p>
                            </div>
                        </div>
                        <p class="mt-5 text-xs text-slate-400">
                            Undangan resmi akan dikirimkan ke email pribadi Anda. Hubungi CS bila ada perubahan.
                        </p>
                        <button class="mt-5 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700" @click="resetAll">
                            Daftar Peserta Baru
                        </button>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-slate-200 bg-white py-6">
            <div class="mx-auto flex max-w-6xl flex-col items-center gap-2 px-4 text-center text-xs text-slate-400">
                <p>© {{ new Date().getFullYear() }} CLSR Academy — Pendaftaran Pelatihan CLSR.</p>
                <p>Data pribadi peserta dienkripsi dan hanya digunakan untuk keperluan pelatihan.</p>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { reactive, ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import FormField from '../components/FormField.vue';
import { FUNCTIONS, EMPLOYMENT_STATUSES, TRAINING_STATUSES } from '../lib/constants';

const batches = ref([]);
const loaded = ref(false);
const selectedBatch = ref(null);
const submitting = ref(false);
const success = ref(false);
const successData = ref(null);
const ktpChecking = ref(false);
const ktpCheck = ref(null);

const form = reactive({
    name: '',
    ktp: '',
    worker_number: '',
    email_private: '',
    email_user: '',
    company_name: '',
    function: '',
    position: '',
    employment_status: '',
    training_status: 'baru',
});

const errors = ref({});

watch(
    () => form.ktp,
    () => {
        ktpCheck.value = null;
    }
);

watch(
    () => form.training_status,
    () => {
        if (form.training_status === 'refreshment') {
            errors.value.training_status = null;
        }
    }
);

function onKtpInput(e) {
    form.ktp = e.target.value.replace(/\D+/g, '').slice(0, 16);
}

async function checkKtp() {
    if (ktpChecking.value) return;
    const digits = (form.ktp || '').replace(/\D+/g, '');
    if (![15, 16].includes(digits.length)) {
        ktpCheck.value = null;
        return;
    }
    ktpChecking.value = true;
    ktpCheck.value = null;
    try {
        const { data } = await axios.post('/check-ktp', { ktp: digits });
        ktpCheck.value = data.exists ? 'duplicate' : 'ok';
        if (data.exists) {
            errors.value.ktp = 'Nomor KTP ini sudah terdaftar pada batch lain.';
        } else {
            delete errors.value.ktp;
        }
    } catch {
        ktpCheck.value = null;
    } finally {
        ktpChecking.value = false;
    }
}

async function loadBatches() {
    try {
        const { data } = await axios.get('/batches/available');
        batches.value = data.batches;
        if (batches.value.length && !selectedBatch.value) {
            const firstOpen = batches.value.find((b) => !b.is_full && !b.is_locked);
            selectedBatch.value = firstOpen || null;
        }
        return batches.value;
    } finally {
        loaded.value = true;
    }
}

function selectBatch(batch) {
    if (batch.is_full || batch.is_locked) {
        selectedBatch.value = { ...batch, is_full: true };
        return;
    }
    selectedBatch.value = batch;
    errors.value.batch_id = null;
    if (window.location.hash !== '#form') {
        scrollToForm();
    }
}

function scrollToForm() {
    document.getElementById('form')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function flatten(errObj) {
    const out = {};
    for (const k in errObj) out[k] = errObj[k][0];
    return out;
}

async function submit() {
    submitting.value = true;
    errors.value = {};

    if (!selectedBatch.value) {
        errors.value.batch_id = 'Silakan pilih batch terlebih dahulu.';
        submitting.value = false;
        return;
    }

    try {
        const payload = {
            ...form,
            ktp: (form.ktp || '').replace(/\D+/g, ''),
            batch_id: selectedBatch.value.id,
            training_date: selectedBatch.value.training_date,
        };
        const { data } = await axios.post('/registrations', payload);
        successData.value = data.registration;
        success.value = true;
        selectedBatch.value = null;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    } catch (e) {
        if (e.response?.status === 422) {
            errors.value = flatten(e.response.data.errors || {});
            if (errors.value.batch_id) {
                const fresh = await loadBatches();
                const id = selectedBatch.value?.id;
                if (id) {
                    const updated = fresh.find((b) => b.id === id);
                    if (updated) {
                        selectedBatch.value = { ...updated, is_full: updated.is_full || updated.is_locked };
                    }
                }
            }
            scrollToTop();
        } else {
            alert('Terjadi kesalahan. Silakan coba lagi.');
        }
    } finally {
        submitting.value = false;
    }
}

function resetAll() {
    Object.assign(form, {
        name: '',
        ktp: '',
        worker_number: '',
        email_private: '',
        email_user: '',
        company_name: '',
        function: '',
        position: '',
        employment_status: '',
        training_status: 'baru',
    });
    ktpCheck.value = null;
    errors.value = {};
    success.value = false;
    successData.value = null;
    loadBatches();
    scrollToTop();
}

onMounted(loadBatches);
</script>

<style scoped>
@reference '../../css/app.css';

.input {
    @apply w-full rounded-xl border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10;
}
.input-error {
    @apply border-rose-400 focus:border-rose-500 focus:ring-rose-500/10;
}
.input-success {
    @apply border-emerald-500 focus:border-emerald-500 focus:ring-emerald-500/10;
}
</style>