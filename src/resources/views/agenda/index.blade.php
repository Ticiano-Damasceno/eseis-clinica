<x-layout.app-layout title="Minha Agenda">
    <section class="text-eseis-brick">
        <header class="flex flex-wrap items-center justify-between gap-6 mb-8">
            <div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('agenda.index', [
    'data' => $anterior->toDateString(),
    'modo' => $modo,
]) }}" aria-label="Período anterior" class="p-2 rounded-lg hover:bg-eseis-tan/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="m15 18-6-6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>

                    <a href="{{ route('agenda.index', [
    'data' => $proximo->toDateString(),
    'modo' => $modo,
]) }}" aria-label="Próximo período" class="p-2 rounded-lg hover:bg-eseis-tan/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="m9 6 6 6-6 6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>

                    <h1 class="text-2xl lg:text-3xl font-bold">
                        {{ ucfirst($data->translatedFormat('F \d\e Y')) }}
                    </h1>
                </div>

                @if ($modo === 'semana')
                    <p class="mt-2 text-sm">
                        {{ $inicio->format('d/m/Y') }}
                        a
                        {{ $inicio->addDays(6)->format('d/m/Y') }}
                    </p>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-6">
                <div class="text-xs space-y-1">
                    <p class="font-bold">Legenda</p>

                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-green-500"></span>
                        Paciente em atendimento
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-red-600"></span>
                        Sala bloqueada
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-eseis-terracotta"></span>
                        Sala reservada
                    </div>
                </div>

                <nav aria-label="Visualização da agenda" class="flex rounded-xl bg-eseis-tan p-1">
                    @foreach (['mes' => 'Mês', 'semana' => 'Semana'] as $valor => $rotulo)
                                        <a href="{{ route('agenda.index', [
                            'data' => $data->toDateString(),
                            'modo' => $valor,
                        ]) }}" @if ($modo === $valor) aria-current="true" @endif class="px-4 py-2 rounded-lg text-sm font-semibold
                                                {{ $modo === $valor
                            ? 'bg-eseis-beige text-neutral-700'
                            : 'text-neutral-600 hover:bg-white/20' }}">
                                            {{ $rotulo }}
                                        </a>
                    @endforeach
                </nav>
            </div>
        </header>

        @if ($modo === 'mes')
            <x-calendario.mensal :dias="$dias" :data="$data" />
        @else
            <x-calendario.semanal :dias="$dias" />
        @endif

        <p class="mt-3 text-xs text-neutral-500">
            A exibição de reservas e atendimentos será disponibilizada em breve.
        </p>
    </section>
</x-layout.app-layout>