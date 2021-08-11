<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 10. 8. 2021.
 * Time: 12:11
 */

namespace SasaB\Monri\Client;


interface TransactionType
{
    public const AUTHORIZATION = 'authorize';

    public const PURCHASE = 'purchase';

    public const CAPTURE = 'capture';

    public const REFUND = 'refund';

    public const VOID = 'void';
}
