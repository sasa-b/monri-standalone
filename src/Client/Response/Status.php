<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 14. 8. 2021.
 * Time: 19:27
 */

namespace SasaB\Monri\Client\Response;


interface Status
{
    public const APPROVED = 'approved';
    public const DECLINED = 'declined';
    public const ERROR = 'invalid';
}
