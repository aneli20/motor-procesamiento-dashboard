<?php

namespace App\Services;

use App\Models\Pedido;
use App\Support\Money;
use Illuminate\Support\Facades\DB;

class AplicarRecargoExpress
{
    /**
     * @return array{found: int, processed: int, ids: array<int, int>}
     */
    public function ejecutar(bool $dryRun = false): array
    {
        $baseQuery = Pedido::query()->elegiblesParaRecargoExpress()->orderBy('id');
        $found = (clone $baseQuery)->count();
        $ids = [];
        $processed = 0;

        if ($dryRun) {
            return [
                'found' => $found,
                'processed' => 0,
                'ids' => (clone $baseQuery)->pluck('id')->map(fn ($id): int => (int) $id)->all(),
            ];
        }

        $baseQuery->chunkById(100, function ($pedidos) use (&$ids, &$processed): void {
            DB::transaction(function () use ($pedidos, &$ids, &$processed): void {
                foreach ($pedidos as $pedido) {
                    $locked = Pedido::query()
                        ->whereKey($pedido->id)
                        ->elegiblesParaRecargoExpress()
                        ->lockForUpdate()
                        ->first();

                    if (! $locked) {
                        continue;
                    }

                    $centavos = Money::decimalToCents($locked->total);
                    $nuevoTotal = Money::centsToDecimal(Money::addPercent($centavos, 10));

                    $locked->forceFill([
                        'total' => $nuevoTotal,
                    ])->save();

                    $ids[] = (int) $locked->id;
                    $processed++;
                }
            });
        });

        return compact('found', 'processed', 'ids');
    }
}
