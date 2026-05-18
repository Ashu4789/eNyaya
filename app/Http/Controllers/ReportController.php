<?php

namespace App\Http\Controllers;

use App\Models\Hearing;
use App\Models\LegalCase;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type', 'pending');
        $cases = LegalCase::query()
            ->when($type === 'pending', fn ($q) => $q->whereNotIn('status', ['disposed', 'dismissed']))
            ->when($type === 'completed', fn ($q) => $q->whereIn('status', ['disposed', 'dismissed']))
            ->latest()
            ->paginate(20);

        return view('reports.index', [
            'type' => $type,
            'cases' => $cases,
            'monthlyHearings' => Hearing::selectRaw('DATE_FORMAT(scheduled_at, "%Y-%m") as month, count(*) as total')->groupBy('month')->orderBy('month', 'desc')->limit(12)->get(),
            'activityUsers' => User::withCount(['filedCases', 'advocatedCases', 'judgedCases'])->orderBy('name')->limit(20)->get(),
        ]);
    }

    public function export(Request $request)
    {
        $rows = LegalCase::select('case_number', 'title', 'category', 'status', 'priority', 'filing_date')->get();
        $csv = "Case Number,Title,Category,Status,Priority,Filing Date\n";
        foreach ($rows as $row) {
            $csv .= implode(',', array_map(fn ($value) => '"'.str_replace('"', '""', (string) $value).'"', $row->toArray()))."\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="enyaya-cases-report.csv"',
        ]);
    }
}
