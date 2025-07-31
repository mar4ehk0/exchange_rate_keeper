<?php

namespace App\View;

use App\Entity\Currency;

readonly class CurrencyErrorView
{
    public static function notFound(int $id): array
    {
        return [
            'class' => Currency::class,
            'id' => $id,
        ];
    }

    public static function httpConflict(int $id, string $message = ''): array
    {
        return [
            'class' => Currency::class,
            'id' => $id,
            'message' => $message,
        ];
    }

    public static function internalServerError(int $id, string $message = ''): array
    {
        return [
            'class' => Currency::class,
            'id' => $id,
            'message' => $message,
        ];
    }
}
