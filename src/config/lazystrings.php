<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Google spreadsheet public URL
    |--------------------------------------------------------------------------
    | Ex: http://docs.google.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/export?format=csv
    | File gets published automatically each time you make changes.
    |
    */

    'csv_url' => '',

    /*
    |--------------------------------------------------------------------------
    | Google spreadsheets
    |--------------------------------------------------------------------------
    |
    | Specify here all the spreadsheets in your Google Doc.
    | Format: 'locale' => sheet_id
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

    'target_folder' => 'lazystrings',

    /*
    |--------------------------------------------------------------------------
    | LazyStrings route
    |--------------------------------------------------------------------------
    |
    | Route that will be used to generate the strings.
    |
    */

    'strings_route' => 'lazy/build-copy'

);
