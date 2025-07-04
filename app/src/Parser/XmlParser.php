<?php

namespace App\Parser;

use App\DTO\ResultDtoParser;
use App\Entity\Currency;
use App\Exception\CannotParseStructException;
use App\Interface\ParserInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Serializer;

class XmlParser implements ParserInterface
{
    public function __construct()
    {
        // инжекти в конструктор Serializer
    }

    /**
     * @return ResultDtoParser[]
     * @throws CannotParseStructException
     */
    public function parse(string $rawContent): array
    {
        $encoders = [new XmlEncoder()];
        $serializer = new Serializer([], $encoders);

        $utf8Content = mb_convert_encoding($rawContent, 'UTF-8', 'windows-1251');

        $utf8Content = str_replace('encoding="windows-1251"', 'encoding="UTF-8"', $utf8Content);

        /*
            $xml = simplexml_load_string($utf8Content);
            return json_decode(json_encode($xml), true);
        */

        $decodedCurrencyRates = $serializer->decode($utf8Content, 'xml');
        if (empty($decodedCurrencyRates['Valute'])) {
            throw new CannotParseStructException();
        }

        $result = [];
        foreach ($decodedCurrencyRates['Valute'] as $item) {
            if (!$this->isValidStructure($item)) {
                continue;
            }

            $result[] = new ResultDtoParser(
                code: $item['NumCode'],
                char: $item['CharCode'],
                nominal: $item['Nominal'],
                humanName: $item['Name'],
                valueRate: $item['Value'],
            );
        }

        return $result;
    }

    private function isValidStructure(array $item): bool
    {
        if (empty($item['NumCode'])) {
            return false;
        }
        if (empty($item['CharCode'])) {
            return false;
        }
        if (empty($item['Nominal'])) {
            return false;
        }
        if (empty($item['Name'])) {
            return false;
        }
        if (empty($item['Value'])) {
            return false;
        }

        return true;
    }
}
