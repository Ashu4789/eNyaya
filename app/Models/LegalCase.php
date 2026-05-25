<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LegalCase extends Model
{
    public const STATUSES = [
        'filed',
        'accepted',
        'under_review',
        'hearing_scheduled',
        'in_progress',
        'judgment_reserved',
        'disposed',
        'dismissed',
    ];

    public const PRIORITIES = [
        'low',
        'normal',
        'high',
        'urgent',
    ];

    public const CATEGORIES = [
        'Urgent',
        'Bail',
        'Civil',
        'Criminal',
        'Family',
        'Consumer',
        'Cyber Crime',
    ];

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
        'vakalatnama_path',
        'vakalatnama_status',
        'vakalatnama_verified_at',
    ];

    protected function casts(): array
    {
        return [
            'filing_date' => 'date',
            'next_hearing_date' => 'datetime',
            'vakalatnama_verified_at' => 'datetime',
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
