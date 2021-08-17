<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 10. 8. 2021.
 * Time: 12:12
 */

declare(strict_types=1);

namespace SasaB\Monri;

use SasaB\Monri\Client\Client;
use SasaB\Monri\Client\Request;
use SasaB\Monri\Client\Response;
use SasaB\Monri\Client\TransactionType;

use SasaB\Monri\Model\Order;
use SasaB\Monri\Model\Customer;
use Webmozart\Assert\Assert;

final class Monri
{
    use CanDigest;

    private Client $client;
    private Options $options;
    private string $token;
    private string $key;

    public function __construct(Client $client, Options $options, string $token, string $key)
    {
        $this->client = $client;
        $this->options = $options;
        $this->setToken($token);
        $this->key = $key;
    }

    public static function api(string $token = null, string $key = null, Options $options = null): self
    {
        $token ??= env('MONRI_TOKEN');
        $key ??= env('MONRI_KEY');
        $options ??= Options::default();

        Assert::notNull($token, 'Invalid token value. Expected alphanumeric value. Got: null');
        Assert::notNull($key, 'Invalid key value. Expected alphanumeric value. Got: null');

        return new self(Client::prod(), $options, $token, $key);
    }

    public static function testApi(string $token = null, string $key = null, Options $options = null): self
    {
        $token ??= env('MONRI_TOKEN');
        $key ??= env('MONRI_KEY');
        $options ??= Options::default();

        Assert::notNull($token, 'Invalid token value. Expected alphanumeric value. Got: null');
        Assert::alnum($token, 'Invalid token value. Expected alphanumeric value. Got: %s');
        Assert::notNull($key, 'Invalid key value. Expected alphanumeric value. Got: null');
        Assert::alnum($key, 'Invalid key value. Expected alphanumeric value. Got: %s');

        return new self(Client::dev(), $options, $token, $key);
    }

    public function transaction(Request $request): Response
    {
        if ($request->getKey() === '') {
            $request->setKey($this->key);
        }

        if ($request->getToken() === '') {
            $request->setToken($this->token);
        }

        return $this->client->request($request);
    }

    public function authorize(Customer $customer, Order $order, ?Options $options = null): Response
    {
        return $this->client->transaction(TransactionType::AUTHORIZATION, $this->buildFormBody($customer, $order, $options ?? Options::default()));
    }

    public function purchase(Customer $customer, Order $order, ?Options $options = null): Response
    {
        return $this->client->transaction(TransactionType::PURCHASE, $this->buildFormBody($customer, $order, $options ?? Options::default()));
    }

    public function capture(Order $order): Response
    {
        return $this->client->transaction(TransactionType::CAPTURE, $this->buildXmlBody($order));
    }

    public function refund(Order $order): Response
    {
        return $this->client->transaction(TransactionType::REFUND, $this->buildXmlBody($order));
    }

    public function void(Order $order): Response
    {
        return $this->client->transaction(TransactionType::VOID, $this->buildXmlBody($order));
    }

    private function buildFormBody(Customer $customer, Order $order, Options $options): array
    {
        $digest = $this->digest(
            $this->key,
            $order->getNumber()->value(),
            $order->getAmount()->value(),
            $order->getCurrency()->value()
        );

        return array_merge(
            $customer->asArray(),
            $order->asArray(),
            $options->asArray(),
            ['authenticity_token' => $this->token, 'digest' => $digest]
        );
    }

    private function buildXmlBody(Order $order): array
    {
        $digest = $this->digest(
            $this->key,
            $order->getNumber()->value(),
            $order->getAmount()->value(),
            $order->getCurrency()->value()
        );

        return array_merge($order->asArray(), ['authenticity_token' => $this->token, 'digest' => $digest]);
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    public function getOptions(): Options
    {
        return $this->options;
    }

    public function setOptions(Options $options): void
    {
        $this->options = $options;
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
