<x-layouts.guest>
    <div class="container">
        <div class="grid grid-cols-2 gap-6">
            <div>
                <div class="flexslider">
                    <ul class="slides">
                        @foreach ($product->images->slice(1)->take(4) as $image)
                            {{-- @if (isset($product) && !empty($product->images)) --}}
                            <li data-thumb="{{ Storage::url($image->path) }}">
                                <img src="{{ Storage::url($image->path) }}" />
                            </li>
                            {{-- @endif --}}
                        @endforeach
                    </ul>
                </div>
            </div>
            <div>
            </div>
        </div>
    </div>
    @push('script')
        <script>
            // Can also be used with $(document).ready() o $(window).load()
            $(document).ready(function() {
                $('.flexslider').flexslider({
                    animation: "slide",
                    controlNav: "thumbnails"
                });
            });
        </script>
    @endpush
</x-layouts.guest>
