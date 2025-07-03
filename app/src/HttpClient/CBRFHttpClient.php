<?php

namespace App\HttpClient;

use App\DTO\CBRFHttpClientResultDto;
use App\Exception\CBRFHttpClientException;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

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
            if ($statusCode !== Response::HTTP_OK) {
                throw new Exception('Cannot retrieve response status code');
            }

            $headers = $response->getHeaders();
            if (empty($headers['content-type']) && !empty($headers['content-type'][0])) {
                throw new Exception('Content-Type in headers is empty');
            }

            $contentType = $headers['content-type'][0];
            $rawData = $response->getContent();

            return new CBRFHttpClientResultDto(contentType: $contentType, rawContent: $rawData);
        } catch (Throwable $exception) {
            throw new CBRFHttpClientException($exception->getMessage());
        }
    }

}
