@props(['sala'])

<div class="border border-eseis-tan rounded-2xl overflow-hidden">
    <div class="h-48 bg-neutral-200 flex items-center justify-center text-neutral-400">
        Foto da sala
    </div>

    <div class="p-4">
        <h3 class="font-semibold text-eseis-terracotta">
            {{ $sala->nome }} · {{ $sala->tipo_label }}
        </h3>

        <p class="text-sm text-neutral-500 mt-1">
            {{ $sala->descricao }}
        </p>

        <dib class="flex items-center gap-4 mt-3 text-sm text-neutral-600">
            <span>{{ $sala->capacidade }} {{ Str::plural('pessoa', $sala->capacidade) }}</span>
            <span>R$ {{ number_format($sala->valor_hora, 2, ',', '.') }}/h</span>
        </dib>

        <x-ui.button variant="secondary" class="mt-4">
            Saiba mais
        </x-ui.button>
    </div>
</div>