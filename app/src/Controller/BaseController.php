<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class BaseController extends AbstractController
{
    public function createResponseSuccess(mixed $data): JsonResponse
    {
        return $this->json($data);
    }

    public function notFound(mixed $data): JsonResponse
    {
        return $this->json($data, 404);
    }

    public function internalServerError(mixed $data): JsonResponse
    {
        return $this->json($data, 500);
    }
}
