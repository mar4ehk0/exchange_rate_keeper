<?php

namespace App\Command;

use App\DTO\CurrencyCreationDto;
use App\DTO\CurrencyUpdateDto;
use App\DTO\ResultDtoParser;
use App\Entity\Currency;
use App\Exception\CannotParseStructException;
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
use Throwable;

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

            $this->save($result);

        } catch (CBRFHttpClientException $e) {
            $this->logger->error($e->getMessage());

            $output->writeln('<error>Сервер ЦБ РФ имеет сетевые проблемы.</error>');
            return Command::FAILURE;
        } catch (CannotParseStructException $e) {
            $this->logger->error($e->getMessage());

            $output->writeln('<error>ЦБ РФ изменил структуру ответа.</error>');
            return Command::INVALID;
        } catch (Throwable $e) {
            $this->logger->error($e->getMessage());

            $output->writeln('<error>Ошибка! Проверь логи.</error>');
            return Command::INVALID;
        }

        return Command::SUCCESS;
    }

    /**
     * @param ResultDtoParser[] $data
     */
    public function save(array $data): void
    {
        // делаем запрос в БД, чтобы понять какие валюты отслеживает пользователь

        foreach ($data as $item) {
            // фильтруешь по $code только те которые отслеживает пользватель,
            // для отфильтрованных создаешь CurrencyRate ( сервис и dto для создания CurrencyRate)
//            try {
//                $existing = $this->currencyService->findCurrencyByCode($item->code);
//
//                if ($existing instanceof Currency) {
//                    $this->updateCurrency($existing, $item);
//                } else {
//                    $this->createCurrency($item);
//                }
//            } catch (\Throwable $e) {
//                $this->logger->error('Ошибка при обработке валюты', [
//                    'exception' => $e,
//                    'item' => $item,
//                ]);
//            }
        }
    }

//    private function updateCurrency(Currency $currency, $item): void
//    {
//        $updateDto = new CurrencyUpdateDto(
//            id: $currency->getId(),
//            code: $item['NumCode'],
//            char: $item['CharCode'],
//            nominal: (int) $item['Nominal'],
//            humanName: $item['Name']
//        );
//
//        $this->currencyService->updateCurrency($updateDto);
//    }
//
//    private function createCurrency(array $item): void
//    {
//        $createDto = new CurrencyCreationDto(
//            code: $item['NumCode'],
//            char: $item['CharCode'],
//            nominal: (int) $item['Nominal'],
//            humanName: $item['Name']
//        );
//
//        $this->currencyService->createCurrency($createDto);
//    }
}

