<?php

namespace Nobox\LazyStrings\Helpers;

use Exception;

class Str
{
    /**
     * Strip newlines and returns from string.
     *
     * @param string $string String to strip.
     *
     * @return string
     */
    public function strip($string)
    {
        return trim(preg_replace('/\s+/', ' ', $string));
    }

    /**
     * Convert dotted string to a nested array.
     * Append the value to the last nested array.
     *
     * @param string $string
     * @param string $value
     *
     * @return string|array
     */
    public function convertToArray($string, $value)
    {
        $keys = explode('.', $string);

        return $this->buildDimensionalArray($keys, count($keys), $value);
    }

    /**
     * Check if the string is separated by dots.
     *
     * @param string $string
     *
     * @return boolean
     */
    public function hasDots($string)
    {
        if (strpos($string, '.') === false) {
            return false;
        }

        return true;
    }

    /**
     * Builds nested array with the specified keys.
     *
     * @param array $keys
     * @param int $dimensions
     * @param string $value
     *
     * @return array
     */
    private function buildDimensionalArray(array $keys, $dimensions, $value)
    {
        $result = [];

        // add first dimension
        $result[$keys[0]] = [];
        $pointer = &$result[$keys[0]];

        for ($i = 1; $i < $dimensions; $i++) {
            if ($i === ($dimensions - 1)) {
                $pointer[$keys[$i]] = $value;
            } else {
                $pointer[$keys[$i]] = [];
                $pointer = &$pointer[$keys[$i]];
            }
        }

        return $result;
    }
}
