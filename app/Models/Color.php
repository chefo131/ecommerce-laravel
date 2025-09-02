<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperColor
 */
class Color extends Model
{
    use HasFactory;
    // Evitar asignación masiva
    protected $guarded = ['id', 'created_at', 'updated_at'];


    //Relación muchos a muchos inversa entre Color y Product

    public function products()
    {
        // return $this->belongsToMany(Product::class, 'color_product');
        return $this->belongsToMany(Product::class, 'color_product')
            ->using(ColorProduct::class)
            ->withPivot('quantity', 'id')
            ->withTimestamps();
    }

    //Relación inversa muchos a muchos entre Color y Size
    public function sizes()
    {
        // return $this->belongsToMany(Size::class, 'color_size')->withPivot('quantity');
        return $this->belongsToMany(Size::class, 'color_size')
            ->using(ColorSize::class)
            ->withPivot('quantity', 'id');
    }
}
