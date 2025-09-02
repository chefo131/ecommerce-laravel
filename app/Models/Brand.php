<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperBrand
 */
class Brand extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug', // ¡Aquí está la clave! Añadimos 'slug' a la lista.
    ];

    // Relación uno a muchos con productos
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Relación muchos a muchos con categorías (usada en el formulario de productos)
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}