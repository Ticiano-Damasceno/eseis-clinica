@props([
    'dias',
    'compacto' => false,
])

<div class="overflow-x-auto">
    <table class="w-full table-fixed border-collapse
                  {{ $compacto ? 'min-w-[420px]' : 'min-w-[700px]' }}">
        <caption class="sr-only">Calendário semanal</caption>

        <thead>
            <tr>
                @foreach (['DOM', 'SEG', 'TER', 'QUA', 'QUI', 'SEX', 'SÁB'] as $nome)
                    <th scope="col" class="pb-3 text-xs font-bold text-eseis-brick">
                        {{ $nome }}
                    </th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            <tr>
                @foreach ($dias as $dia)
                    <td class="border border-eseis-terracotta/60
                               p-2 align-top bg-white
                               {{ $compacto ? 'h-40' : 'h-96' }}">
                        <div class="text-center">
                            <time
                                datetime="{{ $dia->toDateString() }}"
                                @if ($dia->isToday()) aria-current="date" @endif
                                class="inline-flex items-center justify-center
                                       rounded-full px-2 py-1 text-xs font-bold
                                {{ $dia->isToday()
                                    ? 'bg-eseis-terracotta text-white'
                                    : 'text-eseis-brick' }}">
                                {{ $dia->format('d/m') }}
                            </time>
                        </div>

                        {{-- Eventos serão adicionados depois. --}}
                    </td>
                @endforeach
            </tr>
        </tbody>
    </table>
</div>