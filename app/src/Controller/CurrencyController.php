<?php

namespace App\Controller;

use App\DTO\CurrencyCreationDto;
use App\DTO\CurrencyUpdateDto;
use App\Entity\Currency;
use App\Exception\CurrencyAlreadyExistsException;
use App\Service\CurrencyService;
use App\View\CurrencyView;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        try {
            $currency = $this->currencyService->createCurrency($dto);
        } catch (CurrencyAlreadyExistsException $e) {
            return $this->createResponseHttpConflict($e);
        }

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

        $view = new CurrencyView($currency);

        return $this->createResponseSuccess($view->getData());
    }

    #[Route('/currency/{id}', name: 'currency_update', methods: ['PATCH'])]
    public function updateCurrency(CurrencyUpdateDto $dto): JsonResponse
    {
        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            return $this->createResponseBadRequest($errors);
        }

        try {
            $currency = $this->currencyService->updateCurrency($dto);
            if (!$currency instanceof Currency) {
                return $this->createResponseNotFound(['class' => Currency::class, 'id' => $dto->id]);
            }
        } catch (CurrencyAlreadyExistsException $e) {
            return $this->createResponseHttpConflict(['class' => Currency::class, 'id' => $dto->id, 'message' => $e->getMessage()]);
        }

        return $this->createResponseSuccess([
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
            return $this->createResponseInternalServerError(['class' => Currency::class, 'id' => $id]);
        }

        return $this->createResponseSuccess(['success' => 'Currency successfully deleted']);
    }
}
