<x-layout.app-layout title="Nova Sala">

    <h1 class="font-display text-3xl text-eseis-terracotta mb-6">Nova Sala</h1>

    @if (session('status'))
        <p class="text-sm text-green-600 mb-4">{{ session('status') }}</p>
    @endif

    <form method="POST" action="{{ route('salas.store') }}" class="max-w-lg space-y-4">
        @csrf

        <div>
            <x-ui.input name="nome" placeholder="Nome da sala" :value="old('nome')" required />
            @error('nome') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <textarea name="descricao" placeholder="Descrição" class="w-full bg-neutral-100 border-none rounded-lg py-3 px-4 focus:ring-2 focus:ring-eseis-terracotta focus:outline-none">{{ old('descricao') }}</textarea>
            @error('descricao') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <x-ui.input type="number" name="capacidade" placeholder="Capacidade (pessoas)" :value="old('capacidade')" required />
            @error('capacidade') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <x-ui.input type="number" name="valor_hora" placeholder="Valor por hora (R$)" :value="old('valor_hora')" required />
            @error('valor_hora') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-6 text-sm text-neutral-600">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="infantil" value="1"> Infantil
            </label>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="online" value="1"> Online
            </label>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="ar_condicionado" value="1"> Ar-condicionado
            </label>
        </div>

        <x-ui.button variant="primary" type="submit">
            Cadastrar Sala
        </x-ui.button>
    </form>

</x-layout.app-layout>