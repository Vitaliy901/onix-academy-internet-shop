<?php

namespace App\Enums;

enum Status: string
{
    case TRUE = 'true';
    case FALSE = 'false';
    case UNPAID = 'unpaid';
    case PAID = 'paid';
    case OPEN = 'open';
    case CANCELED = 'canceled';
    case CONFIRMED = 'confirmed';
    case UP = 'up';
    case DOWN = 'down';
}
