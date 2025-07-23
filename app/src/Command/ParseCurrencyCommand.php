<?php

namespace App\Command;

use App\DTO\ResultDtoParser;
use App\Entity\Currency;
use App\Entity\CurrencyRate;
use App\Exception\CannotParseStructException;
use App\Exception\CBRFHttpClientException;
use App\Fabric\CBRFParserFabric;
use App\HttpClient\CBRFHttpClient;
use App\Repository\CurrencyRateRepository;
use App\Service\CurrencyService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:parse-currency', description: 'Parse currency')]
class ParseCurrencyCommand extends Command
{
    public function __construct(
        private CBRFHttpClient $client,
        private CBRFParserFabric $parserFabric,
        private LoggerInterface $logger,
        private CurrencyService $currencyService,
        private EntityManagerInterface $entityManager,
        private CurrencyRateRepository $currencyRateRepository,
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
        } catch (\Throwable $e) {
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
        foreach ($data as $item) {
            try {
                $currency = $this->currencyService->getCurrencyByCode($item->code);
                if (!$currency instanceof Currency) {
                    continue;
                }

                $currencyRate = new CurrencyRate(
                    $currency,
                    $item->valueRate,
                    new \DateTimeImmutable()
                );

                $this->currencyRateRepository->add($currencyRate);
            } catch (\Throwable $e) {
                $this->logger->error('Ошибка при обработке валюты', [
                    'exception' => $e,
                    'item' => $item,
                ]);
            }
        }

        $this->entityManager->flush();
    }
}
