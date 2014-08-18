<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Google spreadsheet public URL
    |--------------------------------------------------------------------------
    |
    | Remember to publish the file each time you make changes.
    | File -> Publish to the web...
    |
    */

    'csv_url' => '',

    /*
    |--------------------------------------------------------------------------
    | Google spreadsheets
    |--------------------------------------------------------------------------
    |
    | Specify here all the spreadsheets in your Google Doc.
    | Format: 'file_name' => sheet_number
    |
    */

    'sheets' => array(),

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
    | Strings route
    |--------------------------------------------------------------------------
    |
    | Route that will be used to generate the JSON files.
    |
    */

    'strings_route' => 'lazy/build-copy'

);
