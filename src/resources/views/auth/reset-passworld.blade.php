<x-layout.guest-layout title="Redefinir senha">
    <h2 class="font-display text-3xl text-neutral-800 mb-6">Nova Senha</h2>

    <form action="" class="space-y-4">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-ui.input type="email" name="email" placeholder="E-mail" :value="old('email', $request->email)" required autofocus />
            @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-ui.input type="password" name="password" placeholder="Nova Senha" icon="lock" required />
            @error('password')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <x-ui.input type="password" name="password_confirmation" placeholder="Confirmar Nova Senha" icon="lock" required />

        <x-ui.button type="submit" variant="primary">Redefinir Senha</x-ui.button>
    </form>
</x-layout.guest-layout>
    