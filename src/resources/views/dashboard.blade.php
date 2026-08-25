<x-layout.app-layout title="Dashboard">

    <h1 class="font-display text-3xl text-neutral-800">
        Olá, {{ auth()->user()->nome ?? auth()->user()->name }}
    </h1>

    <p class="text-neutral-500 mt-2">
        Bem-vindo à área logada do Eseís.
    </p>

</x-layout.app-layout>