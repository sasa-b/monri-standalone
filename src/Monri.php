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
use SasaB\Monri\Client\Response;
use SasaB\Monri\Client\TransactionType;

use SasaB\Monri\Model\Order;
use SasaB\Monri\Model\Customer;

final class Monri
{
    private Client $client;
    private Options $options;

    public function __construct(Client $client, Options $options)
    {
        $this->client = $client;
        $this->options = $options;
    }

    public static function api(?string $token = null, ?string $key = null, ?string $url = null): self
    {
        return new self(
            Client::default($token, $key, $url),
            Options::default()
        );
    }

    public function authorize(Customer $customer, Order $order, ?Options $options = null): Response
    {
        $this->client->transaction(TransactionType::AUTHORIZATION, array_merge($customer->asArray(), $order->asArray()));
    }

    public function purchase(?Options $options = null): Response
    {
        $this->client->transaction(TransactionType::PURCHASE);
    }

    public function capture(?Options $options = null): Response
    {
        $this->client->transaction(TransactionType::CAPTURE);
    }

    public function refund(?Options $options = null): Response
    {
        $this->client->transaction(TransactionType::REFUND);
    }

    public function void(?Options $options = null): Response
    {
        $this->client->transaction(TransactionType::VOID);
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
}
