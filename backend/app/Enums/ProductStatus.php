<?php

namespace App\Enums;

enum ProductStatus: string {
    case CREATED = 'created';
    case ENABLED = 'enabled';
    case DISABLED = 'disabled';

    public function label(): string
    {
        return match($this) {
            static::CREATED => __('Created'),
            static::ENABLED => __('Enabled'),
            static::DISABLED => __('Disabled'),
        };
    }

    public static function selectOptions(): array
    {
        return [
            static::CREATED->value => __('Created'),
            static::ENABLED->value => __('Enabled'),
            static::DISABLED->value => __('Disabled'),
        ];
    }
}