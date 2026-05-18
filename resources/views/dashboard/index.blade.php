@extends('layouts.app')
@section('page-title', $roleName.' Dashboard')
@section('content')
<div class="row g-3">
    <div class="col-md-3"><div class="metric"><span>Total Cases</span><strong>{{ $totalCases }}</strong><i class="bi bi-folder2"></i></div></div>
    <div class="col-md-3"><div class="metric"><span>Upcoming Hearings</span><strong>{{ $upcomingHearings->count() }}</strong><i class="bi bi-calendar-check"></i></div></div>
    <div class="col-md-3"><div class="metric"><span>Pending Hearings</span><strong>{{ $pendingHearings }}</strong><i class="bi bi-hourglass-split"></i></div></div>
    <div class="col-md-3"><div class="metric"><span>Registered Users</span><strong>{{ $usersCount }}</strong><i class="bi bi-people"></i></div></div>
</div>
<div class="row g-3 mt-1">
    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-header"><h2>Recent Cases</h2><a href="{{ route('cases.create') }}" class="btn btn-sm btn-success"><i class="bi bi-plus-lg"></i> New Case</a></div>
            <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Case No.</th><th>Title</th><th>Status</th><th>Priority</th></tr></thead><tbody>
            @forelse($recentCases as $case)<tr><td><a href="{{ route('cases.show',$case) }}">{{ $case->case_number }}</a></td><td>{{ $case->title }}</td><td><span class="badge status">{{ str_replace('_',' ',$case->status) }}</span></td><td>{{ ucfirst($case->priority) }}</td></tr>@empty<tr><td colspan="4">No cases found.</td></tr>@endforelse
            </tbody></table></div>
        </div>
    </div>
    <div class="col-lg-4">
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
