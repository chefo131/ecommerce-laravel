{{-- Contenedor principal: 1 columna en móvil, 2 en pantallas grandes (lg) --}}
<div class="container grid grid-cols-1 gap-6 py-8 lg:grid-cols-5">

    {{-- Columna Izquierda: Formulario de contacto y envío --}}
    {{-- En móvil (por defecto) es el segundo elemento (order-2), en escritorio (lg) es el primero (lg:order-1) --}}
    <div class="order-2 lg:order-1 lg:col-span-3">
        <div class="w-full rounded-2xl bg-white p-6 shadow-2xl">
            <p class="mb-4 text-lg font-semibold text-gray-700">Información de Contacto</p>
            <label for="contact" class="mb-2 block text-sm font-medium text-gray-700">Nombre de contacto</label>
            <input id="contact" type="text" wire:model.defer="contact"
                placeholder="Ingrese el nombre de la persona que recibirá el producto..."
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
            @error('contact')
                <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
            @enderror

            <label for="phone" class="mb-2 mt-4 block text-sm font-medium text-gray-700">Teléfono de contacto</label>
            <input id="phone" type="text" wire:model.defer="phone"
                placeholder="Ingrese un número de teléfono de contacto..."
                class="w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
            @error('phone')
                <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>

        {{-- Move x-data to this parent div to encompass both the radio group and the conditional content --}}
        {{-- Opciones de envío --}}
        <div class="mt-6 w-full rounded-2xl bg-white p-6 shadow-2xl" x-data="{ envio_type: @entangle('envio_type') }">
            <p class="mb-3 text-lg font-semibold text-gray-700 dark:text-gray-400">Selecciona el tipo de envío</p>
            <div class="space-y-4">
                <div class="rounded-2xl bg-white p-6 shadow-2xl">
                    <label class="flex items-center">
                        <input wire:model.live="envio_type" type="radio" value="1" name="envio_type"
                            class="form-radio h-5 w-5 text-lime-600">
                        <div class="ml-4">
                            <span class="text-lg font-semibold text-gray-900 dark:text-gray-200">Recoger en
                                tienda</span>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Sin gastos de envío</p>
                        </div>
                    </label>
                </div>
                <div class="rounded-2xl bg-white p-6 shadow-2xl">
                    <label class="flex items-center">
                        <input wire:model.live="envio_type" type="radio" value="2" name="envio_type"
                            class="form-radio h-5 w-5 text-lime-600">
                        <div class="ml-4">
                            <span class="text-lg font-semibold text-gray-900 dark:text-gray-200">Envío a
                                domicilio</span>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Esta opción puede tener gastos de envío.
                            </p>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Formulario para provincias, ciudades y códigos postales --}}
            {{-- Now this div is within the x-data scope and uses x-show --}}
            <div class="grid grid-cols-2 gap-6 px-6 pb-6" x-show="envio_type == 2"
                wire:key="address-form-{{ $envio_type }}">

                {{-- Departments...provincias --}}
                <div>
                    <label for="department_id" class="mb-2 block text-sm font-medium text-gray-700">Provincia</label>
                    <select id="department_id" wire:model.live="department_id"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        <option value="" disabled selected>
                            Seleccione una provincia...
                        </option>
                        @foreach ($this->departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                {{-- Cities...Ciudades --}}
                <div>
                    <label for="city_id" class="mb-2 block text-sm font-medium text-gray-700">Ciudad</label>
                    <select id="city_id" wire:model.live="city_id"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 disabled:opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        @disabled(!$department_id)>
                        <option value="" disabled selected>
                            Seleccione una ciudad...
                        </option>
                        @foreach ($this->cities as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                    @error('city_id')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                {{-- Distritos...Codigos postales --}}
                <div>
                    <label for="district_id" class="mb-2 block text-sm font-medium text-gray-700">Código Postal</label>
                    <select id="district_id" wire:model.live="district_id"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 disabled:opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        @disabled(!$city_id)>
                        <option value="" disabled selected>
                            Seleccione un código postal...
                        </option>
                        @foreach ($this->districts as $district)
                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                        @endforeach
                    </select>
                    @error('district_id')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                {{-- Direcciones de envío --}}
                <div>
                    <label for="address" class="mb-2 block text-sm font-medium text-gray-700">Dirección de
                        envío</label>
                    <input id="address" type="text" wire:model.defer="address"
                        placeholder="Ingrese la dirección..."
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                    @error('address')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                {{-- Referencias de envío --}}
                <div class="col-span-2">
                    <label for="references" class="mb-2 block text-sm font-medium text-gray-700">Referencia de
                        envío</label>
                    <input id="references" type="text" wire:model.defer="references"
                        placeholder="Ingrese una referencia..."
                        class="w-1/2 rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                    @error('references')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="mt-6">
            <button type="button" wire:click="create_order" wire:loading.attr="disabled" wire:target="create_order"
                class="w-full cursor-pointer rounded-md border-2 border-lime-400 bg-gray-500 px-4 py-2 text-white shadow-sm hover:bg-lime-500 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 disabled:opacity-50 sm:w-auto">
                Continuar con la compra
            </button>
            <hr class="my-4">
            <p class="mt-2 text-xs text-gray-500">
                Al realizar la compra, aceptas nuestras <a href="#"
                    class="font-semibold text-lime-600 hover:underline">Políticas de Privacidad</a> y <a href="#"
                    class="font-semibold text-lime-600 hover:underline">Términos de Servicio</a>.
            </p>
        </div>
    </div>

    {{-- Columna Derecha: Resumen del pedido --}}
    {{-- En móvil (por defecto) es el primer elemento (order-1), en escritorio (lg) es el segundo (lg:order-2) --}}
    <div class="order-1 lg:order-2 lg:col-span-2">
        <div class="w-full rounded-2xl bg-white p-6 shadow-2xl">
            <p class="text-lg font-semibold text-gray-700">Resumen del Pedido</p>
            <ul class="mt-4 space-y-3 divide-y divide-gray-200">
                @forelse (Cart::content() as $item)
                    <li class="flex items-center pt-3 first:pt-0">
                        <img class="h-12 w-12 rounded-md object-cover" src="{{ $item->options->image }}"
                            alt="">
                        <article class="mx-4 flex-1">
                            <h1 class="font-bold">{{ $item->name }}</h1>
                            <div>
                                <p class="text-xs text-gray-500">Cant: {{ $item->qty }}</p>
                                @isset($item->options['color'])
                                    <p> Color: {{ __($item->options['color']) }}</p>
                                @endisset
                                @isset($item->options['size'])
                                    <p> Talla: {{ __($item->options['size']) }}</p>
                                @endisset
                            </div>
                            <p class="font-semibold">€ {{ number_format($item->price * $item->qty, 2) }}</p>
                        </article>
                    </li>
                @empty
                    <li>
                        <p class="p-4">Tu carrito está vacío.</p>
                    </li>
                @endforelse
            </ul>
            <hr class="my-4">
            @php
                // 1. Obtenemos el subtotal como un número (float) para poder hacer cálculos.
                $subtotal_float = (float) Cart::subtotal(2, '.', '');

                // 2. Determinamos el costo de envío que se usará en el cálculo.
                // Si es recogida en tienda (tipo 1), el costo a sumar es 0. Si no, es el valor de $shopping_cost.
                $costo_envio_calculado = $envio_type == 1 ? 0 : $shopping_cost;

                // 3. Calculamos el total a pagar de forma clara y segura.
                $total_a_pagar = $subtotal_float + $costo_envio_calculado;
            @endphp
            <div class="text-gray-700">
                <p class="flex items-center justify-between font-semibold">
                    <span>Subtotal</span>
                    <span>@money($subtotal_float)</span>
                </p>
                <p class="flex items-center justify-between font-semibold">
                    <span>Envío</span>
                    <span class="font-semibold">
                        @if ($envio_type == 1 || $shopping_cost == 0)
                            Gratis
                        @else
                            @money($shopping_cost)
                        @endif
                    </span>
                </p>
                <hr class="my-4">
                <p class="flex items-center justify-between text-xl font-bold text-gray-800">
                    <span>Total</span>
                    {{-- Usamos la variable calculada y la formateamos para mostrarla correctamente. --}}
                    <span>@money($total_a_pagar)</span>
                </p>
            </div>
        </div>
    </div>
</div>
