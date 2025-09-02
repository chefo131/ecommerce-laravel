<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white">Editar Producto: <span
                    class="text-lime-500">{{ $product->name }}</span></h1>
            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                Modifica la información del producto. Las imágenes se gestionan en una sección aparte.
            </p>
        </div>
        <div>
            <a href="{{ route('admin.index') }}"
                class="text-sm font-medium text-lime-600 hover:text-lime-500 dark:text-lime-400">
                &larr; Volver al listado
            </a>
        </div>
    </div>

    {{-- Mensaje de éxito --}}
    @if (session()->has('message'))
        <div class="mt-4 rounded-md bg-green-100 p-4 dark:bg-green-900/50">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                        {{ session('message') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- Layout principal de dos columnas --}}
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Columna Izquierda (más ancha) para los datos principales del producto --}}
        <div class="lg:col-span-2">
            <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-800">
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
                            <label for="name"
                                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-200">Nombre
                                del Producto</label>
                            <input wire:model.live="name" type="text" id="name"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white" />
                            @error('name')
                                <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Slug --}}
                        <div class="sm:col-span-2">
                            <label for="slug"
                                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-200">Slug
                                (URL)</label>
                            <input wire:model="slug" type="text" id="slug"
                                class="w-full rounded-md border-gray-300 bg-gray-100 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                                readonly />
                            @error('slug')
                                <span class="mt-1 text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Description con CKEditor --}}
                        <div class="sm:col-span-6" wire:ignore>
                            <label for="description"
                                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-200">Descripción</label>
                            <textarea id="description"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                rows="4" x-data x-init="ClassicEditor.create($refs.editor)
                                    .then(function(editor) {
                                        editor.setData(@js($description)); // Carga el contenido inicial
                                        editor.model.document.on('change:data', () => {
                                            @this.set('description', editor.getData());
                                        })
                                    })
                                    .catch(error => { console.error(error) });" x-ref="editor"></textarea>
                        </div>
                        <div class="-mt-4 sm:col-span-6">
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

                        {{-- Cantidad (solo si no tiene variantes) --}}
                        @if (!$product->subcategory->category->features['color'] && !$product->subcategory->category->features['size'])
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

                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                            class="rounded-md border border-transparent bg-lime-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2">
                            Actualizar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Columna Derecha para Imágenes y Variantes --}}
        <div class="space-y-6">
            {{-- Tarjeta para el Estado del Producto --}}
            <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-800">
                @livewire('admin.status-product', ['product' => $product], key('status-product-' . $product->id))
            </div>

            {{-- Tarjeta para las Imágenes --}}
            <div class="rounded-lg bg-white p-6 shadow-md dark:bg-gray-800">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Imágenes del Producto</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Arrastra para reordenar. La primera imagen es la principal.
                </p>
                <div class="mt-4">
                    @livewire('admin.product-image', ['product' => $product], key('product-images-' . $product->id))
                </div>
            </div>

            {{-- Sección para gestionar colores (si aplica) --}}
            @if ($product->subcategory->category->features['color'] && !$product->subcategory->category->features['size'])
                @livewire('admin.color-product', ['product' => $product], key('color-product-' . $product->id))
            @endif

            {{-- Sección para gestionar tallas y colores (si aplica) --}}
            @if ($product->subcategory->category->features['size'])
                @livewire('admin.size-product', ['product' => $product], key('size-product-' . $product->id))
            @endif
        </div>

    </div>
</div>
