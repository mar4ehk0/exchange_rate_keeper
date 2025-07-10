<?php

namespace App\Tests\Fabric;

use App\Fabric\CBRFParserFabric;
use App\Parser\JsonParser;
use App\Parser\XmlParser;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\SerializerInterface;

class CBRFParserFabricTest extends TestCase
{
    private CBRFParserFabric $fabric;

    public function setUp(): void
    {
        $serializer = $this->createMock(SerializerInterface::class);

        $this->fabric = new CBRFParserFabric($serializer);
    }

    public function testCreateXmlParser(): void
    {
        // act
        $actual = $this->fabric->create('text/xml');

        // assert
        $this->assertInstanceOf(XmlParser::class, $actual);
    }

    public function testCreateJsonParser(): void
    {
        // act
        $actual = $this->fabric->create('application/json');

        // assert
        $this->assertInstanceOf(JsonParser::class, $actual);
    }
}

