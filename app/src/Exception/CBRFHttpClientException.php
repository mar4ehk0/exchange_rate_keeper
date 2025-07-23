<?php

namespace App\Exception;

class CBRFHttpClientException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
