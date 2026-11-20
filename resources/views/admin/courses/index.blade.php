@extends('layout')
@section('title', 'Course')
@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-3xl font-extrabold tracking-tight text-ink">Course</h1>
            <p class="text-sm text-ink-soft mt-1">
                {{ $courses->count() }} course, {{ $courses->where('status', 'published')->count() }} sudah dipublikasikan.
            </p>
        </div>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary shrink-0">
            <span>+ Tambah course</span>
        </a>
    </div>

    @if($courses->isEmpty())
        <div class="rounded-2xl border border-dashed border-line bg-white p-12 text-center shadow-2xs">
            <p class="font-display text-xl font-extrabold text-ink">Belum ada course</p>
            <p class="mt-1 text-sm text-ink-soft">Buat course pertama, lalu tambahkan lesson dan unggah videonya.</p>
            <a href="{{ route('admin.courses.create') }}" class="btn btn-primary mt-5">Tambah course</a>
        </div>
    @else
        <!-- Ringkasan Cepat -->
        <div class="grid grid-cols-3 gap-3 sm:gap-4">
            <div class="rounded-2xl border border-line bg-white px-4 py-3.5 shadow-2xs">
                <div class="text-xs font-medium text-ink-soft">Total Course</div>
                <div class="mt-1 font-display text-2xl font-extrabold text-ink">{{ $courses->count() }}</div>
            </div>
            <div class="rounded-2xl border border-line bg-white px-4 py-3.5 shadow-2xs">
                <div class="text-xs font-medium text-ink-soft">Published</div>
                <div class="mt-1 font-display text-2xl font-extrabold text-emerald-700">
                    {{ $courses->where('status', 'published')->count() }}
                </div>
            </div>
            <div class="rounded-2xl border border-line bg-white px-4 py-3.5 shadow-2xs">
                <div class="text-xs font-medium text-ink-soft">Total Lesson</div>
                <div class="mt-1 font-display text-2xl font-extrabold text-brand">
                    {{ $courses->sum('lessons_count') }}
                </div>
            </div>
        </div>

        <!-- Daftar Course -->
        <ul class="space-y-3">
        @foreach($courses as $c)
            <li class="group flex flex-col sm:flex-row sm:items-center gap-4 rounded-2xl border border-line bg-white p-3.5 sm:pr-4 shadow-2xs hover:border-slate-300 hover:shadow-sm transition-all">
                <a href="{{ route('admin.courses.show', $c) }}" class="shrink-0 overflow-hidden rounded-xl border border-line/70">
                    @include('partials.cover', ['course' => $c, 'class' => 'h-20 w-28', 'size' => 'text-2xl'])
                </a>

                <div class="min-w-0 flex-1">
                    <a href="{{ route('admin.courses.show', $c) }}"
                       class="block truncate font-semibold text-base text-ink group-hover:text-brand transition-colors">
                        {{ $c->title }}
                    </a>
                    <div class="mt-2 flex flex-wrap items-center gap-3 text-sm text-ink-soft">
                        @if($c->status === 'published')
                            <span class="chip bg-emerald-100 text-emerald-800">
                                <i class="dot bg-emerald-500"></i>Published
                            </span>
                        @else
                            <span class="chip bg-slate-100 text-slate-600">
                                <i class="dot bg-slate-400"></i>Draft
                            </span>
                        @endif
                        <span class="text-xs sm:text-sm font-medium">{{ $c->lessons_count }} lesson</span>
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-2 sm:pt-0 border-t sm:border-t-0 border-line/70 shrink-0">
                    <a href="{{ route('admin.courses.show', $c) }}" class="btn btn-primary btn-sm">Kelola lesson</a>
                    <a href="{{ route('admin.courses.edit', $c) }}" class="btn btn-ghost btn-sm">Ubah</a>
                    <form method="POST" action="{{ route('admin.courses.destroy', $c) }}"
                          onsubmit="return confirm('Hapus course ini beserta semua lesson dan videonya?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </div>
            </li>
        @endforeach
        </ul>
    @endif
</div>
@endsection