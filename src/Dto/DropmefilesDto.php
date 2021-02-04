<?php

namespace Porox\Dropmefiles\Client\Dto;

class DropmefilesDto
{
    /**
     * @var string
     */
    protected $uid;

    /**
     * @var string
     */
    protected $password = '';

    /**
     * @var array
     */
    protected $files = [];

    public function getUid(): string
    {
        return $this->uid;
    }

    public function setUid(string $uid): void
    {
        $this->uid = $uid;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function addFile(DropmefilesFileDto $fileDto): void
    {
        $this->files[] = $fileDto;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function countFiles(): int
    {
        return count($this->files);
    }

    public function getFilesSize(): int
    {
        $res = 0;
        foreach ($this->files as $file) {
            if ($file instanceof DropmefilesFileDto) {
                $res += $file->getSize();
            }
        }

        return $res;
    }
}
