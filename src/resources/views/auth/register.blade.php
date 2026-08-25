<x-layout.guest-layout title="Cadastro">
    <h2 class="font-display text-3xl text-neutral-800 mb-6">Criar conta</h2>

    <div x-data="{ perfil: 'psicologo' }">
        <!-- <x-ui.tab-toggle
            name="perfil"
            :options="['psicologo'=>'Psicólogo', 'admin' => 'Administrador']" /> -->

        <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
            @csrf

            <div>
                <x-ui.input name="name" placeholder="Nome completo" icon="user" :value="old('name')" required autofocus />
                @error('name')
                <p class="text-sm text-red-600 mt-1 ">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-ui.input 
                    name="cpf" 
                    placeholder="CPF" 
                    icon="user" 
                    :value="old('cpf')" 
                    required 
                    maxlength="14" 
                    x-data
                    x-on:input="$el.value = $el.value
                        .replace(/\D/g,'')
                        .replace(/(\d{3})(\d)/,'$1.$2')
                        .replace(/(\d{3})(\d)/,'$1.$2')
                        .replace(/(\d{3})(\d{1,2})$/,'$1-$2')
                        .slice(0,14)
                    " />
                @error('cpf')
                <p class="text-sm text-red-600 mt-1 ">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-ui.input type="email" name="email" placeholder="E-mail" icon="user" :value="old('email')" required />
                @error('email')
                <p class="text-sm text-red-600 mt-1 ">{{ $message }}</p>
                @enderror
            </div>

            <div x-show="perfil === 'psicologo'" x-cloak>
                <x-ui.input name="crp" placeholder="CRP" icon="user" :value="old('crp')" />
                @error('crp')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-ui.input type="password" name="password" placeholder="Senha" icon="lock" required />
                @error('password')
                <p class="text-sm text-red-600 mt-1 ">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-ui.input type="password" name="password_confirmation" placeholder="Confirma senha" icon="lock" required />
            </div>

            <x-ui.button variant="primary" type="submit">Cadastrar</x-ui.button>
        </form>
        <p class="text-center text-sm text-neutral-500 mt-6">
            Já tem uma conta?
            <a href="{{ route('login') }}" class="text-eseis-terracotta font-medium hover:underline">Entrar</a>
        </p>
    </div>
</x-layout.guest-layout>