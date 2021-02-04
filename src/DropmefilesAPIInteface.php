<?php

namespace Porox\Dropmefiles\Client;

use Porox\Dropmefiles\Client\Dto\DropmefilesDto;

use SplFileInfo;

interface DropmefilesAPIInteface
{
    public function create(int $size, int $period, ?DropmefilesDto $dto = null): DropmefilesDto;

    public function uploadFile(SplFileInfo $fileInfo, DropmefilesDto $dto): bool;

    public function save(int $period, DropmefilesDto $dto): bool;

    public function getUrl(DropmefilesDto $dto): string;

    public function password(DropmefilesDto $dto): bool;
}
