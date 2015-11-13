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
     * Currently supporting up to 5 dimensions.
     *
     * @param string $string
     * @param string $value
     *
     * @return string|array
     */
    public function convertToArray($string, $value)
    {
        $result = [];
        $keys = explode('.', $string);
        $dimensions = count($keys);
        $supportedDimensions = 5;

        // check if dimensions is larger than the amount of allowed dimensions
        if ($dimensions > $supportedDimensions) {
            $message = 'Too many dimensions. Currently supporting up to ' . $supportedDimensions . ' dimensions.';
            throw new Exception($message);
        }

        if ($dimensions > 0) {
            $result = $this->appendValueToLastItem(
                $this->buildDimensionalArray($keys, $dimensions),
                $keys,
                $dimensions,
                $value
            );
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

    /**
     * Builds nested array with the specified keys.
     *
     * @param array $keys
     * @param int $dimensions
     *
     * @return array
     */
    private function buildDimensionalArray(array $keys, $dimensions)
    {
        $result = [];

        // add first dimension
        $result[$keys[0]] = [];

        for ($i = 1; $i < $dimensions; $i++) {
            $emptyItem = [$keys[$i] => []];

            if ($i === 1) { // two dimensions
                $result[$keys[$i - 1]] = $emptyItem;
            } elseif ($i === 2) { // three dimensions
                $result[$keys[$i - 2]][$keys[$i - 1]] = $emptyItem;
            } elseif ($i === 3) { // four dimensions
                $result[$keys[$i - 3]][$keys[$i - 2]][$keys[$i - 1]] = $emptyItem;
            } elseif ($i === 4) { // five dimensions
                $result[$keys[$i - 4]][$keys[$i - 3]][$keys[$i - 2]][$keys[$i - 1]] = $emptyItem;
            }
        }

        return $result;
    }

    /**
     * Append key value to the last nested item on the array.
     * Depending on the dimensions.
     *
     * @param array $results
     * @param array $keys
     * @param int $dimensions
     * @param string $value
     *
     * @return array
     */
    private function appendValueToLastItem($results, $keys, $dimensions, $value)
    {
        if ($dimensions === 1) {
            $result[$keys[$dimensions - 1]] = $value;
        } elseif ($dimensions === 2) {
            $result[$keys[$dimensions - 2]][$keys[$dimensions - 1]] = $value;
        } elseif ($dimensions === 3) {
            $result[$keys[$dimensions - 3]][$keys[$dimensions - 2]][$keys[$dimensions - 1]] = $value;
        } elseif ($dimensions === 4) {
            $result[$keys[$dimensions - 4]][$keys[$dimensions - 3]][$keys[$dimensions - 2]][$keys[$dimensions - 1]] = $value;
        } elseif ($dimensions === 5) {
            $result[$keys[$dimensions - 5]][$keys[$dimensions - 4]][$keys[$dimensions - 3]][$keys[$dimensions - 2]][$keys[$dimensions - 1]] = $value;
        }

        return $result;
    }
}
