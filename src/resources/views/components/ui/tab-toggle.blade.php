@props([
    'options' => [],
    'name' => 'tab',
])

<div class="w-full">
    <input type="hidden" name="{{ $name }}" :value="perfil">
    
    <div class="flex bg-eseis-beige rounded-lg p-1">
        @foreach ($options as $value => $label)
            <button
                type="button"
                @click="perfil = '{{ $value }}'"
                :class="perfil === '{{ $value }}'
                    ? 'bg-eseis-terracotta text-white'
                    : 'text-neutral-600 hover:text-neutral-800'
                "
                class="flex-1 py-2 text-sm font-medium rounded-md transition-colors"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>
</div>