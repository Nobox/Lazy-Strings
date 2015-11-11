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
