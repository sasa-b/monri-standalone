<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 11. 8. 2021.
 * Time: 22:58
 */

namespace Sco\Monri;

trait CanDigest
{
    public function digest(string $key, string $orderNumber, int $amount, string $currency): string
    {
        return hash('sha512', $key.$orderNumber.$amount.$currency);
    }
}
