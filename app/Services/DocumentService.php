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

    public function store(LegalCase $case, UploadedFile $file, string $label): array
    {
        $path = $file->store("case-documents/{$case->case_number}", 'local');

        $metadata = [
            'case_id' => $case->id,
            'case_number' => $case->case_number,
            'label' => $label,
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
}
