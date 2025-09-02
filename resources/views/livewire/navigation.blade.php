{{-- El estilo para el hover del submenu se mueve a navigation.css --}}
{{-- Usando el componente Alpine registrado en app.js navigationDropdown() en vez de { open: false } --}}

<div>
    {{-- Este bg-gray-400 es del contenedor principal, no del botón --}}

    {{-- Banner de Alerta de Pedidos Pendientes --}}
    @if (isset($pendiente) && $pendiente)
        <div class="border-l-4 border-yellow-500 bg-yellow-100 p-4 text-center text-yellow-700" role="alert">
            <p>
                <span class="font-bold">¡Atención!</span> Tienes una o más órdenes pendientes de pago.
                <a href="{{ route('orders.index') }}" class="font-bold underline hover:text-yellow-800">Ver mis pedidos
                    pendientes</a>.
            </p>
        </div>
    @endif

    <div x-data="navigationDropdown()" x-init="$watch('open', value => document.body.classList.toggle('overflow-hidden', value))" class="main-header">
        {{-- Este bg-gray-400 es del contenedor principal, no del botón --}}
        {{-- Contenedor para los elementos de la barra de navegación --}}
        {{-- Added items-center --}}
        <div
            class="relative flex h-16 w-full items-center justify-between bg-gray-400 md:justify-start dark:bg-gray-700">


            {{-- Ajustado dark:bg-gray-100 a dark:bg-gray-700 para consistencia --}}
            {{-- Menu Hamburguesa --}}
            <a @click="show()" :class="{ 'bg-gray-400 dark:bg-gray-700 text-lime-300 dark:text-lime-400': open }"
                {{-- Fondo base transparente (hereda de flex-container), hover sutil, ajustado padding --}}
                class="relative order-last flex h-full flex-shrink-0 cursor-pointer flex-col items-center justify-center px-6 font-semibold hover:bg-gray-400 md:order-first md:px-4 dark:hover:bg-gray-800">
                <svg viewBox="0 0 100 80" width="35" height="35" fill="currentColor">
                    {{-- Cambiado fill a currentColor --}}
                    <rect width="100" height="20"></rect>
                    <rect y="30" width="100" height="20"></rect>
                    <rect y="60" width="100" height="20"></rect>
                </svg>
                <span class="text-xs text-white dark:text-lime-100"
                    :class="{ 'text-lime-500 dark:text-lime-400': open }">
                    Categorías
                </span>


            </a>
            {{-- Logo --}}
            <a href="/" class="logo-container">
                <x-app-logo class="logo-image" />
            </a>
            {{-- Input y lupa --}}
            {{-- Hacemos que el contenedor del buscador ocupe el espacio disponible en el flex layout.
             Añadimos mx-2 para un pequeño espacio en pantallas pequeñas, y md:mx-4 para un espacio mayor en medianas y grandes. --}}
            <div class="search-container mx-2 flex-1 md:mx-4">
                @livewire('search')
            </div>
            {{-- Carro de la compra --}}
            <div class="actions-container items-center">
                {{-- Wrapper para el carrito para un ajuste vertical fino --}}
                <div class="cart-wrapper ml-5 mt-2 hidden md:block"> {{-- Puedes ajustar mt-0.5 a mt-1 si necesitas más, o quitarlo --}}
                    @livewire('dropdown-cart')
                </div>

                {{-- Wrapper para el bloque de autenticación para añadir un margen izquierdo específico --}}
                {{-- Este margen se suma al 'gap' existente en .actions-container --}}
                {{-- Puedes ajustar 'ml-4' (1rem) a 'ml-2', 'ml-6', etc., según el espaciado deseado --}}
                <div class="auth-block-wrapper ml-7 hidden md:block">
                    @auth
                        {{-- Usamos el componente de dropdown de usuario --}}
                        <x-user-auth-dropdown align="end">
                            <x-slot:trigger>
                                {{-- blade-formatter-disable --}}
                            <flux:profile
                                class="flex h-10 w-10 py-2 -ml-2 cursor-pointer items-center justify-center rounded-md border border-gray-300 bg-gray-300 text-sm font-semibold text-gray-700 hover:border-lime-500 focus:outline-none dark:border-gray-500 dark:bg-gray-600 dark:text-gray-100 dark:hover:border-lime-400"
                                :name="auth()->user()->name" :initials="auth()->user()->initials()"
                                avatar="{{ asset('storage/images/avatar.jpg') }}" />
                            {{-- blade-formatter-enable --}}

                            </x-slot:trigger>
                            {{-- El contenido del menú ahora está definido DENTRO de x-user-auth-dropdown --}}
                            {{-- (usando flux:menu), por lo que el slot "content" aquí ya no es necesario. --}}
                        </x-user-auth-dropdown>
                    @else
                        {{-- Enlaces para usuarios no autenticados (invitados) --}}
                        {{-- Agrupamos los enlaces de login/register para un espaciado consistente si es necesario --}}
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('login') }}"
                                class="btn -ml-6 bg-white text-black hover:bg-lime-500 hover:text-white">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="btn mr-2 bg-white text-black hover:bg-lime-500 hover:text-white">
                                    Register
                                </a>
                            @endif
                        </div>
                    @endauth
                </div>
            </div>
        </div>
        {{-- Fin de flex-container --}}

        {{-- Contenedor del menú desplegable de Categorías --}}
        <div x-show="open" @click.away="close()" x-cloak x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 transform -translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-2" class="absolute left-0 top-full z-30 w-full">
            {{-- El <nav> ahora está dentro de este div y se le aplican nuevos estilos --}}
            <nav class="h-[calc(100vh-4rem)] overflow-y-auto bg-gray-700 shadow-lg dark:bg-gray-800">
                {{-- Asumimos que la barra de navegación (flex-container) tiene una altura de 4rem (h-16) --}}

                {{-- Menú pantalla grande --}}
                <div class="container mx-auto hidden h-full md:block">
                    {{-- Added mx-auto for container centering --}}
                    <div class="relative grid h-full w-full grid-cols-4">
                        {{-- Columna Izquierda: Lista de Categorías --}}
                        <ul class="h-full overflow-y-auto bg-white dark:bg-gray-700">
                            @foreach ($categories as $category)
                                <li class="navigation-link:hover border-b border-gray-200 hover:bg-lime-100 dark:border-gray-600 dark:hover:bg-lime-700"
                                    x-data="{ submenuOpen: false, closeTimer: null }" @mouseenter="clearTimeout(closeTimer); submenuOpen = true"
                                    @mouseleave="closeTimer = setTimeout(() => { submenuOpen = false }, 200)"
                                    {{-- Retardo de 200ms antes de cerrar --}}>
                                    <a href="{{ route('categories.show', $category) }}"
                                        class="flex items-center px-4 py-3 text-gray-700 hover:text-lime-600 dark:text-gray-200 dark:hover:text-lime-400">
                                        <!-- ... contenido del link de categoría ... -->
                                        <span
                                            class="mr-3 flex h-9 w-9 items-center justify-center text-gray-700 dark:text-gray-200">
                                            <x-icon :name="$category->icon" />
                                        </span>
                                        {{ $category->name }}
                                    </a>
                                    {{-- Submenu que aparece al hacer hover sobre este item de categoría --}}
                                    <div x-show="submenuOpen" @mouseenter="clearTimeout(closeTimer); submenuOpen = true"
                                        {{-- Mantener abierto si el cursor entra al submenú --}}
                                        @mouseleave="closeTimer = setTimeout(() => { submenuOpen = false }, 300)"
                                        {{-- Aumentado el retardo a 300ms --}} {{-- Cerrar si el cursor sale del submenú --}} x-cloak
                                        x-transition:enter="transition ease-out duration-300" {{-- Aumentada duración a 300ms --}}
                                        x-transition:enter-start="opacity-0 transform scale-95"
                                        x-transition:enter-end="opacity-100 transform scale-100"
                                        x-transition:leave="transition ease-in duration-300" {{-- Aumentada duración a 200ms --}}
                                        x-transition:leave-start="opacity-100 transform scale-100"
                                        x-transition:leave-end="opacity-0 transform scale-95"
                                        class="navigation-submenu absolute right-0 top-0 z-10 h-full w-3/4 rounded-md bg-gray-300 shadow-lg dark:bg-gray-600">



                                        <!-- ... contenido del submenú ... -->

                                        <div class="grid grid-cols-4 gap-4 p-4">
                                            <div class="col-span-1">
                                                <p
                                                    class="mb-3 text-center text-lg font-bold text-gray-800 dark:text-gray-100">
                                                    Subcategorías
                                                </p>
                                                @if ($category->subcategories->isNotEmpty())
                                                    <ul class="space-y-1">
                                                        @foreach ($category->subcategories as $subcategory)
                                                            <li>
                                                                <a href="{{ route('categories.show', $category) }}?subcategory={{ $subcategory->slug }}"
                                                                    class="inline-block w-full text-center text-sm font-semibold text-gray-700 hover:text-lime-600 dark:text-gray-200 dark:hover:text-lime-400">
                                                                    {{ $subcategory->name }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                @endif
                                            </div>
                                            <div class="col-span-3">
                                                @if ($category->getFirstMediaUrl('categories'))
                                                    <img class="h-64 w-full rounded object-cover object-center"
                                                        src="{{ $category->getFirstMediaUrl('categories') }}"
                                                        alt="{{ $category->name }}">
                                                @else
                                                    <div
                                                        class="flex h-64 w-full items-center justify-center rounded bg-gray-400 dark:bg-gray-700">
                                                        <p class="text-gray-600 dark:text-gray-200">
                                                            Sin imagen
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        {{-- Columna Derecha: Contenido de la categoría (inicialmente la primera) --}}
                        <div class="col-span-3 h-full overflow-y-auto bg-gray-200 p-6 dark:bg-gray-800">
                            @if ($categories->isNotEmpty() && ($firstCategory = $categories->first()))
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                                    <div class="md:col-span-1">
                                        <h3
                                            class="mb-3 text-center text-lg font-semibold text-gray-800 md:text-left dark:text-gray-100">
                                            Subcategorías de {{ $firstCategory->name }}
                                        </h3>
                                        @if ($firstCategory->subcategories->isNotEmpty())
                                            <ul class="space-y-2">
                                                @foreach ($firstCategory->subcategories as $subcategory)
                                                    <li>
                                                        <a href="{{ route('categories.show', $firstCategory) }}?subcategory={{ $subcategory->slug }}"
                                                            class="block text-center text-sm font-medium text-gray-700 hover:text-lime-600 md:text-left dark:text-gray-300 dark:hover:text-lime-400">
                                                            {{ $subcategory->name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p
                                                class="text-center text-sm text-gray-500 md:text-left dark:text-gray-400">
                                                No hay subcategorías.
                                            </p>
                                        @endif
                                    </div>
                                    <div class="md:col-span-3">
                                        @if ($firstCategory->getFirstMediaUrl('categories'))
                                            <img class="h-64 w-full rounded-md object-cover object-center shadow-md"
                                                src="{{ $firstCategory->getFirstMediaUrl('categories') }}"
                                                alt="{{ $firstCategory->name }}">
                                        @else
                                            <div
                                                class="flex h-64 w-full items-center justify-center rounded-md bg-gray-300 shadow-md dark:bg-gray-700">
                                                <p class="text-gray-500 dark:text-gray-400">
                                                    Imagen no disponible
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="flex h-full items-center justify-center">
                                    <p class="text-lg text-gray-600 dark:text-gray-400">
                                        @if ($categories->isEmpty())
                                            No hay categorías para mostrar.
                                        @else
                                            Seleccione una categoría.
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Menú pantalla pequeña --}}

                {{-- Este menú es solo para pantallas pequeñas, oculto en md+ --}}
                <div class="h-full overflow-y-auto bg-white md:hidden">

                    <ul class="dark:bg-gray-700"> {{-- Añadido dark mode background --}}
                        @foreach ($categories as $category)
                            <li class="border-b border-gray-200 hover:bg-lime-100 dark:border-gray-600 dark:hover:bg-lime-700"
                                x-data="{ submenuOpen: false, closeTimer: null }" @mouseenter="clearTimeout(closeTimer); submenuOpen = true"
                                @mouseleave="closeTimer = setTimeout(() => { submenuOpen = false }, 200)"
                                {{-- Retardo de 200ms antes de cerrar --}}>
                                <a href="{{ route('categories.show', $category) }}"
                                    class="flex items-center bg-lime-100 px-4 py-3 text-gray-700 hover:text-lime-600 dark:text-gray-200 dark:hover:text-lime-400">
                                    <!-- ... contenido del link de categoría ... -->
                                    <span
                                        class="mr-3 flex h-9 w-9 items-center justify-center text-gray-700 dark:text-gray-200">
                                        <x-icon :name="$category->icon" />
                                    </span>
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <p class="my-4 px-6 text-gray-600">
                        Usuarios
                    </p>
                    @livewire('cart-movil')
                    @auth
                        <a href="{{ route('orders.index') }}"
                            class="flex items-center border-b border-gray-200 bg-lime-100 px-4 py-3 text-gray-700 hover:bg-lime-100 hover:text-lime-600 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-lime-700 dark:hover:text-lime-400">
                            <!-- ... contenido del link de Mis pedidos ... -->
                            <span class="mr-3 flex h-9 w-9 items-center justify-center text-gray-700 dark:text-gray-200">
                                <x-icon name="fa-solid fa-shopping-bag" />
                            </span>
                            Mis pedidos
                        </a>
                        @can('view-dashboard')
                            <a href="{{ route('admin.index') }}"
                                class="flex items-center border-b border-gray-200 bg-lime-100 px-4 py-3 text-gray-700 hover:bg-lime-100 hover:text-lime-600 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-lime-700 dark:hover:text-lime-400">
                                <!-- ... contenido del link de Administrador ... -->
                                {{-- Usamos @can para mostrar este enlace SOLO si el usuario tiene el permiso 'view-dashboard' --}}
                                <span class="mr-3 flex h-9 w-9 items-center justify-center text-gray-700 dark:text-gray-200">
                                    {{-- Usamos el mismo icono que en el menú de escritorio para consistencia --}}
                                    <x-heroicon-o-shield-check class="h-6 w-6" />
                                </span>
                                Administrador
                            </a>
                        @endcan
                        <a href="{{ route('settings.profile') }}"
                            class="flex items-center border-b border-gray-200 bg-lime-100 px-4 py-3 text-gray-700 hover:bg-lime-100 hover:text-lime-600 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-lime-700 dark:hover:text-lime-400">
                            <!-- ... contenido del link de Perfil ... -->
                            <span class="mr-3 flex h-9 w-9 items-center justify-center text-gray-700 dark:text-gray-200">
                                <x-icon name="fa-solid fa-user-plus" />
                            </span>
                            Perfil
                        </a>
                        <a href="#"
                            onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"
                            class="flex items-center border-b border-gray-200 bg-lime-100 px-4 py-3 text-gray-700 hover:bg-lime-100 hover:text-lime-600 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-lime-700 dark:hover:text-lime-400">
                            <span class="mr-3 flex h-9 w-9 items-center justify-center text-gray-700 dark:text-gray-200">
                                <x-icon name="fa-solid fa-right-from-bracket" />
                            </span>
                            Cerrar sesión
                        </a>
                        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                            @csrf
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="flex items-center border-b border-gray-200 bg-lime-100 px-4 py-3 text-gray-700 hover:bg-lime-100 hover:text-lime-600 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-lime-700 dark:hover:text-lime-400">
                            <!-- ... contenido del link de Login ... -->
                            <span class="mr-3 flex h-9 w-9 items-center justify-center text-gray-700 dark:text-gray-200">
                                <x-icon name="fa-solid fa-users-gear" />
                            </span>
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="flex items-center border-b border-gray-200 bg-lime-100 px-4 py-3 text-gray-700 hover:bg-lime-100 hover:text-lime-600 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-lime-700 dark:hover:text-lime-400">
                            <!-- ... contenido del link de Register ... -->
                            <span class="mr-3 flex h-9 w-9 items-center justify-center text-gray-700 dark:text-gray-200">
                                <x-icon name="fa-solid fa-fingerprint" />
                            </span>
                            Register
                        </a>
                    @endauth
                </div>
            </nav>
        </div>
    </div>
</div>
