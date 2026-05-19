<?php

namespace App\Http\Controllers;

use App\Models\Hearing;
use App\Models\User;
use Illuminate\Http\Request;

class CauseListController extends Controller
{
    public function index(Request $request)
    {
        return $this->render($request);
    }

    public function export(Request $request)
    {
        return $this->render($request, true);
    }

    private function render(Request $request, bool $print = false)
    {
        $date = $request->date('date') ?? today();

        $hearings = Hearing::with(['legalCase.judge', 'legalCase.advocate'])
            ->whereDate('scheduled_at', $date)
            ->when($request->filled('courtroom'), fn ($query) => $query->where('courtroom', $request->courtroom))
            ->when($request->filled('judge_id'), fn ($query) => $query->whereHas('legalCase', fn ($caseQuery) => $caseQuery->where('judge_id', $request->judge_id)))
            ->get()
            ->sortBy([
                fn ($a, $b) => $this->priorityRank($a) <=> $this->priorityRank($b),
                fn ($a, $b) => strcmp((string) $a->courtroom, (string) $b->courtroom),
                fn ($a, $b) => $a->scheduled_at <=> $b->scheduled_at,
            ])
            ->values();

        return view('cause-list.index', [
            'date' => $date,
            'hearings' => $hearings,
            'courtrooms' => Hearing::select('courtroom')->distinct()->orderBy('courtroom')->pluck('courtroom'),
            'judges' => User::whereHas('role', fn ($query) => $query->where('slug', 'judge'))->orderBy('name')->get(),
            'print' => $print,
        ]);
    }

    private function priorityRank(Hearing $hearing): int
    {
        $case = $hearing->legalCase;

        if ($case?->priority === 'urgent') {
            return 0;
        }

        if (in_array(strtolower((string) $case?->category), ['bail', 'criminal'], true)) {
            return 1;
        }

        return match ($case?->priority) {
            'high' => 2,
            'normal' => 3,
            default => 4,
        };
    }
}
