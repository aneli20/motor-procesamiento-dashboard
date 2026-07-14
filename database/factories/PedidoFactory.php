<?php

namespace Database\Factories;

use App\Enums\PedidoEstado;
use App\Models\Cliente;
use App\Models\Pedido;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pedido>
 */
class PedidoFactory extends Factory
{
    protected $model = Pedido::class;

    public function definition(): array
    {
        return [
            'cliente_id' => Cliente::factory(),
            'fecha_entrega' => fake()->dateTimeBetween('-10 days', '+20 days')->format('Y-m-d'),
            'total' => '0.00',
            'estado' => fake()->randomElement(PedidoEstado::cases())->value,
            'express_charge_applied_at' => null,
        ];
    }
}
