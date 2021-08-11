<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 11. 8. 2021.
 * Time: 16:33
 */

declare(strict_types=1);

namespace SasaB\Monri {

    if (!function_exists('env')) {
        function env(string $key, $default = null)
        {
            $value = trim((string) getenv($key));

            switch ($value) {
                case 'true':
                    return true;
                case 'false':
                    return false;
                case '1':
                case '0':
                    return (int) $value;
                case 'null':
                    return $default;
            }

            return $value ?: $default;
        }
    }

}
