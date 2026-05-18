@extends('layouts.app')
@section('page-title', 'Hearing Calendar')
@section('content')
<div class="row g-3">
    <div class="col-lg-4"><div class="panel"><h2>Schedule Hearing</h2><form method="POST" action="{{ route('hearings.store') }}" class="vstack gap-3">@csrf <select class="form-select" name="legal_case_id" required><option value="">Select case</option>@foreach($cases as $case)<option value="{{ $case->id }}">{{ $case->case_number }} - {{ $case->title }}</option>@endforeach</select><input class="form-control" type="datetime-local" name="scheduled_at" required><input class="form-control" name="courtroom" placeholder="Courtroom" required><input class="form-control" name="purpose" placeholder="Purpose"><textarea class="form-control" name="notes" placeholder="Hearing notes"></textarea><button class="btn btn-primary">Schedule</button></form></div></div>
    <div class="col-lg-8"><div class="panel"><h2>Calendar View</h2><div class="calendar-list">@foreach($hearings as $hearing)<div class="calendar-item"><time>{{ $hearing->scheduled_at->format('d M') }}<small>{{ $hearing->scheduled_at->format('h:i A') }}</small></time><div><strong>{{ $hearing->legalCase->case_number }}</strong><p>{{ $hearing->purpose }} in {{ $hearing->courtroom }}</p></div><span class="badge status">{{ $hearing->status }}</span></div>@endforeach</div>{{ $hearings->links() }}</div></div>
</div>
@endsection
