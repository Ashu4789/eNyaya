<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LegalCase;
use Illuminate\Http\Request;

class CaseApiController extends Controller
{
    public function index(Request $request)
    {
        return LegalCase::with(['client', 'advocate', 'judge'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->priority, fn ($q) => $q->where('priority', $request->priority))
            ->paginate(15);
    }

    public function show(LegalCase $case)
    {
        return $case->load(['client', 'advocate', 'judge', 'hearings']);
    }
}
