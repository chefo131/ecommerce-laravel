<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

    <head>
        {{-- Los estilos (CSS) se cargan en el head para evitar el "flash of unstyled content" --}}
        @vite(['resources/css/app.css'])
        @include('partials.head')
        @livewireStyles
        {{-- styles --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
            integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />

        {{-- Flexslider css - Necesario si algún componente lo usa, para consistencia --}}
        <link rel="stylesheet" href="{{ asset('vendor/flexslider/flexslider.css') }}">

    </head>

    <body class="min-h-screen bg-white dark:bg-zinc-800">
        {{-- Cambiado para usar el componente de navegación principal --}}
        @livewire('navigation')
        <main class="pt-16">
            {{ $slot }}
        </main>
        {{-- Los scripts (JS) se cargan al final del body para un rendimiento óptimo y evitar conflictos --}}
        {{-- jQuery y Flexslider deben cargarse ANTES de app.js, ya que este depende de ellos --}}
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="{{ asset('vendor/flexslider/jquery.flexslider-min.js') }}"></script>
        @vite(['resources/js/app.js'])
        @livewireScripts
        @fluxScripts
        @stack('scripts')
    </body>

</html>
