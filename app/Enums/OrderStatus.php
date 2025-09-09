<?php
namespace App\Enums;

enum OrderStatus: string {
    case Pending   = 'pending';
    case Submitted = 'submitted';
    case Paid      = 'paid';
    case Cancelled = 'cancelled';
    case Expired   = 'expired';
}
