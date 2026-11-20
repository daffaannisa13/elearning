@extends('layout')
@section('title', $course->title)
@section('content')
@php
    $chips = [
        'none'      => ['Belum ada video', 'bg-slate-100 text-slate-600', 'bg-slate-400'],
        'uploading' => ['Mengunggah', 'bg-amber-100 text-amber-800', 'bg-amber-500 animate-pulse'],
        'ready'     => ['Siap', 'bg-emerald-100 text-emerald-800', 'bg-emerald-500'],
        'failed'    => ['Gagal', 'bg-red-100 text-red-700', 'bg-red-500'],
    ];
@endphp

<a href="{{ route('admin.courses.index') }}" class="text-sm text-ink-soft hover:text-ink">&larr; Semua course</a>

<div class="mt-3 mb-8 overflow-hidden rounded-2xl border border-line bg-white md:flex">
    <div class="md:w-56 shrink-0">@include('partials.cover', ['course' => $course, 'class' => 'h-32 md:h-full'])</div>
    <div class="flex-1 p-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="font-display text-2xl font-extrabold">{{ $course->title }}</h1>
                <div class="mt-2">
                    @if($course->status === 'published')
                        <span class="chip bg-emerald-100 text-emerald-800"><i class="dot bg-emerald-500"></i>Published</span>
                    @else
                        <span class="chip bg-slate-100 text-slate-600"><i class="dot bg-slate-400"></i>Draft</span>
                    @endif
                </div>
            </div>
            <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-ghost btn-sm">Ubah course</a>
        </div>
        @if($course->description)<p class="mt-3 text-sm text-ink-soft max-w-xl">{{ $course->description }}</p>@endif
    </div>
</div>

<div class="flex items-center justify-between mb-3">
    <div>
        <h2 class="font-display text-xl font-extrabold">Lesson <span class="text-ink-soft font-semibold">({{ $course->lessons->count() }})</span></h2>
        <p class="text-xs text-ink-soft mt-0.5">Geser ikon titik untuk mengurutkan, atau fokus ke ikon lalu tekan panah atas/bawah. <span id="order-status" class="font-semibold text-emerald-700"></span></p>
    </div>
</div>

<details class="group mb-4 rounded-2xl border border-line bg-white" @if($errors->any() && old('title') !== null) open @endif>
    <summary class="flex cursor-pointer list-none items-center gap-2 px-5 py-3.5 text-sm font-semibold">
        <svg class="h-4 w-4 transition-transform group-open:rotate-45" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 4v12M4 10h12"/></svg>
        Tambah lesson
    </summary>
    <form method="POST" action="{{ route('admin.lessons.store', $course) }}" class="space-y-3 border-t border-line p-5">
        @csrf
        <div>
            <label class="field-label" for="new-title">Judul lesson</label>
            <input id="new-title" name="title" required class="input" value="{{ old('title') }}">
        </div>
        <div>
            <label class="field-label" for="new-desc">Deskripsi <span class="text-ink-soft font-normal">(opsional)</span></label>
            <textarea id="new-desc" name="description" rows="2" class="input">{{ old('description') }}</textarea>
        </div>
        <button class="btn btn-primary">Simpan lesson</button>
    </form>
</details>

@if($course->lessons->isEmpty())
    <div class="rounded-2xl border border-dashed border-line bg-white p-10 text-center">
        <p class="font-semibold">Belum ada lesson</p>
        <p class="mt-1 text-sm text-ink-soft">Tambahkan lesson pertama, lalu unggah videonya.</p>
    </div>
@endif

<ol id="lessons" class="space-y-3" data-reorder-url="{{ route('admin.lessons.reorder', $course) }}">
@foreach($course->lessons as $lesson)
    @php $st = $lesson->video_status; @endphp
    <li class="rounded-2xl border border-line bg-white" data-lesson="{{ $lesson->id }}">
        <div class="flex items-start gap-3 p-4">
            <button type="button" class="handle mt-0.5 cursor-grab rounded p-1.5 text-slate-400 hover:bg-slate-100 hover:text-ink active:cursor-grabbing" aria-label="Urutkan lesson {{ $lesson->title }}">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><circle cx="7" cy="5" r="1.5"/><circle cx="13" cy="5" r="1.5"/><circle cx="7" cy="10" r="1.5"/><circle cx="13" cy="10" r="1.5"/><circle cx="7" cy="15" r="1.5"/><circle cx="13" cy="15" r="1.5"/></svg>
            </button>
            <div class="num grid h-8 w-8 shrink-0 place-items-center rounded-full bg-brand-light text-sm font-bold text-brand">{{ $loop->iteration }}</div>

            <div class="min-w-0 flex-1">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="truncate font-semibold">{{ $lesson->title }}</div>
                        @if($lesson->description)<div class="mt-0.5 text-sm text-ink-soft line-clamp-2">{{ $lesson->description }}</div>@endif
                    </div>
                    <span class="chip shrink-0 {{ $chips[$st][1] }}" data-chip><i class="dot {{ $chips[$st][2] }}"></i><span class="chip-text">{{ $chips[$st][0] }}</span></span>
                </div>

                <div class="mt-3 flex gap-2">
                    <button type="button" class="btn btn-ghost btn-sm" data-toggle-edit>Ubah</button>
                    <form method="POST" action="{{ route('admin.lessons.destroy', $lesson) }}" onsubmit="return confirm('Hapus lesson ini beserta videonya?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </div>

                <form method="POST" action="{{ route('admin.lessons.update', $lesson) }}" class="mt-3 hidden space-y-2 rounded-xl bg-slate-50 p-3" data-edit-form>
                    @csrf @method('PUT')
                    <input name="title" value="{{ $lesson->title }}" required class="input" aria-label="Judul lesson">
                    <textarea name="description" rows="2" class="input" aria-label="Deskripsi lesson">{{ $lesson->description }}</textarea>
                    <button class="btn btn-primary btn-sm">Simpan perubahan</button>
                </form>
            </div>
        </div>

        {{-- Video --}}
        <div class="border-t border-line bg-slate-50/60 px-4 py-3 rounded-b-2xl" data-upload data-url="{{ route('admin.lessons.video.upload', $lesson) }}">
            <div class="flex items-center justify-between gap-3 text-sm">
                <div class="min-w-0 truncate">
                    <span class="font-medium">Video:</span>
                    <span class="filename text-ink-soft">
                        @if($lesson->video_original_name){{ $lesson->video_original_name }} ({{ number_format($lesson->video_size / 1048576, 1) }} MB)@else belum ada @endif
                    </span>
                </div>
                <form method="POST" action="{{ route('admin.lessons.video.destroy', $lesson) }}" onsubmit="return confirm('Hapus video ini?')" class="{{ $lesson->video_path ? '' : 'hidden' }}" data-del>
                    @csrf @method('DELETE')
                    <button class="text-xs font-semibold text-red-600 hover:underline">Hapus video</button>
                </form>
            </div>

            <p class="error mt-1.5 text-xs text-red-600">{{ $lesson->video_error }}</p>

            <div class="zone mt-2 flex items-center justify-between gap-3 rounded-xl border border-dashed border-slate-300 bg-white px-4 py-3 text-sm text-ink-soft transition-colors">
                <span>Tarik video ke sini (mp4, webm, ogg, mov; maks. 500 MB)</span>
                <button type="button" class="btn btn-ghost btn-sm pick">{{ $lesson->video_path ? 'Ganti video' : 'Pilih video' }}</button>
                <input type="file" class="sr-only" accept="video/mp4,video/webm,video/ogg,video/quicktime" tabindex="-1">
            </div>

            <div class="progress mt-3 hidden">
                <div class="mb-1 flex justify-between text-xs">
                    <span class="progress-text font-medium">0%</span>
                    <button type="button" class="cancel font-semibold text-red-600 hover:underline">Batalkan</button>
                </div>
                <div class="h-2 w-full overflow-hidden rounded-full bg-slate-200"><div class="bar h-2 rounded-full bg-brand transition-[width]" style="width:0%"></div></div>
            </div>
        </div>
    </li>
@endforeach
</ol>
@endsection

@push('scripts')
<script>
const CHIPS = @json($chips);
const csrf = document.querySelector('meta[name=csrf-token]').content;
const MAX = 500 * 1024 * 1024;
const fmt = b => (b / 1048576).toFixed(1) + ' MB';

/* ---------- Ubah lesson (tampilkan form) ---------- */
document.querySelectorAll('[data-lesson]').forEach(li => {
    const btn = li.querySelector('[data-toggle-edit]');
    const form = li.querySelector('[data-edit-form]');
    btn.addEventListener('click', () => form.classList.toggle('hidden'));
});

/* ---------- Upload video (XHR + progress) ---------- */
document.querySelectorAll('[data-upload]').forEach(box => {
    const li = box.closest('[data-lesson]');
    const chip = li.querySelector('[data-chip]');
    const input = box.querySelector('input[type=file]');
    const zone = box.querySelector('.zone');
    const err = box.querySelector('.error');
    const fname = box.querySelector('.filename');
    const progress = box.querySelector('.progress');
    const bar = box.querySelector('.bar');
    const ptext = box.querySelector('.progress-text');
    const cancel = box.querySelector('.cancel');
    const pick = box.querySelector('.pick');
    const del = box.querySelector('[data-del]');
    let xhr = null;

    function setStatus(s) {
        const [text, c1, c2] = CHIPS[s];
        chip.className = 'chip shrink-0 ' + c1;
        chip.innerHTML = '<i class="dot ' + c2 + '"></i><span class="chip-text">' + text + '</span>';
    }

    function start(file) {
        err.textContent = '';
        if (!file) return;
        if (!file.type.startsWith('video/')) { err.textContent = 'File harus berupa video.'; return; }
        if (file.size > MAX) { err.textContent = 'Ukuran video maksimal 500 MB.'; return; }

        const fd = new FormData();
        fd.append('video', file);
        xhr = new XMLHttpRequest();
        xhr.open('POST', box.dataset.url);
        xhr.setRequestHeader('X-CSRF-TOKEN', csrf);
        xhr.setRequestHeader('Accept', 'application/json');

        xhr.upload.onprogress = e => {
            if (!e.lengthComputable) return;
            const p = Math.round(e.loaded / e.total * 100);
            bar.style.width = p + '%';
            ptext.textContent = p + '%  (' + fmt(e.loaded) + ' / ' + fmt(e.total) + ')';
        };
        const finish = () => { progress.classList.add('hidden'); pick.disabled = false; xhr = null; input.value = ''; };
        const fail = msg => { setStatus('failed'); err.textContent = msg; finish(); };

        xhr.onload = () => {
            let res = {};
            try { res = JSON.parse(xhr.responseText); } catch (e) {}
            if (xhr.status >= 200 && xhr.status < 300) {
                setStatus('ready');
                fname.textContent = (res.name || file.name) + ' (' + fmt(file.size) + ')';
                del.classList.remove('hidden');
                pick.textContent = 'Ganti video';
                finish();
            } else {
                fail(res.message || ('Upload gagal (HTTP ' + xhr.status + ').'));
            }
        };
        xhr.onerror = () => fail('Koneksi terputus saat mengunggah. Coba lagi.');
        xhr.onabort = () => fail('Upload dibatalkan.');

        bar.style.width = '0%'; ptext.textContent = '0%';
        progress.classList.remove('hidden');
        pick.disabled = true;
        setStatus('uploading');
        fname.textContent = file.name;
        xhr.send(fd);
    }

    pick.addEventListener('click', () => input.click());
    input.addEventListener('change', () => start(input.files[0]));
    cancel.addEventListener('click', () => xhr && xhr.abort());
    ['dragenter', 'dragover'].forEach(ev => zone.addEventListener(ev, e => { e.preventDefault(); zone.classList.add('border-brand', 'bg-brand-light'); }));
    ['dragleave', 'drop'].forEach(ev => zone.addEventListener(ev, e => { e.preventDefault(); zone.classList.remove('border-brand', 'bg-brand-light'); }));
    zone.addEventListener('drop', e => { if (!xhr) start(e.dataTransfer.files[0]); });
});

/* ---------- Urutkan lesson (drag & drop + keyboard) ---------- */
const list = document.getElementById('lessons');
const status = document.getElementById('order-status');
let dragging = null;

function saveOrder() {
    const order = [...list.querySelectorAll('[data-lesson]')].map(el => +el.dataset.lesson);
    list.querySelectorAll('.num').forEach((n, i) => n.textContent = i + 1);
    status.textContent = 'Menyimpan urutan...';
    fetch(list.dataset.reorderUrl, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify({ order }),
    }).then(r => { status.textContent = r.ok ? 'Urutan tersimpan.' : 'Gagal menyimpan urutan.'; })
      .catch(() => { status.textContent = 'Gagal menyimpan urutan.'; });
}

list.querySelectorAll('[data-lesson]').forEach(el => {
    const handle = el.querySelector('.handle');
    handle.addEventListener('mousedown', () => el.draggable = true);
    el.addEventListener('dragstart', e => { dragging = el; el.classList.add('opacity-50'); e.dataTransfer.effectAllowed = 'move'; });
    el.addEventListener('dragend', () => { el.draggable = false; el.classList.remove('opacity-50'); dragging = null; saveOrder(); });
    el.addEventListener('dragover', e => {
        e.preventDefault();
        if (!dragging || dragging === el) return;
        const r = el.getBoundingClientRect();
        list.insertBefore(dragging, e.clientY > r.top + r.height / 2 ? el.nextSibling : el);
    });
    handle.addEventListener('keydown', e => {
        if (e.key === 'ArrowUp' && el.previousElementSibling) { e.preventDefault(); list.insertBefore(el, el.previousElementSibling); handle.focus(); saveOrder(); }
        if (e.key === 'ArrowDown' && el.nextElementSibling) { e.preventDefault(); list.insertBefore(el.nextElementSibling, el); handle.focus(); saveOrder(); }
    });
});
</script>
@endpush
