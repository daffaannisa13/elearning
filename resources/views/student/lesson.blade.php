@extends('layout')
@section('title', $lesson->title)
@section('content')
@php
    $ready = $course->lessons->filter(fn ($l) => $l->isReady())->values();
    $idx = $ready->search(fn ($l) => $l->id === $lesson->id);
    $prev = ($idx !== false && $idx > 0) ? $ready[$idx - 1] : null;
    $next = $idx !== false ? ($ready[$idx + 1] ?? null) : null;
@endphp

<a href="{{ route('student.courses.show', $course) }}" class="text-sm text-ink-soft hover:text-ink">&larr; {{ $course->title }}</a>

<div class="mt-3 grid gap-8 lg:grid-cols-3">
    <div class="lg:col-span-2 min-w-0">
        <div class="overflow-hidden rounded-2xl bg-ink shadow-xl shadow-slate-300/60">
            @if($lesson->isReady())
                <video controls controlsList="nodownload" preload="metadata" class="aspect-video w-full bg-black" src="{{ route('lessons.video', $lesson) }}"></video>
            @else
                <div class="grid aspect-video place-items-center text-white/70 text-sm">Video belum tersedia.</div>
            @endif
        </div>

        <h1 class="font-display text-2xl md:text-3xl font-extrabold mt-6">{{ $lesson->title }}</h1>
        @if($lesson->description)<p class="mt-2 text-ink-soft max-w-prose">{{ $lesson->description }}</p>@endif

        <div class="mt-6 flex items-center justify-between gap-3">
            @if($prev)
                <a href="{{ route('student.courses.lesson', [$course, $prev]) }}" class="btn btn-ghost">&larr; Sebelumnya</a>
            @else <span></span> @endif
            @if($next)
                <a href="{{ route('student.courses.lesson', [$course, $next]) }}" class="btn btn-primary">Lesson berikutnya</a>
            @else
                <a href="{{ route('student.courses.show', $course) }}" class="btn btn-ghost">Kembali ke course</a>
            @endif
        </div>
    </div>

    <aside class="lg:sticky lg:top-6 h-fit rounded-2xl border border-line bg-white">
        <div class="border-b border-line px-4 py-3 font-display font-extrabold">Daftar lesson</div>
        <ol class="p-2 text-sm">
        @foreach($course->lessons as $l)
            <li>
            @if($l->isReady())
                <a href="{{ route('student.courses.lesson', [$course, $l]) }}"
                   class="flex items-center gap-3 rounded-lg px-3 py-2.5 {{ $l->id === $lesson->id ? 'bg-brand text-white font-semibold' : 'hover:bg-slate-50' }}">
                    <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full text-xs font-bold {{ $l->id === $lesson->id ? 'bg-white/20' : 'bg-brand-light text-brand' }}">{{ $loop->iteration }}</span>
                    <span class="truncate">{{ $l->title }}</span>
                </a>
            @else
                <div class="flex items-center gap-3 px-3 py-2.5 text-slate-400">
                    <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full bg-slate-100 text-xs font-bold">{{ $loop->iteration }}</span>
                    <span class="truncate">{{ $l->title }}</span>
                </div>
            @endif
            </li>
        @endforeach
        </ol>
    </aside>
</div>
@endsection
