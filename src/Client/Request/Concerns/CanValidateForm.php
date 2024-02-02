<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 11. 8. 2021.
 * Time: 23:07
 */

namespace Sco\Monri\Client\Request\Concerns;

use Sco\Monri\Client\Exception\MissingRequiredFieldException;
use Sco\Monri\Client\TransactionType;
use Webmozart\Assert\Assert;

trait CanValidateForm
{
    private function validateFormRequest(array $payload): void
    {
        foreach ([
            'ch_full_name',
            'ch_email',
            'ch_phone' ,
            'ch_address',
            'ch_city',
            'ch_zip',
            'ch_country',
            'order_info',
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
            TransactionType::AUTHORIZATION,
            TransactionType::PURCHASE,
        ], 'Invalid transaction_type value. Expected authorization or purchase. Got: %s');

        Assert::inArray($payload['currency'], [
            'USD', 'EUR', 'BAM', 'HRK'
        ], 'Invalid currency value. Expected USD, EUR, BAM or HRK. Got: %s');

        Assert::inArray($payload['language'], [
            'en', 'es', 'ba', 'hr'
        ], 'Invalid language value. Expected en, es, ba or hr. Got: %s');
    }
}
