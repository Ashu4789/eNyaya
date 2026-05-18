@extends('layouts.app')
@section('page-title', $case->exists ? 'Edit Case' : 'Register Case')
@section('content')
<div class="panel">
<form method="POST" action="{{ $case->exists ? route('cases.update',$case) : route('cases.store') }}" class="row g-3">
    @csrf @if($case->exists) @method('PUT') @endif
    <div class="col-md-4"><label class="form-label">Case ID</label><input class="form-control" name="case_number" value="{{ old('case_number',$case->case_number) }}" placeholder="Auto generated if blank"></div>
    <div class="col-md-8"><label class="form-label">Case Title</label><input class="form-control" name="title" value="{{ old('title',$case->title) }}" required></div>
    <div class="col-md-4"><label class="form-label">Category</label><input class="form-control" name="category" value="{{ old('category',$case->category) }}" required></div>
    <div class="col-md-4"><label class="form-label">Filing Date</label><input class="form-control" type="date" name="filing_date" value="{{ old('filing_date',optional($case->filing_date)->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required></div>
    <div class="col-md-4"><label class="form-label">Hearing Date</label><input class="form-control" type="datetime-local" name="next_hearing_date" value="{{ old('next_hearing_date',optional($case->next_hearing_date)->format('Y-m-d\\TH:i')) }}"></div>
    <div class="col-md-6"><label class="form-label">Petitioner</label><input class="form-control" name="petitioner_name" value="{{ old('petitioner_name',$case->petitioner_name) }}" required></div>
    <div class="col-md-6"><label class="form-label">Petitioner Contact</label><input class="form-control" name="petitioner_contact" value="{{ old('petitioner_contact',$case->petitioner_contact) }}"></div>
    <div class="col-md-6"><label class="form-label">Respondent</label><input class="form-control" name="respondent_name" value="{{ old('respondent_name',$case->respondent_name) }}" required></div>
    <div class="col-md-6"><label class="form-label">Respondent Contact</label><input class="form-control" name="respondent_contact" value="{{ old('respondent_contact',$case->respondent_contact) }}"></div>
    <div class="col-md-3"><label class="form-label">Client</label><select class="form-select" name="client_id"><option value="">Unassigned</option>@foreach($clients as $user)<option @selected(old('client_id',$case->client_id)==$user->id) value="{{ $user->id }}">{{ $user->name }}</option>@endforeach</select></div>
    <div class="col-md-3"><label class="form-label">Advocate</label><select class="form-select" name="advocate_id"><option value="">Unassigned</option>@foreach($advocates as $user)<option @selected(old('advocate_id',$case->advocate_id)==$user->id) value="{{ $user->id }}">{{ $user->name }}</option>@endforeach</select></div>
    <div class="col-md-3"><label class="form-label">Judge</label><select class="form-select" name="judge_id"><option value="">Unassigned</option>@foreach($judges as $user)<option @selected(old('judge_id',$case->judge_id)==$user->id) value="{{ $user->id }}">{{ $user->name }}</option>@endforeach</select></div>
    <div class="col-md-3"><label class="form-label">Priority</label><select class="form-select" name="priority">@foreach(['low','normal','high','urgent'] as $p)<option @selected(old('priority',$case->priority ?? 'normal')===$p) value="{{ $p }}">{{ ucfirst($p) }}</option>@endforeach</select></div>
    <div class="col-md-4"><label class="form-label">Status</label><select class="form-select" name="status">@foreach(['filed','under_review','hearing_scheduled','in_progress','disposed','dismissed'] as $s)<option @selected(old('status',$case->status ?? 'filed')===$s) value="{{ $s }}">{{ str_replace('_',' ',ucfirst($s)) }}</option>@endforeach</select></div>
    <div class="col-12"><label class="form-label">Summary</label><textarea class="form-control" name="summary" rows="4">{{ old('summary',$case->summary) }}</textarea></div>
    @if($errors->any())<div class="col-12 text-danger small">{{ $errors->first() }}</div>@endif
    <div class="col-12 d-flex gap-2"><button class="btn btn-primary">Save Case</button><a class="btn btn-outline-secondary" href="{{ route('cases.index') }}">Cancel</a></div>
</form>
</div>
@endsection
