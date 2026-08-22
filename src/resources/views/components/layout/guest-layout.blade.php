<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? "Eseís" }}</title>
    @vite(["resources/css/app.css", "resources/js/app.js"])
</head>
<body class="font-sans antialiased">
    <div class="flex min-h-screen">
        <!-- Coluna esquerda -->
        <div class="hidden lg:flex lg:w-1/2 bg-eseis-terracotta items-center justify-center relative overflow-hidden">
            <h1 class="font-display text-white text-6xl">Eseís</h1>
            <p class="text-white/90 mt-2 tracking-widest text-sm uppercase">
                Saúde emocional e cuidado
            </p>
        </div>
        <!-- Coluna direita -->
        <div class="w-full lg:w-1/2 flex items-center justify-center bg-white px-6 py-12">
            <div class="w-full max-w-sm">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>