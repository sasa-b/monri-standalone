<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 15. 8. 2021.
 * Time: 11:06
 */

use Sco\Monri\Client\TransactionType;
use Sco\Monri\Tests\MockXmlResponse;
use Symfony\Component\HttpFoundation\Request;

require_once dirname(__DIR__).'/vendor/autoload.php';

function form_handler(Request $request): void
{
    echo "Form Handler";
    parse_str($request->getContent(), $body);

    if ($body['transaction_type'] === TransactionType::AUTHORIZATION) {
        echo "Authorize transaction request \n";
    } else {
        echo "Purchase transaction request \n";
    }

    $redirect = $request->getBaseUrl().'/form?'.http_build_query($body);

    header("Location: $redirect");
}

function capture_handler(Request $request, string $orderNumber): void
{
    header('Content-Type: application/xml');
    echo MockXmlResponse::capture();
}

function refund_handler(Request $request, string $orderNumber): void
{
    header('Content-Type: application/xml');
    echo MockXmlResponse::refund();
}

function void_handler(Request $request, string $orderNumber): void
{
    header('Content-Type: application/xml');
    echo MockXmlResponse::void();
}

function success_callback_handler(Request $request): void
{
    $success = [
        'acquirer'               => 'integration_acq',
        'amount'                 => 1000,
        'approval_code'          => '629762',
        'authentication'         => 'Y',
        'cc_type'                => 'visa',
        'ch_full_name'           => 'John Doe',
        'currency'               => 'USD',
        'custom_params'          => '%7Ba%3Ab%2C+c%3Ad%7D',
        'enrollment'             => 'Y',
        'language'               => 'en',
        'masked_pan'             => '434179-xxx-xxx-0044',
        'number_of_installments' => '',
        'order_number'           => '0000001',
        'response_code'          => '0000',
        'digest'                 => '575c64b2f5a0701997c8f9cfe4293706e88203cd911695ab747ce45830e4e3cbf71577c401e476988e4a4e1b0b5f3ecbc56277394df07fa51fbe05869d1b067a'
    ];
}

function router(Request $request): void
{
    $routes = [
        '/' => function () {
            echo "Hello World";
        },
        '/form' => function (Request $request) {
            $type = $request->query->all()['transaction_type'];
            echo <<<HTML
                <!DOCTYPE html>
                <html lang="en">
                    <head>
                        <title>Monri $type form</title>
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
        },
        '/v2/form' => 'form_handler',
        '/transactions/:order_number/capture.xml' => 'capture_handler',
        '/transactions/:order_number/refund.xml' => 'refund_handler',
        '/transactions/:order_number/void.xml' => 'void_handler',
    ];

    $handler = $routes[$request->getPathInfo()] ?? null;

    if ($handler) {
        // @phpstan-ignore-next-line
        $handler($request);
        die;
    }

    foreach ($routes as $path => $handler) {
        $matches = [];
        $regex = str_replace(':order_number', '(\w+)', $path);
        if (preg_match("#^$regex$#", $request->getPathInfo(), $matches)) {
            $handler($request, $matches[1]);
            die;
        }
    }

    http_response_code(404);
    echo "404 - Not Found";
}

router(Request::createFromGlobals());
