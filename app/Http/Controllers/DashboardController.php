<?php

namespace App\Http\Controllers;

use App\Models\CaseNotification;
use App\Models\Hearing;
use App\Models\LegalCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();
        $cases = LegalCase::query();

        if ($user->hasRole('judge')) {
            $cases->where('judge_id', $user->id);
        } elseif ($user->hasRole('advocate')) {
            $cases->where('advocate_id', $user->id);
        } elseif ($user->hasRole('client')) {
            $cases->where('client_id', $user->id);
        }

        $baseCases = clone $cases;
        $caseIds = (clone $baseCases)->pluck('id');
        $todayHearingCount = Hearing::whereIn('legal_case_id', $caseIds)->whereDate('scheduled_at', today())->count();
        $disposedCases = (clone $cases)->where('status', 'disposed')->count();
        $urgentCases = (clone $cases)->where('priority', 'urgent')->orderBy('next_hearing_date')->limit(6)->get();
        $judgeWorkloads = User::whereHas('role', fn ($query) => $query->where('slug', 'judge'))
            ->withCount([
                'judgedCases as assigned_cases_count',
                'judgedCases as pending_hearings_count' => fn ($query) => $query->whereHas('hearings', fn ($hearingQuery) => $hearingQuery->where('status', 'scheduled')),
                'judgedCases as disposed_cases_count' => fn ($query) => $query->where('status', 'disposed'),
            ])
            ->limit(5)
            ->get();

        return view('dashboard.index', [
            'roleName' => $user->role?->name ?? 'User',
            'totalCases' => (clone $cases)->count(),
            'pendingHearings' => Hearing::whereIn('legal_case_id', $caseIds)->where('status', 'scheduled')->count(),
            'todayHearingCount' => $todayHearingCount,
            'disposedCases' => $disposedCases,
            'upcomingHearings' => Hearing::with('legalCase')->where('scheduled_at', '>=', now())->orderBy('scheduled_at')->limit(6)->get(),
            'notifications' => CaseNotification::where('user_id', $user->id)->latest()->limit(6)->get(),
            'recentCases' => (clone $cases)->latest()->limit(8)->get(),
            'urgentCases' => $urgentCases,
            'judgeWorkloads' => $judgeWorkloads,
            'usersCount' => User::count(),
            'statusStats' => LegalCase::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
        ]);
    }
}
