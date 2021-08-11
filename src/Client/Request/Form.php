<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 11. 8. 2021.
 * Time: 22:40
 */

declare(strict_types=1);

namespace SasaB\Monri\Client\Request;

use SasaB\Monri\Arrayable;
use SasaB\Monri\CanDigest;
use SasaB\Monri\Client\Request;
use SasaB\Monri\Client\Request\Concerns\CanValidateForm;
use SasaB\Monri\Client\TransactionType;
use SasaB\Monri\Model\Customer;
use SasaB\Monri\Model\Order;
use SasaB\Monri\Options;
use Webmozart\Assert\Assert;

final class Form implements Request, Arrayable
{
    use CanValidateForm;
    use CanDigest;

    private Customer $customer;
    private Order $order;
    private Options $options;
    private string $type;
    private string $token = '';
    private string $key = '';
    private float $timestamp;

    private function __construct(Customer $customer, Order $order, Options $options, string $type)
    {
        $this->customer = $customer;
        $this->order = $order;
        $this->options = $options;
        $this->type = $type;
        $this->timestamp = microtime(true);
    }

    public static function authorize(Customer $customer, Order $order, Options $options = null): self
    {
        return new self($customer, $order, $options ?? Options::default(), TransactionType::AUTHORIZATION);
    }

    public static function purchase(Customer $customer, Order $order, Options $options = null): self
    {
        return new self($customer, $order, $options ?? Options::default(), TransactionType::PURCHASE);
    }

    public static function fromArray(array $data): Arrayable
    {
        Assert::inArray($data['transaction_type'] ?? 'none', [
            TransactionType::AUTHORIZATION,
            TransactionType::PURCHASE,
        ], 'Invalid transaction_type value. Expected authorization or purchase. Got: %s');

        return new self(
            Customer::fromArray($data),
            Order::fromArray($data),
            Options::fromArray($data),
            $data['transaction_type']
        );
    }

    public function asArray(): array
    {
        $digest = $this->digest(
            $this->key,
            $this->order->getNumber()->value(),
            $this->order->getAmount()->value(),
            $this->order->getCurrency()->value()
        );

        return array_merge(
            $this->customer->asArray(),
            $this->order->asArray(),
            $this->options->asArray(),
            [
                'digest'             => $digest,
                'transaction_type'   => $this->type,
                'authenticity_token' => $this->token,
            ]
        );
    }

    public function getBody(): array
    {
        $body = $this->asArray();

        $this->validateFormRequest($body);

        return $body;
    }

    public function getType(): string
    {
        return $this->type;
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

    public function getTimestamp(): float
    {
        return $this->timestamp;
    }
}
