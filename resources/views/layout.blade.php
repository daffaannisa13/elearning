<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'E-Learning')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                fontFamily: {
                    display: ['"Bricolage Grotesque"', 'system-ui', 'sans-serif'],
                    sans: ['"DM Sans"', 'system-ui', 'sans-serif'],
                },
                colors: {
                    ink: { DEFAULT: '#16213A', soft: '#5B6478' },
                    brand: { DEFAULT: '#2547D0', dark: '#1B37A6', light: '#E8EDFD' },
                    surface: '#F3F5F9',
                    line: '#E1E5EE',
                },
            } },
        };
    </script>
    <style type="text/tailwindcss">
        @layer components {
            .btn { @apply inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold transition-colors disabled:opacity-50 disabled:cursor-not-allowed; }
            .btn-sm { @apply px-3 py-1.5 text-xs; }
            .btn-primary { @apply bg-brand text-white hover:bg-brand-dark; }
            .btn-ghost { @apply bg-white text-ink border border-line hover:bg-slate-50; }
            .btn-danger { @apply bg-white text-red-600 border border-red-200 hover:bg-red-50; }
            .input { @apply w-full rounded-lg border border-line bg-white px-3.5 py-2.5 text-sm placeholder:text-slate-400 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/25; }
            .field-label { @apply block text-sm font-medium mb-1.5; }
            .chip { @apply inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold; }
            .dot { @apply inline-block h-1.5 w-1.5 rounded-full; }
        }
    </style>
    <style>
        :focus-visible { outline: 2px solid #2547D0; outline-offset: 2px; }
        @media (prefers-reduced-motion: reduce) { * { animation: none !important; transition: none !important; } }
    </style>
</head>
<body class="bg-surface text-ink font-sans antialiased min-h-screen">
@php
    $user = auth()->user();
    $isAdmin = $user && $user->isAdmin();
@endphp

@if($isAdmin)
    <div class="md:flex min-h-screen">
        <aside class="bg-ink text-white md:w-64 md:fixed md:inset-y-0 flex md:flex-col items-center md:items-stretch justify-between md:justify-start gap-2 px-5 py-3 md:py-6">
            @include('partials.brand', ['dark' => true])
            <nav class="md:mt-10 flex-1 flex md:block gap-1">
                <a href="{{ route('admin.courses.index') }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.courses.*') ? 'bg-white/10 text-white' : 'text-white/65 hover:text-white hover:bg-white/5' }}">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 5.5 10 2l7 3.5-7 3.5-7-3.5Z"/><path d="m3 10 7 3.5 7-3.5M3 14.5 10 18l7-3.5"/></svg>
                    Course
                </a>
            </nav>
            <div class="flex md:block items-center gap-3 md:border-t md:border-white/10 md:pt-4">
                <div class="hidden md:block mb-3">
                    <div class="text-sm font-semibold truncate">{{ $user->name }}</div>
                    <div class="text-xs text-white/55">Admin</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button class="text-sm text-white/70 hover:text-white">Keluar</button>
                </form>
            </div>
        </aside>
        <main class="md:ml-64 flex-1 min-w-0 p-5 md:p-10">
            <div class="max-w-4xl">
                @include('partials.flash')
                @yield('content')
            </div>
        </main>
    </div>
@elseif($user)
    <header class="bg-white border-b border-line">
        <div class="max-w-6xl mx-auto px-5 h-16 flex items-center justify-between">
            <a href="{{ route('dashboard') }}">@include('partials.brand', ['dark' => false])</a>
            <div class="flex items-center gap-4 text-sm">
                <span class="hidden sm:inline text-ink-soft">{{ $user->name }}</span>
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button class="btn btn-ghost btn-sm">Keluar</button>
                </form>
            </div>
        </div>
    </header>
    <main class="max-w-6xl mx-auto px-5 py-8 md:py-10">
        @include('partials.flash')
        @yield('content')
    </main>
@else
    @yield('content')
@endif

@stack('scripts')
</body>
</html>
