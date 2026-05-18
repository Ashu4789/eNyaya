<?php

namespace App\Services;

use App\Models\LegalCase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CaseService
{
    public function __construct(private readonly MongoLogService $mongoLogService)
    {
    }

    public function create(array $data): LegalCase
    {
        $case = LegalCase::create(array_merge($data, [
            'case_number' => $data['case_number'] ?? $this->nextCaseNumber(),
        ]));

        $this->audit('case_created', $case, $data);

        return $case;
    }

    public function update(LegalCase $case, array $data): LegalCase
    {
        $before = $case->only(array_keys(Arr::except($data, ['_token', '_method'])));
        $case->update($data);
        $this->audit('case_updated', $case, ['before' => $before, 'after' => $data]);

        return $case;
    }

    public function delete(LegalCase $case): void
    {
        $this->audit('case_deleted', $case, $case->toArray());
        $case->delete();
    }

    private function nextCaseNumber(): string
    {
        return 'ENY-'.now()->format('Y').'-'.str_pad((string) (LegalCase::max('id') + 1), 5, '0', STR_PAD_LEFT);
    }

    private function audit(string $action, LegalCase $case, array $data): void
    {
        $this->mongoLogService->record('audit_history', [
            'action' => $action,
            'case_id' => $case->id,
            'case_number' => $case->case_number,
            'actor_id' => Auth::id(),
            'payload' => $data,
        ]);
    }
}
