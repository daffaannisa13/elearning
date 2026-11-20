@extends('layout')
@section('title', 'Ubah course')
@section('content')
<a href="{{ route('admin.courses.index') }}" class="text-sm text-ink-soft hover:text-ink">&larr; Semua course</a>
<h1 class="font-display text-3xl font-extrabold mt-2 mb-8">Ubah course</h1>
<form method="POST" action="{{ route('admin.courses.update', $course) }}" class="rounded-2xl border border-line bg-white p-6 md:p-8 max-w-2xl">
    @method('PUT')
    @include('admin.courses._form')
</form>
@endsection
