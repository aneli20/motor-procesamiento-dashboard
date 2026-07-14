<?php

use App\Enums\PedidoEstado;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->date('fecha_entrega')->index();
            $table->decimal('total', 12, 2);
            $table->string('estado')->default(PedidoEstado::Pendiente->value)->index();
            $table->timestamp('express_charge_applied_at')->nullable()->index();
            $table->timestamps();

            $table->index(['estado', 'fecha_entrega']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
