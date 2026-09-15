<x-layout.app-layout title="Funcionamento da clínica">
    <div class="mx-auto max-w-5xl space-y-8 text-eseis-brick">
        <header>
            <h1 class="font-display text-3xl text-eseis-terracotta">
                Funcionamento da clínica
            </h1>

            <p class="mt-2 text-sm text-neutral-600">
                Consulte os horários semanais e os fechamentos por data.
            </p>
        </header>

        @if ($errors->any())
            <ul role="alert" class="list-disc rounded-lg bg-red-50 p-4 pl-8 text-sm text-red-700">
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        @endif

        @if (session('success'))
            <div role="status" class="rounded-lg bg-green-50 p-4 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <section class="rounded-2xl border border-eseis-tan bg-white p-5">
            <h2 class="text-lg font-semibold">
                Adicionar faixa de funcionamento
            </h2>

            <form method="POST" action="{{ route('admin.configuracoes.funcionamento.store') }}" class="mt-4 space-y-4">
                @csrf

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <label for="dia_semana" class="mb-1 block text-sm font-medium">
                            Dia da semana
                        </label>

                        <select id="dia_semana" name="dia_semana" required class="w-full rounded-lg border-neutral-300">
                            <option value="">Selecione</option>

                            @foreach ($diasSemana as $numero => $nome)
                                <option value="{{ $numero }}" @selected((string) old('dia_semana', '') === (string) $numero)>
                                    {{ $nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="horario_inicio" class="mb-1 block text-sm font-medium">
                            Início
                        </label>

                        <input id="horario_inicio" name="horario_inicio" type="time" step="60" required
                            value="{{ old('horario_inicio') }}" class="w-full rounded-lg border-neutral-300">
                    </div>

                    <div>
                        <label for="horario_fim" class="mb-1 block text-sm font-medium">
                            Término
                        </label>

                        <input id="horario_fim" name="horario_fim" type="time" step="60" required
                            value="{{ old('horario_fim') }}" class="w-full rounded-lg border-neutral-300">
                    </div>

                    <x-ui.button type="submit">
                        Adicionar horário
                    </x-ui.button>
                </div>
            </form>
        </section>

        {{-- Funcionamento semanal --}}
        <section class="overflow-hidden rounded-2xl border border-eseis-tan bg-white">
            <div class="border-b border-eseis-tan p-5">
                <h2 class="text-lg font-semibold">
                    Horários semanais
                </h2>

                <p class="mt-1 text-sm text-neutral-500">
                    Dias sem faixas cadastradas são considerados fechados.
                </p>
            </div>

            <ul class="divide-y divide-eseis-tan">
                @foreach ($diasSemana as $numero => $nome)
                    <li class="flex flex-col gap-3 p-5 sm:flex-row sm:items-start">
                        <h3 class="shrink-0 font-semibold sm:w-40">
                            {{ $nome }}
                        </h3>

                        <div class="flex flex-wrap gap-2">
                            @forelse ($horariosPorDia->get($numero, collect()) as $horario)
                                <div class="rounded-lg bg-eseis-tan/30 px-3 py-2 text-sm">
                                    <span>
                                        {{ substr($horario->horario_inicio, 0, 5) }}
                                        às
                                        {{ substr($horario->horario_fim, 0, 5) }}
                                    </span>

                                    <details class="mt-2">
                                        <summary class="cursor-pointer font-semibold hover:underline">
                                            Editar
                                        </summary>

                                        <form method="POST"
                                            action="{{ route('admin.configuracoes.funcionamento.update', $horario) }}"
                                            class="mt-3 space-y-3">
                                            @csrf
                                            @method('PUT')

                                            <div>
                                                <label for="dia-{{ $horario->id }}" class="block">
                                                    Dia
                                                </label>

                                                <select id="dia-{{ $horario->id }}" name="dia_semana" required
                                                    class="mt-1 w-full rounded-lg border-neutral-300">
                                                    @foreach ($diasSemana as $valor => $rotulo)
                                                        <option value="{{ $valor }}" @selected((int) $horario->dia_semana === $valor)>
                                                            {{ $rotulo }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label for="inicio-{{ $horario->id }}" class="block">
                                                    Início
                                                </label>

                                                <input id="inicio-{{ $horario->id }}" type="time" name="horario_inicio"
                                                    value="{{ substr($horario->horario_inicio, 0, 5) }}" step="60" required
                                                    class="mt-1 w-full rounded-lg border-neutral-300">
                                            </div>

                                            <div>
                                                <label for="fim-{{ $horario->id }}" class="block">
                                                    Término
                                                </label>

                                                <input id="fim-{{ $horario->id }}" type="time" name="horario_fim"
                                                    value="{{ substr($horario->horario_fim, 0, 5) }}" step="60" required
                                                    class="mt-1 w-full rounded-lg border-neutral-300">
                                            </div>

                                            <x-ui.button type="submit">
                                                Salvar alteração
                                            </x-ui.button>
                                        </form>
                                        <form method="POST"
                                            action="{{ route('admin.configuracoes.funcionamento.destroy', $horario) }}"
                                            class="mt-3"
                                            onsubmit="return confirm('Excluir esta faixa de funcionamento semanal?');">
                                            @csrf
                                            @method('DELETE')

                                            <x-ui.button variant="danger" type="submit">
                                                Excluir faixa
                                            </x-ui.button>
                                        </form>
                                    </details>
                                </div>
                            @empty
                                <span class="rounded-lg bg-neutral-100 px-3 py-2 text-sm text-neutral-500">
                                    Fechado
                                </span>
                            @endforelse
                        </div>
                    </li>
                @endforeach
            </ul>
        </section>

        {{-- Bloqueios por data --}}
        <section class="overflow-hidden rounded-2xl border border-eseis-tan bg-white">
            <div class="border-b border-eseis-tan p-5">
                <h2 class="text-lg font-semibold">
                    Bloqueios por data
                </h2>

                <p class="mt-1 text-sm text-neutral-500">
                    Fechamentos da clínica, incluindo datas passadas.
                </p>
            </div>

            @if ($bloqueios->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <caption class="sr-only">
                            Datas de fechamento e seus motivos
                        </caption>

                        <thead class="bg-eseis-tan/20">
                            <tr>
                                <th scope="col" class="px-5 py-3 font-semibold">
                                    Data
                                </th>

                                <th scope="col" class="px-5 py-3 font-semibold">
                                    Motivo
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-eseis-tan">
                            @foreach ($bloqueios as $bloqueio)
                                <tr>
                                    <td class="whitespace-nowrap px-5 py-4 align-top">
                                        <time datetime="{{ $bloqueio->data->format('Y-m-d') }}">
                                            {{ $bloqueio->data->format('d/m/Y') }}
                                        </time>
                                    </td>

                                    <td class="px-5 py-4 text-neutral-600 break-words">
                                        {{ $bloqueio->motivo }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($bloqueios->hasPages())
                    <div class="border-t border-eseis-tan p-5">
                        {{ $bloqueios->links() }}
                    </div>
                @endif
            @else
                <div class="p-8 text-center text-sm text-neutral-500">
                    Nenhum bloqueio cadastrado.
                </div>
            @endif
        </section>
    </div>
</x-layout.app-layout>