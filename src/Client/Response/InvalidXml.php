<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 14. 8. 2021.
 * Time: 19:24
 */

namespace SasaB\Monri\Client\Response;

use SasaB\Monri\Client\Response;

final class InvalidXml implements Response
{
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
}
