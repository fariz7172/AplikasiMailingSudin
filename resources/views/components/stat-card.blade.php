@props(['title', 'value', 'trend', 'icon', 'color' => 'primary'])

@php
    $colorClasses = [
        'primary' => 'bg-primary/10 text-primary',
        'accent' => 'bg-accent/10 text-accent',
        'red' => 'bg-red-50 text-red-500',
        'green' => 'bg-green-50 text-green-500',
        'yellow' => 'bg-yellow-50 text-yellow-500',
    ];
    $bgClass = $colorClasses[$color] ?? $colorClasses['primary'];
@endphp

<div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center justify-between group hover:shadow-xl hover:shadow-{{ $color === 'primary' ? 'primary' : ($color === 'accent' ? 'accent' : $color) }}/5 transition-all cursor-pointer">
    <div>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">{{ $title }}</p>
        <h3 class="text-2xl font-black text-slate-800">{{ $value }}</h3>
        <p class="text-[10px] {{ str_contains($trend, '+') ? 'text-green-500' : 'text-slate-400' }} mt-1 flex items-center gap-1 font-bold">
            @if(str_contains($trend, '+'))
                <i data-lucide="trending-up" class="w-3 h-3"></i>
            @else
                <i data-lucide="info" class="w-3 h-3"></i>
            @endif
            {{ $trend }}
        </p>
    </div>
    <div class="w-14 h-14 {{ $bgClass }} rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
        <i data-lucide="{{ $icon }}" class="w-7 h-7"></i>
    </div>
</div>
