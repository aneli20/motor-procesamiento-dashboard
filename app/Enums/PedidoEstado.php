<?php

namespace App\Enums;

enum PedidoEstado: string
{
    case Pendiente = 'pendiente';
    case Entregado = 'entregado';
    case Cancelado = 'cancelado';
}
