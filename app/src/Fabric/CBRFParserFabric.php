<?php

namespace App\Fabric;

use App\Interface\ParserInterface;
use App\Parser\JsonParser;
use App\Parser\XmlParser;
use Symfony\Component\Serializer\SerializerInterface;

class CBRFParserFabric
{
    public function __construct(private SerializerInterface $serializer)
    {
    }
    private const CONTENT_TYPE_XML = 'text/xml';
    private const CONTENT_TYPE_JSON = 'application/json';

    /**
     * @throws \Exception
     */
    public function create(string $contentType): ParserInterface
    {
        switch ($contentType) {
            case self::CONTENT_TYPE_XML:
                return new XmlParser($this->serializer);
            case self::CONTENT_TYPE_JSON:
                return new JsonParser();
            default:
                throw new \Exception(sprintf('Parser does not implemented for content type: %s', $contentType));
        }
    }
}
