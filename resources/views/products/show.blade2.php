<x-layouts.guest>

    <div class="container">
        <div class="grid grid-cols-2 gap-6">
            {{-- Carrusel con imágenes --}}
            <div class="flexslider mt-4">
                <ul class="slides">
                    @foreach ($product->images->slice(1)->take(4) as $image)
                        <li data-thumb="{{ Storage::url($image->path) }}">
                            <img class="thumbnail h-20 w-20 cursor-pointer rounded-md"
                                src="{{ Storage::url($image->path) }}" data-image="{{ Storage::url($image->path) }}"
                                data-title="{{ $product->name }}" data-price="{{ $product->price }}€"
                                data-description="{{ $product->description }}" />
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Columna derecha: Atributos del producto --}}
            <div class="flex flex-col justify-start">
                <h2 id="productTitle" class="text-2xl font-semibold">{{ $product->name }}</h2>
                <p id="productPrice" class="text-xl font-bold text-gray-700">Precio: {{ $product->price }}€</p>
                <p id="productDescription" class="mt-2 text-gray-600">{{ $product->description }}</p>
            </div>
        </div>
    </div>



    @push('script')
        <script>
            $(document).ready(function() {
                $('.flexslider').flexslider({
                    animation: "slide",
                    controlNav: "thumbnails",
                    start: function(slider) {
                        let firstImage = $('.thumbnail').eq(0).data('image');
                        $('#mainImage').attr('src', firstImage);
                    }
                });

                $('.thumbnail').click(function() {
                    let newImage = $(this).data('image');
                    let newTitle = $(this).data('title');
                    let newPrice = $(this).data('price');
                    let newDescription = $(this).data('description');

                    $('#mainImage').attr('src', newImage);
                    $('#productTitle').text(newTitle);
                    $('#productPrice').text("Precio: " + newPrice);
                    $('#productDescription').text(newDescription);
                });
            });
        </script>
    @endpush
</x-layouts.guest>
