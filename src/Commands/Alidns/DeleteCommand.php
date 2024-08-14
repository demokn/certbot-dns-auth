<?php

namespace Demokn\DnsAuth\Commands\Alidns;

use Demokn\DnsAuth\Facades\Alidns;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'alidns:delete')]
class DeleteCommand extends Command
{
    protected function configure()
    {
        $this->setDescription('删除 Aliyun DNS 解析记录')
            ->setHelp('删除 Aliyun DNS 解析记录');

        $this->addArgument('domain', InputArgument::REQUIRED, '域名/子域名')
            ->addArgument('rr', InputArgument::REQUIRED, '主机记录')
            ->addArgument('app_key', InputArgument::REQUIRED, '阿里云 AccessKey ID')
            ->addArgument('app_secret', InputArgument::REQUIRED, '阿里云 AccessKey Secret');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $alidns = Alidns::createClient($input->getArgument('app_key'), $input->getArgument('app_secret'));

        $queryRequest = $alidns->describeDomainRecords([
            'query' => [
                'DomainName' => $input->getArgument('domain'),
                'KeyWord' => $input->getArgument('rr'),
                'SearchMode' => 'EXACT',  // LIKE=模糊 EXACT=精确 ADVANCED=高级
            ],
        ]);

        $queryResult = $queryRequest->request();
        if ($queryRequest->getStatusCode() >= 400) {
            throw new \RuntimeException('Request aliyun failed.');
        }
        $records = $queryResult->toArray()['DomainRecords']['Record'];
        $recordsCount = count($records);
        if ($recordsCount !== 1) {
            throw new \RuntimeException("Found {$recordsCount} records, expects 1.");
        }

        $recordId = current($records)['RecordId'];
        $deleteRequest = $alidns->deleteDomainRecord([
            'query' => [
                'RecordId' => $recordId,
            ],
        ]);
        $deleteResult = $deleteRequest->request();
        if ($deleteResult->getStatusCode() >= 400) {
            throw new \RuntimeException('Request aliyun failed.');
        }

        return Command::SUCCESS;
    }
}
