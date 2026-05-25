<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hearing extends Model
{
    public const STATUSES = [
        'scheduled',
        'rescheduled',
        'completed',
        'adjourned',
        'cancelled',
    ];

    protected $fillable = [
        'legal_case_id',
        'scheduled_at',
        'courtroom',
        'hearing_sequence',
        'status',
        'purpose',
        'notes',
        'adjournment_requested_by',
        'adjournment_reason',
        'created_by',
    ];

    protected function casts(): array
    {
        return ['scheduled_at' => 'datetime'];
    }

    public function legalCase(): BelongsTo
    {
        return $this->belongsTo(LegalCase::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
