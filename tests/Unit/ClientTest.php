<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 16. 8. 2021.
 * Time: 09:22
 */

namespace Sco\Monri\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sco\Monri\Client\Client;
use Sco\Monri\Client\Request\Authorize;
use Sco\Monri\Client\Request\Capture;
use Sco\Monri\Client\Request\Purchase;
use Sco\Monri\Client\Request\Refund;
use Sco\Monri\Client\Request\VoidTransaction;
use Sco\Monri\Client\Response\Xml;
use Sco\Monri\Model\Customer;
use Sco\Monri\Model\Customer\Address;
use Sco\Monri\Model\Customer\Email;
use Sco\Monri\Model\Customer\FullName;
use Sco\Monri\Model\Customer\Phone;
use Sco\Monri\Model\Order;
use Sco\Monri\Model\Order\Amount;
use Sco\Monri\Model\Order\Currency;
use Sco\Monri\Model\Order\OrderInfo;
use Sco\Monri\Model\Order\OrderNumber;
use Sco\Monri\Tests\MockXmlResponse;
use Symfony\Component\Process\Process;

class ClientTest extends TestCase
{
    private Client $client;
    private string $token;
    private string $key;

    private static Process $serverProcess;

    protected function setUp(): void
    {
        parent::setUp();

        $this->token = substr(bin2hex(random_bytes(40)), 0, 40);
        $this->key = 'xyz1234';

        $this->client = Client::test();
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

    public function test_it_can_send_authorize_request(): void
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

        $request = Authorize::for($customer, $order);
        $request->setToken($this->token);
        $request->setKey($this->key);

        $response =  $this->client->request($request);

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

    public function test_it_can_send_purchase_request(): void
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

        $request = Purchase::for($customer, $order);
        $request->setToken($this->token);
        $request->setKey($this->key);

        $response =  $this->client->request($request);

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

    public function test_it_can_send_capture_request(): void
    {
        $order = new Order(
            new OrderInfo('Paper clips'),
            new OrderNumber('0000001'),
            new Amount(1000),
            new Currency('USD')
        );

        $request = Capture::for($order);
        $request->setToken($this->token);
        $request->setKey($this->key);

        $response = $this->client->request($request);

        $expected = Xml::fromString(MockXmlResponse::capture());
        $request = $response->getRequest();
        $this->assertNotNull($request);
        $expected->forRequest($request);

        $this->assertEquals($expected, $response);
    }

    public function test_it_can_send_refund_request(): void
    {
        $order = new Order(
            new OrderInfo('Paper clips'),
            new OrderNumber('0000001'),
            new Amount(1000),
            new Currency('USD')
        );

        $request = Refund::for($order);
        $request->setToken($this->token);
        $request->setKey($this->key);

        $response = $this->client->request($request);

        $expected = Xml::fromString(MockXmlResponse::refund());
        $request = $response->getRequest();
        $this->assertNotNull($request);
        $expected->forRequest($request);

        $this->assertEquals($expected, $response);
    }

    public function test_it_can_send_void_request(): void
    {
        $order = new Order(
            new OrderInfo('Paper clips'),
            new OrderNumber('0000001'),
            new Amount(1000),
            new Currency('USD')
        );

        $request = VoidTransaction::for($order);
        $request->setToken($this->token);
        $request->setKey($this->key);

        $response = $this->client->request($request);

        $expected = Xml::fromString(MockXmlResponse::void());
        $request = $response->getRequest();
        $this->assertNotNull($request);
        $expected->forRequest($request);

        $this->assertEquals($expected, $response);
    }
}
