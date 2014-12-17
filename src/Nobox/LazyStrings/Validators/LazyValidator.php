<?php namespace Nobox\LazyStrings\Validators;

use Exception;

class LazyValidator
{
    /**
     * Provided url must be a valid google doc url.
     *
     * Ex: http://docs.google.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/export?format=csv
     *
     * @param type string
     * @return boolean
     **/
    public static function validateDocUrl($url)
    {
        $pattern = '/http:\/\/docs\.google\.com\/spreadsheets\/d\/.*\/export\?format=csv/';

        if (is_null($url) || $url === '' || preg_match($pattern, $url) !== 1) {
            return false;
        }

        return true;
    }

    /**
     * Make sure that at least 1 sheet id is provided
     *
     * @param $sheets The sheets to validate
     * @return boolean
     **/
    public static function validateSheets($sheets)
    {
        if (count($sheets) === 0) {
            throw new Exception('No sheets were provided.');
        }
    }
}
