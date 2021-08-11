<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 6. 8. 2021.
 * Time: 15:29
 */

declare(strict_types=1);

namespace SasaB\Monri\Client;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Webmozart\Assert\Assert;

final class Client
{
    private const TEST_URL = 'https://ipgtest.monri.com';
    private const PATH = '/v2/form';

    private HttpClientInterface $client;
    private Config $config;

    public function __construct(HttpClientInterface $client, Config $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    public static function default(string $token = null, string $key = null, string $url = null): self
    {
        return new self(HttpClient::create(), Config::default($token, $key, $url));
    }

    public function transaction(string $type, array $payload, array $headers = []): Response
    {
        $payload = array_merge($payload, ['transaction_type' => $type]);

        $url = rtrim($this->config->get('url', self::TEST_URL), '/').self::PATH;

        $response = $this->client->request('POST', $url, [
            'body'    => $this->validate($payload),
            'headers' => $headers
        ])->toArray();

        return Response::fromArray($response);
    }

    public function validate(array $payload): array
    {
        Assert::inArray($payload['transaction_type'], [
            TransactionType::AUTHORIZATION,
            TransactionType::PURCHASE,
            TransactionType::CAPTURE,
            TransactionType::REFUND,
            TransactionType::VOID
        ], 'Invalid transaction_type value. Expected authorization, purchase, capture, refund or void. Got: %s');

        Assert::inArray($payload['currency'], [
            'USD', 'EUR', 'BAM', 'HRK'
        ], 'Invalid currency value. Expected USD, EUR, BAM or HRK. Got: %s');

        Assert::inArray($payload['language'], [
            'en', 'es', 'ba', 'hr'
        ], 'Invalid language value. Expected en, es, ba or hr. Got: %s');

        return $payload;
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    public function setConfig(Config $config): void
    {
        $this->config = $config;
    }
}
