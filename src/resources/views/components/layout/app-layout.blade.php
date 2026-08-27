<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Eseís' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-neutral-50">
    <div class="flex min-h-screen">
        <aside class="hidden lg:flex lg:flex-col lg:w-64 bg-eseis-terracotta text-white px-6 py-8">
            <div class="flex items-center gap-2 mb-10">
                <span class="font-display text-2xl">Eseís</span>
            </div>

            <nav class="flex-1 space-y-1">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10">
                    Início
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10">
                    Minha Agenda
                </a>
                <a href="{{ route('salas.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg bg-white/20 font-semibold">
                    Buscar Salas
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10">
                    Créditos
                </a>
            </nav>

            <form method="post" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/10 w-full text-left">
                    Sair
                </button>
            </form>
        </aside>
        <main class="flex-1 px-6 lg:px-12 py-8">
            {{ $slot }}
        </main>
    </div>
</body>
</html>