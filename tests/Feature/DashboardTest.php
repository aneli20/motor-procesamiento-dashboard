<?php

namespace Tests\Feature;

use App\Enums\PedidoEstado;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_dashboard(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create();
        $cliente = Cliente::factory()->create();
        $producto = Producto::factory()->create(['nombre' => 'Caja de prueba']);
        $pedido = Pedido::factory()->create([
            'cliente_id' => $cliente->id,
            'fecha_entrega' => today(),
            'estado' => PedidoEstado::Pendiente,
            'total' => '100.00',
        ]);
        $pedido->productos()->attach($producto->id);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Por Enviar')
            ->assertSee($cliente->nombre)
            ->assertSee('Caja de prueba');
    }
}
