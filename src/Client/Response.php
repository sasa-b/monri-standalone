<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 14. 8. 2021.
 * Time: 19:53
 */

namespace SasaB\Monri\Client;


interface Response
{
    public function getBody(): array;
}
