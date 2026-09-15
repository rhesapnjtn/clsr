<?php

namespace App\Http\Controllers;

use App\Exports\RegistrationsExport;
use App\Mail\InvitationMail;
use App\Models\Batch;
use App\Models\Registration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class RegistrationController extends Controller
{
    private const STATUS_LABELS = [
        'registered' => 'Terdaftar',
        'invited' => 'Undangan Dikirim',
        'confirmed' => 'Konfirmasi Hadir',
        'attended' => 'Hadir',
        'cancelled' => 'Batal',
        'no_show' => 'Tidak Hadir',
    ];

    public function checkKtp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ktp' => ['required', 'digits_between:15,16'],
        ]);

        return response()->json([
            'exists' => Registration::isKtpRegistered($validated['ktp']),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'batch_id' => ['required', 'exists:batches,id'],
            'name' => ['required', 'string', 'max:255'],
            'ktp' => ['required', 'digits_between:15,16'],
            'email_private' => ['required', 'email', 'max:255'],
            'email_user' => ['nullable', 'email', 'max:255'],
            'worker_number' => ['nullable', 'string', 'max:50'],
            'company_name' => ['required', 'string', 'max:255'],
            'function' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'employment_status' => ['required', 'string', 'max:255'],
            'training_status' => ['required', 'in:baru,refreshment'],
            'training_date' => ['nullable', 'date'],
        ]);

        $batch = Batch::withoutGlobalScopes()->find($data['batch_id']);

        if (! $batch) {
            throw ValidationException::withMessages(['batch_id' => ['Batch tidak ditemukan.']]);
        }

        if ($batch->is_locked || $batch->status === 'locked' || $batch->status === 'completed') {
            throw ValidationException::withMessages(['batch_id' => ['Pendaftaran untuk batch ini sudah ditutup.']]);
        }

        if ($batch->training_date->lt(now()->startOfDay())) {
            throw ValidationException::withMessages(['batch_id' => ['Batch sudah melewati tanggal pelaksanaan.']]);
        }

        $created = null;

        try {
            DB::transaction(function () use ($data, $batch, &$created) {
                $batch = Batch::lockForUpdate()->find($batch->id);

                if ($batch->isFull()) {
                    throw ValidationException::withMessages([
                        'batch_id' => ['Kuota batch ini sudah penuh (30 peserta). Pendaftaran ditutup.'],
                    ]);
                }

                if (Registration::isKtpRegistered($data['ktp'])) {
                    throw ValidationException::withMessages([
                        'ktp' => ['Nomor KTP ini sudah terdaftar. Satu KTP hanya dapat mendaftar satu kali.'],
                    ]);
                }

                $created = Registration::create([
                    'batch_id' => $batch->id,
                    'name' => $data['name'],
                    'ktp_number' => encrypt($data['ktp']),
                    'ktp_hash' => Registration::ktpHash($data['ktp']),
                    'email_private' => $data['email_private'],
                    'email_user' => $data['email_user'] ?? null,
                    'worker_number' => $data['worker_number'] ?? null,
                    'company_name' => $data['company_name'],
                    'function' => $data['function'],
                    'position' => $data['position'],
                    'employment_status' => $data['employment_status'],
                    'training_status' => $data['training_status'],
                    'training_date' => $data['training_date'] ?? $batch->training_date,
                    'status' => 'registered',
                ]);

                if ($batch->isFull()) {
                    $batch->is_locked = true;
                    $batch->status = 'locked';
                    $batch->save();
                }
            });
        } catch (ValidationException $e) {
            return $this->validationError($e);
        }

        return response()->json([
            'registration' => [
                'id' => $created->id,
                'name' => $created->name,
                'code' => sprintf('CLSR-%s-%04d', now()->format('Ymd'), $created->id),
                'batch' => [
                    'code' => $batch->code,
                    'title' => $batch->title,
                    'training_date_label' => $batch->training_date->translatedFormat('l, d F Y'),
                    'location' => $batch->location,
                    'slots_left' => $batch->slotsLeft(),
                    'is_full' => $batch->isFull(),
                ],
            ],
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $query = Registration::with('batch')
            ->when($request->filled('batch_id'), fn ($q) => $q->where('batch_id', $request->input('batch_id')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = '%'.trim($request->input('search')).'%';
                $q->where(function ($sub) use ($s) {
                    $sub->where('name', 'like', $s)
                        ->orWhere('company_name', 'like', $s)
                        ->orWhere('position', 'like', $s)
                        ->orWhere('email_private', 'like', $s);
                });
            })
            ->orderByDesc('created_at');

        $registrations = $query->paginate($request->integer('per_page', 15));

        $items = collect($registrations->items())->map(fn (Registration $r) => $this->format($r));

        return response()->json([
            'registrations' => $items,
            'meta' => [
                'current_page' => $registrations->currentPage(),
                'last_page' => $registrations->lastPage(),
                'per_page' => $registrations->perPage(),
                'total' => $registrations->total(),
            ],
        ]);
    }

    public function stats(): JsonResponse
    {
        $total = Registration::count();
        $byStatus = Registration::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $byBatch = Batch::withCount([
            'registrations as active_count' => fn ($q) => $q->where('status', '!=', 'cancelled'),
        ])->orderByDesc('training_date')->limit(10)->get()->map(function (Batch $b) {
            return [
                'id' => $b->id,
                'code' => $b->code,
                'title' => $b->title,
                'training_date_label' => $b->training_date->translatedFormat('l, d F Y'),
                'active_count' => $b->active_count,
                'max_participants' => $b->max_participants,
                'is_full' => $b->active_count >= $b->max_participants,
            ];
        });

        return response()->json([
            'stats' => [
                'total' => $total,
                'by_status' => $byStatus,
                'registered' => $byStatus['registered'] ?? 0,
                'invited' => $byStatus['invited'] ?? 0,
                'confirmed' => $byStatus['confirmed'] ?? 0,
                'attended' => $byStatus['attended'] ?? 0,
                'cancelled' => $byStatus['cancelled'] ?? 0,
                'no_show' => $byStatus['no_show'] ?? 0,
            ],
            'by_batch' => $byBatch,
        ]);
    }

    public function invite(Registration $registration): JsonResponse
    {
        if ($registration->status === 'cancelled') {
            return response()->json(['message' => 'Peserta sudah batal, tidak dapat mengirim undangan.'], 422);
        }

        Mail::to($registration->email_private)
            ->when($registration->email_user, fn ($mail) => $mail->cc($registration->email_user))
            ->send(new InvitationMail($registration));

        $registration->update([
            'status' => 'invited',
            'invitation_sent_at' => now(),
        ]);

        return response()->json([
            'message' => 'Undangan berhasil dikirim.',
            'registration' => $this->format($registration->fresh('batch')),
        ]);
    }

    public function confirm(Registration $registration): JsonResponse
    {
        if ($registration->status === 'cancelled') {
            return response()->json(['message' => 'Peserta sudah batal.'], 422);
        }

        $registration->update(['status' => 'confirmed']);

        return response()->json([
            'message' => 'Peserta dikonfirmasi hadir.',
            'registration' => $this->format($registration->fresh('batch')),
        ]);
    }

    public function attend(Registration $registration): JsonResponse
    {
        $registration->update(['status' => 'attended']);

        return response()->json([
            'message' => 'Peserta ditandai hadir.',
            'registration' => $this->format($registration->fresh('batch')),
        ]);
    }

    public function noShow(Registration $registration): JsonResponse
    {
        $registration->update(['status' => 'no_show']);

        return response()->json([
            'message' => 'Peserta ditandai tidak hadir.',
            'registration' => $this->format($registration->fresh('batch')),
        ]);
    }

    public function cancel(Request $request, Registration $registration): JsonResponse
    {
        $data = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        if ($registration->status === 'attended' || $registration->status === 'no_show') {
            return response()->json(['message' => 'Peserta sudah memiliki status akhir.'], 422);
        }

        $registration->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancel_reason' => $data['reason'],
        ]);

        $batch = $registration->batch;

        if ($batch && $batch->is_locked && $batch->activeCount() < $batch->max_participants) {
            $batch->update(['is_locked' => false, 'status' => 'open']);
        }

        return response()->json([
            'message' => 'Pendaftaran dibatalkan dan kuota dikembalikan.',
            'registration' => $this->format($registration->fresh('batch')),
        ]);
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'batch_id' => ['nullable', 'exists:batches,id'],
            'status' => ['nullable', 'string'],
        ]);

        $name = 'daftar-peserta-clsr-'.now()->format('Ymd-His').'.xlsx';

        return Excel::download(
            new RegistrationsExport($validated['batch_id'] ?? null, $validated['status'] ?? null),
            $name
        );
    }

    private function format(Registration $r): array
    {
        return [
            'id' => $r->id,
            'name' => $r->name,
            'email_private' => $r->email_private,
            'email_user' => $r->email_user,
            'worker_number' => $r->worker_number,
            'company_name' => $r->company_name,
            'function' => $r->function,
            'position' => $r->position,
            'employment_status' => $r->employment_status,
            'training_status' => $r->training_status,
            'training_status_label' => $r->training_status === 'baru' ? 'Baru' : 'Refreshment',
            'training_date' => $r->training_date?->toDateString(),
            'status' => $r->status,
            'status_label' => self::STATUS_LABELS[$r->status] ?? $r->status,
            'ktp_masked' => $this->maskKtp($r->ktp_number),
            'invitation_sent_at' => $r->invitation_sent_at?->toISOString(),
            'cancelled_at' => $r->cancelled_at?->toISOString(),
            'cancel_reason' => $r->cancel_reason,
            'notes' => $r->notes,
            'batch' => $r->batch ? [
                'id' => $r->batch->id,
                'code' => $r->batch->code,
                'title' => $r->batch->title,
                'training_date_label' => $r->batch->training_date->translatedFormat('l, d F Y'),
            ] : null,
            'created_at' => $r->created_at?->toISOString(),
        ];
    }

    private function maskKtp(?string $encrypted): ?string
    {
        if (! $encrypted) {
            return null;
        }

        try {
            $ktp = decrypt($encrypted);
        } catch (\Throwable $e) {
            return null;
        }

        return substr($ktp, 0, 4).'••••••'.substr($ktp, -4);
    }

    private function validationError(ValidationException $e): JsonResponse
    {
        return response()->json([
            'message' => $e->getMessage(),
            'errors' => $e->errors(),
        ], 422);
    }
}
