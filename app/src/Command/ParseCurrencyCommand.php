<?php

namespace App\Command;

use App\Exception\CBRFHttpClientException;
use App\HttpClient\CBRFHttpClient;
use Doctrine\DBAL\Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\Exception\ClientException;

#[AsCommand(name: 'app:parse-currency', description: 'Parse currency')]
class ParseCurrencyCommand extends Command
{
    public function __construct(private CBRFHttpClient $client)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
//        $output->writeln('<info>Hello World</info>');

        try {
            $this->client->request();
        } catch (CBRFHttpClientException $e) {

        } catch (Exception $e) {

        }

        return Command::SUCCESS;
    }
}

// curl -> guzzle

