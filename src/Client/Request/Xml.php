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
use SasaB\Monri\Client\TransactionType;
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
final class Xml implements Request, Arrayable
{
    use CanValidateXml;
    use CanDigest;

    private Order $order;
    private string $type;
    private string $token = '';
    private string $key = '';
    private float $timestamp;

    private function __construct(Order $order, string $type)
    {
        $this->order = $order;
        $this->type = $type;
        $this->timestamp = microtime(true);
    }

    public static function capture(Order $order): self
    {
        return new self($order, TransactionType::CAPTURE);
    }

    public static function refund(Order $order): self
    {
        return new self($order, TransactionType::REFUND);
    }

    public static function void(Order $order): self
    {
        return new self($order, TransactionType::VOID);
    }

    public static function fromArray(array $data): self
    {
        Assert::inArray($data['transaction_type'] ?? 'none', [
            TransactionType::CAPTURE,
            TransactionType::REFUND,
            TransactionType::VOID
        ], 'Invalid transaction_type value. Expected capture, refund or void. Got: %s');

        return new self(Order::fromArray($data), $data['transaction_type']);
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

    public function getType(): string
    {
        return $this->type;
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
