<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 12. 8. 2021.
 * Time: 00:46
 */

namespace SasaB\Monri\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SasaB\Monri\Client\Serializer;
use SasaB\Monri\Client\Response\Xml;

class ResponseTest extends TestCase
{
    public function test_it_can_be_deserialized_from_xml()
    {
        $deserializer = new Serializer();

        $id = 845;
        $acquirer = 'rogach bank';
        $orderNumber = 'abcdef';
        $amount = 54321;
        $responseCode = '000';
        $approvalCode = '38860';
        $responseMessage = 'authorization OK';
        $refNumber = '898951263';
        $systan = '83704';
        $ccType = 'visa';
        $status = 'approved';
        $transType = 'capture';
        $createdAt = '2011-10-25T03:18:38+02:00';

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
            <transaction>
                <id type=\"integer\">$id</id>
                <acquirer>$acquirer</acquirer>
                <order-number>$orderNumber</order-number>
                <amount type=\"integer\">$amount</amount>
                <response-code>$responseCode</response-code>
                <approval-code>$approvalCode</approval-code>
                <response-message>$responseMessage</response-message>
                <reference-number>$refNumber</reference-number>
                <systan>$systan</systan>
                <cc-type>$ccType</cc-type>
                <status>$status</status>
                <transaction-type>$transType</transaction-type>
                <created-at type=\"datetime\">$createdAt</created-at>
            </transaction>";

        $response = $deserializer->deserializeXml($xml);

        $this->assertInstanceOf(Xml::class, $response);

        $this->assertSame($id, $response->getId());
        $this->assertSame($acquirer, $response->getAcquirer());
        $this->assertSame($orderNumber, $response->getOrderNumber());
        $this->assertSame($amount, $response->getAmount());
        $this->assertSame($responseCode, $response->getResponseCode());
        $this->assertSame($approvalCode, $response->getApprovalCode());
        $this->assertSame($responseMessage, $response->getResponseMessage());
        $this->assertSame($refNumber, $response->getReferenceNumber());
        $this->assertSame($systan, $response->getSystan());
        $this->assertSame($ccType, $response->getCcType());
        $this->assertSame($status, $response->getStatus());
        $this->assertSame($transType, $response->getTransactionType());
        $this->assertSame($createdAt, $response->getCreatedAt()->format(DATE_ATOM));
    }
}
