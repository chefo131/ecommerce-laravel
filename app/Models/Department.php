<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperDepartment
 */
class Department extends Model
{
    /** @use HasFactory<\Database\Factories\DepartmentFactory> */
    use HasFactory;

    protected $fillable = ['name'];


    //Relación uno a muchos entre Department y City
    public function cities()
    {
        return $this->hasMany(City::class);
    }

    //Relación uno a muchos entre Department y Orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
