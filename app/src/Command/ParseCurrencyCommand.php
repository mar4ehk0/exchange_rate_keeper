<?php

namespace App\Command;

use App\DTO\CurrencyCreationDto;
use App\DTO\CurrencyUpdateDto;
use App\Entity\Currency;
use App\Exception\CBRFHttpClientException;
use App\Fabric\CBRFParserFabric;
use App\HttpClient\CBRFHttpClient;
use App\Service\CurrencyService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\Exception\ClientException;

#[AsCommand(name: 'app:parse-currency', description: 'Parse currency')]
class ParseCurrencyCommand extends Command
{
    public function __construct(
        private CBRFHttpClient $client,
        private CBRFParserFabric $parserFabric,
        private LoggerInterface $logger,
        private CurrencyService $currencyService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $dto = $this->client->request();

            $parser = $this->parserFabric->create($dto->contentType); // добавить обработку исключению

            $result = $parser->parse($dto->rawContent); // result будет содержать массив объектов Currency которые надо будет сохранить

            $this->save($result["Valute"]);

        } catch (CBRFHttpClientException $e) {
            $this->logger->error($e->getMessage());

            return Command::FAILURE;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());

            return Command::INVALID;
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());

            return Command::INVALID;
        }

        return Command::SUCCESS;
    }

    /**
     * @throws \Throwable
     */
    public function save(array $data): void
    {
        foreach ($data as $currencyItem) {
            try {
                if (!isset($currencyItem['NumCode'], $currencyItem['CharCode'], $currencyItem['Nominal'], $currencyItem['Name'])) {
                    $this->logger->warning('Некорректный элемент', $currencyItem);
                    continue;
                }

                $existing = $this->currencyService->getCurrencyByCode($currencyItem['NumCode']);

                if ($existing) {
                    $this->updateCurrency($existing, $currencyItem);
                } else {
                    $this->createCurrency($currencyItem);
                }
            } catch (\Throwable $e) {
                $this->logger->error('Ошибка при обработке валюты', [
                    'exception' => $e,
                    'item' => $currencyItem,
                ]);
            }
        }
    }

    private function updateCurrency(Currency $currency, $item): void
    {
        $updateDto = new CurrencyUpdateDto(
            id: $currency->getId(),
            code: $item['NumCode'],
            char: $item['CharCode'],
            nominal: (int) $item['Nominal'],
            humanName: $item['Name']
        );

        $this->currencyService->updateCurrency($updateDto);
    }

    private function createCurrency(array $item): void
    {
        $createDto = new CurrencyCreationDto(
            code: $item['NumCode'],
            char: $item['CharCode'],
            nominal: (int) $item['Nominal'],
            humanName: $item['Name']
        );

        $this->currencyService->createCurrency($createDto);
    }
}

