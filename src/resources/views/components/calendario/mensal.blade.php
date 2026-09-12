@props(['dias', 'data'])

<div class="overflow-x-auto">
    <table class="w-full min-w-[700px] table-fixed border-collapse">
        <caption class="sr-only">
            Calendário mensal — {{ $data->translatedFormat('F Y') }}
        </caption>

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
            @foreach ($dias->chunk(7) as $semana)
                <tr>
                    @foreach ($semana as $dia)
                        <td class="h-28 border border-eseis-terracotta/60
                                   p-2 align-top bg-white">
                            <div class="text-center">
                                <time
                                    datetime="{{ $dia->toDateString() }}"
                                    @if ($dia->isToday()) aria-current="date" @endif
                                    class="inline-flex h-6 w-6 items-center justify-center
                                           rounded-full text-xs font-bold
                                    {{ $dia->isToday()
                                        ? 'bg-eseis-terracotta text-white'
                                        : ($dia->format('Y-m') !== $data->format('Y-m')
                                            ? 'text-eseis-terracotta/50'
                                            : 'text-eseis-brick') }}">
                                    {{ $dia->format('d') }}
                                </time>
                            </div>

                            {{-- Eventos serão adicionados depois. --}}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>