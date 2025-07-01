<?php

namespace App\Controller;

use App\DTO\CurrencyUpdateDto;
use App\Entity\Currency;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CurrencyService;
use App\DTO\CurrencyCreationDto;

class CurrencyController extends BaseController
{
    public function __construct(private CurrencyService $currencyService) {}

    #[Route('/currency', name:'currency_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        dd($request->getPayload());

        $dto = new CurrencyCreationDto(
            $request->get('code'),
            $request->get('char'),
            $request->get('nominal'),
            $request->get('humanName'),
        );

        $currency = $this->currencyService->createCurrency($dto);

        return $this->createResponseSuccess([
            'id' => $currency->getId(),
            'code' => $currency->getCode(),
            'char' => $currency->getChar(),
            'nominal' => $currency->getNominal(),
            'humanName' => $currency->getHumanName(),
        ]);
    }

    #[Route('/currency/{id}', name:'get_currency', methods: ['GET'])]
    public function getCurrency(int $id): JsonResponse
    {
        $currency = $this->currencyService->getCurrencyById($id);

        if (!$currency instanceof Currency) {
            return $this->createResponseNotFound(['class' => Currency::class, 'id' => $id]);
        }

        return $this->json([
            'code' => $currency->getCode(),
            'char' => $currency->getChar(),
            'nominal' => $currency->getNominal(),
            'humanName' => $currency->getHumanName(),
        ]);

    }

    #[Route('/currency/{id}', name:'currency_update', methods: ['POST'])]
    public function updateCurrency(int $id, Request $request): JsonResponse
    {
        $dto = new CurrencyUpdateDto(
            $request->get('code'),
            $request->get('char'),
            $request->get('nominal'),
            $request->get('humanName'),
        );

        $currency = $this->currencyService->updateCurrency($id, $dto);
        if (!$currency instanceof Currency) {
            return $this->createResponseNotFound(['class' => Currency::class, 'id' => $id]);
        }

        return $this->json([
            'code' => $currency->getCode(),
            'char' => $currency->getChar(),
            'nominal' => $currency->getNominal(),
            'humanName' => $currency->getHumanName(),
        ]);

    }

    #[Route('/currency/{id}', name:'currency_delete', methods: ['DELETE'])]
    public function deleteCurrency(int $id): JsonResponse
    {
        $deleted = $this->currencyService->deleteCurrency($id);

        if (!$deleted) {
            return $this->createResponseNotFound(['class' => Currency::class, 'id' => $id]);
        }

        return $this->createResponseSuccess(["success" => "Currency successfully deleted"]);
    }
}
