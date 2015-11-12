<?php

namespace Nobox\LazyStrings\Helpers;

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
     * Append the value to the last nested array
     *
     * @param string $string
     * @param string $value
     *
     * @return string|array
     */
    public function convertToArray($string, $value)
    {
        $result = [];
        $keys = explode('.', $string); // this, is, something
        $dimensions = count($keys);
        $supportedDimensions = 3;

        // check if dimensions is larger than the amount
        // of allowed dimensions, if so, throw an exception.
        // Support up to 5 dimensions? (one.two.three.four.five)

        if ($dimensions > 0) {
            // add first dimension
            $result[$keys[0]] = [];

            for ($i = 1; $i < $dimensions; $i++) {
                $emptyItem = [$keys[$i] => []];

                if ($i === 1) {
                    $result[$keys[$i - 1]] = $emptyItem;
                } elseif ($i === 2) {
                    $result[$keys[$i - 2]][$keys[$i - 1]] = $emptyItem;
                }
            }

            // append value to last item
            if ($dimensions === 1) {
                $result[$keys[$dimensions - 1]] = $value;
            } elseif ($dimensions === 2) {
                $result[$keys[$dimensions - 2]][$keys[$dimensions - 1]] = $value;
            } elseif ($dimensions === 3) {
                $result[$keys[$dimensions - 3]][$keys[$dimensions - 2]][$keys[$dimensions - 1]] = $value;
            }
        }

        return $result;
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
}
