{{--
  Componente para el menú desplegable del usuario autenticado.
  Props:
  - trigger (slot): El contenido HTML que actuará como disparador del dropdown (ej. flux:profile).
  - position: Posición del dropdown (default: 'top').
  - align: Alineación del dropdown (default: 'end').
  - userInfoPaddingClasses: Clases de padding para la sección de información del usuario dentro del menú (default: 'px-1 py-1.5').
--}}

@props([
    'position' => 'top',
    'align' => 'end',
    'userInfoPaddingClasses' => 'px-1 py-1.5',
    //Paddingpordefecto,
    //usado en navigation.blade.php,
])

<flux:dropdown {{ $attributes->merge(['position' => $position, 'align' => $align]) }}> {{-- Imprimimos el contenido del slot $trigger directamente como hijo de flux:dropdown --}}
    {{ $trigger }}
    <flux:menu>
        <flux:menu.radio.group>
            <div class="p-0 text-sm font-normal">
                <div class="{{ $userInfoPaddingClasses }} flex items-center gap-2 text-start text-sm">
                    <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                        <span
                            class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                            {{ auth()->user()->initials() }}
                        </span>
                    </span>
                    <div class="grid flex-1 text-start text-sm leading-tight">
                        <span class="truncate font-semibold">
                            {{ auth()->user()->name }}
                        </span>
                        <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                    </div>
                </div>
            </div>
        </flux:menu.radio.group>
        <flux:menu.separator />
        <flux:menu.radio.group>
            <flux:menu.item :href="route('orders.index')" icon="shopping-bag" wire:navigate>
                {{ __('Mis pedidos') }}
            </flux:menu.item>
            {{-- Usamos @can para mostrar este enlace SOLO si el usuario tiene el permiso 'view-dashboard' --}}
            @can('view-dashboard')
                <flux:menu.item :href="route('admin.index')" icon="shield-check" wire:navigate>
                    {{ __('Administrador') }}
                </flux:menu.item>
            @endcan
            <flux:menu.item :href="route('settings.profile')" icon="user-circle" wire:navigate>
                {{ __('Tu Perfil') }}
            </flux:menu.item>
        </flux:menu.radio.group>
        <flux:menu.separator />
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                {{ __('Log Out') }}
            </flux:menu.item>
        </form>
    </flux:menu>
</flux:dropdown>
