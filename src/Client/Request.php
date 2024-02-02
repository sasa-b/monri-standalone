<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 11. 8. 2021.
 * Time: 17:56
 */

namespace Sco\Monri\Client;

interface Request
{
    public function getBody(): array;

    public function getType(): string;

    public function setToken(string $token): void;

    public function getToken(): string;

    public function setKey(string $key): void;

    public function getKey(): string;
}
