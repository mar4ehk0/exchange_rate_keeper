<?php

namespace App\HttpClient;

use App\DTO\CBRFHttpClientResultDto;
use App\Exception\CBRFHttpClientException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CBRFHttpClient
{
    public function __construct(
        private HttpClientInterface $client,
        private string $cbRFUrl,
    ) {
    }

    /**
     * @throws CBRFHttpClientException
     */
    public function request(): CBRFHttpClientResultDto
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->cbRFUrl
            );

            $statusCode = $response->getStatusCode();
            if (Response::HTTP_OK !== $statusCode) {
                throw new \Exception('Cannot retrieve response status code');
            }

            $headers = $response->getHeaders();
            if (empty($headers['content-type']) && !empty($headers['content-type'][0])) {
                throw new \Exception('Content-Type in headers is empty');
            }

            $contentType = $headers['content-type'][0];
            $rawData = $response->getContent();

            return new CBRFHttpClientResultDto(contentType: $contentType, rawContent: $rawData);
        } catch (\Throwable $exception) {
            throw new CBRFHttpClientException($exception->getMessage());
        }
    }
}
