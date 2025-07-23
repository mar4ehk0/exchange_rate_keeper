<?php

namespace App\Controller;

use App\DTO\CurrencyCreationDto;
use App\DTO\CurrencyUpdateDto;
use App\Entity\Currency;
use App\Service\CurrencyService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CurrencyController extends BaseController
{
    public function __construct(
        private CurrencyService $currencyService,
        private ValidatorInterface $validator,
    ) {
    }

    #[Route('/currency', name: 'currency_create', methods: ['POST'])]
    public function create(CurrencyCreationDto $dto): JsonResponse
    {
        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            return $this->createResponseBadRequest($errors);
        }

        $currency = $this->currencyService->createCurrency($dto);

        return $this->createResponseSuccess([
            'id' => $currency->getId(),
            'code' => $currency->getCode(),
            'char' => $currency->getChar(),
            'nominal' => $currency->getNominal(),
            'humanName' => $currency->getHumanName(),
        ]);
    }

    #[Route('/currency/{id}', name: 'get_currency', methods: ['GET'])]
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

    #[Route('/currency/{id}', name: 'currency_update', methods: ['POST'])]
    public function updateCurrency(int $id, Request $request): JsonResponse
    {
        $data = $request->toArray(); // давай пока так потом покажу как делать по симфони стайлу

        $dto = new CurrencyUpdateDto(
            $id, // клади в dto для обновления и id
            $data['code'] ?? '',
            $data['char'] ?? '',
            isset($data['nominal']) ? (int) $data['nominal'] : 0,
            $data['humanName'] ?? '',
        );

        $currency = $this->currencyService->updateCurrency($dto);
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

    #[Route('/currency/{id}', name: 'currency_delete', methods: ['DELETE'])]
    public function deleteCurrency(int $id): JsonResponse
    {
        $deleted = $this->currencyService->deleteCurrency($id);

        // оставлю тут коммент, потому что тут вообще странное все делается, не удален значит not found
        if (!$deleted) {
            return $this->createResponseNotFound(['class' => Currency::class, 'id' => $id]);
        }

        return $this->createResponseSuccess(['success' => 'Currency successfully deleted']);
    }
}
