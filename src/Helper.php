<?php

declare(strict_types=1);

namespace App;

class Helper
{

    public static function toCamelCase(string $string): string
    {
        $string = str_replace('-', ' ', strtolower($string));

        $string = lcfirst(str_replace(' ', '', ucwords($string)));

        return $string;
    }
}
