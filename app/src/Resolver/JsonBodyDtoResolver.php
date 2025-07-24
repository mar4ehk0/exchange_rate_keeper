<?php

namespace App\Resolver;

use App\Exception\JsonBodyDtoResolverException;
use App\Interface\JsonBodyDtoRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;

class JsonBodyDtoResolver implements ValueResolverInterface
{
    private const CONTENT_TYPE_JSON = 'json';

    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $type = $argument->getType();
        if (
            null === $type
            || !is_subclass_of($type, JsonBodyDtoRequestInterface::class)
            || self::CONTENT_TYPE_JSON !== $request->getContentTypeFormat()
        ) {
            return [];
        }

        $data = $request->getContent();

        try {
            $dto = $this->serializer->deserialize($data, $type, self::CONTENT_TYPE_JSON);

            return [$dto];
        } catch (\Throwable $e) {
            throw new JsonBodyDtoResolverException($e);
        }
    }
}
