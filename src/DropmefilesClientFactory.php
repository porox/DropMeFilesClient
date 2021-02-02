<?php

namespace Porox\Dropmefiles\Client;

use GuzzleHttp\ClientInterface;

class DropmefilesClientFactory
{
    public static function create(ClientInterface $client): DropmefilesClientInterface
    {
        return new Client(new DropmefilesAPI($client));
    }
}
