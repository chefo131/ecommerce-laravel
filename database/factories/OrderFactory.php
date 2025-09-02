<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Department;
use App\Models\District;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Get random location data
        $department = Department::all()->random();
        $city = City::where('department_id', $department->id)->get()->random();
        $district = District::where('city_id', $city->id)->get()->random();

        // Get a random user
        $user = User::all()->random();

        // Simulate some products in the cart
        $products = Product::inRandomOrder()->take(rand(1, 4))->get();
        $cartContent = [];
        $total = 0;

        foreach ($products as $product) {
            $qty = rand(1, 3);
            $price = $product->price;
            $subtotal = $qty * $price;
            $total += $subtotal;

            $cartContent[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'qty' => $qty,
                'price' => $price,
                'options' => [
                    'image' => $product->getFirstMediaUrl('products'),
                    'slug' => $product->slug,
                ],
            ];
        }

        $shippingCost = $city->cost;
        $total += $shippingCost;

        return [
            'user_id' => $user->id,
            'contact' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'envio_type' => $this->faker->randomElement([1, 2]), // 1: Recojo en tienda, 2: Envío a domicilio
            'shopping_cost' => $shippingCost,
            'total' => $total,
            'content' => $cartContent, // Pass as an array, Eloquent will cast to JSON object
            'status' => $this->faker->numberBetween(1, 5), // PENDIENTE, PAGADO, ENVIADO, ENTREGADO, ANULADO
            'envio' => [
                'department' => $department->name,
                'city' => $city->name,
                'district' => $district->name,
                'address' => $this->faker->streetAddress(),
                'references' => $this->faker->secondaryAddress(),
            ],
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}