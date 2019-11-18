<?php

namespace Demokn\DnsAuth\Facades;

use AlibabaCloud\Alidns\V20150109\AlidnsApiResolver;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Alidns\Alidns as AlidnsClient;

class Alidns
{
    public static function createClient(string $accessKeyId, string $accessKeySecret): AlidnsApiResolver
    {
        AlibabaCloud::accessKeyClient($accessKeyId, $accessKeySecret)
            ->regionId('cn-shanghai')
            ->asDefaultClient();

        return AlidnsClient::v20150109();
    }
}
