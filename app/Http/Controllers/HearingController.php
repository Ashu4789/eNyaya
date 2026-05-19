<?php

namespace App\Http\Controllers;

use App\Models\Hearing;
use App\Models\LegalCase;
use App\Services\MongoLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

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
            'hearing_sequence' => ['nullable', 'integer', 'min:1'],
            'purpose' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $scheduledAt = Carbon::parse($data['scheduled_at']);

        $conflict = Hearing::where('courtroom', $data['courtroom'])
            ->whereBetween('scheduled_at', [
                $scheduledAt->copy()->subMinutes(30),
                $scheduledAt->copy()->addMinutes(30),
            ])
            ->exists();

        if ($conflict) {
            return back()->withErrors(['scheduled_at' => 'This courtroom already has a hearing within the same time slot.'])->withInput();
        }

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
            'hearing_sequence' => ['nullable', 'integer', 'min:1'],
            'status' => ['required', 'in:scheduled,rescheduled,completed,adjourned,cancelled'],
            'purpose' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'adjournment_requested_by' => ['nullable', 'string', 'max:120'],
            'adjournment_reason' => ['nullable', 'string', 'max:255'],
        ]);

        if ($data['status'] === 'adjourned' && blank($data['adjournment_reason'] ?? null)) {
            return back()->withErrors(['adjournment_reason' => 'Adjournment reason is required when a hearing is adjourned.'])->withInput();
        }

        $hearing->update($data);
        $logger->record('audit_history', ['action' => 'hearing_updated', 'hearing_id' => $hearing->id, 'payload' => $data, 'actor_id' => Auth::id()]);

        return back()->with('status', 'Hearing updated.');
    }
}
