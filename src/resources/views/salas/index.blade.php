<x-layout.app-layout title="Salas">
    <h1 class="font-display text-3xl text-neutral-800">Salas</h1>

    @forelse ($salas as $sala)
        <p>{{ $sala->nome }}</p>
        <p>{{ $sala->tipo_label }}</p>
    @empty
        <p class="text-neutral-500 mt-4">Nenhuma sala cadastrada ainda.</p>
    @endforelse
</x-layout.app-layout>