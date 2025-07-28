<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{
    public function createResponseSuccess(mixed $data): JsonResponse
    {
        return $this->json($data);
    }

    public function createResponseNotFound(mixed $data): JsonResponse
    {
        return $this->json($data, Response::HTTP_NOT_FOUND);
    }

    public function createResponseInternalServerError(mixed $data): JsonResponse
    {
        return $this->json($data, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function createResponseBadRequest(mixed $data): JsonResponse
    {
        return $this->json($data, Response::HTTP_BAD_REQUEST);
    }

    public function createResponseHttpConflict(mixed $data): JsonResponse
    {
        return $this->json($data, Response::HTTP_CONFLICT);
    }
}
