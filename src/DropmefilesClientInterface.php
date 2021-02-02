<?php

namespace Porox\Dropmefiles\Client;

interface DropmefilesClientInterface
{
    public function sendFiles(CreateFileConfig $config): ResponseInterface;
}
