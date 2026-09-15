export const FUNCTIONS = [
    'HSSE - Health, Safety, Security & Environment',
    'Engineering',
    'Drilling & Well Services',
    'Production',
    'Maintenance & Reliability',
    'Operations',
    'Procurement & Supply Chain',
    'Project Management',
    'Finance & Accounting',
    'Human Resources',
    'Legal & Compliance',
    'Information Technology',
    'Public Affairs & Relations',
    'Upstream Development',
    'Upstream Optimization',
    'General Services',
    'Quality Assurance',
    'Transportation & Logistic',
    'Lainnya',
];

export const EMPLOYMENT_STATUSES = [
    'Contractor',
    'Karyawan PHM',
    'TKJP (Tenaga Kerja Jasa Penunjang)',
    'Vendor / Supplier',
    'Konsultan',
    'Outsourcing',
    'Magang / Trainee',
    'Lainnya',
];

export const TRAINING_STATUSES = [
    { value: 'baru', label: 'Baru (Pertama kali mengikuti CLSR)' },
    { value: 'refreshment', label: 'Refreshment (Perpanjangan sertifikat)' },
];

export const REGISTRATION_STATUSES = [
    { value: 'registered', label: 'Terdaftar', color: 'bg-sky-100 text-sky-700' },
    { value: 'invited', label: 'Undangan Dikirim', color: 'bg-violet-100 text-violet-700' },
    { value: 'confirmed', label: 'Konfirmasi Hadir', color: 'bg-emerald-100 text-emerald-700' },
    { value: 'attended', label: 'Hadir', color: 'bg-teal-600 text-white' },
    { value: 'cancelled', label: 'Batal', color: 'bg-rose-100 text-rose-700' },
    { value: 'no_show', label: 'Tidak Hadir', color: 'bg-amber-100 text-amber-700' },
];

export const STATUS_MAP = Object.fromEntries(REGISTRATION_STATUSES.map((s) => [s.value, s]));

export function statusChip(status) {
    const s = STATUS_MAP[status] ?? { label: status, color: 'bg-slate-100 text-slate-700' };
    return `${s.color} ${s.label}`;
}

export function formatDate(iso) {
    if (!iso) return '-';
    return new Date(iso).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    });
}

export function formatDateTime(iso) {
    if (!iso) return '-';
    return new Date(iso).toLocaleString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
}