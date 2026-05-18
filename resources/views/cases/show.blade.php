@extends('layouts.app')
@section('page-title', $case->case_number)
@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-header"><h2>{{ $case->title }}</h2><a class="btn btn-outline-primary btn-sm" href="{{ route('cases.edit',$case) }}">Edit</a></div>
            <div class="timeline mb-3">@foreach(['filed','under_review','hearing_scheduled','in_progress','disposed'] as $step)<span class="{{ $case->status === $step ? 'active' : '' }}">{{ str_replace('_',' ',$step) }}</span>@endforeach</div>
            <dl class="row"><dt class="col-sm-3">Category</dt><dd class="col-sm-9">{{ $case->category }}</dd><dt class="col-sm-3">Petitioner</dt><dd class="col-sm-9">{{ $case->petitioner_name }}</dd><dt class="col-sm-3">Respondent</dt><dd class="col-sm-9">{{ $case->respondent_name }}</dd><dt class="col-sm-3">Advocate</dt><dd class="col-sm-9">{{ $case->advocate?->name ?? 'Unassigned' }}</dd><dt class="col-sm-3">Judge</dt><dd class="col-sm-9">{{ $case->judge?->name ?? 'Unassigned' }}</dd></dl>
            <p>{{ $case->summary }}</p>
        </div>
        <div class="panel mt-3"><h2>Hearing History</h2><table class="table"><thead><tr><th>Date</th><th>Courtroom</th><th>Status</th><th>Notes</th></tr></thead><tbody>@forelse($case->hearings as $hearing)<tr><td>{{ $hearing->scheduled_at->format('d M Y h:i A') }}</td><td>{{ $hearing->courtroom }}</td><td>{{ $hearing->status }}</td><td>{{ $hearing->notes }}</td></tr>@empty<tr><td colspan="4">No hearings yet.</td></tr>@endforelse</tbody></table></div>
    </div>
    <div class="col-lg-4">
        <div class="panel">
            <h2>Upload Document</h2>
            <form method="POST" action="{{ route('cases.documents.store',$case) }}" enctype="multipart/form-data" class="vstack gap-3">@csrf
                <input class="form-control" name="label" placeholder="Document label" required>
                <input class="form-control" type="file" name="document" accept=".pdf,.jpg,.jpeg,.png" required>
                <button class="btn btn-success">Upload</button>
            </form>
        </div>
        <form method="POST" action="{{ route('cases.destroy',$case) }}" class="mt-3">@csrf @method('DELETE')<button class="btn btn-outline-danger w-100" onclick="return confirm('Delete this case?')">Delete Case</button></form>
    </div>
</div>
@endsection
