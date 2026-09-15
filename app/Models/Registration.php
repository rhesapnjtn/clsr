<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
    use HasFactory;

    public const STATUSES = [
        'registered',
        'invited',
        'confirmed',
        'attended',
        'cancelled',
        'no_show',
    ];

    protected $fillable = [
        'batch_id',
        'name',
        'ktp_number',
        'ktp_hash',
        'email_private',
        'email_user',
        'worker_number',
        'company_name',
        'function',
        'position',
        'employment_status',
        'training_status',
        'training_date',
        'status',
        'invitation_sent_at',
        'cancelled_at',
        'cancel_reason',
        'notes',
    ];

    protected $hidden = [
        'ktp_number',
        'ktp_hash',
    ];

    protected function casts(): array
    {
        return [
            'training_date' => 'date',
            'invitation_sent_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function scopeNotCancelled($query)
    {
        return $query->where('status', '!=', 'cancelled');
    }

    public static function ktpHash(string $ktp): string
    {
        return hash('sha256', strtolower(trim(preg_replace('/\D+/', '', $ktp))));
    }

    public static function isKtpRegistered(string $ktp): bool
    {
        return self::where('ktp_hash', self::ktpHash($ktp))
            ->where('status', '!=', 'cancelled')
            ->exists();
    }
}
