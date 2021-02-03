<?php

namespace Porox\Dropmefiles\Tests;

use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Porox\Dropmefiles\Client\DropmefilesClientFactory;
use Porox\Dropmefiles\Client\DropmefilesClientInterface;


class DropmefilesClientFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $stub = $this->createMock(ClientInterface::class);
        $this->assertInstanceOf(DropmefilesClientInterface::class, DropmefilesClientFactory::create($stub));
    }
}
