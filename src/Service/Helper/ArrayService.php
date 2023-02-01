<?php

namespace App\Service\Helper;

use App\Service\Helper\StringService as Str;

class ArrayService
{
    /**
     * Returns a new array, with keys converted to camelCase.
     *
     * @param  array  $array
     *
     * @return array
     */
    public static function keysSnakeCaseToCamelCase(array $array): array
    {
        $newArray = [];
        foreach ($array as $key => $value) {
            $newKey = Str::snakeCaseToCamelCase($key);
            $newArray[$newKey] = $value;

            if (is_array($value)) {
                $newArray[$newKey] = self::keysSnakeCaseToCamelCase($value);
            }
        }

        return $newArray;
    }
}