<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\Order;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Size> $sizes
 * @property-read \App\Models\Brand $brand
 * @property-read \App\Models\Subcategory $subcategory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Color> $colors
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read mixed $stock
 * @mixin IdeHelperProduct
 */
class Product extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    // Añadimos el trait para interactuar con Medialibrary
    use HasFactory, InteractsWithMedia;

    const BORRADOR = 1;
    const PUBLICADO = 2;

    // Usamos $guarded en lugar de $fillable. Es más seguro, flexible y la causa
    // más probable de que los datos no se estén guardando.
    protected $guarded = ['id', 'created_at', 'updated_at'];

    // Relación muchos a muchos entre Product y Size
    public function sizes(): BelongsToMany
    {
        // Un producto puede tener muchas tallas, y una talla puede estar en muchos productos.
        return $this->belongsToMany(Size::class, 'product_size')
            ->withTimestamps();
    }

     /**
     * Un producto puede tener muchas reseñas.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Relación para obtener solo las reseñas aprobadas.
     */
    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', Review::APROBADO);
    }

    /**
     * Un producto puede estar en muchos pedidos.
     * Relación Muchos a Muchos.
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    //Relación uno a muchos inversa entre Producto y Brand
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    //Relación uno a muchos inversa entre Producto y Subcategory
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    //Relación muchos a muchos entre Product y Color
    public function colors(): BelongsToMany
    {
        // return $this->belongsToMany(Color::class)->withPivot('quantity');
        return $this->belongsToMany(Color::class)
            ->using(ColorProduct::class)
            ->withPivot('quantity', 'id') // Es buena práctica incluir 'id' si la tabla pivote tiene PK
             ->withTimestamps();

    }

    /**
     * Define las conversiones de imágenes para Medialibrary.
     * Aquí le enseñamos a crear la miniatura 'thumb'.
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100)
            ->sharpen(10);
    }

    //URLs amigables
    public function getRouteKeyName()
    {
        return 'slug';
    }


    //accesores
    public function getStockAttribute()
    {
        // Corregimos la lógica: las características 'size' y 'color' están en la categoría, a través de la subcategoría
        if ($this->subcategory?->category?->size) {
            return ColorSize::whereHas('size.products', function (Builder $query) {
                $query->where('id', $this->id);
            })->sum('quantity');
        } elseif ($this->subcategory?->category?->color) {
            return $this->colors()->sum('color_product.quantity');
        } else {
            return $this->quantity;
        }
    }

    /**
     * Determina si el usuario autenticado puede dejar una reseña para este producto.
     *
     * @return bool
     */
    public function canBeReviewed(): bool
    {
        // 1. El usuario debe estar autenticado.
        if (!auth()->check()) {
            return false;
        }

        // 2. El usuario no debe haber dejado ya una reseña para este producto.
        $hasAlreadyReviewed = $this->reviews()->where('user_id', auth()->id())->exists();
        if ($hasAlreadyReviewed) {
            return false;
        }

        // 3. El usuario debe haber comprado el producto en un pedido completado.
        // ¡AQUÍ ESTÁ EL DETALLE! La lógica original (>= 4) era demasiado amplia y podía
        // incluir estados no deseados como "Cancelado" si tuviera un valor alto.
        // Lo cambiamos para que compruebe explícitamente el estado "Entregado" (que asumimos es 4).
        // Lo ideal sería usar una constante del modelo Order, como Order::ENTREGADO.
        return auth()->user()->orders()
            ->where('status', Order::ENTREGADO) // Usamos la constante para un código más limpio y robusto.
            ->whereHas('products', fn($query) => $query->where('product_id', $this->id))
            ->exists();

    }
}
