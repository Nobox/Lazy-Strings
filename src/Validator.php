<?php

namespace Nobox\LazyStrings;

use Exception;

class Validator
{
    /**
     * Provided url must be a valid google doc url.
     *
     * @param string $url Url of google doc.
     *
     * @return boolean
     */
    public function validateDocUrl($url)
    {
        $pattern = '/http:\/\/docs\.google\.com\/spreadsheets\/d\/.*\/export\?format=csv/';

        if (is_null($url) || $url === '' || preg_match($pattern, $url) !== 1) {
            return false;
        }

        return true;
    }

    /**
     * Make sure that at least 1 sheet id is provided.
     *
     * @param array $sheets The sheets to validate.
     *
     * @return boolean
     */
    public function validateSheets(array $sheets)
    {
        if (count($sheets) === 0) {
            throw new Exception('No sheets were provided.');
        }
    }
}
