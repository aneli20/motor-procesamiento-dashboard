<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $categorias = [
            'por_enviar' => ['titulo' => 'Por Enviar', 'scope' => 'porEnviar', 'page' => 'por_enviar_page'],
            'retrasados' => ['titulo' => 'Retrasados', 'scope' => 'retrasados', 'page' => 'retrasados_page'],
            'entregados' => ['titulo' => 'Entregados', 'scope' => 'entregados', 'page' => 'entregados_page'],
            'cancelados' => ['titulo' => 'Cancelados', 'scope' => 'cancelados', 'page' => 'cancelados_page'],
        ];

        $pedidos = [];

        foreach ($categorias as $key => $categoria) {
            $pedidos[$key] = Pedido::query()
                ->select(['id', 'cliente_id', 'fecha_entrega', 'total', 'estado'])
                ->with([
                    'cliente:id,nombre,email',
                    'productos:id,nombre',
                ])
                ->tap(fn (Builder $query) => $query->{$categoria['scope']}())
                ->latest('fecha_entrega')
                ->paginate(10, ['*'], $categoria['page'])
                ->withQueryString();
        }

        return view('dashboard.index', compact('categorias', 'pedidos'));
    }
}
