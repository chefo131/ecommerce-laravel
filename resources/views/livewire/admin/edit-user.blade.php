<div class="p-4 sm:p-6 lg:p-8">
    <div class="mx-auto max-w-2xl">
        <div class="rounded-lg bg-white p-6 shadow dark:bg-gray-800">
            <div class="mb-6">
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Editar Usuario</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                    Asigna un nuevo rol al usuario <span class="font-bold">{{ $user->name }}</span>.
                </p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $user->name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <p class="mt-1 text-gray-900 dark:text-white">{{ $user->email }}</p>
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Rol
                    </label>
                    <select id="role" wire:model="selectedRole" wire:change="updateUserRole"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 sm:text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200"
                        {{ $user->id === 1 ? 'disabled' : '' }}>
                        <option value="">Seleccionar un rol</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                    @if ($user->id === 1)
                        <p class="mt-2 text-sm text-yellow-600 dark:text-yellow-400">
                            El rol del superadministrador no se puede modificar.
                        </p>
                    @endif
                </div>
            </div>

            <div class="mt-8 border-t border-gray-200 pt-5 dark:border-gray-700">
                <div class="flex justify-end">
                    <a href="{{ route('admin.users.index') }}"
                        class="inline-flex items-center justify-center rounded-md border border-transparent bg-lime-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 sm:w-auto">
                        Volver al listado
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
