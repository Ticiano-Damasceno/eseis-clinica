<x-layout.app-layout :title="$sala->nome">
    <article class="mx-auto max-w-6xl overflow-hidden rounded-3xl
                    border border-eseis-terracotta bg-white
                    grid grid-cols-1 lg:grid-cols-12">

        {{-- Foto da sala --}}
        <div class="relative h-72 lg:h-auto lg:col-span-5 bg-neutral-100">
            @if ($sala->imagem)
                <img src="{{ asset('storage/' . $sala->imagem) }}" alt="Foto de {{ $sala->nome }}"
                    class="absolute inset-0 w-full h-full object-cover">
            @else
                <div class="absolute inset-0 flex items-center justify-center text-neutral-400">
                    Sem foto da sala
                </div>
            @endif
        </div>

        {{-- Informações --}}
        <div class="lg:col-span-7 min-w-0 p-6 lg:p-8 text-eseis-terracotta">
            <a href="{{ route('salas.index') }}" class="inline-flex items-center gap-2 text-sm hover:underline">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H5m7-7-7 7 7 7" />
                </svg>
                VOLTAR
            </a>

            <h1 class="mt-6 text-2xl lg:text-3xl font-bold text-eseis-brick">
                {{ $sala->nome }} · {{ $sala->tipo_label }}
            </h1>

            <p class="mt-4 text-lg leading-relaxed whitespace-pre-line">{{ $sala->descricao }}</p>

            {{-- Valor e capacidade --}}
            <div class="my-8 flex flex-wrap items-center justify-center gap-6">
                <div class="flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="12" r="9" stroke-width="2" />
                        <path stroke-linecap="round" stroke-width="2"
                            d="M12 6v12m3-9h-4a2 2 0 0 0 0 4h2a2 2 0 0 1 0 4H9" />
                    </svg>

                    <span class="text-lg">
                        R$ {{ number_format($sala->valor_hora, 2, ',', '.') }}/h
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="7" r="3" stroke-width="2" />
                        <path stroke-linecap="round" stroke-width="2"
                            d="M6 21v-3a6 6 0 0 1 12 0v3M5 5a3 3 0 0 0 0 6m14-6a3 3 0 0 1 0 6" />
                    </svg>

                    <span class="text-lg">
                        Até {{ $sala->capacidade }}
                        {{ $sala->capacidade == 1 ? 'pessoa' : 'pessoas' }}
                    </span>
                </div>
            </div>

            {{-- Características cadastradas --}}
            <section>
                <h2 class="font-bold text-eseis-brick">
                    DIFERENCIAIS
                </h2>

                <ul class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @if ($sala->infantil)
                        <li>✓ Atendimento infantil</li>
                    @endif

                    @if ($sala->online)
                        <li>✓ Atendimento online</li>
                    @endif

                    @if ($sala->ar_condicionado)
                        <li>✓ Ambiente climatizado</li>
                    @endif
                </ul>

                @if (!$sala->infantil && !$sala->online && !$sala->ar_condicionado)
                    <p class="mt-3 text-sm">Nenhum diferencial informado.</p>
                @endif
            </section>

            {{-- Espaço reservado para o calendário --}}
            <section class="mt-8">
                <h2 class="font-bold text-eseis-brick">
                    DISPONIBILIDADE — SEMANA ATUAL
                </h2>

                <div class="mt-3">
                    <x-calendario.semanal :dias="$dias" :compacto="true" />
                </div>

                <p class="mt-3 text-xs text-neutral-500">
                    Reservas ainda não disponíveis. A grade vazia não indica horários livres.
                </p>

                <button type="button" disabled class="mt-6 w-full rounded-lg bg-eseis-tan/50
                           py-3 font-semibold text-eseis-brick
                           cursor-not-allowed">
                    Reservar agora
                </button>
            </section>
        </div>
    </article>
</x-layout.app-layout>