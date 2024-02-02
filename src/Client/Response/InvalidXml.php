<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 14. 8. 2021.
 * Time: 19:24
 */

namespace Sco\Monri\Client\Response;

use Sco\Monri\Client\Request;
use Sco\Monri\Client\Response;

final class InvalidXml implements Response
{
    private Request $request;
    private string $error;

    public function setError(string $error): void
    {
        $this->error = $error;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function getBody(): array
    {
        return [
            'error' => $this->error
        ];
    }

    public function forRequest(Request $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}
