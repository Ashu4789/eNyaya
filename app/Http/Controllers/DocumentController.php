<?php

namespace App\Http\Controllers;

use App\Models\LegalCase;
use App\Services\DocumentService;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function store(Request $request, LegalCase $case, DocumentService $documents)
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:120'],
            'document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        $documents->store($case, $data['document'], $data['label']);

        return back()->with('status', 'Document uploaded and metadata recorded.');
    }
}
