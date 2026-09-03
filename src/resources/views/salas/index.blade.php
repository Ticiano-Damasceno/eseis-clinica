<x-layout.app-layout title="Salas">

    <div class="flex items-center justify-between mb-6">
        <h1 class="font-display text-3xl text-eseis-terracotta">Salas de atendimentos</h1>

        @if (auth()->user()->perfil === 'admin')
            <a href="{{ route('admin.salas.create') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('admin.salas.*') ? 'bg-white/20 font-semibold' : 'hover:bg-white/10' }}">
                <x-ui.button variant="primary" class="px-2">
                    + Nova sala
                </x-ui.button>
            </a>
        @endif
    </div>

    @if (session('status'))
        <p class="text-sm text-green-600 mb-4">{{ session('status') }}</p>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse ($salas as $sala)
            <x-ui.sala-card :sala="$sala" />
        @empty
            <p class="text-neutral-500 col-span-full">Nenhuma sala cadastrada ainda.</p>
        @endforelse
    </div>

</x-layout.app-layout>