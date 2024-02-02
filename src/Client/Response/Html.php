<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 15. 8. 2021.
 * Time: 14:32
 */

namespace Sco\Monri\Client\Response;

use Sco\Monri\Client\Request;
use Sco\Monri\Client\Response;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class Html implements Response
{
    private Request $request;

    private function __construct(private readonly string $content) {}

    public static function fromCurl(ResponseInterface $response): self
    {
        return new self($response->getContent());
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

    public function getBody(): string
    {
        return $this->content;
    }
}
