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
    public function setRequest(Request $request): void;

    public function getRequest(): Request;

    /**
     * @return array|string
     */
    public function getBody();
}
