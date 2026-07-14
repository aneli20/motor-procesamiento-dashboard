<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('productos')->upsert($this->productos(), ['id'], ['nombre', 'sku', 'precio', 'updated_at']);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function productos(): array
    {
        $now = now();

        return [
            ['id' => 1, 'nombre' => 'Caja estandar', 'sku' => 'CAJA-ESTANDAR', 'precio' => '120.00', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'nombre' => 'Empaque termico', 'sku' => 'EMPAQUE-TERMICO', 'precio' => '280.00', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'nombre' => 'Seguro basico', 'sku' => 'SEGURO-BASICO', 'precio' => '95.50', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'nombre' => 'Entrega local', 'sku' => 'ENTREGA-LOCAL', 'precio' => '180.00', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'nombre' => 'Manejo Especial', 'sku' => 'MANEJO-ESPECIAL', 'precio' => '350.00', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'nombre' => 'Etiqueta fragil', 'sku' => 'ETIQUETA-FRAGIL', 'precio' => '45.00', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'nombre' => 'Servicio express', 'sku' => 'SERVICIO-EXPRESS', 'precio' => '420.00', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 8, 'nombre' => 'Confirmacion SMS', 'sku' => 'CONFIRMACION-SMS', 'precio' => '30.00', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 9, 'nombre' => 'Guia retorno', 'sku' => 'GUIA-RETORNO', 'precio' => '155.75', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 10, 'nombre' => 'Custodia nocturna', 'sku' => 'CUSTODIA-NOCTURNA', 'precio' => '510.00', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 11, 'nombre' => 'Entrega foranea', 'sku' => 'ENTREGA-FORANEA', 'precio' => '640.00', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 12, 'nombre' => 'Caja reforzada', 'sku' => 'CAJA-REFORZADA', 'precio' => '210.00', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 13, 'nombre' => 'Recoleccion programada', 'sku' => 'RECOLECCION-PROGRAMADA', 'precio' => '175.25', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 14, 'nombre' => 'Validacion identidad', 'sku' => 'VALIDACION-IDENTIDAD', 'precio' => '88.00', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 15, 'nombre' => 'Fotografia entrega', 'sku' => 'FOTOGRAFIA-ENTREGA', 'precio' => '65.00', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 16, 'nombre' => 'Almacen temporal', 'sku' => 'ALMACEN-TEMPORAL', 'precio' => '235.00', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 17, 'nombre' => 'Seguro premium', 'sku' => 'SEGURO-PREMIUM', 'precio' => '395.00', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 18, 'nombre' => 'Documentacion aduanal', 'sku' => 'DOCUMENTACION-ADUANAL', 'precio' => '725.00', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 19, 'nombre' => 'Monitoreo activo', 'sku' => 'MONITOREO-ACTIVO', 'precio' => '145.00', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 20, 'nombre' => 'Prueba de entrega', 'sku' => 'PRUEBA-ENTREGA', 'precio' => '110.00', 'created_at' => $now, 'updated_at' => $now],
        ];
    }
}
