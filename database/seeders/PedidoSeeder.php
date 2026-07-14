<?php

namespace Database\Seeders;

use App\Enums\PedidoEstado;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use App\Support\Money;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PedidoSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = Cliente::query()->pluck('id')->all();
        $productos = Producto::query()->orderBy('id')->get(['id', 'precio']);
        $precios = $productos->pluck('precio', 'id');
        $productoIds = $productos->pluck('id')->all();
        $escenarios = $this->escenariosObligatorios();
        $pedidos = [];
        $pivotes = [];
        $now = now();

        for ($i = 0; $i < 1000; $i++) {
            $productoSeleccionados = $escenarios[$i]['productos'] ?? fake()->randomElements($productoIds, fake()->numberBetween(1, 5));
            sort($productoSeleccionados);

            $pedidos[] = [
                'cliente_id' => fake()->randomElement($clientes),
                'fecha_entrega' => $escenarios[$i]['fecha_entrega'] ?? $this->fechaAleatoria(),
                'total' => $this->sumarProductos($productoSeleccionados, $precios),
                'estado' => $escenarios[$i]['estado'] ?? fake()->randomElement(PedidoEstado::cases())->value,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $pivotes[$i + 1] = $productoSeleccionados;
        }

        foreach (array_chunk($pedidos, 200) as $chunk) {
            Pedido::query()->insert($chunk);
        }

        $pedidoIds = Pedido::query()->orderBy('id')->pluck('id')->all();
        $pivotRows = [];

        foreach ($pedidoIds as $index => $pedidoId) {
            foreach ($pivotes[$index + 1] as $productoId) {
                $pivotRows[] = [
                    'pedido_id' => $pedidoId,
                    'producto_id' => $productoId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        foreach (array_chunk($pivotRows, 1000) as $chunk) {
            DB::table('pedido_producto')->insert($chunk);
        }
    }

    private function escenariosObligatorios(): array
    {
        return [
            0 => ['fecha_entrega' => today()->addDay()->toDateString(), 'estado' => PedidoEstado::Pendiente->value, 'productos' => [5, 7]],
            1 => ['fecha_entrega' => today()->addDay()->toDateString(), 'estado' => PedidoEstado::Pendiente->value, 'productos' => [1, 2]],
            2 => ['fecha_entrega' => today()->addDays(2)->toDateString(), 'estado' => PedidoEstado::Pendiente->value, 'productos' => [5, 3]],
            3 => ['fecha_entrega' => today()->addDay()->toDateString(), 'estado' => PedidoEstado::Entregado->value, 'productos' => [5, 4]],
            4 => ['fecha_entrega' => today()->addDay()->toDateString(), 'estado' => PedidoEstado::Cancelado->value, 'productos' => [5, 6]],
            5 => ['fecha_entrega' => today()->subDay()->toDateString(), 'estado' => PedidoEstado::Pendiente->value, 'productos' => [5, 8]],
            6 => ['fecha_entrega' => today()->toDateString(), 'estado' => PedidoEstado::Pendiente->value, 'productos' => [9]],
        ];
    }

    private function sumarProductos(array $productoIds, Collection $precios): string
    {
        $centavos = array_sum(array_map(
            fn (int $id): int => Money::decimalToCents($precios->get($id)),
            $productoIds,
        ));

        return Money::centsToDecimal($centavos);
    }

    private function fechaAleatoria(): string
    {
        return fake()->randomElement([
            today()->subDays(fake()->numberBetween(1, 10)),
            today(),
            today()->addDay(),
            today()->addDays(fake()->numberBetween(2, 3)),
            today()->addDays(fake()->numberBetween(4, 20)),
        ])->toDateString();
    }
}
