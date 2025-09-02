<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperDistrict
 */
class District extends Model
{
    /** @use HasFactory<\Database\Factories\DistrictFactory> */
    use HasFactory;

    protected $fillable = ['name', 'city_id'];

     // Relación de uno a muchos (inversa)
    // Un distrito pertenece a una ciudad
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    //Relación uno a muchos entre District y Orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
