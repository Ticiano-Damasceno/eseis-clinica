@props([
    "variant" => "primary",
    "type" => "button",
])

@php
$variants = [
    "primary" => "bg-eseis-orange hover:bg-eseis-terracotta text-white",
    "secondary" => "bg-eseis-yellow hover:bg-eseis-terracotta text-neutral-800",
    "outline" => "border border-neutral-300 hover:bg-neutral-50 text-neutral-700",
    "danger" => "bg-red-600 hover:bg-red-700 text-white",
];
$classes = $variants[$variant] ?? $variants["primary"];
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge([
        "class" => "$classes w-full py-3 rounded-lg font-medium transition-colors"
    ]) }}
>
    {{ $slot }}
</button>