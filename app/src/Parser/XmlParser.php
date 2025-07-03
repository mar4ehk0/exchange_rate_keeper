<?php

namespace App\Parser;

use App\Entity\Currency;
use App\Interface\ParserInterface;

class XmlParser implements ParserInterface
{
    /**
     * @return Currency[]
     */
    public function parse(string $rawContent): array
    {
        // TODO: Implement parse() method.
        // написать парсер из xml, кодировка указана в xml encoding="windows-1251"
        // тебе надо перекодировать в UTF-8 iconv или mb_convert_encoding
        // и затем используй простую парсер simplexml_load_string

        return [];
    }
}
