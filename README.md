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