<?php

namespace App\Http\Controllers;

use App\Models\LegalCase;
use App\Models\User;
use App\Services\CaseService;
use Illuminate\Http\Request;

class CaseController extends Controller
{
    public function index(Request $request)
    {
        $cases = LegalCase::with(['client', 'advocate', 'judge'])
            ->when($request->filled('q'), fn ($query) => $query->where(function ($inner) use ($request) {
                $inner->where('case_number', 'like', "%{$request->q}%")
                    ->orWhere('title', 'like', "%{$request->q}%")
                    ->orWhere('petitioner_name', 'like', "%{$request->q}%")
                    ->orWhere('respondent_name', 'like', "%{$request->q}%");
            }))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('priority'), fn ($query) => $query->where('priority', $request->priority))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('cases.index', ['cases' => $cases]);
    }

    public function create()
    {
        return view('cases.form', $this->formData(new LegalCase()));
    }

    public function store(Request $request, CaseService $caseService)
    {
        $case = $caseService->create($this->validated($request));

        return redirect()->route('cases.show', $case)->with('status', 'Case registered successfully.');
    }

    public function show(LegalCase $case)
    {
        $case->load(['client', 'advocate', 'judge', 'hearings' => fn ($q) => $q->latest('scheduled_at')]);

        return view('cases.show', ['case' => $case]);
    }

    public function edit(LegalCase $case)
    {
        return view('cases.form', $this->formData($case));
    }

    public function update(Request $request, LegalCase $case, CaseService $caseService)
    {
        $caseService->update($case, $this->validated($request));

        return redirect()->route('cases.show', $case)->with('status', 'Case updated.');
    }

    public function destroy(LegalCase $case, CaseService $caseService)
    {
        $caseService->delete($case);

        return redirect()->route('cases.index')->with('status', 'Case deleted.');
    }

    private function formData(LegalCase $case): array
    {
        return [
            'case' => $case,
            'clients' => User::whereHas('role', fn ($q) => $q->where('slug', 'client'))->orderBy('name')->get(),
            'advocates' => User::whereHas('role', fn ($q) => $q->where('slug', 'advocate'))->orderBy('name')->get(),
            'judges' => User::whereHas('role', fn ($q) => $q->where('slug', 'judge'))->orderBy('name')->get(),
        ];
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'case_number' => ['nullable', 'string', 'max:50', 'unique:legal_cases,case_number,'.$request->route('case')?->id],
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'petitioner_name' => ['required', 'string', 'max:255'],
            'petitioner_contact' => ['nullable', 'string', 'max:100'],
            'respondent_name' => ['required', 'string', 'max:255'],
            'respondent_contact' => ['nullable', 'string', 'max:100'],
            'filing_date' => ['required', 'date'],
            'next_hearing_date' => ['nullable', 'date'],
            'status' => ['required', 'in:filed,under_review,hearing_scheduled,in_progress,disposed,dismissed'],
            'priority' => ['required', 'in:low,normal,high,urgent'],
            'client_id' => ['nullable', 'exists:users,id'],
            'advocate_id' => ['nullable', 'exists:users,id'],
            'judge_id' => ['nullable', 'exists:users,id'],
            'summary' => ['nullable', 'string'],
        ]);
    }
}
