<?php

namespace Porox\Dropmefiles\Client;

/**
 * Class CreateFileConfig.
 */
class CreateFileConfig
{
    protected bool $needPassword = false;

    /**
     * @var \SplFileInfo []
     */
    protected array $files = [];

    protected int $size = 0;

    protected int $period = PeriodTypes::DAYS_3;

    public function isNeedPassword(): bool
    {
        return $this->needPassword;
    }

    public function setNeedPassword(bool $needPassword): void
    {
        $this->needPassword = $needPassword;
    }

    public function getPeriod(): int
    {
        return $this->period;
    }

    public function setPeriod(int $period): void
    {
        $this->period = $period;
    }

    public function addFile(\SplFileInfo $fileInfo): void
    {
        $this->size += $fileInfo->getSize();
        $this->files[] = $fileInfo;
    }

    /**
     * @return \SplFileInfo[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }
}
