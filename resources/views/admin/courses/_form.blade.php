@csrf
@php $st = old('status', $course->status ?? 'draft'); @endphp
<div class="space-y-6">
    <div>
        <label for="title" class="field-label">Judul course</label>
        <input id="title" name="title" value="{{ old('title', $course->title ?? '') }}" required class="input" placeholder="Contoh: Dasar-dasar Laravel">
    </div>
    <div>
        <label for="description" class="field-label">Deskripsi</label>
        <textarea id="description" name="description" rows="5" class="input" placeholder="Apa yang akan dipelajari student di course ini?">{{ old('description', $course->description ?? '') }}</textarea>
    </div>
    <fieldset>
        <legend class="field-label">Status publikasi</legend>
        <div class="grid sm:grid-cols-2 gap-3">
            @foreach([['draft', 'Draft', 'Hanya terlihat oleh admin.'], ['published', 'Published', 'Student bisa melihat dan menonton.']] as [$v, $t, $d])
                <label class="cursor-pointer">
                    <input type="radio" name="status" value="{{ $v }}" class="peer sr-only" @checked($st === $v)>
                    <div class="rounded-xl border border-line bg-white p-4 peer-checked:border-brand peer-checked:ring-2 peer-checked:ring-brand/25 peer-focus-visible:outline peer-focus-visible:outline-2 peer-focus-visible:outline-brand">
                        <div class="font-semibold">{{ $t }}</div>
                        <div class="text-sm text-ink-soft">{{ $d }}</div>
                    </div>
                </label>
            @endforeach
        </div>
    </fieldset>
    <div class="flex gap-2">
        <button class="btn btn-primary">Simpan course</button>
        <a href="{{ route('admin.courses.index') }}" class="btn btn-ghost">Batal</a>
    </div>
</div>
