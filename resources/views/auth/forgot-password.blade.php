@extends('layouts.app')
@section('title', 'Forgot Password | eNyaya')
@section('content')
<div class="auth-page">
    <div class="auth-panel">
        <div class="brand mb-4"><span class="brand-mark">eN</span><div><strong>eNyaya</strong><small>Password Recovery</small></div></div>
        <form method="POST" action="{{ route('password.email') }}" class="vstack gap-3">
            @csrf
            <p class="text-muted">Enter your registered email address to receive a reset link.</p>
            <input class="form-control" name="email" type="email" value="{{ old('email') }}" placeholder="Email address" required>
            @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
            <button class="btn btn-primary">Send reset link</button>
            <a class="btn btn-outline-secondary" href="{{ route('login') }}">Back to login</a>
        </form>
    </div>
</div>
@endsection
