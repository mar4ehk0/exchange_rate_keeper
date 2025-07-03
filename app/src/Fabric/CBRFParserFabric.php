<?php

namespace App\Fabric;

use App\Interface\ParserInterface;
use App\Parser\JsonParser;
use App\Parser\XmlParser;
use Exception;

class CBRFParserFabric
{
    /**
     * @throws Exception
     */
    public function create(string $contentType): ParserInterface
    {
        switch ($contentType) {
            case 'text/xml':
                return new XmlParser();
            case 'application/json':
                return new JsonParser();
            default:
                throw new Exception(sprintf('Parser does not implemented for content type: %s',$contentType));
        }
    }
}
