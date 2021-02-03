<?php

namespace Porox\Dropmefiles\Client;

interface ResponseInterface
{
    public function getUrl(): string;

    public function setUrl(string $url): void;

    public function getPassword(): ?string;

    public function setPassword(string $password): void;

    public function isSuccess(): bool;

    public function setSuccess(bool $success): void;

    public function getErrorText(): string;

    public function setErrorText(string $errorText): void;

    public function getErrorCode(): ?int;

    public function setErrorCode(?int $errorCode): void;
}
