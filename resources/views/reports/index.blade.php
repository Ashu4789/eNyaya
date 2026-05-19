@extends('layouts.app')
@section('page-title', 'Reports')
@section('content')
<div class="panel">
    <div class="panel-header"><div class="btn-group"><a class="btn btn-outline-primary @if($type==='pending') active @endif" href="?type=pending">Pending Cases</a><a class="btn btn-outline-primary @if($type==='completed') active @endif" href="?type=completed">Completed Cases</a></div><a href="{{ route('reports.export') }}" class="btn btn-success"><i class="bi bi-download"></i> Export CSV</a></div>
    <table class="table"><thead><tr><th>Case</th><th>Title</th><th>Status</th><th>Priority</th><th>Filed</th></tr></thead><tbody>@foreach($cases as $case)<tr><td>{{ $case->case_number }}</td><td>{{ $case->title }}</td><td>{{ $case->status }}</td><td>{{ $case->priority }}</td><td>{{ $case->filing_date->format('d M Y') }}</td></tr>@endforeach</tbody></table>{{ $cases->links() }}
</div>
<div class="row g-3 mt-1"><div class="col-lg-6"><div class="panel"><h2>Monthly Hearing Report</h2>@foreach($monthlyHearings as $row)<div class="d-flex justify-content-between border-bottom py-2"><span>{{ $row->month }}</span><strong>{{ $row->total }}</strong></div>@endforeach</div></div><div class="col-lg-6"><div class="panel"><h2>User Activity Report</h2>@foreach($activityUsers as $user)<div class="d-flex justify-content-between border-bottom py-2"><span>{{ $user->name }}</span><small>Cases {{ $user->filed_cases_count + $user->advocated_cases_count + $user->judged_cases_count }}</small></div>@endforeach</div></div></div>
<div class="panel mt-3">
    <h2>AI Future Enhancement Roadmap</h2>
    <div class="row g-3">
        <div class="col-md-4"><div class="roadmap-item"><strong>Hearing Delay Prediction</strong><span>Use adjournment count, category, judge load, and hearing age to flag high-risk matters.</span></div></div>
        <div class="col-md-4"><div class="roadmap-item"><strong>AI Legal Summarization</strong><span>Summarize pleadings, orders, evidence notes, and hearing history for quick judicial review.</span></div></div>
        <div class="col-md-4"><div class="roadmap-item"><strong>Case Recommendation System</strong><span>Recommend similar disposed cases, next procedural step, and document checklist.</span></div></div>
    </div>
</div>
@endsection
