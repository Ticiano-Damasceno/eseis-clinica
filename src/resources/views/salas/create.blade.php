<x-layout.app-layout title="Nova Sala">

    <h1 class="font-display text-3xl text-eseis-terracotta mb-6">Nova Sala</h1>

    @if (session('status'))
        <p class="text-sm text-green-600 mb-4">{{ session('status') }}</p>
    @endif

    <form method="POST" action="{{ route('admin.salas.store') }}" class="max-w-lg space-y-4"
        enctype="multipart/form-data">
        @csrf

        <div>
            <x-ui.input name="nome" placeholder="Nome da sala" :value="old('nome')" required />
            @error('nome')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="imagem" class="block text-sm text-neutral-600 mb-1">Foto da sala</label>
            <input type="file" name="imagem" id="imagem" accept="image/*" class="w-full text-sm text-neutral-600
               file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
               file:bg-eseis-tan/40 file:text-eseis-terracotta file:font-medium
               hover:file:bg-eseis-tan/60 file:cursor-pointer">
            @error('imagem')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <textarea name="descricao" placeholder="Descrição"
                class="w-full bg-neutral-100 border-none rounded-lg py-3 px-4 focus:ring-2 focus:ring-eseis-terracotta focus:outline-none">{{ old('descricao') }}</textarea>
            @error('descricao')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <x-ui.input type="number" name="capacidade" placeholder="Capacidade (pessoas)" :value="old('capacidade')"
                required />
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
                <input type="checkbox" name="infantil" value="1"> Infantil
            </label>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="online" value="1"> Online
            </label>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="ar_condicionado" value="1"> Ar-condicionado
            </label>
        </div>

        <div class="flex gap-3">
            <x-ui.button variant="primary" type="submit">
                Cadastrar Sala
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