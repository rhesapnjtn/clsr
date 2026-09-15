<x-mail::message>
# Undangan Pelatihan CLSR

Yth. **{{ $registration->name }}**,

Anda terdaftar sebagai peserta pelatihan **COMPETENT LAWS AND SAFETY REGULATIONS (CLSR)**. Berikut detail kegiatan Anda:

| | |
|---|---|
| **Kode Batch** | {{ $registration->batch?->code }} |
| **Batch** | {{ $registration->batch?->title }} |
| **Tanggal Pelaksanaan** | {{ optional($registration->batch?->training_date)->translatedFormat('l, d F Y') }} |
| **Waktu** | {{ $registration->batch?->start_time ? \Carbon\Carbon::parse($registration->batch->start_time)->format('H:i').' - '.($registration->batch->end_time ? \Carbon\Carbon::parse($registration->batch->end_time)->format('H:i') : 'Selesai') : 'Menunggu informasi selanjutnya' }} |
| **Lokasi** | {{ $registration->batch?->location ?? 'Menunggu informasi selanjutnya' }} |
| **Nama** | {{ $registration->name }} |
| **Perusahaan** | {{ $registration->company_name }} |

Mohon hadir tepat waktu dan membawa KTP asli untuk verifikasi pada saat registrasi ulang (re-registration) di lokasi pelatihan.

### Hal yang perlu dibawa
- KTP asli
- Alat tulis
- Perlengkapan safety (apabila dilaksanakan di area operasi)

Apabila Anda berhalangan hadir, mohon segera menghubungi **CS / Penyelenggara** agar kami dapat memberikan slot kepada peserta lain.

Terima kasih atas partisipasi Anda.

Salam,

**Panitia Pelatihan CLSR**
</x-mail::message>