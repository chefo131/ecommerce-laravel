<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


/**
 * @property-read bool $color
 * @property-read bool $size
 * @mixin IdeHelperCategory
 */
class Category extends Model implements HasMedia
{
    // Añadimos el trait para interactuar con Medialibrary
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['name', 'slug', 'image', 'icon', 'features'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'features' => 'array',
    ];

    //Relación uno a muchos entre Category y Subcategory
    public function subcategories(): HasMany
    {
        return $this->hasMany(Subcategory::class);
    }

    // (Opcional pero recomendado) Define las colecciones de medios y sus conversiones.
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('categories')
            ->singleFile() // Solo permite un fichero por categoría
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(200); // Crea una miniatura de 200px de ancho
            });
    }

    //Relación muchos a muchos entre Category y Brand
    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class);
    }

        /**
     * Define las conversiones de imágenes para Medialibrary.
     * Aquí le enseñamos a crear la miniatura 'thumb'.
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100);
    }


    //Relación uno a muchos entre Category y Product a través de Subcategory
    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(Product::class, Subcategory::class);
    }

    //URLs amigables
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // --- ACCESORES PARA FEATURES ---
    // Estos métodos permiten acceder a 'features.size' y 'features.color'
    // como si fueran propiedades directas del modelo (ej: $category->size)
    // lo que hace que el código en las vistas sea mucho más limpio.

    /**
     * Accesor para la característica 'size'.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<boolean, never>
     */
    protected function size(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->features['size'] ?? false,
        );
    }

    /**
     * Accesor para la característica 'color'.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<boolean, never>
     */
    protected function color(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->features['color'] ?? false,
        );
    }
}

