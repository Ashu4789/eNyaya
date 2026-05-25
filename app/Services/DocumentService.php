<?php

namespace App\Services;

use App\Models\LegalCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    public function __construct(private readonly MongoLogService $mongoLogService)
    {
    }

    public function store(LegalCase $case, UploadedFile $file, string $label, string $category = 'other', ?string $tags = null): array
    {
        $path = $file->store("case-documents/{$case->case_number}", 'local');

        $metadata = [
            'case_id' => $case->id,
            'case_number' => $case->case_number,
            'label' => $label,
            'category' => $category,
            'tags' => collect(explode(',', (string) $tags))->map(fn ($tag) => trim($tag))->filter()->values()->all(),
            'original_name' => $file->getClientOriginalName(),
            'stored_path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'version' => now()->timestamp,
            'uploaded_by' => Auth::id(),
        ];

        $this->mongoLogService->record('document_metadata', $metadata);

        return $metadata;
    }

    public function download(string $path)
    {
        abort_unless(Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->download($path);
    }

    public function getDocuments(LegalCase $case): array
    {
        $docs = $this->mongoLogService->retrieve('document_metadata', ['case_id' => $case->id]);

        if (!empty($docs)) {
            return array_map(function ($doc) {
                return (array) $doc;
            }, $docs);
        }

        $directory = "case-documents/{$case->case_number}";
        if (!Storage::disk('local')->exists($directory)) {
            return [];
        }

        $files = Storage::disk('local')->files($directory);
        $docs = [];
        foreach ($files as $file) {
            $docs[] = [
                'case_id' => $case->id,
                'case_number' => $case->case_number,
                'label' => basename($file),
                'category' => 'other',
                'tags' => [],
                'original_name' => basename($file),
                'stored_path' => $file,
                'mime_type' => Storage::disk('local')->mimeType($file) ?: 'application/octet-stream',
                'size' => Storage::disk('local')->size($file) ?: 0,
                'uploaded_by' => null,
                'recorded_at' => null,
            ];
        }

        return $docs;
    }
}
