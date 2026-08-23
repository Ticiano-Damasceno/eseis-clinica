<x-layout.guest-layout title="Recuperar senha">
    <h2 class="font-display text-3xl text-neutral-800 mb-4">
        Recuperar senha
    </h2>

    <p class="text-sm text-neutral-500 mb-6">
        Informe seu e-mail e enviaremos um link para redefinir sua senha.
    </p>

    @if (session('status'))
    <p class="text-sm text-green-600 mb-4">{{ session('status') }}</p>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf
        <div>
            <x-ui.input type="email" name="email" placeholder="E-mail" icon="user" :value="old('email')" required autofocus />
            @error('email')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <x-ui.button variant="primary" type="submit">
            Enviar link de recuperação
        </x-ui.button>
    </form>

    <p class="text-center text-sm text-neutral-500 mt-6">
        <a href="{{ route('login') }}" class="text-eseis-terrecotta font-medium hover:underline">
            Voltar para o login
        </a>
    </p>
</x-layout.guest-layout>