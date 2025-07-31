<?php

namespace App\View;

readonly class CurrencyDeleteView
{
    public static function getData(): array
    {
        return [
            'message' => 'Currency successfully deleted.',
            'status' => true,
        ];
    }
}
