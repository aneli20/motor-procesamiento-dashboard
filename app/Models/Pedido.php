<?php

namespace App\Models;

use App\Enums\PedidoEstado;
use Carbon\CarbonInterface;
use Database\Factories\PedidoFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pedido extends Model
{
    /** @use HasFactory<PedidoFactory> */
    use HasFactory;

    public const MANEJO_ESPECIAL_PRODUCTO_ID = 5;

    protected $fillable = [
        'cliente_id',
        'fecha_entrega',
        'total',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_entrega' => 'date',
            'total' => 'decimal:2',
            'estado' => PedidoEstado::class,
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'pedido_producto')->withTimestamps();
    }

    /**
     * Incluye hoy y los siguientes 3 dias calendario; no incluye fechas vencidas.
     */
    public function scopePorEnviar(Builder $query): Builder
    {
        return $query
            ->where('estado', PedidoEstado::Pendiente->value)
            ->whereDate('fecha_entrega', '>=', today())
            ->whereDate('fecha_entrega', '<=', today()->addDays(3));
    }

    public function scopeRetrasados(Builder $query): Builder
    {
        return $query
            ->where('estado', PedidoEstado::Pendiente->value)
            ->whereDate('fecha_entrega', '<', today());
    }

    public function scopeEntregados(Builder $query): Builder
    {
        return $query->where('estado', PedidoEstado::Entregado->value);
    }

    public function scopeCancelados(Builder $query): Builder
    {
        return $query->where('estado', PedidoEstado::Cancelado->value);
    }

    public function scopeElegiblesParaRecargoExpress(Builder $query, ?CarbonInterface $fecha = null): Builder
    {
        $manana = ($fecha ?? today())->copy()->addDay()->toDateString();

        return $query
            ->where('estado', PedidoEstado::Pendiente->value)
            ->whereDate('fecha_entrega', $manana)
            ->whereHas('productos', fn (Builder $query): Builder => $query->whereKey(self::MANEJO_ESPECIAL_PRODUCTO_ID));
    }
}
