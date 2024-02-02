<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 11. 8. 2021.
 * Time: 22:54
 */

namespace Sco\Monri\Client\Exception;

use Sco\Monri\Client\Request;

final class MissingRequiredFieldException extends \InvalidArgumentException
{
    private ?Request $request;

    public static function authenticityToken(Request $request): self
    {
        $e = new self('Authenticity token not set on '.get_class($request).' request object');
        $e->setRequest($request);
        return $e;
    }

    public static function merchantKey(Request $request): self
    {
        $e = new self('Merchant key not set on '.get_class($request).' request object. Cannot calculate digest');
        $e->setRequest($request);
        return $e;
    }

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    public function setRequest(?Request $request): void
    {
        $this->request = $request;
    }
}
