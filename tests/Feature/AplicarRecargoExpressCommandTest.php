<?php

namespace Tests\Feature;

use App\Enums\PedidoEstado;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AplicarRecargoExpressCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_applies_ten_percent_only_to_eligible_orders_and_is_idempotent(): void
    {
        $cliente = Cliente::factory()->create();
        Producto::factory()->create(['id' => 5, 'nombre' => 'Manejo Especial']);
        $otroProducto = Producto::factory()->create(['id' => 6]);

        $eligible = $this->pedido($cliente->id, today()->addDay()->toDateString(), PedidoEstado::Pendiente, '100.00', [5]);
        $notPending = $this->pedido($cliente->id, today()->addDay()->toDateString(), PedidoEstado::Entregado, '200.00', [5]);
        $notTomorrow = $this->pedido($cliente->id, today()->addDays(2)->toDateString(), PedidoEstado::Pendiente, '300.00', [5]);
        $withoutProduct = $this->pedido($cliente->id, today()->addDay()->toDateString(), PedidoEstado::Pendiente, '400.00', [$otroProducto->id]);

        $this->artisan('pedidos:aplicar-recargo-express')
            ->expectsOutput('Pedidos encontrados: 1')
            ->expectsOutput('Pedidos procesados: 1')
            ->assertSuccessful();

        $this->assertSame('110.00', $eligible->fresh()->total);
        $this->assertNotNull($eligible->fresh()->express_charge_applied_at);
        $this->assertSame('200.00', $notPending->fresh()->total);
        $this->assertSame('300.00', $notTomorrow->fresh()->total);
        $this->assertSame('400.00', $withoutProduct->fresh()->total);

        $this->artisan('pedidos:aplicar-recargo-express')
            ->expectsOutput('Pedidos encontrados: 0')
            ->expectsOutput('No existen pedidos elegibles para recargo express.')
            ->expectsOutput('Pedidos procesados: 0')
            ->assertSuccessful();

        $this->assertSame('110.00', $eligible->fresh()->total);
    }

    public function test_dry_run_does_not_modify_orders(): void
    {
        $cliente = Cliente::factory()->create();
        Producto::factory()->create(['id' => 5, 'nombre' => 'Manejo Especial']);
        $eligible = $this->pedido($cliente->id, today()->addDay()->toDateString(), PedidoEstado::Pendiente, '150.00', [5]);

        $this->artisan('pedidos:aplicar-recargo-express --dry-run')
            ->expectsOutput('Pedidos encontrados: 1')
            ->expectsOutput('Dry-run activo. No se modifico ningun pedido.')
            ->assertSuccessful();

        $this->assertSame('150.00', $eligible->fresh()->total);
        $this->assertNull($eligible->fresh()->express_charge_applied_at);
    }

    private function pedido(int $clienteId, string $fecha, PedidoEstado $estado, string $total, array $productoIds): Pedido
    {
        $pedido = Pedido::factory()->create([
            'cliente_id' => $clienteId,
            'fecha_entrega' => $fecha,
            'estado' => $estado,
            'total' => $total,
        ]);
        $pedido->productos()->attach($productoIds);

        return $pedido;
    }
}
