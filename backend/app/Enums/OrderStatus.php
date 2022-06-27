<?php

namespace App\Enums;

enum OrderStatus: string {
    case CREATED = 'created';
    case PAID = 'paid';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';
}