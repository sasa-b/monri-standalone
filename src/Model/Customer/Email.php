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

final class Email extends StringObject
{
    public function __construct(string $email)
    {
        Assert::email($email, 'Invalid email value. Expected a valid email address. Got: %s');
        Assert::lengthBetween($email, 3, 30, 'Invalid email length. Must be between 3-100 characters');
        parent::__construct($email);
    }
}
