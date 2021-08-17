<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 12. 8. 2021.
 * Time: 00:46
 */

namespace SasaB\Monri\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SasaB\Monri\Client\Client;
use SasaB\Monri\Client\Response\Xml;
use SasaB\Monri\Model\Customer;
use SasaB\Monri\Model\Customer\Address;
use SasaB\Monri\Model\Customer\Email;
use SasaB\Monri\Model\Customer\FullName;
use SasaB\Monri\Model\Customer\Phone;
use SasaB\Monri\Model\Order;
use SasaB\Monri\Model\Order\Amount;
use SasaB\Monri\Model\Order\Currency;
use SasaB\Monri\Model\Order\OrderInfo;
use SasaB\Monri\Model\Order\OrderNumber;
use SasaB\Monri\Monri;
use SasaB\Monri\Tests\MockXmlResponse;
use Symfony\Component\Process\Process;

class MonriTest extends TestCase
{
    private Client $client;
    private Monri $monri;
    private string $token;
    private string $key;

    private static Process $serverProcess;

    protected function setUp(): void
    {
        parent::setUp();

        $this->token = substr(bin2hex(random_bytes(40)), 0, 40);
        $this->key = 'xyz1234';

        $this->client = Client::test();
        $this->monri = Monri::testApi($this->token, $this->key);
        $this->monri->setClient($this->client);
    }

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        $tests = dirname(__DIR__);
        self::$serverProcess = Process::fromShellCommandline("php -S localhost:8005 $tests/server.php");
        self::$serverProcess->start();
        sleep(1);
        if (!self::$serverProcess->isRunning()) {
            throw new \Exception(self::$serverProcess->getErrorOutput());
        }
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();
        self::$serverProcess->stop();
    }

    public function test_it_can_authorize(): void
    {
        $customer = new Customer(
            new FullName('Michael Scott'),
            new Email('michale.scott@gmail.com'),
            new Phone('00387653245'),
            new Address('Dunder Mifflin 1', 'Scranton', '18503', 'USA')
        );

        $order = new Order(
            new OrderInfo('Paper clips'),
            new OrderNumber('0000001'),
            new Amount(1000),
            new Currency('USD')
        );

        $response = $this->monri->authorize($customer, $order);

        $request = $response->getRequest();
        $request->setKey($this->key);
        $request->setToken($this->token);

        $monriRedirectForm = <<<HTML
            <!DOCTYPE html>
            <html lang="en">
                <head>
                    <title>Monri authorize form</title>
                </head>
                <body>
                    <form>
                        <div>
                            <label>Credit card:</label>
                            <input type="text"> 
                        </div>
                    </form>
                </body>
            </html>
        HTML;

        $this->assertEquals($response->getBody(), $monriRedirectForm);
    }

    public function test_it_can_purchase(): void
    {
        $customer = new Customer(
            new FullName('Michael Scott'),
            new Email('michale.scott@gmail.com'),
            new Phone('00387653245'),
            new Address('Dunder Mifflin 1', 'Scranton', '18503', 'USA')
        );

        $order = new Order(
            new OrderInfo('Paper clips'),
            new OrderNumber('0000001'),
            new Amount(1000),
            new Currency('USD')
        );

        $response = $this->monri->purchase($customer, $order);

        $request = $response->getRequest();
        $request->setKey($this->key);
        $request->setToken($this->token);

        $monriRedirectForm = <<<HTML
            <!DOCTYPE html>
            <html lang="en">
                <head>
                    <title>Monri purchase form</title>
                </head>
                <body>
                    <form>
                        <div>
                            <label>Credit card:</label>
                            <input type="text"> 
                        </div>
                    </form>
                </body>
            </html>
        HTML;

        $this->assertEquals($response->getBody(), $monriRedirectForm);
    }

    public function test_it_can_capture(): void
    {
        $order = new Order(
            new OrderInfo('Paper clips'),
            new OrderNumber('0000001'),
            new Amount(1000),
            new Currency('USD')
        );

        $response = $this->monri->capture($order);

        $expected = Xml::fromString(MockXmlResponse::capture());
        $expected->setRequest($response->getRequest());

        $this->assertEquals($expected, $response);
    }

    public function test_it_can_refund(): void
    {
        $order = new Order(
            new OrderInfo('Paper clips'),
            new OrderNumber('0000001'),
            new Amount(1000),
            new Currency('USD')
        );

        $response = $this->monri->refund($order);

        $expected = Xml::fromString(MockXmlResponse::refund());
        $expected->setRequest($response->getRequest());

        $this->assertEquals($expected, $response);
    }

    public function test_it_can_void_transaction(): void
    {
        $order = new Order(
            new OrderInfo('Paper clips'),
            new OrderNumber('0000001'),
            new Amount(1000),
            new Currency('USD')
        );

        $response = $this->monri->void($order);

        $expected = Xml::fromString(MockXmlResponse::void());
        $expected->setRequest($response->getRequest());

        $this->assertEquals($expected, $response);
    }
}
