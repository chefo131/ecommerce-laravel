<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperOrder
 */
class Order extends Model
{
    const PENDIENTE = 1;
    const PAGADO = 2;
    const ENVIADO = 3;
    const ENTREGADO = 4;
    const ANULADO = 5;

     // Este es el array que la vista está buscando.
    const STATUS_LABELS = [
        self::PENDIENTE => 'Pendiente',
        self::PAGADO => 'Pagado',
        self::ENVIADO => 'Enviado',
        self::ENTREGADO => 'Entregado',
        self::ANULADO => 'Anulado',
    ];
    
    use HasFactory;

    // Evita la asignación masiva en estos campos por seguridad.
    protected $guarded = ['id', 'created_at', 'updated_at', 'status'];

    /**
     * Define la relación: una orden pertenece a un usuario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Un pedido tiene muchos productos.
     * Relación Muchos a Muchos.
     */
    public function products(): BelongsToMany
    {
        // La tabla pivote es 'order_product'
        // withPivot nos permite acceder a las columnas adicionales de la tabla pivote.
        return $this->belongsToMany(Product::class)
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'content' => 'json', // Es buena práctica usar 'json' o 'array'
        'envio'   => 'json', // ¡Aquí está la magia!
        'status' => 'integer',
        'envio_type' => 'integer',
        'total' => 'float',
        'shopping_cost' => 'float',
    ];

    //Relación inversa uno a muchos entre Department y Order
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    //Relación inversa uno a muchos entre City y Order
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    //Relación inversa uno a muchos entre District y Order
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    //Relación inversa uno a muchos entre OrderStatus y Order
    public function orderStatus(): BelongsTo
    {
        // Por convención, Laravel buscaría 'order_status_id'.
        // Como tu columna se llama 'status', se lo indicamos como segundo parámetro.
        return $this->belongsTo(OrderStatus::class, 'status');
    }


    public static function getStatusName($status): string
    {
        return match ($status) {
            self::PENDIENTE => 'Pendiente',
            self::PAGADO => 'Pagado',
            self::ENVIADO => 'Enviado',
            self::ENTREGADO => 'Entregado',
            self::ANULADO => 'Anulado',
            default => 'Estado Desconocido', // Opcional: Para manejar casos inesperados
        };
    }

    /**
     * Accesor para obtener el nombre del estado de forma más "Eloquent".
     * Permite usar $order->status_name en las vistas.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function statusName(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn () => self::getStatusName($this->status),
        );
    }

    /**
     * Accesor para obtener el icono HTML del estado.
     * Permite usar {!! $order->status_icon !!} en las vistas.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function statusIcon(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn () => match ($this->status) {
                self::PENDIENTE => '<i class="fa-solid fa-business-time text-red-500 opacity-90"></i>',
                self::PAGADO => '<i class="fa-brands fa-paypal text-gray-500 opacity-90"></i>',
                self::ENVIADO => '<i class="fa-solid fa-truck-fast text-yellow-500 opacity-90"></i>',
                self::ENTREGADO => '<i class="fa-solid fa-truck-ramp-box text-pink-500 opacity-90"></i>',
                self::ANULADO => '<i class="fa-solid fa-ban text-green-500 opacity-90"></i>',
                default => '',
            },
        );
    }
}
