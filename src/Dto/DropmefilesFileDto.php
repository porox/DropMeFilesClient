<?php

namespace Porox\Dropmefiles\Client\Dto;

class DropmefilesFileDto implements \JsonSerializable
{
    protected string $id;

    protected string $name;

    protected string $type = 'application\zip';

    protected string  $relativePath = '';

    protected int  $size;
    protected int  $origSize;
    protected int  $loaded;
    protected int $percent = 100;
    protected int $status = 5;
    protected string  $lastModifiedDate = '2021-01-28T14:18:14.996Z';
    protected int  $completeTimestamp;
    protected string $dir;
    protected int $logstatus = 2;

    public function __construct(string $id, string $name, int $size, string $dir)
    {
        $this->id = $id;
        $this->name = $name;
        $this->size = $size;
        $this->origSize = $size;
        $this->loaded = $size;
        $this->completeTimestamp = time();
        $this->dir = $dir;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getRelativePath(): string
    {
        return $this->relativePath;
    }

    public function setRelativePath(string $relativePath): void
    {
        $this->relativePath = $relativePath;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): void
    {
        $this->size = $size;
    }

    public function getOrigSize(): int
    {
        return $this->origSize;
    }

    public function setOrigSize(int $origSize): void
    {
        $this->origSize = $origSize;
    }

    public function getLoaded(): int
    {
        return $this->loaded;
    }

    public function setLoaded(int $loaded): void
    {
        $this->loaded = $loaded;
    }

    public function getPercent(): int
    {
        return $this->percent;
    }

    public function setPercent(int $percent): void
    {
        $this->percent = $percent;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getCompleteTimestamp(): int
    {
        return $this->completeTimestamp;
    }

    public function setCompleteTimestamp(int $completeTimestamp): void
    {
        $this->completeTimestamp = $completeTimestamp;
    }

    public function getDir(): string
    {
        return $this->dir;
    }

    public function setDir(string $dir): void
    {
        $this->dir = $dir;
    }

    public function getLogstatus(): int
    {
        return $this->logstatus;
    }

    public function setLogstatus(int $logstatus): void
    {
        $this->logstatus = $logstatus;
    }

    public function getLastModifiedDate(): string
    {
        return $this->lastModifiedDate;
    }

    public function setLastModifiedDate(string $lastModifiedDate): void
    {
        $this->lastModifiedDate = $lastModifiedDate;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
