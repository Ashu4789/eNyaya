<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LegalCase extends Model
{
    protected $fillable = [
        'case_number',
        'title',
        'category',
        'petitioner_name',
        'petitioner_contact',
        'respondent_name',
        'respondent_contact',
        'filing_date',
        'next_hearing_date',
        'status',
        'priority',
        'client_id',
        'advocate_id',
        'judge_id',
        'summary',
    ];

    protected function casts(): array
    {
        return [
            'filing_date' => 'date',
            'next_hearing_date' => 'datetime',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function advocate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'advocate_id');
    }

    public function judge(): BelongsTo
    {
        return $this->belongsTo(User::class, 'judge_id');
    }

    public function hearings(): HasMany
    {
        return $this->hasMany(Hearing::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(CaseNotification::class);
    }
}
