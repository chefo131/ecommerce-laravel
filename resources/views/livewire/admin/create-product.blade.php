<div class="p-4 sm:p-6 lg:p-8">
    <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Crear Nuevo Producto</h1>
    <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
        Completa la información básica del producto. Podrás añadir detalles como imágenes, colores y tallas después de
        crearlo.
    </p>

    {{-- Usamos un contenedor con fondo blanco para el formulario --}}
    <div class="mt-6 rounded-lg bg-white p-6 shadow-md dark:bg-gray-800">
        <form wire:submit="save">
            <div class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-6">

                {{-- Categoría --}}
                <div class="sm:col-span-3">
                    <label for="category_id"
                        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-200">Categoría</label>
                    <select wire:model.live="category_id" id="category_id"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="" disabled>Selecciona una categoría</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Subcategoría --}}
                <div class="sm:col-span-3">
                    <label for="subcategory_id"
                        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-200">Subcategoría</label>
                    <select wire:model="subcategory_id" id="subcategory_id"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 disabled:opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        @disabled(!$category_id)>
                        <option value="" disabled>Selecciona una subcategoría</option>
                        @foreach ($this->subcategories as $subcategory)
                            <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                        @endforeach
                    </select>
                    @error('subcategory_id')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Nombre --}}
                <div class="sm:col-span-4">
                    <label for="name" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-200">Nombre
                        del Producto</label>
                    <input wire:model.live="name" type="text" id="name"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                    @error('name')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Slug --}}
                <div class="sm:col-span-2">
                    <label for="slug" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-200">Slug
                        (URL)</label>
                    <input wire:model="slug" type="text" id="slug"
                        class="w-full rounded-md border-gray-300 bg-gray-100 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                        readonly />
                    @error('slug')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Description --}}
                {{-- 1. wire:ignore para que Livewire no interfiera con CKEditor --}}
                <div class="sm:col-span-6" wire:ignore>
                    <label for="description"
                        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-200">Descripción</label>
                    {{-- 2. Quitamos wire:model, la sincronización será manual --}}
                    <textarea id="description"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        rows="4" x-data x-init="ClassicEditor.create($refs.editor)
                            .then(function(editor) {
                                // 3. Escuchamos el evento 'change:data' de CKEditor
                                editor.model.document.on('change:data', () => {
                                    // 4. Sincronizamos el contenido con la propiedad de Livewire
                                    @this.set('description', editor.getData());
                                })
                            })
                            .catch(error => { console.error(error) });" x-ref="editor">{{ $description }}</textarea>
                    @error('description')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Marca --}}
                <div class="sm:col-span-2">
                    <label for="brand_id"
                        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-200">Marca</label>
                    <select wire:model="brand_id" id="brand_id"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 disabled:opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        @disabled(!$category_id)>
                        <option value="" selected disabled>Selecciona una marca</option>
                        @foreach ($this->brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    @error('brand_id')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Precio --}}
                <div class="sm:col-span-2">
                    <label for="price"
                        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-200">Precio</label>
                    <input wire:model="price" type="number" step="0.01" id="price"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                    @error('price')
                        <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- En resources/views/livewire/admin/create-product.blade.php (ejemplo) -->
                <div>
                    <x-label>Estado</x-label>
                    <select wire:model="status" class="form-control w-full">
                        <option value="{{ \App\Models\Product::BORRADOR }}">Borrador</option>
                        <option value="{{ \App\Models\Product::PUBLICADO }}">Publicado</option>
                    </select>
                </div>


                {{-- Lógica condicional para la cantidad --}}

                {{-- 1. Si el producto NO tiene color NI talla (ej. TV, audio y video) --}}
                @if (!$productFeatures['color'] && !$productFeatures['size'])
                    <div class="sm:col-span-2">
                        <label for="quantity"
                            class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-200">Cantidad</label>
                        <input wire:model="quantity" type="number" id="quantity"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Stock total del producto.</p>
                        @error('quantity')
                            <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                @endif

                {{-- 2. Si el producto tiene color PERO NO talla (ej. Celulares, consolas) --}}
                @if ($productFeatures['color'] && !$productFeatures['size'])
                    <div class="rounded-md bg-lime-50 p-4 sm:col-span-6 dark:bg-lime-900/20">
                        <p class="text-sm text-lime-800 dark:text-lime-200">
                            Este producto requiere gestión de stock por <strong>color</strong>. Podrás añadir los
                            colores y sus cantidades en la pantalla de edición después de crearlo.
                        </p>
                    </div>
                @endif

                {{-- 3. Si el producto tiene color Y talla (ej. Moda) --}}
                @if ($productFeatures['color'] && $productFeatures['size'])
                    <div class="rounded-md bg-lime-50 p-4 sm:col-span-6 dark:bg-lime-900/20">
                        <p class="text-sm text-lime-800 dark:text-lime-200">
                            Este producto requiere gestión de stock por <strong>talla y color</strong>. Podrás añadir
                            las tallas, colores y sus cantidades en la pantalla de edición después de crearlo.
                        </p>
                    </div>
                @endif
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit"
                    class="rounded-md border border-transparent bg-lime-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2">
                    Crear Producto
                </button>
            </div>
        </form>
    </div>
</div>
