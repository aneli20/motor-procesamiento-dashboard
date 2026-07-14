<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Cliente::factory(100)->create();

        $this->call([
            ProductoSeeder::class,
            PedidoSeeder::class,
        ]);
    }
}
