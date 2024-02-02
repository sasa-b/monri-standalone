<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 6. 8. 2021.
 * Time: 15:29
 */

declare(strict_types=1);

namespace Sco\Monri\Client;

use Sco\Monri\Client\Request\Authorize;
use Sco\Monri\Client\Request\Concerns\CanValidateForm;
use Sco\Monri\Client\Request\Concerns\CanValidateXml;
use Sco\Monri\Client\Request\Form;
use Sco\Monri\Client\Request\Purchase;
use Sco\Monri\Client\Request\Xml as XmlRequest;
use Sco\Monri\Client\Response\Html;
use Sco\Monri\Client\Response\Xml;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Webmozart\Assert\Assert;

final readonly class Client
{
    use CanValidateForm;
    use CanValidateXml;

    public const TEST_URL = 'https://ipgtest.monri.com';
    public const PROD_URL = 'https://ipg.monri.com';
    private const LOCAL_URL = 'http://localhost:8005';

    private const PATH = [
        TransactionType::AUTHORIZATION => '/v2/form',
        TransactionType::PURCHASE      => '/v2/form',
        TransactionType::CAPTURE       => '/transactions/:order_number/capture.xml',
        TransactionType::REFUND        => '/transactions/:order_number/refund.xml',
        TransactionType::VOID          => '/transactions/:order_number/void.xml'
    ];

    public function __construct(
        private HttpClientInterface $client,
        private Serializer $serializer,
    ) {}

    public static function new(string $url, Serializer $serializer): self
    {
        Assert::inArray($url, [self::TEST_URL, self::PROD_URL, self::LOCAL_URL], 'Invalid url. Expected '.self::TEST_URL.' or '.self::PROD_URL.'. Got: %s');

        return new self(HttpClient::createForBaseUri($url), $serializer);
    }

    public static function dev(): self
    {
        return self::new(self::TEST_URL, new Serializer());
    }

    public static function prod(): self
    {
        return self::new(self::PROD_URL, new Serializer());
    }

    public static function test(): self
    {
        return self::new(self::LOCAL_URL, new Serializer());
    }

    /**
     * @throws HttpExceptionInterface
     */
    public function request(Request $request): Response
    {
        $type = $request->getType();
        $body = $request->getBody();

        $path = self::PATH[$type];

        if (in_array(get_class($request), [Authorize::class, Purchase::class], true)) {
            $headers = [
                'content-type' => 'application/x-www-form-urlencoded'
            ];
        } else {
            $headers = [
                'content-type' => 'application/xml'
            ];
            $path = str_replace(':order_number', $body['transaction']['order_number'], $path);
        }

        return $this->sendRequest($request->getType(), $path, $body, $headers, $request);
    }

    /**
     * @throws HttpExceptionInterface
     */
    public function transaction(string $type, array $payload): Response
    {
        if (in_array($type, [TransactionType::AUTHORIZATION, TransactionType::PURCHASE], true)) {
            $headers = [
                'content-type' => 'application/x-www-form-urlencoded'
            ];
            $payload['transaction_type'] = $type;
            $this->validateFormRequest($payload);
            $request = Form::fromArray($payload);
            $request->setToken($payload['authenticity_token']);
        } else {
            $headers = [
                'content-type' => 'application/xml'
            ];
            $this->validateXmlRequest($payload);
            $request = XmlRequest::fromArray(array_merge($payload, ['transaction_type' => $type]));
        }

        $path = str_replace(':order_number', $payload['order_number'], self::PATH[$type]);

        return $this->sendRequest($type, $path, $payload, $headers, $request);
    }

    /**
     * @throws HttpExceptionInterface
     */
    private function sendRequest(string $type, string $path, array $payload, array $headers, Request $request): Response
    {
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
                $response = Xml::fromString($response->getContent());
            } else {
                $response = Html::fromCurl($response);
            }
            $response->forRequest($request);
        } catch (HttpExceptionInterface $e) {
            $response = $e->getResponse();
            if ($response->getStatusCode() !== 406) {
                throw $e;
            }
            $response = $this->serializer->deserializeXml($response->getContent(false));
        }
        return $response;
    }
}
