@extends('layouts.app')
@section('page-title', 'Case Management')
@section('content')
<div class="panel">
    <div class="panel-header">
        <form class="d-flex gap-2 flex-wrap">
            <input class="form-control search" name="q" value="{{ request('q') }}" placeholder="Search case number, party, title">
            <select class="form-select filter" name="status">
                <option value="">All status</option>
                @foreach($statuses as $s)
                    <option @selected(request('status')===$s) value="{{ $s }}">{{ str_replace('_',' ',ucfirst($s)) }}</option>
                @endforeach
            </select>
            <select class="form-select filter" name="category">
                <option value="">All categories</option>
                @foreach($categories as $category)
                    <option @selected(request('category')===$category) value="{{ $category }}">{{ $category }}</option>
                @endforeach
            </select>
            <select class="form-select filter" name="priority">
                <option value="">All priority</option>
                @foreach($priorities as $priority)
                    <option @selected(request('priority')===$priority) value="{{ $priority }}">{{ ucfirst($priority) }}</option>
                @endforeach
            </select>
            <button class="btn btn-primary"><i class="bi bi-search"></i></button>
        </form>
        <a href="{{ route('cases.create') }}" class="btn btn-success"><i class="bi bi-plus-lg"></i> New Case</a>
    </div>
    <div class="table-responsive"><table class="table align-middle sticky-table"><thead><tr><th>Case ID</th><th>Title</th><th>Petitioner</th><th>Judge</th><th>Next Hearing</th><th>Status</th><th>Priority</th><th></th></tr></thead><tbody>
        @forelse($cases as $case)
            <tr class="@if($case->priority === 'urgent') urgent-row @endif"><td>{{ $case->case_number }}</td><td>{{ $case->title }}<small class="d-block text-muted">{{ $case->category }}</small></td><td>{{ $case->petitioner_name }}</td><td>{{ $case->judge?->name ?? 'Unassigned' }}</td><td>{{ $case->next_hearing_date?->format('d M Y h:i A') ?? '-' }}</td><td><span class="badge status">{{ str_replace('_',' ',$case->status) }}</span></td><td><span class="badge priority-{{ $case->priority }}">{{ ucfirst($case->priority) }}</span></td><td><a class="btn btn-sm btn-outline-primary" href="{{ route('cases.show',$case) }}">Open</a></td></tr>
        @empty
            <tr><td colspan="8">No cases found.</td></tr>
        @endforelse
    </tbody></table></div>
    {{ $cases->links() }}
</div>
@endsection
