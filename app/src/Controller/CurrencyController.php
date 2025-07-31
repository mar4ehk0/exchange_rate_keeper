<?php

namespace App\Controller;

use App\DTO\CurrencyCreationDto;
use App\DTO\CurrencyUpdateDto;
use App\Entity\Currency;
use App\Exception\CurrencyAlreadyExistsException;
use App\Service\CurrencyService;
use App\View\CurrencyDeleteView;
use App\View\CurrencyErrorView;
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

        $view = new CurrencyView($currency);

        return $this->createResponseSuccess($view->getData());
    }

    #[Route('/currency/{id}', name: 'get_currency', methods: ['GET'])]
    public function getCurrency(int $id): JsonResponse
    {
        $currency = $this->currencyService->getCurrencyById($id);

        if (!$currency instanceof Currency) {
            return $this->createResponseNotFound(CurrencyErrorView::notFound($id));
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
                return $this->createResponseNotFound(CurrencyErrorView::notFound($dto->id));
            }
        } catch (CurrencyAlreadyExistsException $e) {
            return $this->createResponseHttpConflict(CurrencyErrorView::httpConflict($dto->id, $e->getMessage()));
        }

        $view = new CurrencyView($currency);

        return $this->createResponseSuccess($view->getData());
    }

    #[Route('/currency/{id}', name: 'currency_delete', methods: ['DELETE'])]
    public function deleteCurrency(int $id): JsonResponse
    {
        $deleted = $this->currencyService->deleteCurrency($id);

        // оставлю тут коммент, потому что тут вообще странное все делается, не удален значит not found
        if (!$deleted) {
            return $this->createResponseInternalServerError(CurrencyErrorView::internalServerError($id));
        }

        return $this->createResponseSuccess(CurrencyDeleteView::getData());
    }
}
