@extends('layout')
@section('title', 'Masuk')
@section('content')
<div class="min-h-screen grid md:grid-cols-2">
    <div class="relative hidden md:flex flex-col justify-between bg-brand p-12 text-white overflow-hidden">
        <svg class="absolute inset-0 h-full w-full" viewBox="0 0 400 600" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
            <circle cx="360" cy="90" r="190" fill="#7C9BFF" opacity=".28"/>
            <circle cx="360" cy="90" r="105" fill="#7C9BFF" opacity=".35"/>
            <circle cx="40" cy="560" r="70" fill="#F5B83D"/>
        </svg>
        <div class="relative"><span class="font-display text-lg font-extrabold">E-Learning</span></div>
        <div class="relative">
            <h1 class="font-display text-5xl font-extrabold leading-[1.05] max-w-md">Belajar santai lewat video, satu materi dalam satu waktu.</h1>
            <p class="mt-5 max-w-sm text-white/80">Masuk untuk mulai belajar atau mengelola materi kelas Anda.</p>
        </div>
        <div class="relative h-4"></div>
    </div>

    <div class="flex items-center justify-center p-6">
        <div class="w-full max-w-sm">
            <div class="md:hidden mb-8">@include('partials.brand', ['dark' => false])</div>
            <h2 class="font-display text-3xl font-extrabold">Masuk</h2>
            <p class="mt-1 text-sm text-ink-soft">Gunakan akun yang diberikan admin.</p>

            @if($errors->any())
                <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-3.5 py-2.5 text-sm text-red-800" role="alert">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label for="email" class="field-label">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="input" placeholder="nama@contoh.com">
                </div>
                <div>
                    <label for="password" class="field-label">Password</label>
                    <input id="password" type="password" name="password" required class="input">
                </div>
                <button class="btn btn-primary w-full">Masuk</button>
            </form>

            <div class="mt-8 rounded-xl border border-dashed border-line bg-white p-4 text-sm">
                <div class="font-semibold mb-2">Akun demo</div>
                <div class="flex gap-2">
                    <button type="button" class="btn btn-ghost btn-sm flex-1" data-fill="admin@example.com">Isi sebagai admin</button>
                    <button type="button" class="btn btn-ghost btn-sm flex-1" data-fill="student@example.com">Isi sebagai student</button>
                </div>
                <p class="mt-2 text-xs text-ink-soft">Password: <code>password</code></p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('[data-fill]').forEach(b => b.addEventListener('click', () => {
    document.getElementById('email').value = b.dataset.fill;
    document.getElementById('password').value = 'password';
}));
</script>
@endpush
