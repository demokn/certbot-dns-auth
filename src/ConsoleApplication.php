<?php

namespace Demokn\DnsAuth;

use Symfony\Component\Console\Application;

class ConsoleApplication extends Application
{
    const VERSION = '0.0.1';

    public function __construct()
    {
        parent::__construct('Certbot DNS Auth', self::VERSION);

        $this->addCommands([
            new \Demokn\DnsAuth\Commands\Alidns\CreateCommand(),
            new \Demokn\DnsAuth\Commands\Alidns\DeleteCommand(),
        ]);
    }
}
