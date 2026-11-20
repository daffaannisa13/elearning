@php
    $palettes = [
        ['#2547D0', '#7C9BFF', '#F5B83D'],
        ['#0F766E', '#5EEAD4', '#FDE68A'],
        ['#9D2A5B', '#F9A8D4', '#FDE047'],
        ['#1F2A44', '#64748B', '#34D399'],
        ['#B45309', '#FCD34D', '#1E3A8A'],
        ['#4C1D95', '#C4B5FD', '#FB7185'],
    ];
    $p = $palettes[$course->id % count($palettes)];
    $v = $course->id % 3;
    $initials = \Illuminate\Support\Str::upper(
        collect(preg_split('/\s+/', trim($course->title)))->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode('')
    );
@endphp
<div class="relative overflow-hidden {{ $class ?? 'h-40' }}" style="background: {{ $p[0] }}">
    <svg class="absolute inset-0 h-full w-full" viewBox="0 0 400 240" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
        @if($v === 0)
            <circle cx="330" cy="50" r="130" fill="{{ $p[1] }}" opacity=".35"/>
            <circle cx="330" cy="50" r="72" fill="{{ $p[1] }}" opacity=".5"/>
            <circle cx="70" cy="240" r="48" fill="{{ $p[2] }}"/>
        @elseif($v === 1)
            <g stroke="{{ $p[1] }}" stroke-width="16" opacity=".4">
                <path d="M120 260 L360 -20"/><path d="M180 260 L420 -20"/><path d="M240 260 L480 -20"/>
            </g>
            <rect x="40" y="20" width="56" height="56" rx="10" fill="{{ $p[2] }}"/>
        @else
            <rect x="240" y="10" width="130" height="130" rx="18" fill="{{ $p[1] }}" opacity=".4" transform="rotate(14 305 75)"/>
            <rect x="290" y="80" width="84" height="84" rx="14" fill="{{ $p[2] }}" transform="rotate(14 332 122)"/>
        @endif
    </svg>
    <span class="absolute left-4 bottom-3 font-display font-extrabold leading-none text-white/95 {{ $size ?? 'text-5xl' }}">{{ $initials }}</span>
</div>
