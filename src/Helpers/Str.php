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
}
