<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperSize
 */
class Size extends Model
{
    /** @use HasFactory<\Database\Factories\SizeFactory> */
    use HasFactory;

    // Ya no tenemos product_id aquí, solo el nombre de la talla.
    protected $fillable = ['name'];

    // Relación de muchos a muchos con Product. Una talla puede estar en muchos productos.
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_size')
            ->withTimestamps();
    }

    // Relación de muchos a muchos con Color.
    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(Color::class, 'color_size')
            ->withPivot('quantity', 'id')
            ->withTimestamps();
    }

    /**
     * Calcula el stock total disponible para esta talla específica.
     * Suma las cantidades de todos los colores asociados a esta talla.
     */
    public function getStockAttribute(): int
    {
        // Suma la columna 'quantity' de la tabla pivote 'color_size'
        // para todas las entradas de color relacionadas con esta talla.
        return (int) $this->colors()->sum('color_size.quantity');
    }
}
