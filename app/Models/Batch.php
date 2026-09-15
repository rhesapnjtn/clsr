<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    use HasFactory;

    public const MAX_PARTICIPANTS = 30;

    protected $fillable = [
        'code',
        'title',
        'training_date',
        'start_time',
        'end_time',
        'location',
        'max_participants',
        'status',
        'is_locked',
    ];

    protected function casts(): array
    {
        return [
            'training_date' => 'date',
            'is_locked' => 'boolean',
        ];
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function activeCount(): int
    {
        return $this->registrations()
            ->whereNotIn('status', ['cancelled'])
            ->count();
    }

    public function slotsLeft(): int
    {
        return max(0, $this->max_participants - $this->activeCount());
    }

    public function isFull(): bool
    {
        return $this->activeCount() >= $this->max_participants;
    }

    public function getQuotaAttribute(): array
    {
        return [
            'used' => $this->activeCount(),
            'total' => $this->max_participants,
            'left' => $this->slotsLeft(),
            'is_full' => $this->isFull(),
        ];
    }
}
