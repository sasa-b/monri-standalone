<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 15. 8. 2021.
 * Time: 11:06
 */

use SasaB\Monri\Client\TransactionType;
use Symfony\Component\HttpFoundation\Request;

require_once '../vendor/autoload.php';

function form_handler(Request $request)
{
    echo "Form Handler";
    parse_str($request->getContent(), $body);

    if ($body['transaction_type'] === TransactionType::AUTHORIZATION) {
        echo "Authorize transaction request \n";
    } else {
        echo "Purchase transaction request \n";
    }

    echo json_encode($body, JSON_PRETTY_PRINT);

    $success = [
        'acquirer'               => 'integration_acq',
        'amount'                 => $body['amount'],
        'approval_code'          => '629762',
        'authentication'         => 'Y',
        'cc_type'                => 'visa',
        'ch_full_name'           => $body['ch_full_name'],
        'currency'               => $body['currency'],
        'custom_params'          => '%7Ba%3Ab%2C+c%3Ad%7D',
        'enrollment'             => 'Y',
        'language'               => $body['language'],
        'masked_pan'             => '434179-xxx-xxx-0044',
        'number_of_installments' => $body['number_of_installments'] ?? '',
        'order_number'           => $body['order_number'],
        'response_code'          => '0000',
        'digest'                 => '575c64b2f5a0701997c8f9cfe4293706e88203cd911695ab747ce45830e4e3cbf71577c401e476988e4a4e1b0b5f3ecbc56277394df07fa51fbe05869d1b067a'
    ];

    $redirect = $request->getBaseUrl().'/success?'.http_build_query($success);

    header("Location: $redirect");
    http_response_code(301);
}

function capture_handler(Request $request, string $orderNumber)
{
}

function refund_handler(Request $request, string $orderNumber)
{
}

function void_handler(Request $request, string $orderNumber)
{
}

function router(Request $request): void
{
    $routes = [
        '/' => function () {
            echo "Hello World";
        },
        '/success' => function (Request $request) {
            echo "Form submit success \n";
            echo json_encode($request->query->all(), JSON_PRETTY_PRINT);
        },
        '/v2/form' => 'form_handler',
        '/transactions/:order_number/capture.xml' => 'capture_handler',
        '/transactions/:order_number/refund.xml' => 'refund_handler',
        '/transactions/:order_number/void.xml' => 'void_handler',
    ];

    $handler = $routes[$request->getPathInfo()] ?? null;

    if ($handler) {
        $handler($request);
        die;
    }

    foreach ($routes as $path => $handler) {
        $matches = [];
        $regex = str_replace(':order_number', '(\w+)', $path);
        if (preg_match("#^$regex$#", $request->getPathInfo(), $matches)) {
            echo $handler($request, $matches[1]);
            die;
        }
    }

    http_response_code(404);
    echo "404 - Not Found";
}

router(Request::createFromGlobals());
