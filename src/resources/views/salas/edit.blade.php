<x-layout.app-layout title="Editar Sala">

    <h1 class="font-display text-3xl text-eseis-terracotta mb-6">Editar Sala</h1>

    @if (session('status'))
        <p class="text-sm text-green-600 mb-4">{{ session('status') }}</p>
    @endif

    <form method="POST" action="{{ route('admin.salas.update', $sala) }}" class="max-w-lg space-y-4">
        @csrf
        @method('PUT')

        <div>
            <x-ui.input name="nome" placeholder="Nome da sala" :value="old('nome', $sala->nome)" required />
            @error('nome')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <textarea name="descricao" placeholder="Descrição"
                class="w-full bg-neutral-100 border-none rounded-lg py-3 px-4 focus:ring-2 focus:ring-eseis-terracotta focus:outline-none">{{ old('descricao', $sala->descricao) }}</textarea>
            @error('descricao')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <x-ui.input type="number" name="capacidade" placeholder="Capacidade (pessoas)" :value="old('capacidade', $sala->capacidade)" required />
            @error('capacidade')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-neutral-500 shrink-0">R$</span>
                    <x-ui.input name="valor_hora" inputmode="decimal" placeholder="0,00" :value="old('valor_hora')"
                        required />
                </div>
                @error('valor_hora')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            @error('valor_hora')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-6 text-sm text-neutral-600">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="infantil" value="1" @checked(old('infantil', $sala->infantil))> Infantil
            </label>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="online" value="1" @checked(old('online', $sala->online))> Online
            </label>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="ar_condicionado" value="1" @checked(old('ar_condicionado', $sala->ar_condicionado))> Ar-condicionado
            </label>
        </div>

        <div class="flex gap-3">
            <x-ui.button variant="primary" type="submit">
                Salvar Alterações
            </x-ui.button>

            <a href="{{ route('salas.index') }}" class="w-full">
                <x-ui.button variant="outline" type="button"
                    class="hover:bg-eseis-tan/30 hover:border-eseis-terracotta">
                    Cancelar
                </x-ui.button>
            </a>
        </div>

    </form>

</x-layout.app-layout>