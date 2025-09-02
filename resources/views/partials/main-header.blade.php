
<div x-data="{ open: false }" class="sticky top-0 z-40 h-16 border-b border-gray-300 bg-gray-200 shadow-sm dark:border-gray-600 dark:bg-gray-700">
   
    <div class="flex-container bg-gray-400"> {{-- El flex-container es el hijo directo y maneja el layout interno --}}
        
        {{-- 1. Menú Hamburguesa / Categorías --}}
        <a @click="open = !open"
            class="relative flex flex-shrink-0 cursor-pointer flex-col items-center justify-center pr-4 font-semibold">
            <svg viewBox="0 0 100 80" width="24" height="24">
                <rect width="100" height="20"></rect>
                <rect y="30" width="100" height="20"></rect>
                <rect y="60" width="100" height="20"></rect>
            </svg>
            <span class="text-xs text-lime-900 dark:text-lime-100">Categorías</span>
            
        </a>

        {{-- 2. Logo --}}
        <a href="{{ route('dashboard') }}" class="logo-container"> {{-- Usamos tu clase para el logo --}}
            <x-app-logo class="logo-image" /> {{-- Usamos tu clase para la imagen del logo --}}
        </a>

        {{-- 3. Campo de Búsqueda --}}
        <div class="search-container">
            @livewire('search')
        </div>
 

        {{-- 4. Carrito y Acciones de Usuario --}}
        {{-- Wrapper para el carrito, se centrará en el espacio disponible --}}
        <div class="cart-wrapper" style="margin-left: auto; margin-right: auto; padding-left: 15px; padding-right: 15px;">
            @livewire('dropdown-cart')
        </div>

        {{-- 4. Acciones de Usuario (Avatar/Login/Register) --}}
        <div class="actions-container">
            @auth
                <x-user-auth-dropdown align="end" position="top">
                    <x-slot:trigger>
                        <flux:profile
                            class="flex h-8 w-8 cursor-pointer items-center justify-center rounded-md border border-gray-300 bg-gray-300 text-sm font-semibold text-gray-700 hover:border-lime-500 dark:border-gray-500 dark:bg-gray-600 dark:text-gray-100 dark:hover:border-lime-400"
                            :name="auth()->user()->name" :initials="auth()->user()->initials()" {{-- Asumiendo que tienes un método initials() en tu modelo User --}}
                            avatar="{{ asset('storage/images/avatar.jpg') }}" />
                    </x-slot:trigger>
                </x-user-auth-dropdown>
            @else
                <a href="{{ route('login') }}"
                    class="btn bg-white text-black hover:bg-lime-500 hover:text-white"> {{-- Aplicamos tu clase .btn y las de Tailwind para el look deseado --}}
                    Log in
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                        class="btn bg-white text-black hover:bg-lime-500 hover:text-white"> {{-- Aplicamos tu clase .btn y las de Tailwind para el look deseado --}}
                        Register
                    </a>
                @endif
            @endguest
        </div>
    </div>
   

    {{-- Contenedor del menú desplegable del Dashboard --}}
    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2" class="absolute left-0 top-full z-30 w-full">       
        
         <nav id="dashboard-menu-content" class="overflow-y-auto border-t border-gray-300 bg-white p-6 shadow-lg dark:border-gray-600 dark:bg-gray-800">
            <ul>
                <li class="py-2"><a href="#" class="hover:text-lime-500">Estadísticas</a></li>
                <li class="py-2"><a href="#" class="hover:text-lime-500">Gestión de Usuarios</a></li>
            </ul>
        </nav>
    </div> {{-- Cierre del div x-show="open" (contenedor del menú desplegable) --}}
</div>  {{-- Cierre del div x-data (contenedor principal del header) --}}
