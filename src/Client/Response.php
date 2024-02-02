<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 14. 8. 2021.
 * Time: 19:53
 */

namespace Sco\Monri\Client;

interface Response
{
    public function forRequest(Request $request): self;

    public function getRequest(): ?Request;

    public function getBody(): array|string;
}
