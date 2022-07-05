<?php

namespace App\Enums;

enum PaymentMode: string {
    case CASH = 'cash';
    case CHEQUE = 'cheque';
    case CREDITCARD = 'credit card';
    case ONLINE = 'online';

    public function label(): string
    {
        return match($this) {
            static::CASH => __('Cash'),
            static::CHEQUE => __('Cheque'),
            static::CREDITCARD => __('Credit card'),
            static::ONLINE => __('Online'),
        };
    }

    public static function selectOptions(): array
    {
        return [
            static::CASH->value => __('Cash'),
            static::CHEQUE->value => __('Cheque'),
            static::CREDITCARD->value => __('Credit card'),
            static::ONLINE->value => __('Online'),
        ];
    }
}