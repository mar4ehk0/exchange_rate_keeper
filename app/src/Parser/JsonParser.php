<?php

namespace App\Parser;

use App\Entity\Currency;
use App\Interface\ParserInterface;

class JsonParser implements ParserInterface
{
    /**
     * @return Currency[]
     */
    public function parse(string $rawContent): array
    {
        // TODO: Implement parse() method.

        return [];
    }
}
