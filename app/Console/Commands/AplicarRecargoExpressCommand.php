<?php

namespace App\Console\Commands;

use App\Services\AplicarRecargoExpress;
use Illuminate\Console\Command;
use Throwable;

class AplicarRecargoExpressCommand extends Command
{
    protected $signature = 'pedidos:aplicar-recargo-express {--dry-run : Muestra los pedidos elegibles sin modificarlos}';

    protected $description = 'Aplica un recargo express del 10% a pedidos pendientes elegibles.';

    public function handle(AplicarRecargoExpress $aplicarRecargoExpress): int
    {
        try {
            $resultado = $aplicarRecargoExpress->ejecutar((bool) $this->option('dry-run'));

            $this->info('Pedidos encontrados: '.$resultado['found']);

            if ($resultado['found'] === 0) {
                $this->line('No existen pedidos elegibles para recargo express.');
            }

            if ($this->option('dry-run')) {
                $this->line('Dry-run activo. No se modifico ningun pedido.');
                $this->line('Pedidos elegibles: '.(empty($resultado['ids']) ? 'ninguno' : implode(', ', $resultado['ids'])));

                return self::SUCCESS;
            }

            $this->info('Pedidos procesados: '.$resultado['processed']);

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error('Error al aplicar recargo express: '.$exception->getMessage());

            return self::FAILURE;
        }
    }
}
