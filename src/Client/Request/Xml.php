<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 11. 8. 2021.
 * Time: 22:40
 */

namespace SasaB\Monri\Client\Request;

use SasaB\Monri\Arrayable;
use SasaB\Monri\CanDigest;
use SasaB\Monri\Client\Request;
use SasaB\Monri\Client\Request\Concerns\CanValidateXml;
use SasaB\Monri\Model\Order;
use Webmozart\Assert\Assert;

/**
 * Class Xml
 * @package SasaB\Monri\Client\Request
 *
 * <?xml version="1.0" encoding="UTF-8"?>
 * <transaction>
 *   <amount>54321</amount>
 *   <currency>EUR</currency>
 *   <digest>e64d4cd99367f0254ed5296d38fad6ce87d3acab</digest>
 *   <authenticity-token>7db11ea5d4a1af32421b564c79b946d1ead3daf0</authenticity-token>
 *   <order-number>11qqaazz</order-number>
 * </transaction>
 */
abstract class Xml implements Request, Arrayable
{
    use CanValidateXml;
    use CanDigest;

    protected Order $order;
    protected string $token = '';
    protected string $key = '';
    protected float $timestamp;

    protected function __construct(Order $order)
    {
        $this->order = $order;
        $this->timestamp = microtime(true);
    }

    abstract public function getType(): string;

    public static function fromArray(array $data): Xml
    {
        return new static(Order::fromArray($data));
    }

    public function asArray(): array
    {
        $digest = $this->digest(
            $this->key,
            $this->order->getNumber()->value(),
            $this->order->getAmount()->value(),
            $this->order->getCurrency()->value()
        );

        return [
            'transaction' => array_merge($this->order->asArray(), ['digest' => $digest])
        ];
    }

    public function getBody(): array
    {
        $body = $this->asArray();

        $this->validateXmlRequest($body);

        return $body;
    }

    public function getTimestamp(): float
    {
        return $this->timestamp;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        Assert::length($token, 40, 'Invalid token length. Expected 40 characters. Got: %s');
        $this->token = $token;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }
}
