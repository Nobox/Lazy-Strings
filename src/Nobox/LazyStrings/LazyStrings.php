<?php namespace Nobox\LazyStrings;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Config;

class LazyStrings {

    /**
     * Google doc url
     *
     * @var string
     **/
    private $csvUrl;

    /**
     * Tabs of doc spreadsheet
     *
     * @var array
     **/
    private $sheets;

    /**
     * Folder where the JSON strings will be stored
     *
     * @var string
     **/
    private $targetFolder;

    /**
     * Raw strings
     *
     * @var array
     **/
    private $strings = array();

    /**
     * Setting values from config files
     *
     * @return void
     **/
    public function __construct()
    {
        // select correct config file (command line or package config)
        $configDelimiter = (Config::get('lazystrings.csv_url') != NULL) ? '.' : '::';

        $this->csvUrl = Config::get('lazystrings' . $configDelimiter . 'csv_url');
        $this->sheets = Config::get('lazystrings' . $configDelimiter . 'sheets');
        $this->targetFolder = Config::get('lazystrings' . $configDelimiter . 'target_folder');

        $this->strings['refreshed_by'] = Request::server('DOCUMENT_ROOT');
        $this->strings['refreshed_on'] = date(DATE_RFC822, time());
    }

    /**
     * Generates the copy from the sheets
     *
     * @return void
     **/
    public function generateStrings()
    {
        if (count($this->sheets) > 0) {
            foreach($this->sheets as $key => $value) {
                $this->burnStrings($this->getCopyCsv($this->csvUrl . '&single=true&gid=' . $value),
                                   $this->targetFolder, $key . '.json');
            }
        }

        else {
            throw new \Exception('No sheets were provided.');
        }
    }

    /**
     * Creates the array for the lang file,
     * for the given JSON file
     * This should be added to Laravel internally!
     *
     * @param string
     * @return array
     **/
    public function createLangArray($filename)
    {
        $filePath = storage_path() . '/' . $this->targetFolder . '/' . $filename . '.json';
        $file = file_get_contents($filePath);

        return $this->objectToArray(json_decode($file));
    }

    /**
     * Get strings from Google Doc and converts them in JSON format
     *
     * @param string
     * @return object
     **/
    public function getCopyCsv($csvUrl)
    {
        $fileOpen = fopen($csvUrl, 'r');

        if ($fileOpen !== FALSE) {
            while (($csvFile = fgetcsv($fileOpen, 1000, ',')) !== FALSE) {
                if ($csvFile[0] != 'id') {
                    foreach($csvFile as $csvRow) {
                        if ($csvRow) {
                            $this->strings[$csvFile[0]] = $csvRow;
                        }
                    }
                }
            }

            fclose($fileOpen);

            // TODO: Echo the copy in a pretty way
            // print_r($this->strings);
            // echo '<br><br>';
        }

        return json_encode((object) $this->strings, JSON_PRETTY_PRINT) . PHP_EOL;
    }

    /**
     * Burn JSON strings in a JSON file
     *
     * @param object
     * @param string
     * @param string
     * @return void
     **/
    private function burnStrings($strings, $folder, $file)
    {
        $stringsPath = storage_path() . '/' . $folder;

        if (!file_exists($stringsPath)) {
            mkdir($stringsPath, 0777);
        }

        $stringsFile = fopen($stringsPath . '/' . $file, 'w');
        fwrite($stringsFile, $strings);
    }

    /**
     * Util function that converts an object to an array
     *
     * @param object
     * @return array
     **/
    private function objectToArray($data)
    {
        if (is_array($data) || is_object($data)) {
            $result = array();

            foreach ($data as $key => $value) {
                $result[$key] = $this->objectToArray($value);
            }

            return $result;
        }

        return $data;
    }
}
