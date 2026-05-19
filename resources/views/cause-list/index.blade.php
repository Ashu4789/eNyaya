@extends('layouts.app')
@section('page-title', 'Daily Cause List')
@section('content')
<div class="panel cause-list-panel @if($print) print-mode @endif">
    <div class="court-watermark">eNyaya</div>
    <div class="panel-header">
        <div>
            <h2>Daily Cause List</h2>
            <p class="text-muted mb-0">Generated for {{ $date->format('d M Y') }}</p>
        </div>
        <div class="d-flex gap-2 no-print">
            <a class="btn btn-outline-primary" href="{{ route('cause-list.export', request()->query()) }}" target="_blank"><i class="bi bi-filetype-pdf"></i> Export PDF</a>
        </div>
    </div>
    <form class="row g-2 mb-3 no-print">
        <div class="col-md-3"><input class="form-control" type="date" name="date" value="{{ $date->format('Y-m-d') }}"></div>
        <div class="col-md-3"><select class="form-select" name="courtroom"><option value="">All courtrooms</option>@foreach($courtrooms as $courtroom)<option @selected(request('courtroom')===$courtroom) value="{{ $courtroom }}">{{ $courtroom }}</option>@endforeach</select></div>
        <div class="col-md-4"><select class="form-select" name="judge_id"><option value="">All judges</option>@foreach($judges as $judge)<option @selected((string) request('judge_id') === (string) $judge->id) value="{{ $judge->id }}">{{ $judge->name }}</option>@endforeach</select></div>
        <div class="col-md-2"><button class="btn btn-primary w-100"><i class="bi bi-search"></i> Search</button></div>
    </form>
    <div class="table-responsive">
        <table class="table cause-table align-middle">
            <thead><tr><th>Seq.</th><th>Case ID</th><th>Parties</th><th>Category</th><th>Courtroom</th><th>Judge</th><th>Time</th><th>Priority</th></tr></thead>
            <tbody>
            @forelse($hearings as $index => $hearing)
                <tr class="@if($hearing->legalCase?->priority === 'urgent') table-danger @elseif(strtolower((string) $hearing->legalCase?->category) === 'bail') table-warning @endif">
                    <td>{{ $hearing->hearing_sequence ?? $index + 1 }}</td>
                    <td><a href="{{ route('cases.show', $hearing->legalCase) }}">{{ $hearing->legalCase?->case_number }}</a></td>
                    <td><strong>{{ $hearing->legalCase?->petitioner_name }}</strong> vs {{ $hearing->legalCase?->respondent_name }}</td>
                    <td>{{ $hearing->legalCase?->category }}</td>
                    <td>{{ $hearing->courtroom }}</td>
                    <td>{{ $hearing->legalCase?->judge?->name ?? 'Unassigned' }}</td>
                    <td>{{ $hearing->scheduled_at->format('h:i A') }}</td>
                    <td><span class="badge priority-{{ $hearing->legalCase?->priority }}">{{ ucfirst($hearing->legalCase?->priority ?? 'normal') }}</span></td>
                </tr>
            @empty
                <tr><td colspan="8">No hearings listed for this date.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@if($print)
    @push('scripts')
        <script>window.addEventListener('load', () => window.print());</script>
    @endpush
@endif
@endsection
