# CLSR Registration — Pendaftaran Pelatihan CLSR

Sistem pendaftaran pelatihan **CLSR (Competent Laws & Safety Regulations)** berbasis
**Laravel 12 + Vue 3** dan **Tailwind CSS v4**. Dirancang untuk penyelenggara pelatihan
yang menerima pendaftar dari luar PHM (email pribadi) dengan **kuota maksimal 30 peserta
per batch** dan form yang otomatis terkunci saat kuota terpenuhi.

## Fitur Utama

- **Kuota max 30, terkunci otomatis** — setiap batch maksimal 30 peserta. Saat slot ke-30
  terisi, batch langsung terkunci (`locked`) dan tidak bisa diisi lagi. Proses ini aman
  terhadap kondisi balapan (race condition) karena dieksekusi di dalam transaksi DB.
- **Anti duplikasi KTP** — satu nomor KTP hanya dapat mendaftar satu kali. Pengecekan
  duplikat dilakukan otomatis (live) saat mengisi form dan dipaksa ulang di server.
- **Kelola batch / kelas** — penyelenggara dapat membuat batch dengan tanggal pelaksanaan,
  waktu, lokasi, dan kuota, serta mengunci/membuka/menandai selesai.
- **Kirim undangan (invitation)** — undangan dikirim via email ke **email pribadi** peserta
  (CC ke email user/atasan) berisi detail batch.
- **CS / pembatalan** — jika peserta berhalangan hadir, CS dapat membatalkan pendaftaran
  dengan alasan; satu slot otomatis dikembalikan dan form terbuka kembali.
- **Export Excel** — data peserta dapat diexport ke `.xlsx` dengan filter batch & status.
- **Kerahasiaan data** — kolom No. KTP **dienkripsi** (Laravel encrypter) di database.
  Pendeteksian duplikat memakai hash SHA-256 (bukan plaintext). Kolom KTP ter-mask di
  panel admin. Halaman admin dilindungi login (session auth + CSRF).

## Teknologi

| Bagian | Teknologi |
| --- | --- |
| Backend | Laravel 12 (PHP 8.2+) |
| Frontend | Vue 3 (Composition API), Vue Router, Pinia |
| Styling | Tailwind CSS v4 |
| Excel | maatwebsite/excel (PhpSpreadsheet) |
| Email | Laravel Mail (default `log`, siap diarahkan ke SMTP) |
| Database | SQLite (default) / MySQL |

## Struktur Data

- `batches` — kelas/batch pelatihan (kode unik `CLSR-<bulan><tahun>-<urutan>`, tanggal
  pelaksanaan, waktu, lokasi, kuota, status).
- `registrations` — pendaftar (nama, KTP terenkripsi + `ktp_hash`, email pribadi, email
  user, no. pekerja/GGI, perusahaan, fungsi, posisi, status pekerjaan, status pelatihan,
  status pendaftaran, dll).

Status pendaftaran: `registered → invited → confirmed → attended`
(`cancelled` / `no_show` untuk batal / tidak hadir).

## Instalasi

```bash
composer install
cp .env.example .env
php artisan key:generate

# buat database sqlite bila belum ada
php artisan migrate --seed

npm install
npm run build
```

Menjalankan di development:

```bash
# Terminal 1 — server
php artisan serve --port=8000

# Terminal 2 — asset dev (hot reload)
npm run dev
```

Akses:

- **Halaman pendaftaran umum:** `http://localhost:8000/`
- **Login admin:** `http://localhost:8000/admin` (halaman login Blade, terpisah dari halaman publik)
- **Dashboard admin:** `http://localhost:8000/admin/dashboard` (SPA Vue, butuh login)
- **Login admin:** `admin@clsr.local` / `admin1234`
  (diatur lewat variabel `ADMIN_EMAIL` & `ADMIN_PASSWORD` di `.env`)

> Akun admin dibuat oleh `DatabaseSeeder`. Ubah password melalui `.env` lalu jalankan ulang
> `php artisan db:seed` untuk memperbarui.

## Email

Secara default `MAIL_MAILER=log` — isi email ditulis ke `storage/logs/laravel.log`.
Aktifkan pengiriman nyata dengan mengubah `.env`, misalnya:

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=no-reply@clsr.local
```

## API Ringkas

| Method | Endpoint | Akses | Keterangan |
| --- | --- | --- | --- |
| GET | `/api/batches/available` | publik | daftar batch terbuka + kuota |
| POST | `/api/registrations` | publik | pendaftaran peserta |
| POST | `/api/check-ktp` | publik | cek duplikasi KTP |
| POST | `/admin/login` | publik | login admin (form Blade, session) |
| POST | `/admin/logout` | admin | logout (form) |
| GET | `/api/admin/stats` | admin | statistik dashboard |
| GET | `/api/admin/batches` | admin | daftar batch |
| POST | `/api/admin/batches` | admin | buat batch |
| PATCH | `/api/admin/batches/{id}/toggle-lock` | admin | kunci / buka |
| GET | `/api/admin/registrations` | admin | daftar peserta (filter + paginasi) |
| POST | `/api/admin/registrations/{id}/invite` | admin | kirim undangan email |
| POST | `/api/admin/registrations/{id}/confirm` | admin | konfirmasi hadir |
| POST | `/api/admin/registrations/{id}/attend` | admin | tandai hadir |
| POST | `/api/admin/registrations/{id}/no-show` | admin | tandai tidak hadir |
| DELETE | `/api/admin/registrations/{id}` | admin | batalkan (kembalikan kuota) |
| GET | `/api/admin/registrations/export` | admin | download Excel |

## Keamanan & Catatan Produksi

- Pastikan `APP_ENV=production` dan `APP_DEBUG=false` saat production.
- Gunakan HTTPS (termasuk cookie `SESSION_SECURE_COOKIE=true`) agar token & sesi aman.
- Ganti `ADMIN_PASSWORD` dengan password yang kuat.
- Untuk skala besar, aktifkan queue worker untuk email:
  `php artisan queue:work` (email saat ini dikirim sinkron demi kesederhanaan).