<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 11. 8. 2021.
 * Time: 17:56
 */

namespace SasaB\Monri\Client;

interface Request
{
    public function getBody(): array;

    public function getType(): string;
}
