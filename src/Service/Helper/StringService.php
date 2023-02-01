<?php

namespace App\Service\Helper;

class StringService
{
    /**
     * Returns a new, converted to camelCase string.
     *
     * @param  string  $string
     *
     * @return string
     */
    public static function snakeCaseToCamelCase(string $string): string
    {
        return lcfirst(implode('', array_map('ucfirst', explode('_', $string))));
    }
}