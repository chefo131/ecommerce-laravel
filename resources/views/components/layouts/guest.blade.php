<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

    <head>
        {{-- Aquí puedes incluir cualquier meta etiqueta, título, etc. --}}
        {{-- Incluye tu head partial común --}}
        @include('partials.head') {{-- Incluye tu head partial común --}}
        {{-- Añade aquí cualquier CSS o JS específico para páginas de invitado si es necesario --}}
        {{-- Los estilos se cargan en el head --}}
        @vite(['resources/css/app.css'])
        @livewireStyles
        <link class="icon" rel="icon" type="image/x-icon" href="/storage/images/favicon.ico">


        {{-- Fontawesome --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
            integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />

        {{-- Flexslider css --}}
        {{-- Cargamos Flexslider desde los assets locales para consistencia con el JS --}}
        <link rel="stylesheet" href="{{ asset('vendor/flexslider/flexslider.css') }}">


    </head>



    <body class="min-h-screen bg-white dark:bg-zinc-800">

        @livewire('navigation') {{-- Componente de navegación Livewire. Ahora manejará los enlaces de login/register para invitados. --}}

        <main>
            {{ $slot }} {{-- Aquí se inyectará el contenido de welcome.blade.php --}}
        </main>

        {{-- @fluxScripts Si usas Flux UI y necesitas scripts globales --}}
        {{-- Los scripts se cargan al final del body para un rendimiento óptimo --}}
        {{-- jQuery debe cargarse antes de los scripts que dependen de él, como Flexslider --}}
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="{{ asset('vendor/flexslider/jquery.flexslider-min.js') }}"></script>
        @vite(['resources/js/app.js'])
        @livewireScripts
        @stack('scripts') {{-- Para scripts específicos de la página --}}
        @fluxScripts
    </body>

</html>
