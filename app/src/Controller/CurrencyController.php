<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CurrencyService;
use App\DTO\CurrencyCreationDto;

class CurrencyController extends BaseController {

    public function __construct(private CurrencyService $currencyService) {}

    #[Route('/currency', name:'currency_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $dto = new CurrencyCreationDto(
            $data['code'],
            $data['char'],
            $data['nominal'],
            $data['humanName']
        );

        $currency = $this->currencyService->createCurrency($dto);

        return $this->json([
            'id' => $currency->getId(),
            'code' => $currency->getCode(),
            'char' => $currency->getChar(),
            'nominal' => $currency->getNominal(),
            'humanName' => $currency->getHumanName(),
        ]);
    }

}
