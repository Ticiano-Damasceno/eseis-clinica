@props([
'type' => 'text',
'name',
'icon' => null,
'placeholder' => '',
])

<div class="relative">
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge([
            'class' => 'w-full bg-neutral-100 border-none rounded-lg py-3 pl-4 pr-10 focus:ring-2 focus:ring-eseis-terracotta focus:outline-none'
        ]) }}>
    @if ($icon === 'user')
    <svg class="w-5 h-5 absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
    </svg>
    @elseif ($icon === 'lock')
    <svg class="w-5 h-5 absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
    </svg>
    @endif
</div>