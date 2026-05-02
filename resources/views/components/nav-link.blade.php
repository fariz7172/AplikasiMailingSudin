@props(['active' => false, 'icon' => null])

@php
$classes = ($active ?? false)
    ? 'flex items-center gap-3 px-4 py-3 bg-white/10 text-white rounded-2xl font-bold shadow-lg shadow-black/10 transition-all duration-300'
    : 'flex items-center gap-3 px-4 py-3 text-white/60 hover:text-white hover:bg-white/5 rounded-2xl font-medium transition-all duration-300 group';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
        <i data-lucide="{{ $icon }}" class="w-5 h-5 {{ $active ? 'text-accent' : 'text-white/40 group-hover:text-white/80' }} transition-colors"></i>
    @endif
    <span class="text-sm tracking-wide">{{ $slot }}</span>
</a>
