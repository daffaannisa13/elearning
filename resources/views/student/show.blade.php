@extends('layout')
@section('title', $course->title)
@section('content')
@php $first = $course->lessons->first(fn ($l) => $l->isReady()); @endphp

<a href="{{ route('student.courses.index') }}" class="text-sm text-ink-soft hover:text-ink">&larr; Semua course</a>

<div class="mt-3 mb-10 overflow-hidden rounded-3xl bg-white border border-line md:grid md:grid-cols-5">
    <div class="md:col-span-2">@include('partials.cover', ['course' => $course, 'class' => 'h-48 md:h-full min-h-[14rem]', 'size' => 'text-7xl'])</div>
    <div class="p-6 md:p-10 md:col-span-3 flex flex-col justify-center">
        <h1 class="font-display text-3xl md:text-4xl font-extrabold leading-tight">{{ $course->title }}</h1>
        <p class="mt-3 text-ink-soft max-w-prose">{{ $course->description }}</p>
        <div class="mt-6 flex items-center gap-4">
            @if($first)
                <a href="{{ route('student.courses.lesson', [$course, $first]) }}" class="btn btn-primary">Mulai belajar</a>
            @endif
            <span class="text-sm text-ink-soft">{{ $course->lessons->count() }} lesson</span>
        </div>
    </div>
</div>

<h2 class="font-display text-xl font-extrabold mb-3">Daftar lesson</h2>
@if($course->lessons->isEmpty())
    <div class="rounded-2xl border border-dashed border-line bg-white p-10 text-center text-sm text-ink-soft">Lesson belum ditambahkan untuk course ini.</div>
@else
<ol class="overflow-hidden rounded-2xl border border-line bg-white divide-y divide-line">
@foreach($course->lessons as $l)
    <li>
    @if($l->isReady())
        <a href="{{ route('student.courses.lesson', [$course, $l]) }}" class="flex items-center gap-4 p-4 hover:bg-brand-light/50">
            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-brand text-white">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M6 4.5v11a1 1 0 0 0 1.5.86l9-5.5a1 1 0 0 0 0-1.72l-9-5.5A1 1 0 0 0 6 4.5Z"/></svg>
            </span>
            <span class="min-w-0 flex-1">
                <span class="block font-semibold">{{ $loop->iteration }}. {{ $l->title }}</span>
                @if($l->description)<span class="block text-sm text-ink-soft truncate">{{ $l->description }}</span>@endif
            </span>
            <span class="text-sm font-semibold text-brand">Tonton</span>
        </a>
    @else
        <div class="flex items-center gap-4 p-4 opacity-60">
            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-slate-100 text-slate-400 text-sm font-bold">{{ $loop->iteration }}</span>
            <span class="min-w-0 flex-1">
                <span class="block font-semibold">{{ $l->title }}</span>
                <span class="block text-sm text-ink-soft">Video belum tersedia</span>
            </span>
        </div>
    @endif
    </li>
@endforeach
</ol>
@endif
@endsection
