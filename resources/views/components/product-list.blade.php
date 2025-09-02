 <li class="rounded-lg bg-white shadow">
     <article class="flex flex-col md:flex-row">
         <figure class="md:flex-shrink-0">
             {{-- La imagen ahora es full-width en móvil y de ancho fijo en pantallas más grandes --}}
             @if ($product->getFirstMedia('products'))
                 <img class="h-48 w-full object-cover object-center md:w-56"
                     src="{{ $product->getFirstMediaUrl('products') }}" alt="{{ $product->name }}">
             @else
                 {{-- Placeholder si no hay imagen --}}
                 <div
                     class="flex h-48 w-full items-center justify-center bg-gray-200 object-cover object-center text-gray-400 md:w-56">
                     <i class="fa-solid fa-image fa-2x"></i>
                 </div>
             @endif
         </figure>
         <div class="flex flex-1 flex-col px-6 py-4">
             {{-- En pantallas pequeñas, el título y las estrellas se apilan --}}
             <div class="flex flex-col justify-between sm:flex-row">
                 <div>
                     <h1 class="text-lg font-semibold text-gray-700">
                         <a href="{{ route('products.show', $product) }}">
                             {{ Str::limit($product->name, 40) }}
                         </a>
                     </h1>
                     <p class="font-bold text-gray-700">€ {{ $product->price }}</p>
                 </div>
                 {{-- Añadimos un margen superior en móvil que desaparece en pantallas más grandes --}}
                 <div class="mt-2 flex items-center sm:mt-0">
                     <ul class="flex text-sm">
                         <li><i class="fa-solid fa-star mr-1 text-yellow-400"></i></li>
                         <li><i class="fa-solid fa-star mr-1 text-yellow-400"></i></li>
                         <li><i class="fa-solid fa-star mr-1 text-yellow-400"></i></li>
                         <li><i class="fa-solid fa-star mr-1 text-yellow-400"></i></li>
                         <li><i class="fa-solid fa-star mr-1 text-yellow-400"></i></li>
                     </ul>
                     <span class="text-sm text-gray-700">(24)</span>
                 </div>
             </div>
             {{-- mt-auto empuja el botón hacia abajo, y pt-4 le da un respiro --}}
             <div class="mt-auto pt-4">
                 {{-- Hacemos el botón full-width en móvil para mejor usabilidad y cambiamos el color --}}
                 <flux:button href="{{ route('products.show', $product) }}" variant="primary" class="w-full sm:w-auto">
                     Más información
                 </flux:button>
             </div>
         </div>
     </article>
 </li>
