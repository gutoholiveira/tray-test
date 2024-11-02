<?php

namespace Database\Factories;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Venda>
 */
class SaleFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $valor = fake()->numberBetween(100, 5000);
        
        return [
            Sale::SELLER_ID => fake()->numberBetween(1, 5),
            Sale::VALUE     => $valor,
            Sale::COMISSION => $valor * 0.085,
            Sale::DATE      => fake()->date(),
        ];
    }
}
