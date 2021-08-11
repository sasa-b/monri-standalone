<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 6. 8. 2021.
 * Time: 15:29
 */

declare(strict_types=1);

namespace SasaB\Monri\Client;

use SasaB\Monri\Client\Request\Concerns\CanValidateForm;
use SasaB\Monri\Client\Request\Concerns\CanValidateXml;
use SasaB\Monri\Client\Request\Form;
use SasaB\Monri\Client\Request\Xml;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Webmozart\Assert\Assert;

final class Client
{
    use CanValidateForm;
    use CanValidateXml;

    public const TEST_URL = 'https://ipgtest.monri.com';
    public const PROD_URL = 'https://ipg.monri.com';

    private const PATH = '/v2/form';

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public static function new(string $url = self::TEST_URL): self
    {
        Assert::inArray($url, [self::TEST_URL, self::PROD_URL], 'Invalid url. Expected '.self::TEST_URL.' or '.self::PROD_URL.'. Got: %s');
        return new self(HttpClient::createForBaseUri($url));
    }

    public static function dev(): self
    {
        return self::new();
    }

    public static function prod(): self
    {
        return self::new(self::PROD_URL);
    }

    public function request(Request $request): Response
    {
        if (in_array($request->getType(), [TransactionType::AUTHORIZATION, TransactionType::PURCHASE], true)) {
            $headers = [
                'content-type' => 'application/x-www-form-urlencoded'
            ];
        } else {
            $headers = [
                'content-type' => 'application/xml'
            ];
        }

        $response = $this->client->request('POST', self::PATH, [
            'body'    => $request->getBody(),
            'headers' => $headers
        ])->toArray();

        $response = Response::fromArray($response);
        $response->setRequest($request);
        return $response;
    }

    public function transaction(string $type, array $payload): Response
    {
        if (in_array($type, [TransactionType::AUTHORIZATION, TransactionType::PURCHASE], true)) {
            $headers = [
                'content-type' => 'application/x-www-form-urlencoded'
            ];
            $this->validateFormRequest($payload);
            $payload['transaction_type'] = $type;
            $request = Form::fromArray($payload);
        } else {
            $headers = [
                'content-type' => 'application/xml'
            ];
            $this->validateXmlRequest($payload);
            $request = Xml::fromArray(array_merge($payload, ['transaction_type' => $type]));
        }

        $response = $this->client->request('POST', self::PATH, [
            'body'    => $payload,
            'headers' => $headers
        ])->toArray();

        $response = Response::fromArray($response);
        $response->setRequest($request);
        return $response;
    }
}
