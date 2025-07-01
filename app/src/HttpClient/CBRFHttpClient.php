<?php

namespace App\HttpClient;

use App\Exception\CBRFHttpClientException;
use App\Fabric\CBRFParserFabric;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class CBRFHttpClient
{
    public function __construct(
        private HttpClientInterface $client,
        private string $cbRFUrl,
        private CBRFParserFabric $parserFabric,
    ) {
    }

    /**
     * @throws CBRFHttpClientException
     * @throws Exception|TransportExceptionInterface
     */
    public function request(): void
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->cbRFUrl
            );
        } catch (Throwable $exception) {
            throw new CBRFHttpClientException($exception->getMessage());
        }

        $statusCode = $response->getStatusCode();
        if ($statusCode !== Response::HTTP_OK) {
            throw new CBRFHttpClientException('Cannot retrieve response status code');
        }

        $contentType = $response->getHeaders()['content-type'][0];
        $parser = $this->parserFabric->create($contentType);
        $parser->parse($response->getContent());

//        $content = );
//        $content = $response->toArray();
//        dd($statusCode, $contentType, $content);
    }

    // parsering xml

}
