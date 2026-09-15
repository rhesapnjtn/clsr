<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BatchController extends Controller
{
    public function available(): JsonResponse
    {
        $now = now();
        $batches = Batch::withCount([
            'registrations as active_count' => fn ($q) => $q->where('status', '!=', 'cancelled'),
        ])
            ->where('training_date', '>=', $now->toDateString())
            ->where('status', '!=', 'completed')
            ->orderBy('training_date')
            ->get()
            ->map(function (Batch $batch) {
                return $this->format($batch);
            });

        return response()->json(['batches' => $batches]);
    }

    public function all(Request $request): JsonResponse
    {
        $batches = Batch::withCount([
            'registrations as active_count' => fn ($q) => $q->where('status', '!=', 'cancelled'),
        ])
            ->orderByDesc('training_date')
            ->get()
            ->map(fn (Batch $b) => $this->format($b));

        return response()->json(['batches' => $batches]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'training_date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'location' => ['nullable', 'string', 'max:255'],
            'max_participants' => ['required', 'integer', 'min:1', 'max:500'],
        ]);

        $data['code'] = $this->nextCode($data['training_date']);
        $batch = Batch::create($data + ['status' => 'open']);

        return response()->json(['batch' => $this->format($batch)], 201);
    }

    public function toggleLock(Batch $batch): JsonResponse
    {
        $batch->is_locked = ! $batch->is_locked;
        $batch->status = $batch->is_locked ? 'locked' : 'open';
        $batch->save();

        return response()->json(['batch' => $this->format($batch)]);
    }

    public function resetLock(Batch $batch): JsonResponse
    {
        $batch->update([
            'is_locked' => false,
            'status' => 'open',
        ]);

        return response()->json(['batch' => $this->format($batch)]);
    }

    public function complete(Batch $batch): JsonResponse
    {
        $batch->update([
            'status' => 'completed',
            'is_locked' => true,
        ]);

        return response()->json(['batch' => $this->format($batch)]);
    }

    private function format(Batch $batch): array
    {
        return [
            'id' => $batch->id,
            'code' => $batch->code,
            'title' => $batch->title,
            'training_date' => $batch->training_date?->toDateString(),
            'training_date_label' => $batch->training_date?->translatedFormat('l, d F Y'),
            'start_time' => $batch->start_time ? Str::substr((string) $batch->start_time, 0, 5) : null,
            'end_time' => $batch->end_time ? Str::substr((string) $batch->end_time, 0, 5) : null,
            'location' => $batch->location,
            'max_participants' => $batch->max_participants,
            'active_count' => $batch->active_count ?? $batch->activeCount(),
            'slots_left' => $batch->slotsLeft(),
            'is_full' => $batch->isFull(),
            'is_locked' => $batch->is_locked,
            'status' => $batch->status,
            'created_at' => $batch->created_at?->toISOString(),
        ];
    }

    private function nextCode(string $date): string
    {
        $short = Carbon::parse($date)->format('mY');
        $seq = Batch::where('code', 'like', "CLSR-$short-%")->count() + 1;

        return sprintf('CLSR-%s-%02d', $short, $seq);
    }
}
