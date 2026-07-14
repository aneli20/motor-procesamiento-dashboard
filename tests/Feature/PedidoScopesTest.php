<?php

namespace Tests\Feature;

use App\Enums\PedidoEstado;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PedidoScopesTest extends TestCase
{
    use RefreshDatabase;

    public function test_scopes_return_only_matching_orders(): void
    {
        $cliente = Cliente::factory()->create();

        $porEnviar = $this->pedido($cliente->id, today()->addDays(3)->toDateString(), PedidoEstado::Pendiente);
        $retrasado = $this->pedido($cliente->id, today()->subDay()->toDateString(), PedidoEstado::Pendiente);
        $entregado = $this->pedido($cliente->id, today()->toDateString(), PedidoEstado::Entregado);
        $cancelado = $this->pedido($cliente->id, today()->toDateString(), PedidoEstado::Cancelado);
        $lejano = $this->pedido($cliente->id, today()->addDays(4)->toDateString(), PedidoEstado::Pendiente);

        $this->assertTrue(Pedido::porEnviar()->pluck('id')->contains($porEnviar->id));
        $this->assertFalse(Pedido::porEnviar()->pluck('id')->contains($retrasado->id));
        $this->assertFalse(Pedido::porEnviar()->pluck('id')->contains($lejano->id));
        $this->assertSame([$retrasado->id], Pedido::retrasados()->pluck('id')->all());
        $this->assertSame([$entregado->id], Pedido::entregados()->pluck('id')->all());
        $this->assertSame([$cancelado->id], Pedido::cancelados()->pluck('id')->all());
    }

    public function test_express_scope_filters_in_sql_conditions(): void
    {
        $cliente = Cliente::factory()->create();
        Producto::factory()->create(['id' => 5, 'nombre' => 'Manejo Especial']);
        $otroProducto = Producto::factory()->create(['id' => 6]);

        $eligible = $this->pedido($cliente->id, today()->addDay()->toDateString(), PedidoEstado::Pendiente);
        $eligible->productos()->attach(5);

        $sinProducto = $this->pedido($cliente->id, today()->addDay()->toDateString(), PedidoEstado::Pendiente);
        $sinProducto->productos()->attach($otroProducto->id);

        $ids = Pedido::elegiblesParaRecargoExpress()->pluck('id')->all();

        $this->assertSame([$eligible->id], $ids);
    }

    private function pedido(int $clienteId, string $fecha, PedidoEstado $estado): Pedido
    {
        return Pedido::factory()->create([
            'cliente_id' => $clienteId,
            'fecha_entrega' => $fecha,
            'estado' => $estado,
            'total' => '100.00',
        ]);
    }
}
