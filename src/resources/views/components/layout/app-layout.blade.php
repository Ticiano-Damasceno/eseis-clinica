<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Eseís' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <nav class="bg-white border-b border-neutral-200 px-6 py-4">
        <div class="flex items-center justify-between max-w-6xl mx-auto">
            <span class="font-display text-2xl text-eseis-terracotta">Eseís</span>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm text-neutral-500 hover:text-eseis-terracotta">Sair</button>
            </form>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto ps-6 py-8">
        {{ $slot }}
    </main>
</body>
</html>