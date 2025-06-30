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
    public function createResponseError(mixed $data, int $code): JsonResponse
    {
        return $this->json($data, $code);
    }
}
