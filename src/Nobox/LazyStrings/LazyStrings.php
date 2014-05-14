<?php namespace Nobox\LazyStrings;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Config;

class LazyStrings {

    private $csv_url, $sheets, $target_folder;
    private $strings = array();

    /**
     * Setting values from config files
     * TODO: Maybe the package should use a default configuration,
     * and the app configuration should overwrite it?
     *
     * @return void
     */
    public function __construct()
    {
        $this->csv_url = Config::get('lazystrings.csv_url');
        $this->sheets = Config::get('lazystrings.sheets');
        $this->target_folder = Config::get('lazystrings.target_folder');

        $this->strings['refreshed_by'] = Request::server('DOCUMENT_ROOT');
        $this->strings['refreshed_on'] = date(DATE_RFC822, time());
    }

    /**
     * Generates the copy from the sheets
     *
     * @return void
     */
    public function generateStrings()
    {
        if (count($this->sheets) > 0) {
            foreach($this->sheets as $key => $value) {
                $this->burnStrings($this->getCopyCSV($this->csv_url . '&single=true&gid=' . $value),
                                   $this->target_folder, $key . '.json');
            }
        }

        else {
            // TODO: throw error to specify sheets?
        }
    }

    /**
     * Creates the array for the lang file,
     * for the given JSON file
     *
     * @return array
     */
    public function createLangArray($filename)
    {
        $file_path = storage_path() . '/' . $this->target_folder . '/' . $filename . '.json';
        $file = file_get_contents($file_path);

        if (!$file) {
            // TODO: throw error that the file was not found?
        }

        return $this->objectToArray(json_decode($file));
    }

    /**
     * Get strings from Google Doc and converts them in JSON format
     *
     * @return object
     */
    private function getCopyCSV($csv_url)
    {
        $file_open = fopen($csv_url, 'r');

        if ($file_open !== FALSE) {
            while (($csv_file = fgetcsv($file_open)) !== FALSE) {
                if ($csv_file[0] != 'id') {
                    foreach($csv_file as $csv_row) {
                        if ($csv_row) {
                            $this->strings[$csv_file[0]] = $csv_row;
                        }
                    }
                }
            }

            fclose($file_open);

            // TODO: Echo the copy in a pretty way
            print_r($this->strings);
            echo '<br><br>';
        }

        else {
            // TODO: Throw failed to open file exception?
        }

        return json_encode((object) $this->strings, JSON_PRETTY_PRINT) . PHP_EOL;
    }

    /**
     * Burn JSON strings in a JSON file
     *
     * @return void
     */
    private function burnStrings($strings, $folder, $file)
    {
        $strings_path = storage_path() . '/' . $folder;

        if (!file_exists($strings_path)) {
            mkdir($strings_path, 0777);
        }

        $strings_file = fopen($strings_path . '/' . $file, 'w');
        fwrite($strings_file, $strings);
    }

    /**
     * Util function that converts an object to an array
     *
     * @return array
     */
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
