<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;


/**
 * @property int $product_id
 * @property int $color_id
 * @property int $quantity
 * @mixin IdeHelperColorProduct
 */
class ColorProduct extends Pivot
{
    use HasFactory;
    protected $table = 'color_product';

    //Relación uno a muchos inversa entre ColorProduct y Product
    public function product()
    {
        return $this->belongsTo(Product::class);
        
    }

    //Relación uno a muchos inversa entre ColorProduct y Color
    public function color()
    {
        return $this->belongsTo(Color::class);
        
    }
}
