<?php

namespace App\Interface;

use App\DTO\ResultDtoParser;
use App\Exception\CannotParseStructException;

interface ParserInterface
{
    /**
     * @return ResultDtoParser[]
     *
     * @throws CannotParseStructException
     */
    public function parse(string $rawContent): array;
}
