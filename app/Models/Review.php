<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperReview
 */
class Review extends Model
{
     use HasFactory;

    const PENDIENTE = 1;
    const APROBADO = 2;
    const RECHAZADO = 3;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Una reseña pertenece a un usuario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Una reseña pertenece a un producto.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
