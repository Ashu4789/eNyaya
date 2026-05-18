@extends('layouts.app')
@section('title', 'Login | eNyaya')
@section('content')
<div class="auth-page">
    <div class="auth-panel">
        <div class="brand mb-4"><span class="brand-mark">eN</span><div><strong>eNyaya</strong><small>Secure Case Management</small></div></div>
        <h2>Sign in</h2>
        <form method="POST" action="{{ route('login') }}" class="vstack gap-3">
            @csrf
            <input class="form-control" name="email" type="email" value="{{ old('email') }}" placeholder="Email address" required autofocus>
            <input class="form-control" name="password" type="password" placeholder="Password" required>
            @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
            <div class="d-flex justify-content-between align-items-center">
                <label class="form-check"><input class="form-check-input" type="checkbox" name="remember"> <span class="form-check-label">Remember</span></label>
                <a href="{{ route('password.request') }}" class="small">Forgot password?</a>
            </div>
            <button class="btn btn-primary">Login</button>
            <a class="btn btn-outline-secondary" href="{{ route('register') }}">Create client/lawyer account</a>
        </form>
        <div class="demo-box mt-4 small">Demo: admin@enyaya.local / password</div>
    </div>
</div>
@endsection
