<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 15. 8. 2021.
 * Time: 15:21
 */

namespace Sco\Monri\Tests;

abstract class MockXmlResponse
{
    public static function capture(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
        <transaction>
            <id type="integer">845</id>
            <acquirer>rogach bank</acquirer>
            <order-number>abcdef</order-number>
            <amount type="integer">54321</amount>
            <response-code>000</response-code>
            <approval-code>38860</approval-code>
            <response-message>authorization OK</response-message>
            <reference-number>898951263</reference-number>
            <systan>83704</systan>
            <cc-type>visa</cc-type>
            <status>approved</status>
            <transaction-type>capture</transaction-type>
            <created-at type="datetime">2011-10-25T03:18:38+02:00</created-at>
        </transaction>';
    }

    public static function refund(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
        <transaction>
            <id type="integer">845</id>
            <acquirer>rogach bank</acquirer>
            <order-number>abcdef</order-number>
            <amount type="integer">54321</amount>
            <response-code>000</response-code>
            <approval-code>38860</approval-code>
            <response-message>authorization OK</response-message>
            <reference-number>898951263</reference-number>
            <systan>83704</systan>
            <cc-type>visa</cc-type>
            <status>approved</status>
            <transaction-type>refund</transaction-type>
            <created-at type="datetime">2011-10-25T03:18:38+02:00</created-at>
        </transaction>';
    }

    public static function void(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
        <transaction>
            <id type="integer">845</id>
            <acquirer>rogach bank</acquirer>
            <order-number>abcdef</order-number>
            <amount type="integer">54321</amount>
            <response-code>000</response-code>
            <approval-code>38860</approval-code>
            <response-message>authorization OK</response-message>
            <reference-number>898951263</reference-number>
            <systan>83704</systan>
            <cc-type>visa</cc-type>
            <status>approved</status>
            <transaction-type>void</transaction-type>
            <created-at type="datetime">2011-10-25T03:18:38+02:00</created-at>
        </transaction>';
    }
}
