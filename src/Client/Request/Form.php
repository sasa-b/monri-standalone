<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 11. 8. 2021.
 * Time: 22:40
 */

declare(strict_types=1);

namespace Sco\Monri\Client\Request;

use Sco\Monri\Arrayable;
use Sco\Monri\CanDigest;
use Sco\Monri\Client\Exception\MissingRequiredFieldException;
use Sco\Monri\Client\Request;
use Sco\Monri\Client\Request\Concerns\CanValidateForm;
use Sco\Monri\Client\TransactionType;
use Sco\Monri\Model\Customer;
use Sco\Monri\Model\Order;
use Sco\Monri\Options;
use Webmozart\Assert\Assert;

abstract class Form implements Request, Arrayable
{
    use CanValidateForm;
    use CanDigest;

    protected Customer $customer;
    protected Order $order;
    protected Options $options;
    protected string $token = '';
    protected string $key = '';
    protected float $timestamp;

    protected function __construct(Customer $customer, Order $order, Options $options)
    {
        $this->customer = $customer;
        $this->order = $order;
        $this->options = $options;
        $this->timestamp = microtime(true);
    }

    abstract public function getType(): string;

    public static function fromArray(array $data): Form
    {
        if ($data['transaction_type'] === TransactionType::PURCHASE) {
            return new Purchase(
                Customer::fromArray($data),
                Order::fromArray($data),
                Options::fromArray($data)
            );
        }
        return new Authorize(
            Customer::fromArray($data),
            Order::fromArray($data),
            Options::fromArray($data)
        );
    }

    public function asArray(): array
    {
        if ($this->key === '') {
            throw MissingRequiredFieldException::merchantKey($this);
        }

        if ($this->token === '') {
            throw MissingRequiredFieldException::authenticityToken($this);
        }

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
                'transaction_type'   => $this->getType(),
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
