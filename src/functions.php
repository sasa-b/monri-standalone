<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 11. 8. 2021.
 * Time: 16:33
 */

declare(strict_types=1);

namespace Sco\Monri {

    if (!function_exists('env')) {
        function env(string $key, mixed $default = null): mixed
        {
            $value = trim((string)getenv($key));

            return match ($value) {
                'true' => true,
                'false' => false,
                '1', '0' => (int)$value,
                'null' => $default,
                default => $value ?: $default,
            };
        }
    }

}
