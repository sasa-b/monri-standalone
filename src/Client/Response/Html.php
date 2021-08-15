<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 15. 8. 2021.
 * Time: 14:32
 */

namespace SasaB\Monri\Client\Response;


use SasaB\Monri\Client\Request;
use SasaB\Monri\Client\Response;
use Symfony\Component\HttpClient\Response\CurlResponse;

final class Html implements Response
{
    private Request $request;
    private string $content;

    private function __construct(string $content)
    {
        $this->content = $content;
    }

    public static function fromCurl(CurlResponse $response): self
    {
        return new self($response->getContent());
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
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
