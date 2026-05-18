@extends('layouts.app')
@section('title', 'Register | eNyaya')
@section('content')
<div class="auth-page">
    <div class="auth-panel wide">
        <div class="brand mb-4"><span class="brand-mark">eN</span><div><strong>eNyaya</strong><small>Portal Registration</small></div></div>
        <form method="POST" action="{{ route('register') }}" class="row g-3">
            @csrf
            <div class="col-md-6"><label class="form-label">Name</label><input class="form-control" name="name" value="{{ old('name') }}" required></div>
            <div class="col-md-6"><label class="form-label">Email</label><input class="form-control" type="email" name="email" value="{{ old('email') }}" required></div>
            <div class="col-md-6"><label class="form-label">Phone</label><input class="form-control" name="phone" value="{{ old('phone') }}"></div>
            <div class="col-md-6"><label class="form-label">Role</label><select class="form-select" name="role_id" required>@foreach($roles as $role)<option value="{{ $role->id }}">{{ $role->name }}</option>@endforeach</select></div>
            <div class="col-12"><label class="form-label">Address</label><textarea class="form-control" name="address">{{ old('address') }}</textarea></div>
            <div class="col-md-6"><label class="form-label">Password</label><input class="form-control" type="password" name="password" required></div>
            <div class="col-md-6"><label class="form-label">Confirm Password</label><input class="form-control" type="password" name="password_confirmation" required></div>
            @if($errors->any())<div class="col-12 text-danger small">{{ $errors->first() }}</div>@endif
            <div class="col-12 d-flex gap-2"><button class="btn btn-primary">Register</button><a href="{{ route('login') }}" class="btn btn-outline-secondary">Back to login</a></div>
        </form>
    </div>
</div>
@endsection
