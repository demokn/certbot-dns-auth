<?php

namespace Demokn\DnsAuth\Commands\Alidns;

use Demokn\DnsAuth\Facades\Alidns;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateCommand extends Command
{
    protected static $defaultName = 'alidns:create';

    protected function configure()
    {
        $this->setDescription('修改 Aliyun DNS 解析记录')
            ->setHelp('修改 Aliyun DNS 解析记录');

        $this->addArgument('domain', InputArgument::REQUIRED, '域名/子域名')
            ->addArgument('rr', InputArgument::REQUIRED, '主机记录')
            ->addArgument('rr_type', InputArgument::REQUIRED, '主机记录类型')
            ->addArgument('rr_value', InputArgument::REQUIRED, '主机记录值')
            ->addArgument('app_key', InputArgument::REQUIRED, '阿里云 AccessKey ID')
            ->addArgument('app_secret', InputArgument::REQUIRED, '阿里云 AccessKey Secret');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $alidns = Alidns::createClient($input->getArgument('app_key'), $input->getArgument('app_secret'));

        $request = $alidns->addDomainRecord([
            'query' => [
                'DomainName' => $input->getArgument('domain'),
                'RR' => $input->getArgument('rr'),
                'Type' => $input->getArgument('rr_type'),
                'Value' => $input->getArgument('rr_value'),
            ],
        ]);

        $result = $request->request();
        if ($result->getStatusCode() >= 400) {
            throw new \RuntimeException("Request aliyun failed.");
        }

        $output->writeln(json_encode($result->toArray()));
    }
}
