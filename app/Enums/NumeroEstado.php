<?php
namespace App\Enums;

enum NumeroEstado: string {
    case Disponible = 'disponible';
    case Reservado  = 'reservado';
    case Pagado     = 'pagado';
}
