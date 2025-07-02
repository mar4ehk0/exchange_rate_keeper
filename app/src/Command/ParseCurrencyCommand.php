<?php

namespace App\Command;

use App\Exception\CBRFHttpClientException;
use App\Fabric\CBRFParserFabric;
use App\HttpClient\CBRFHttpClient;
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
        private LoggerInterface $logger
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $dto = $this->client->request();

            $parser =  $this->parserFabric->create($dto->contentType); // добавить обработку исключению

            $result = $parser->parse($dto->rawContent); // result будет содержать массив объектов Currency которые надо будет сохранить

        } catch (CBRFHttpClientException $e) {
            $this->logger->error($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

