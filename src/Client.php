<?php

namespace Porox\Dropmefiles\Client;

use Porox\Dropmefiles\Client\Exception\DropmefilesException;
use Throwable;

class Client implements DropmefilesClientInterface
{
    /**
     * @var DropmefilesAPIInteface
     */
    protected $api;

    public function __construct(DropmefilesAPIInteface $api)
    {
        $this->api = $api;
    }

    public function sendFiles(CreateFileConfig $config): ResponseInterface
    {
        $dropmeFilesDto = null;
        $resp = new Response();
        try {
            foreach ($config->getFiles() as $file) {
                $dropmeFilesDto = $this->api->create($file->getSize(), $config->getPeriod(), $dropmeFilesDto);
                $this->api->uploadFile($file, $dropmeFilesDto);
                $this->api->save($config->getPeriod(), $dropmeFilesDto);
            }
            if (null === $dropmeFilesDto) {
                throw new DropmefilesException('No Files.');
            }
            $resp->setUrl($this->api->getUrl($dropmeFilesDto));
            if ($config->isNeedPassword()) {
                $this->api->password($dropmeFilesDto);
                $resp->setPassword($dropmeFilesDto->getPassword());
            }
            $resp->setSuccess(true);
        } catch (Throwable $exception) {
            $resp->setSuccess(false);
            $resp->setErrorText($exception->getMessage());
            if ($exception instanceof DropmefilesException) {
                $resp->setErrorCode((int) $exception->getCode());
            }
        } finally {
            return $resp;
        }
    }
}
