<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 11. 8. 2021.
 * Time: 23:07
 */

namespace Sco\Monri\Client\Request\Concerns;

use Sco\Monri\Client\Exception\MissingRequiredFieldException;

trait CanValidateXml
{
    private function validateXmlRequest(array $payload): void
    {
        foreach ([
            'order_number',
            'amount',
            'currency',
            'authenticity_token',
            'digest'
        ] as $field) {
            if (!array_key_exists($field, $payload)) {
                throw new MissingRequiredFieldException("Missing $field is required");
            }
        }
    }
}
