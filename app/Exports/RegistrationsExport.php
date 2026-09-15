<?php

namespace App\Exports;

use App\Models\Registration;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RegistrationsExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    private const STATUS_LABELS = [
        'registered' => 'Terdaftar',
        'invited' => 'Undangan Dikirim',
        'confirmed' => 'Konfirmasi Hadir',
        'attended' => 'Hadir',
        'cancelled' => 'Batal',
        'no_show' => 'Tidak Hadir',
    ];

    public function __construct(
        private readonly ?int $batchId = null,
        private readonly ?string $status = null,
    ) {}

    public function query()
    {
        return Registration::query()
            ->with('batch')
            ->when($this->batchId, fn ($q) => $q->where('batch_id', $this->batchId))
            ->when($this->status, fn ($q) => $q->where('status', $this->status))
            ->orderByDesc('created_at');
    }

    public function headings(): array
    {
        return [
            'No',
            'Batch',
            'Tanggal Pelaksanaan',
            'Nama',
            'No. KTP',
            'Email Pribadi',
            'Email User',
            'No. Pekerja / GGI',
            'Nama Perusahaan',
            'Fungsi',
            'Posisi / Jabatan',
            'Status Pekerjaan',
            'Status Pelatihan',
            'Status Pendaftaran',
            'Waktu Daftar',
        ];
    }

    public function map($registration): array
    {
        static $i = 0;

        return [
            ++$i,
            $registration->batch?->code,
            $registration->training_date?->format('d/m/Y'),
            $registration->name,
            $this->plainKtp($registration->ktp_number),
            $registration->email_private,
            $registration->email_user,
            $registration->worker_number,
            $registration->company_name,
            $registration->function,
            $registration->position,
            $registration->employment_status,
            $registration->training_status === 'baru' ? 'Baru' : 'Refreshment',
            self::STATUS_LABELS[$registration->status] ?? $registration->status,
            $registration->created_at?->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:O1')->getFont()->setBold(true);
        $sheet->getStyle('A1:O1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FF1E3A5F');
        $sheet->getStyle('A1:O1')->getFont()->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle('A1:O1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:O1')->getAlignment()->setVertical('center');
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(16);
        $sheet->getColumnDimension('C')->setWidth(18);
        $sheet->getColumnDimension('D')->setWidth(28);
        $sheet->getColumnDimension('E')->setWidth(18);
        $sheet->getColumnDimension('F')->setWidth(30);
        $sheet->getColumnDimension('G')->setWidth(30);
        $sheet->getColumnDimension('H')->setWidth(18);
        $sheet->getColumnDimension('I')->setWidth(26);
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('K')->setWidth(22);
        $sheet->getColumnDimension('L')->setWidth(22);
        $sheet->getColumnDimension('M')->setWidth(18);
        $sheet->getColumnDimension('N')->setWidth(22);
        $sheet->getColumnDimension('O')->setWidth(20);
        $sheet->getRowDimension(1)->setRowHeight(30);

        return $sheet;
    }

    private function plainKtp(?string $encrypted): ?string
    {
        if (! $encrypted) {
            return null;
        }

        try {
            return decrypt($encrypted);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
