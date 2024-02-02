<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 11. 8. 2021.
 * Time: 23:22
 */

namespace Sco\Monri\Model;

final class CardBrands
{
    public const VISA = 'visa';
    public const MASTER = 'master';
    public const MAESTRO = 'maestro';
    public const DINERS = 'diners';
    public const JCB = 'jcb';
    public const DISCOVER = 'discover';

    public static function all(): array
    {
        return [
            self::VISA,
            self::MASTER,
            self::MAESTRO,
            self::DINERS,
            self::JCB,
            self::DISCOVER,
        ];
    }

    public static function allAsString(): string
    {
        return implode(',', self::all());
    }
}
