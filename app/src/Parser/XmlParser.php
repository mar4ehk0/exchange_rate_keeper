<?php

namespace App\Parser;

use App\DTO\ResultDtoParser;
use App\Exception\CannotParseStructException;
use App\Interface\ParserInterface;
use Symfony\Component\Serializer\SerializerInterface;

class XmlParser implements ParserInterface
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    /**
     * @return ResultDtoParser[]
     *
     * @throws CannotParseStructException
     */
    public function parse(string $rawContent): array
    {
        $utf8Content = mb_convert_encoding($rawContent, 'UTF-8', 'windows-1251');
        $utf8Content = str_replace('encoding="windows-1251"', 'encoding="UTF-8"', $utf8Content);

        $decodedCurrencyRates = $this->serializer->decode($utf8Content, 'xml');
        if (empty($decodedCurrencyRates['Valute'])) {
            throw new CannotParseStructException();
        }

        $result = [];
        foreach ($decodedCurrencyRates['Valute'] as $item) {
            if (!$this->isValidStructure($item)) {
                continue;
            }
            // заменяем , на . (не знаю где лучше это делать но мне показалось что здесь)
            $valueRate = str_replace(',', '.', $item['Value']);

            $result[] = new ResultDtoParser(
                code: $item['NumCode'],
                char: $item['CharCode'],
                nominal: $item['Nominal'],
                humanName: $item['Name'],
                valueRate: $valueRate,
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
