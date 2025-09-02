<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCity
 */
class City extends Model
{
    /** @use HasFactory<\Database\Factories\CityFactory> */
    use HasFactory;

    protected $fillable = ['name', 'cost', 'department_id'];

    // Relación uno a muchos: Una ciudad tiene muchos distritos
    public function districts()
    {
        return $this->hasMany(District::class);
    }

    // Relación uno a muchos: Una ciudad puede tener muchas órdenes
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Relación uno a muchos (inversa): Una ciudad pertenece a un departamento
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
