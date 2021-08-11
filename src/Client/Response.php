<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 10. 8. 2021.
 * Time: 14:11
 */

declare(strict_types=1);

namespace SasaB\Monri\Client;

use SasaB\Monri\Arrayable;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class Response implements Arrayable
{
    private ?Request $request = null;
    private array $body;
    private float $timestamp;

    public function __construct(array $body)
    {
        $this->body = $body;
        $this->timestamp = microtime(true);
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function asArray(): array
    {
        return [

        ];
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getBody(): array
    {
        return $this->body;
    }
}
