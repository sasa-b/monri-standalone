<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 6. 8. 2021.
 * Time: 15:29
 */

declare(strict_types=1);

namespace SasaB\Monri\Client;

use SasaB\Monri\Client\Request\Authorize;
use SasaB\Monri\Client\Request\Concerns\CanValidateForm;
use SasaB\Monri\Client\Request\Concerns\CanValidateXml;
use SasaB\Monri\Client\Request\Form;
use SasaB\Monri\Client\Request\Purchase;
use SasaB\Monri\Client\Request\Xml;
use SasaB\Monri\Client\Response\Deserializer;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Webmozart\Assert\Assert;

final class Client
{
    use CanValidateForm;
    use CanValidateXml;

    public const TEST_URL = 'https://ipgtest.monri.com';
    public const PROD_URL = 'https://ipg.monri.com';

    private const PATH = [
        TransactionType::AUTHORIZATION => '/v2/form',
        TransactionType::PURCHASE      => '/v2/form',
        TransactionType::CAPTURE       => '/transactions/:order_number/capture.xml',
        TransactionType::REFUND        => '/transactions/:order_number/refund.xml',
        TransactionType::VOID          => '/transactions/:order_number/void.xml'
    ];

    private HttpClientInterface $client;
    private Deserializer $deserializer;

    public function __construct(HttpClientInterface $client, Deserializer $deserializer)
    {
        $this->client = $client;
        $this->deserializer = $deserializer;
    }

    public static function new(string $url = self::TEST_URL): self
    {
        Assert::inArray($url, [self::TEST_URL, self::PROD_URL], 'Invalid url. Expected '.self::TEST_URL.' or '.self::PROD_URL.'. Got: %s');
        return new self(HttpClient::createForBaseUri($url), new Deserializer());
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
        if (in_array(get_class($request), [Authorize::class, Purchase::class], true)) {
            $headers = [
                'content-type' => 'application/x-www-form-urlencoded'
            ];
        } else {
            $headers = [
                'content-type' => 'application/xml'
            ];
        }

        $body = $request->getBody();

        $path = str_replace(':order_number', $body['order_number'], self::PATH[$request->getType()]);

        $response = $this->client->request('POST', $path, [
            'body'    => $body,
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

        $path = str_replace(':order_number', $payload['order_number'], self::PATH[$type]);

        try {

            $response = $this->client->request('POST', $path, [
                'body'    => $payload,
                'headers' => $headers
            ]);

            if (in_array($type, [
                TransactionType::CAPTURE,
                TransactionType::REFUND,
                TransactionType::VOID
            ], true)) {
                return $this->deserializer->deserializeXml($response->getContent());
            }

        } catch (HttpExceptionInterface $e) {
            $response = $e->getResponse()->getContent(false);
        }


        $response = Response::fromArray($response);
        $response->setRequest($request);
        return $response;
    }
}
