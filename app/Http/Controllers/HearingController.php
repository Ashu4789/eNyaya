<?php

namespace App\Http\Controllers;

use App\Models\Hearing;
use App\Models\LegalCase;
use App\Services\MongoLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HearingController extends Controller
{
    public function index()
    {
        return view('hearings.index', [
            'hearings' => Hearing::with('legalCase')->orderBy('scheduled_at')->paginate(12),
            'cases' => LegalCase::orderBy('case_number')->get(),
        ]);
    }

    public function store(Request $request, MongoLogService $logger)
    {
        $data = $request->validate([
            'legal_case_id' => ['required', 'exists:legal_cases,id'],
            'scheduled_at' => ['required', 'date'],
            'courtroom' => ['required', 'string', 'max:100'],
            'purpose' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $hearing = Hearing::create($data + ['created_by' => Auth::id()]);
        $hearing->legalCase()->update(['next_hearing_date' => $hearing->scheduled_at, 'status' => 'hearing_scheduled']);
        $logger->record('hearing_notes', ['hearing_id' => $hearing->id, 'notes' => $data['notes'] ?? null, 'actor_id' => Auth::id()]);

        return back()->with('status', 'Hearing scheduled.');
    }

    public function update(Request $request, Hearing $hearing, MongoLogService $logger)
    {
        $data = $request->validate([
            'scheduled_at' => ['required', 'date'],
            'courtroom' => ['required', 'string', 'max:100'],
            'status' => ['required', 'in:scheduled,rescheduled,completed,adjourned,cancelled'],
            'purpose' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $hearing->update($data);
        $logger->record('audit_history', ['action' => 'hearing_updated', 'hearing_id' => $hearing->id, 'payload' => $data, 'actor_id' => Auth::id()]);

        return back()->with('status', 'Hearing updated.');
    }
}
