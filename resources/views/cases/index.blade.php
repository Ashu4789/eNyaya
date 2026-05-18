@extends('layouts.app')
@section('page-title', 'Case Management')
@section('content')
<div class="panel">
    <div class="panel-header">
        <form class="d-flex gap-2 flex-wrap"><input class="form-control search" name="q" value="{{ request('q') }}" placeholder="Search case number, party, title"><select class="form-select filter" name="status"><option value="">All status</option>@foreach(['filed','under_review','hearing_scheduled','in_progress','disposed','dismissed'] as $s)<option @selected(request('status')===$s) value="{{ $s }}">{{ str_replace('_',' ',ucfirst($s)) }}</option>@endforeach</select><button class="btn btn-primary"><i class="bi bi-search"></i></button></form>
        <a href="{{ route('cases.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> New Case</a>
    </div>
    <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Case ID</th><th>Title</th><th>Petitioner</th><th>Judge</th><th>Next Hearing</th><th>Status</th><th></th></tr></thead><tbody>
        @forelse($cases as $case)
            <tr><td>{{ $case->case_number }}</td><td>{{ $case->title }}</td><td>{{ $case->petitioner_name }}</td><td>{{ $case->judge?->name ?? 'Unassigned' }}</td><td>{{ $case->next_hearing_date?->format('d M Y h:i A') ?? '-' }}</td><td><span class="badge status">{{ str_replace('_',' ',$case->status) }}</span></td><td><a class="btn btn-sm btn-outline-primary" href="{{ route('cases.show',$case) }}">Open</a></td></tr>
        @empty
            <tr><td colspan="7">No cases found.</td></tr>
        @endforelse
    </tbody></table></div>
    {{ $cases->links() }}
</div>
@endsection
