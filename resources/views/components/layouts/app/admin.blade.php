<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Admin</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        {{-- Font Awesome --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
            integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />

        {{-- Editor de texto --}}
        <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
        {{-- Esta línea ya no es necesaria y se puede eliminar --}}

        {{-- Estilos personalizados para pulir detalles --}}
        <style>
            [wire\:sortable\.handle] {
                cursor: grab !important;
            }
        </style>


        <!-- Script para gestionar el modo oscuro/claro y evitar el "flash" -->
        <script>
            if (localStorage.getItem('darkMode') === 'true' || (!('darkMode' in localStorage) && window.matchMedia(
                    '(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>

    <body x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => { localStorage.setItem('darkMode', val); if (val) { document.documentElement.classList.add('dark'); } else { document.documentElement.classList.remove('dark'); } })" class="font-sans antialiased">
        <div x-data="{ open: false }" class="absolute inset-0 top-0 min-h-screen bg-gray-100 dark:bg-gray-900">
            {{-- Aquí podríamos tener una barra de navegación específica para el admin --}}
            <nav class="border-b border-gray-200 dark:border-gray-700 dark:bg-gray-800">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <div class="top-0 flex flex-shrink-0 items-center">
                                <a href="{{ route('home') }}">
                                    <x-app-logo class="block h-9 w-auto" />
                                </a>
                                <span class="ml-4 font-bold text-gray-700 dark:text-gray-200">Panel de
                                    Administración</span>
                            </div>
                        </div>
                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('admin.index') }}"
                                class="{{ request()->routeIs('admin.index') ? 'border-lime-500 text-gray-900 dark:text-gray-100' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }} inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium">
                                Productos
                            </a>
                            <a href="{{ route('admin.categories.index') }}"
                                class="{{ request()->routeIs('admin.categories.index') ? 'border-lime-500 text-gray-900 dark:text-gray-100' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }} inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium">
                                Categorías
                            </a>
                            <a href="{{ route('admin.brands.index') }}"
                                class="{{ request()->routeIs('admin.brands.*') ? 'border-lime-500 text-gray-900 dark:text-gray-100' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }} inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium">
                                Marcas
                            </a>
                            <a href="{{ route('admin.orders.index') }}"
                                class="{{ request()->routeIs('admin.orders.*') ? 'border-lime-500 text-gray-900 dark:text-gray-100' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }} inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium">
                                Ordenes
                            </a>
                            <a href="{{ route('admin.departments.index') }}"
                                class="{{ request()->routeIs('admin.departments.*') ? 'border-lime-500 text-gray-900 dark:text-gray-100' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }} inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium">
                                Departamentos
                            </a>
                            <a href="{{ route('admin.users.index') }}"
                                class="{{ request()->routeIs('admin.users.*') ? 'border-lime-500 text-gray-900 dark:text-gray-100' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }} inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium">
                                Usuarios
                            </a>
                            <a href="{{ route('admin.reviews.index') }}"
                                class="{{ request()->routeIs('admin.reviews.index') ? 'border-lime-500 text-gray-900 dark:text-gray-100' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200' }} inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium">
                                Reseñas
                            </a>
                        </div>

                        <!-- Menú de Usuario y Configuración -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <!-- Botón de menú de hamburguesa -->
                            <button @click="open = ! open"
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:bg-gray-700 dark:hover:text-gray-400 dark:focus:bg-gray-700 dark:focus:text-gray-400">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center">
                            @auth
                                <!-- Dropdown de Usuario -->
                                <div x-data="{ open: false }" class="relative ml-3">
                                    <div>
                                        <button @click="open = !open" type="button"
                                            class="flex max-w-xs items-center rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 dark:bg-gray-800"
                                            id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                            <span class="sr-only">Abrir menú de usuario</span>
                                            @if (Auth::user()->profile_photo_path)
                                                <img class="h-16 w-16 rounded-full"
                                                    src="{{ Auth::user()->profile_photo_url }}"
                                                    alt="{{ Auth::user()->name }}">
                                            @else
                                                <span
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-gray-500">
                                                    <span class="text-sm font-medium leading-none text-white">
                                                        {{-- Muestra las iniciales del usuario --}}
                                                        {{ substr(Auth::user()->name, 0, 2) }}
                                                    </span>
                                                </span>
                                            @endif
                                        </button>
                                    </div>

                                    <div x-show="open" @click.outside="open = false"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-75"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-gray-700"
                                        role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                                        tabindex="-1">

                                        <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-600">
                                            <p class="text-sm text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                                            <p class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">
                                                {{ Auth::user()->email }}</p>
                                        </div>
                                        {{-- Corregido según tu configuración: 'settings.profile' --}}
                                        <a href="{{ route('settings.profile') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600"
                                            role="menuitem" tabindex="-1" id="user-menu-item-0">Tu Perfil</a>

                                        <div class="border-t border-gray-200 px-4 py-2 dark:border-gray-600">
                                            <button @click="darkMode = !darkMode"
                                                class="flex w-full items-center justify-between text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600"
                                                role="menuitem" tabindex="-1">
                                                <span>Apariencia</span>
                                                <span x-show="!darkMode" class="text-gray-500"><i
                                                        class="fas fa-sun"></i></span>
                                                <span x-show="darkMode" class="text-yellow-400"><i
                                                        class="fas fa-moon"></i></span>
                                            </button>
                                        </div>

                                        <!-- Formulario de Logout -->
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600"
                                                role="menuitem" tabindex="-1" id="user-menu-item-2">
                                                Cerrar sesión
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Menú de navegación responsive -->
            <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
                <div class="space-y-1 pb-3 pt-2">
                    {{-- ¡Código limpio! Usamos nuestro componente x-responsive-nav-link para todo --}}
                    <x-responsive-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.index')">
                        Productos
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.index')">
                        Categorías
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('admin.brands.index') }}" :active="request()->routeIs('admin.brands.*')">
                        Marcas
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('admin.orders.index') }}" :active="request()->routeIs('admin.orders.*')">
                        Ordenes
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('admin.departments.index') }}" :active="request()->routeIs('admin.departments.*')">
                        Departamentos
                    </x-responsive-nav-link>
                    <x-responsive-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')">
                        Usuarios
                    </x-responsive-nav-link>
                    {{-- ¡Y aquí añadimos el enlace que faltaba, usando el mismo componente! --}}
                    <x-responsive-nav-link href="{{ route('admin.reviews.index') }}" :active="request()->routeIs('admin.reviews.index')">
                        Reseñas
                    </x-responsive-nav-link>
                </div>

                <!-- Opciones de usuario responsive -->
                <div class="border-t border-gray-200 pb-1 pt-4 dark:border-gray-600">
                    {{-- Aquí podríamos añadir el resto del menú de usuario si fuera necesario --}}
                    {{-- Por ahora lo mantenemos simple --}}
                </div>
            </div>

            <!-- Page Content -->
            <main>
                <div class="container py-8">
                    {{ $slot }}
                </div>
            </main>
        </div>

        @livewireScripts
        @stack('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener('livewire:initialized', () => {
                // Listener para alertas simples (éxito, error, etc.)
                Livewire.on('swal', (event) => {
                    const data = event[0];
                    Swal.fire({
                        icon: data.icon,
                        title: data.title,
                        text: data.text,
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                    });
                });

                // Listener para alertas de confirmación (para eliminar)
                Livewire.on('swal:confirm', (event) => {
                    const data = event[0];
                    Swal.fire({
                        icon: data.icon,
                        title: data.title,
                        text: data.text,
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: data.confirmButtonText || 'Sí, ¡bórralo!',
                        cancelButtonText: data.cancelButtonText || 'Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Usamos un objeto con nombre para ser más explícitos y robustos
                            Livewire.dispatch(data.method, {
                                id: data.params
                            });
                        }
                    });
                });
            });
        </script>

        {{-- ¡LA CAPA DEL SUPERHÉROE! Script para la funcionalidad de arrastrar y soltar de Livewire --}}
        <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v1.x.x/dist/livewire-sortable.js"></script>


    </body>

</html>
