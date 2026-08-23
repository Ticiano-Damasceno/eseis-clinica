<x-layout.guest-layout title="Login">
    <h2 class="font-display text-3xl text-neutral-800 mb-6">Entrar</h2>
    <div x-data="{ perfil: 'psicologo' }">
        <x-ui.tab-toggle
            name="perfil"
            :options="['psicologo' => 'Psicólogo', 'admin' => 'Administrador']" />

        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
            @csrf
            <x-ui.input name="email" placeholder="E-mail" icon="user" :value="old('email')" required autofocus />
            @error('email')
            <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror

            <div>
                <x-ui.input type="password" name="password" placeholder="Senha" icon="lock" required />
                <div class="text-right mt-2">
                    <a href="{{ route('password.request') }}" class="text-sm text-neutral-500 hover:text-eseis-terracotta">
                        Esqueci minha senha
                    </a>
                </div>
                <x-ui.button variant="primary" type="submit">Login</x-ui.button>
            </div>
        </form>

        <p class="text-center text-sm text-neutral-500 mt-6">
            Não tem uma conta?
            <a href="{{ route('register') }}" class="text-eseis-terracotta font-medium hover:underline">
                Cadastre-se
            </a>
        </p>
    </div>
</x-layout.guest-layout>