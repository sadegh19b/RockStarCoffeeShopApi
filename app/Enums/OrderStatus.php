<?php

namespace App\Enums;

enum OrderStatus: string
{
    case WAITING = 'waiting';
    case PREPARATION = 'preparation';
    case READY = 'ready';
    case DELIVERED = 'delivered';

    public function getEnglishLabel(): string
    {
        return \Str::studly($this->value);
    }

    public static function getValues(array $excludes = []): array
    {
        return array_filter(
            array_map(fn($item) => $item->value, self::cases()),
            fn($item) => ! in_array($item, $excludes, true)
        );
    }
}
