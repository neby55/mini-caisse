<?php

namespace App\Enums;

enum OrderStatus: string {
    case CREATED = 'created';
    case PAID = 'paid';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';

    public function label(): string
    {
        return match($this) {
            static::CREATED => __('Created'),
            static::PAID => __('Paid'),
            static::COMPLETED => __('Completed'),
            static::CANCELED => __('Canceled'),
        };
    }

    public static function selectOptions(): array
    {
        return [
            static::CREATED->value => __('Created'),
            static::PAID->value => __('Paid'),
            static::COMPLETED->value => __('Completed'),
            static::CANCELED->value => __('Canceled'),
        ];
    }
}