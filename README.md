[![Build Status](https://api.travis-ci.com/porox/DropMeFilesClient.svg?branch=master)](https://travis-ci.com/github/porox/DropMeFilesClient)
[![Latest Stable Version](https://img.shields.io/packagist/v/porox/dropmefiles-client.svg)](https://packagist.org/packages/porox/dropmefiles-client)
[![Total Downloads](https://img.shields.io/packagist/dt/porox/dropmefiles-client.svg)](https://packagist.org/packages/porox/dropmefiles-client)
[![Downloads Month](https://img.shields.io/packagist/dm/porox/dropmefiles-client.svg)](https://packagist.org/packages/porox/dropmefiles-client)
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D7.3-8892BF.svg)](https://php.net/)

#  Sample usage  
```php
include 'vendor/autoload.php';


use \Porox\Dropmefiles\Client\CreateFileConfig;
use \Porox\Dropmefiles\Client\PeriodTypes;

$httpClient = new \GuzzleHttp\Client(['']);
$client = \Porox\Dropmefiles\Client\DropmefilesClientFactory::create($httpClient);


$config = new CreateFileConfig();
$config->addFile(new SplFileInfo(__DIR__.'/README.md'));
$config->setNeedPassword(true);
$config->setPeriod(PeriodTypes::DAYS_3);

$resp =$client->sendFiles($config);

var_dump($resp);

```
