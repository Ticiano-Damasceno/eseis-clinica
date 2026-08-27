<x-layout.app-layout title="Salas">

    <h1 class="font-display text-3xl text-eseis-terracotta mb-6">Salas de atendimentos</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse ($salas as $sala)
            <x-ui.sala-card :sala="$sala" />
        @empty
            <p class="text-neutral-500 col-span-full">Nenhuma sala cadastrada ainda.</p>
        @endforelse
    </div>

</x-layout.app-layout>