<?php

namespace App\Interface;

use App\Entity\Currency;

interface ParserInterface
{
    /**
     * @return Currency[]
     */
    public function parse(string $rawContent): array;
}
