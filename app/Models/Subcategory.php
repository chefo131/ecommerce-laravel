<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperSubcategory
 */
class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'color',
        'size',
        'category_id',
    ];

    protected $casts = [
        'color' => 'boolean',
        'size' => 'boolean',
    ];

    // Relación: Una subcategoría pertenece a una categoría
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Relación uno a muchos: Una subcategoría tiene muchos productos
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}