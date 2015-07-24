<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Google spreadsheet public URL
    |--------------------------------------------------------------------------
    | File gets published automatically each time you make changes.
    |
    */

    'csv-url' => 'http://docs.google.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/export?format=csv',

    /*
    |--------------------------------------------------------------------------
    | Google spreadsheets
    |--------------------------------------------------------------------------
    |
    | Specify here all the spreadsheets in your Google Doc.
    | Format: 'locale' => sheetId
    |
    */

    'sheets' => array(
        'en' => 0
    ),

    /*
    |--------------------------------------------------------------------------
    | Target folder
    |--------------------------------------------------------------------------
    |
    | Folder in which the JSON files with the strings will be stored.
    |
    */

    'target-folder' => 'lazy-strings',

    /*
    |--------------------------------------------------------------------------
    | LazyStrings route
    |--------------------------------------------------------------------------
    |
    | Route that will be used to generate the strings.
    |
    */

    'strings-route' => 'build-copy'

);
