<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;


/*
 * @mixin IdeHelperColorSize
 * @property int $color_id
 * @property int $size_id
 * @property int $quantity
 *
 * @property-read \App\Models\Size $size
 * @property-read \App\Models\Color $color
 * @mixin \Eloquent
 * @property int $id
 * @method static \Illuminate\Database\Eloquent\Builder|ColorSize newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColorSize newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColorSize query()
 */

/**
 * @mixin IdeHelperColorSize
 */
class ColorSize extends Pivot
{
    use HasFactory;
    protected $table = 'color_size';

    ///Relación uno a muchos inversa entre ColorSize y Size
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Size, \App\Models\ColorSize>
     */
    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class);
    }

    ///Relación uno a muchos inversa entre ColorSize y Color
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Color, \App\Models\ColorSize>
     */
    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }
}
