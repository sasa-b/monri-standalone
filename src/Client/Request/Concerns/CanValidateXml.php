<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 11. 8. 2021.
 * Time: 23:07
 */

namespace SasaB\Monri\Client\Request\Concerns;


use SasaB\Monri\Client\Exception\MissingRequiredFieldException;
use SasaB\Monri\Client\TransactionType;
use Webmozart\Assert\Assert;

trait CanValidateXml
{
    private function validateXmlRequest(array $payload): void
    {
        foreach ([
            'order_number',
            'amount',
            'currency',
            'transaction_type',
            'authenticity_token',
            'digest'
        ] as $field) {
            if (!array_key_exists($field, $payload)) {
                throw new MissingRequiredFieldException("Missing $field is required");
            }
        }

        Assert::inArray($payload['transaction_type'], [
            TransactionType::CAPTURE,
            TransactionType::REFUND,
            TransactionType::VOID
        ], 'Invalid transaction_type value. Expected capture, refund or void. Got: %s');
    }
}
