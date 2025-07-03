<?php

namespace App\Parser;

use App\Entity\Currency;
use App\Interface\ParserInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Serializer;

class XmlParser implements ParserInterface
{
    /**
     * @return Currency[]
     * @throws ExceptionInterface
     */
    public function parse(string $rawContent): array
    {
        $encoders = [new JsonEncoder(), new XmlEncoder()];
        $serializer = new Serializer([], $encoders);

        $utf8Content = mb_convert_encoding($rawContent, 'UTF-8', 'windows-1251');

        $utf8Content = str_replace('encoding="windows-1251"', 'encoding="UTF-8"', $utf8Content);

        /*
            $xml = simplexml_load_string($utf8Content);
            return json_decode(json_encode($xml), true);
        */

        return $serializer->decode($utf8Content, 'xml');
    }
}
