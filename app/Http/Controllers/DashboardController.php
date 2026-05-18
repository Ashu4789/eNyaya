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

        return view('dashboard.index', [
            'roleName' => $user->role?->name ?? 'User',
            'totalCases' => (clone $cases)->count(),
            'pendingHearings' => Hearing::whereIn('legal_case_id', $baseCases->pluck('id'))->where('status', 'scheduled')->count(),
            'upcomingHearings' => Hearing::with('legalCase')->where('scheduled_at', '>=', now())->orderBy('scheduled_at')->limit(6)->get(),
            'notifications' => CaseNotification::where('user_id', $user->id)->latest()->limit(6)->get(),
            'recentCases' => (clone $cases)->latest()->limit(8)->get(),
            'usersCount' => User::count(),
            'statusStats' => LegalCase::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
        ]);
    }
}
