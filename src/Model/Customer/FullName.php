<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 11. 8. 2021.
 * Time: 09:02
 */

namespace Sco\Monri\Model\Customer;

use Sco\Monri\Model\StringObject;
use Webmozart\Assert\Assert;

final class FullName extends StringObject
{
    public function __construct(string $fullName)
    {
        array_map(
            static fn ($name) => Assert::alnum($name, 'Invalid full_name value. Expected alphanumeric. Got: %s'),
            preg_split('/\s+/', $fullName) ?: ['']
        );
        Assert::lengthBetween($fullName, 3, 30, 'Invalid full_name length. Must be between 3-30 characters');
        parent::__construct($fullName);
    }
}
