@extends('layouts.app')
@section('page-title', $roleName.' Dashboard')
@section('content')
<div class="row g-3">
    <div class="col-md-3"><div class="metric"><span>Total Cases</span><strong>{{ $totalCases }}</strong><i class="bi bi-folder2"></i></div></div>
    <div class="col-md-3"><div class="metric"><span>Today's Hearings</span><strong>{{ $todayHearingCount }}</strong><i class="bi bi-calendar2-day"></i></div></div>
    <div class="col-md-3"><div class="metric"><span>Pending Hearings</span><strong>{{ $pendingHearings }}</strong><i class="bi bi-hourglass-split"></i></div></div>
    <div class="col-md-3"><div class="metric"><span>Disposed Cases</span><strong>{{ $disposedCases }}</strong><i class="bi bi-check2-circle"></i></div></div>
</div>
<div class="row g-3 mt-1">
    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-header"><h2>Recent Cases</h2><a href="{{ route('cases.create') }}" class="btn btn-sm btn-success"><i class="bi bi-plus-lg"></i> New Case</a></div>
            <div class="table-responsive"><table class="table align-middle sticky-table"><thead><tr><th>Case No.</th><th>Title</th><th>Category</th><th>Status</th><th>Priority</th></tr></thead><tbody>
            @forelse($recentCases as $case)<tr class="@if($case->priority === 'urgent') urgent-row @endif"><td><a href="{{ route('cases.show',$case) }}">{{ $case->case_number }}</a></td><td>{{ $case->title }}</td><td>{{ $case->category }}</td><td><span class="badge status">{{ str_replace('_',' ',$case->status) }}</span></td><td><span class="badge priority-{{ $case->priority }}">{{ ucfirst($case->priority) }}</span></td></tr>@empty<tr><td colspan="5">No cases found.</td></tr>@endforelse
            </tbody></table></div>
        </div>
        <div class="panel mt-3">
            <div class="panel-header"><h2>Judge Workload</h2>@if(auth()->user()->hasRole(['super-admin','court-admin','judge']))<a class="btn btn-sm btn-outline-primary" href="{{ route('reports.index') }}">Reports</a>@endif</div>
            <div class="row g-2">
                @forelse($judgeWorkloads as $judge)
                    <div class="col-md-4"><div class="workload-card"><strong>{{ $judge->name }}</strong><span>{{ $judge->assigned_cases_count }} assigned</span><span>{{ $judge->pending_hearings_count }} pending hearings</span><span>{{ $judge->disposed_cases_count }} disposed</span></div></div>
                @empty
                    <p class="text-muted">No judge assignments yet.</p>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel mb-3"><h2>Urgent Matters</h2>@forelse($urgentCases as $case)<div class="notice alert-notice"><strong><a href="{{ route('cases.show',$case) }}">{{ $case->case_number }}</a></strong><p>{{ $case->title }} · {{ $case->next_hearing_date?->format('d M Y h:i A') ?? 'No hearing date' }}</p></div>@empty<p class="text-muted">No urgent cases.</p>@endforelse</div>
        <div class="panel mb-3"><h2>Upcoming Hearings</h2>@forelse($upcomingHearings as $hearing)<div class="calendar-item compact"><time>{{ $hearing->scheduled_at->format('d M') }}<small>{{ $hearing->scheduled_at->format('h:i A') }}</small></time><div><strong>{{ $hearing->legalCase?->case_number }}</strong><p>{{ $hearing->courtroom }}</p></div></div>@empty<p class="text-muted">No upcoming hearings.</p>@endforelse</div>
        <div class="panel mb-3"><h2>Case Statistics</h2><canvas id="caseChart" height="220"></canvas></div>
        <div class="panel"><h2>Notifications</h2>@forelse($notifications as $notice)<div class="notice"><strong>{{ $notice->title }}</strong><p>{{ $notice->message }}</p></div>@empty<p class="text-muted">No notifications.</p>@endforelse</div>
    </div>
</div>
@endsection
@push('scripts')
<script>
new Chart(document.getElementById('caseChart'), {type:'doughnut', data:{labels:@json($statusStats->keys()), datasets:[{data:@json($statusStats->values()), backgroundColor:['#0b2d4d','#198754','#ffc107','#0dcaf0','#6c757d','#dc3545']}]}});
</script>
@endpush
