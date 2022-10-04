<?php

namespace Porox\Dropmefiles\Client;

class Response implements ResponseInterface
{
    /**
     * @var bool
     */
    protected $success = false;

    /**
     * @var string
     */
    protected $url = '';

    /**
     * @var string|null
     */
    protected $password;

    /**
     * @var string
     */
    protected $errorText = '';

    /**
     * @var int|null
     */
    protected $errorCode;

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    public function getErrorText(): string
    {
        return $this->errorText;
    }

    public function setErrorText(string $errorText): void
    {
        $this->errorText = $errorText;
    }

    public function getErrorCode(): ?int
    {
        return $this->errorCode;
    }

    public function setErrorCode(?int $errorCode): void
    {
        $this->errorCode = $errorCode;
    }
}
