<?php

namespace App\Enums;

enum ProductStatus: string {
    case CREATED = 'created';
    case ENABLED = 'enabled';
    case DISABLED = 'disabled';
}