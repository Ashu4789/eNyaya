<!doctype html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'eNyaya') | e-Justice Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif !important;
        }
    </style>
</head>
<body>
<div class="app-shell">
    @auth
        <aside class="sidebar">
            <div class="brand">
                <span class="brand-mark">eN</span>
                <div><strong>eNyaya</strong><small>Justice Portal</small></div>
            </div>
            <nav class="nav flex-column gap-1">
                <a class="nav-link" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a class="nav-link" href="{{ route('cases.index') }}"><i class="bi bi-folder2-open"></i> Cases</a>
                <a class="nav-link" href="{{ route('hearings.index') }}"><i class="bi bi-calendar-event"></i> Hearings</a>
                <a class="nav-link" href="{{ route('cause-list.index') }}"><i class="bi bi-list-ol"></i> Cause List</a>
                <a class="nav-link" href="{{ route('cases.index', ['priority' => 'urgent']) }}"><i class="bi bi-exclamation-octagon"></i> Urgent Matters</a>
                @if(auth()->user()->hasRole(['super-admin','court-admin','judge']))
                    <a class="nav-link" href="{{ route('reports.index') }}"><i class="bi bi-file-earmark-bar-graph"></i> Reports</a>
                @endif
            </nav>
        </aside>
    @endauth
    <main class="main-panel">
        @auth
            <header class="topbar">
                <div>
                    <div class="text-muted small">Electronic Justice & Case Management Portal</div>
                    <h1>@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('cases.index') }}" class="global-search">
                        <i class="bi bi-search"></i>
                        <input name="q" placeholder="Search case ID, advocate, client, hearing date">
                    </form>
                    <button class="btn btn-outline-secondary btn-sm" id="darkModeToggle" type="button" title="Toggle theme"><i class="bi bi-moon"></i></button>
                    <span class="badge text-bg-success">{{ auth()->user()->role?->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">@csrf <button class="btn btn-primary btn-sm">Logout</button></form>
                </div>
            </header>
        @endauth
        <section class="content-wrap">
            @if(session('status'))
                <div class="toast-container position-fixed top-0 end-0 p-3">
                    <div class="toast show"><div class="toast-header"><strong class="me-auto">eNyaya</strong><button class="btn-close" data-bs-dismiss="toast"></button></div><div class="toast-body">{{ session('status') }}</div></div>
                </div>
            @endif
            @yield('content')
        </section>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
