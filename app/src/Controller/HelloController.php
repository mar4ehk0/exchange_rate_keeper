<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class HelloController extends BaseController
{
    #[Route('/word', name: 'hello', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return new JsonResponse('Hello World!');
    }
}

// данные о курсе, крон дергать один раз в день и сохранять
// 0.1+0.2 = 0.3
// руб -> копейки
// usd -> c
// 78,20 -> 78,1958
//
// курс валюты
// id
// code
// char
// nominal
// humanName
// value с округление до сотни
// datetimeRate

//
// user
// id, email, securite - jwt

// 1 пользователь может выбрать валюту и установить лимит на отслеживание и получать данные об этом через email
// 2 пользователь получает email ежедневно // 1_000_000_000 -> генерация email которые рассылаются пачками по 100
