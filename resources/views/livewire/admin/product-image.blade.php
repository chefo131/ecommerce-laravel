<div>
    {{-- SECCIÓN PARA SUBIR NUEVAS IMÁGENES --}}
    <div class="mb-4" x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
        x-on:livewire-upload-finish="isUploading = false; progress = 0"
        x-on:livewire-upload-error="isUploading = false; progress = 0"
        x-on:livewire-upload-progress="progress = $event.detail.progress">

        {{-- Input de fichero que se dispara con el botón --}}
        <input type="file" wire:model.live="image" x-ref="fileInput" class="hidden"
            id="image-upload-{{ $product->id }}" />

        {{-- Botón que el usuario ve --}}
        <button @click="$refs.fileInput.click()" class="btn btn-primary">
            <i class="fa-solid fa-upload mr-2"></i>
            Subir nueva imagen
        </button>

        {{-- Barra de progreso --}}
        <div x-show="isUploading" class="mt-2 w-full rounded-full bg-gray-200 dark:bg-gray-700">
            <div class="rounded-full bg-blue-600 p-0.5 text-center text-xs font-medium leading-none text-blue-100"
                :style="`width: ${progress}%`" x-text="`${progress}%`"></div>
        </div>

        @error('image')
            <span class="mt-2 block text-sm text-red-500">{{ $message }}</span>
        @enderror
    </div>

    {{-- LISTA DE IMÁGENES REORDENABLES --}}
    {{-- Habilitamos la reordenación en la lista ul. Livewire 3 lo gestiona nativamente. --}}
    <ul wire:sortable="updateMediaOrder" class="space-y-4">
        @forelse ($product->getMedia('products') as $media)
            {{-- Cada li es un item reordenable, identificado por su ID --}}
            <li wire:sortable.item="{{ $media->id }}" wire:key="media-{{ $media->id }}"
                class="flex items-center justify-between rounded-lg bg-white p-2 shadow dark:bg-gray-800">
                <div class="flex items-center">
                    {{-- El "handle" para arrastrar. El cursor cambiará a 'grab' al pasar por encima. --}}
                    <button wire:sortable.handle class="mr-2 cursor-grab text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    {{-- Usamos la conversión 'thumb' que creamos en Medialibrary --}}
                    <img src="{{ $media->getUrl('thumb') }}" alt="Miniatura de {{ $media->name }}"
                        class="mr-4 h-16 w-16 rounded-md object-cover" />
                    <span class="text-gray-700 dark:text-gray-200">{{ $media->name }}</span>
                </div>
                {{-- Botón para eliminar la imagen, con confirmación --}}
                <button wire:click="deleteImage({{ $media->id }})"
                    wire:confirm="¿Estás seguro de que quieres eliminar esta imagen?"
                    class="text-red-500 hover:text-red-700" title="Eliminar imagen">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </li>
        @empty
            <li class="rounded-lg border border-dashed border-gray-300 p-4 text-center text-gray-500">
                Aún no hay imágenes para este producto. ¡Sube la primera!
            </li>
        @endforelse
    </ul>
</div>
