@props(['sala'])

<div class="relative border border-eseis-tan rounded-2xl overflow-hidden">
    <div class="h-48 bg-neutral-200 flex items-center justify-center text-neutral-400">
        Foto da sala
        @if (auth()->user()->perfil === 'admin')
            <a href="{{ route('admin.salas.edit', $sala) }}" title="Editar sala" class="absolute top-3 right-3 p-2 rounded-full
                               bg-white/90 text-eseis-terracotta
                               shadow-sm hover:bg-white
                               transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5
                                   M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                </svg>
            </a>
        @endif
    </div>

    <div class="p-4">
        <h3 class="font-semibold text-eseis-terracotta">
            {{ $sala->nome }} · {{ $sala->tipo_label }}
        </h3>

        <p class="text-sm text-neutral-500 mt-1">
            {{ $sala->descricao }}
        </p>

        <div class="flex items-center gap-4 mt-3 text-sm text-neutral-600">
            <span>{{ $sala->capacidade }} {{ Str::plural('pessoa', $sala->capacidade) }}</span>
            <span>R$ {{ number_format($sala->valor_hora, 2, ',', '.') }}/h</span>
        </div>
        
        @if ($sala->infantil || $sala->online || $sala->ar_condicionado)
            <div class="flex flex-wrap gap-2 mt-3">
                @if ($sala->infantil)
                    <span class="px-2 py-1 rounded-full bg-eseis-tan/40 text-xs text-eseis-terracotta">
                        Infantil
                    </span>
                @endif

                @if ($sala->online)
                    <span class="px-2 py-1 rounded-full bg-eseis-tan/40 text-xs text-eseis-terracotta">
                        Online
                    </span>
                @endif

                @if ($sala->ar_condicionado)
                    <span class="px-2 py-1 rounded-full bg-eseis-tan/40 text-xs text-eseis-terracotta">
                        Ar-condicionado
                    </span>
                @endif
            </div>
        @endif

        <x-ui.button variant="secondary" class="mt-4">
            Saiba mais
        </x-ui.button>
    </div>
</div>