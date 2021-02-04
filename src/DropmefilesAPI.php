<?php

namespace Porox\Dropmefiles\Client;

use function array_merge;
use function fclose;
use function fopen;
use function fread;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use function intdiv;
use function json_decode;
use function md5;
use Porox\Dropmefiles\Client\Dto\DropmefilesDto;
use Porox\Dropmefiles\Client\Dto\DropmefilesFileDto;
use Porox\Dropmefiles\Client\Exception\DropmefilesException;
use SplFileInfo;
use function urlencode;

class DropmefilesAPI implements DropmefilesAPIInteface
{
    private const HOST = 'https://dropmefiles.com';
    private const CHUNK_SIZE = 4194304;

    protected $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @throws DropmefilesException
     * @throws GuzzleException
     */
    public function create(int $size, int $period = PeriodTypes::DAYS_3, ?DropmefilesDto $dto = null): DropmefilesDto
    {
        $params = [
            'runtime' => 'html5',
            'server' => 0,
            'updirType' => 'abc',
            'count' => 1,
            'size' => $size,
            'period' => $period,
            'name' => '',
            'comment' => '',
        ];

        if ($dto instanceof DropmefilesDto) {
            $params['updir'] = $dto->getUid();
            $params['group'] = '';
            $params['count'] = $dto->countFiles();
            $params['size'] = $dto->getFilesSize();
        } else {
            $dto = new DropmefilesDto();
        }
        $response = $this->httpClient->request('POST', self::HOST . '/s3/upload/create', [
            RequestOptions::HEADERS => array_merge($this->getBaseHeaders(), [
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
            ]),
            RequestOptions::FORM_PARAMS => $params,
        ]);

        $res = json_decode($response->getBody()->getContents(), true);

        $this->processResponse($res);

        $dto->setUid($res['result'] ?? '');

        return $dto;
    }

    /**
     * @throws DropmefilesException
     * @throws GuzzleException
     */
    public function password(DropmefilesDto $dto): bool
    {
        $response = $this->httpClient->request('POST', self::HOST . '/s3/upload/password', [
            RequestOptions::HEADERS => array_merge($this->getBaseHeaders(), [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ]),
            RequestOptions::FORM_PARAMS => [
                'updirType' => 'abc',
                'password' => 1,
                'uid' => $dto->getUid(),
            ],
        ]);

        $res = json_decode($response->getBody()->getContents(), true);

        $dto->setPassword($res['password'] ?? '');
        $this->processResponse($res);

        return !empty($res['password'] ?? '');
    }

    /**
     * @throws DropmefilesException
     * @throws GuzzleException
     */
    public function uploadFile(SplFileInfo $fileInfo, DropmefilesDto $dto): bool
    {
        $res = [];
        $fileDto = new DropmefilesFileDto(
            $this->getFileId($fileInfo->getFilename()),
            $fileInfo->getFilename(),
            $fileInfo->getSize(),
            $dto->getUid()
        );
        $fileNameTech = $dto->getUid() . '_' . $fileDto->getId();
        $fileStream = fopen($fileInfo->getRealPath(), 'r');
        $chunks = intdiv($fileInfo->getSize(), self::CHUNK_SIZE);
        $chunks = ($fileInfo->getSize() % self::CHUNK_SIZE > 0) ? $chunks + 1 : $chunks;
        $chunkSize = 1 === $chunks ? $fileInfo->getSize() : self::CHUNK_SIZE;
        $sendFileSize = 0;
        for ($chunk = 0; $chunk < $chunks; ++$chunk) {
            $response = $this->httpClient->request(
                'POST',
                self::HOST . '/s3/uploadch?name=' . urlencode($fileInfo->getFilename()) . '&chunk=' . $chunk .
                '&chunks=' . $chunks . '&updir=' . $dto->getUid(),
                [
                    RequestOptions::HEADERS => array_merge($this->getBaseHeaders(), [
                        'Content-Type' => 'application/octet-stream',
                        'content-disposition' => 'attachment; filename="' . $fileNameTech . '"',
                        'Content-Length' => $chunkSize,
                        'session-id' => $fileNameTech,
                        'content-range' => 'bytes ' . $sendFileSize . '-' . (($chunkSize + $sendFileSize) - 1) .
                            '/' . $fileInfo->getSize(),
                    ]),
                    RequestOptions::BODY => fread($fileStream, $chunkSize),
                ]
            );
            $sendFileSize += $chunkSize;
            if (($fileInfo->getSize() - $sendFileSize) < self::CHUNK_SIZE) {
                $chunkSize = $fileInfo->getSize() - $sendFileSize;
            }
            if (200 === $response->getStatusCode()) {
                $res = json_decode($response->getBody()->getContents(), true);
                $this->processResponse($res);
            }
        }
        fclose($fileStream);
        $dto->addFile($fileDto);

        return ($res['result'] ?? false) === null;
    }

    /**
     * @throws DropmefilesException
     * @throws GuzzleException
     */
    public function save(int $period, DropmefilesDto $dto): bool
    {
        $response = $this->httpClient->request('POST', self::HOST . '/s3/upload/save', [
            RequestOptions::HEADERS => array_merge($this->getBaseHeaders(), [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ]),
            RequestOptions::FORM_PARAMS => [
                'uid' => $dto->getUid(),
                'updirType' => 'abc',
                'count' => $dto->countFiles(),
                'size' => $dto->getFilesSize(),
                'period' => $period,
                'files' => json_encode($dto->getFiles()),
                'group' => '',
                'speed' => '851023',
                'name' => '',
            ],
        ]);

        $res = json_decode($response->getBody()->getContents(), true);
        $this->processResponse($res);

        return ($res['result'] ?? null) === 'Saved';
    }

    public function getUrl(DropmefilesDto $dto): string
    {
        return self::HOST . '/' . $dto->getUid();
    }

    /**
     * @return string[]
     */
    private function getBaseHeaders(): array
    {
        return [
            'X-Requested-With' => 'XMLHttpRequest',
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.1.2 Safari/605.1.15',
            'Cache-Control' => 'no-cache',
            'Host' => 'dropmefiles.com',
            'Origin' => 'https://dropmefiles.com',
            'Referer' => 'https://dropmefiles.com/',
        ];
    }

    private function getFileId(string $fileName): string
    {
        return 'o_' . md5($fileName . time());
    }

    /**
     * @param $res
     *
     * @throws DropmefilesException
     */
    private function processResponse($res): void
    {
        if (isset($res['error'])) {
            throw new DropmefilesException('Dropmefiles error code: ' . ($res['error']['code'] ?? 0) . ' message: ' . ($res['error']['message'] ?? ''), ($res['error']['code'] ?? 0));
        }
    }
}
