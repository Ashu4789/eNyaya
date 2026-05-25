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
            'category' => ['required', 'in:evidence,vakalatnama,affidavit,petition,hearing_notice,other'],
            'tags' => ['nullable', 'string', 'max:255'],
            'document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,mp4,mp3,wav', 'max:51200'],
        ]);

        $metadata = $documents->store($case, $data['document'], $data['label'], $data['category'], $data['tags'] ?? null);

        if ($data['category'] === 'vakalatnama') {
            $case->update([
                'vakalatnama_path' => $metadata['stored_path'],
                'vakalatnama_status' => 'pending',
            ]);
        }

        return back()->with('status', 'Document uploaded and metadata recorded.');
    }

    public function download(Request $request, LegalCase $case, DocumentService $documents)
    {
        $path = $request->query('path');

        // Directory traversal protection: force download path to remain within case directory
        $prefix = "case-documents/{$case->case_number}/";
        abort_unless(str_starts_with($path, $prefix), 403, 'Unauthorized file access.');

        return $documents->download($path);
    }
}
