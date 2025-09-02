@props(['on'])

<div x-data="{ shown: false, timeout: null }" x-init="@this.on('{{ $on }}', () => { clearTimeout(timeout);
    shown = true;
    timeout = setTimeout(() => { shown = false }, 2000); })" x-show.transition.out.opacity.duration.1500ms="shown" x-cloak
    {{ $attributes->merge(['class' => 'text-sm text-gray-600 dark:text-gray-300']) }}>
    {{ $slot->isEmpty() ? 'Guardado.' : $slot }}
</div>
