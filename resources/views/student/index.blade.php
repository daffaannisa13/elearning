@extends('layout')
@section('title', 'Course')
@section('content')
<div class="mb-8">
    <p class="text-sm text-ink-soft">Halo, {{ \Illuminate\Support\Str::of(auth()->user()->name)->explode(' ')->first() }}.</p>
    <h1 class="font-display text-3xl md:text-4xl font-extrabold mt-1">Mau belajar apa hari ini?</h1>
</div>

@if($courses->isEmpty())
    <div class="rounded-2xl border border-dashed border-line bg-white p-12 text-center">
        <p class="font-display text-xl font-extrabold">Belum ada course tersedia</p>
        <p class="mt-1 text-sm text-ink-soft">Course akan muncul di sini setelah admin mempublikasikannya.</p>
    </div>
@else
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
    @foreach($courses as $c)
        <a href="{{ route('student.courses.show', $c) }}" class="group overflow-hidden rounded-2xl border border-line bg-white transition-shadow hover:shadow-lg hover:shadow-slate-200">
            @include('partials.cover', ['course' => $c, 'class' => 'h-40'])
            <div class="p-5">
                <h2 class="font-display text-lg font-extrabold leading-snug group-hover:text-brand">{{ $c->title }}</h2>
                <p class="mt-1.5 text-sm text-ink-soft line-clamp-2">{{ $c->description }}</p>
                <p class="mt-4 text-xs font-semibold text-brand">{{ $c->lessons_count }} lesson</p>
            </div>
        </a>
    @endforeach
    </div>
@endif
@endsection
