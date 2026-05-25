@extends('layouts.app')
@section('page-title', $case->case_number)
@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-header"><h2>{{ $case->title }}</h2><a class="btn btn-outline-primary btn-sm" href="{{ route('cases.edit',$case) }}">Edit</a></div>
            <div class="timeline mb-3">@foreach(['filed','accepted','under_review','hearing_scheduled','in_progress','judgment_reserved','disposed'] as $step)<span class="{{ $case->status === $step ? 'active' : '' }}">{{ str_replace('_',' ',$step) }}</span>@endforeach</div>
            <div class="row g-2 mb-3">
                <div class="col-md-4"><div class="case-signal @if($adjournmentCount >= 3) danger @elseif($case->next_hearing_date && $case->next_hearing_date->diffInDays(now(), false) >= -7) warning @endif"><span>Adjournments</span><strong>{{ $adjournmentCount }}</strong></div></div>
                <div class="col-md-4"><div class="case-signal"><span>Vakalatnama</span><strong>{{ str_replace('_',' ',ucfirst($case->vakalatnama_status ?? 'not_uploaded')) }}</strong></div></div>
                <div class="col-md-4"><div class="case-signal"><span>Priority</span><strong>{{ ucfirst($case->priority) }}</strong></div></div>
            </div>
            <dl class="row"><dt class="col-sm-3">Category</dt><dd class="col-sm-9">{{ $case->category }}</dd><dt class="col-sm-3">Petitioner</dt><dd class="col-sm-9">{{ $case->petitioner_name }}</dd><dt class="col-sm-3">Respondent</dt><dd class="col-sm-9">{{ $case->respondent_name }}</dd><dt class="col-sm-3">Advocate</dt><dd class="col-sm-9">{{ $case->advocate?->name ?? 'Unassigned' }}</dd><dt class="col-sm-3">Judge</dt><dd class="col-sm-9">{{ $case->judge?->name ?? 'Unassigned' }}</dd></dl>
            <p>{{ $case->summary }}</p>
        </div>
        <div class="panel mt-3"><h2>Hearing History</h2><table class="table"><thead><tr><th>Date</th><th>Courtroom</th><th>Status</th><th>Adjournment</th><th>Notes</th></tr></thead><tbody>@forelse($case->hearings as $hearing)<tr class="@if($hearing->status === 'adjourned') table-warning @endif"><td>{{ $hearing->scheduled_at->format('d M Y h:i A') }}</td><td>{{ $hearing->courtroom }}</td><td>{{ $hearing->status }}</td><td>{{ $hearing->adjournment_reason ? $hearing->adjournment_requested_by.' - '.$hearing->adjournment_reason : '-' }}</td><td>{{ $hearing->notes }}</td></tr>@empty<tr><td colspan="5">No hearings yet.</td></tr>@endforelse</tbody></table></div>
    </div>
    <div class="col-lg-4">
        <div class="panel">
            <h2>Evidence & Documents</h2>
            <form method="POST" action="{{ route('cases.documents.store',$case) }}" enctype="multipart/form-data" class="vstack gap-3">@csrf
                <input class="form-control" name="label" placeholder="Document label" required>
                <select class="form-select" name="category" required><option value="evidence">Evidence</option><option value="vakalatnama">Vakalatnama</option><option value="affidavit">Affidavit</option><option value="petition">Petition</option><option value="hearing_notice">Hearing Notice</option><option value="other">Other</option></select>
                <input class="form-control" name="tags" placeholder="Tags: CCTV, audio, screenshot">
                <input class="form-control" type="file" name="document" accept=".pdf,.jpg,.jpeg,.png,.mp4,.mp3,.wav" required>
                <button class="btn btn-success">Upload</button>
            </form>
        </div>
        <div class="panel mt-3">
            <h2>Case Documents File List</h2>
            <div class="list-group list-group-flush">
                @forelse($documents as $doc)
                    <div class="list-group-item d-flex justify-content-between align-items-center py-2 px-0 bg-transparent border-bottom">
                        <div>
                            <strong>{{ $doc['label'] }}</strong>
                            <span class="badge bg-secondary ms-1">{{ $doc['category'] }}</span>
                            <div class="text-muted small">
                                {{ $doc['original_name'] }} ({{ number_format($doc['size'] / 1024, 1) }} KB)
                                @if(!empty($doc['tags']))
                                    · Tags: {{ is_array($doc['tags']) ? implode(', ', $doc['tags']) : $doc['tags'] }}
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('cases.documents.download', [$case, 'path' => $doc['stored_path']]) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i></a>
                    </div>
                @empty
                    <p class="text-muted small">No documents uploaded for this case yet.</p>
                @endforelse
            </div>
        </div>
        <div class="panel mt-3">
            <h2>Legal Templates</h2>
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action" href="{{ route('templates.download','affidavit') }}"><i class="bi bi-download"></i> Affidavit</a>
                <a class="list-group-item list-group-item-action" href="{{ route('templates.download','petition') }}"><i class="bi bi-download"></i> Petition</a>
                <a class="list-group-item list-group-item-action" href="{{ route('templates.download','vakalatnama') }}"><i class="bi bi-download"></i> Vakalatnama</a>
                <a class="list-group-item list-group-item-action" href="{{ route('templates.download','hearing-notice') }}"><i class="bi bi-download"></i> Hearing Notice</a>
            </div>
        </div>
        <form method="POST" action="{{ route('cases.destroy',$case) }}" class="mt-3">@csrf @method('DELETE')<button class="btn btn-outline-danger w-100" onclick="return confirm('Delete this case?')">Delete Case</button></form>
    </div>
</div>
@endsection
