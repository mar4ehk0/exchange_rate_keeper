<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

class NotDeleteCurrencyException extends \Exception
{
    public function __construct(\Throwable $previous)
    {
        parent::__construct($previous->getMessage(), Response::HTTP_NOT_FOUND, $previous);
    }
}
