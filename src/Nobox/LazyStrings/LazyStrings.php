<?php namespace Nobox\LazyStrings;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Filesystem\Filesystem;

use Nobox\LazyStrings\Validators\LazyValidator;

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
     * Strings generation route
     *
     * @var string
     **/
    private $stringsRoute;

    /**
     * Path to locale folder
     *
     * @var string
     **/
    private $localePath;

    /**
     * Filename for the generated language file.
     *
     * @var string
     **/
    private $languageFilename = 'app';

    /**
     * Some basic data when strings are generated
     *
     * @var array
     **/
    private $stringsMetadata = array();

    /**
     * Filesystem instance
     *
     * @var Filesystem
     **/
    private $file;

    /**
     * Setting values from config files
     * Initial setup
     *
     * @return void
     **/
    public function __construct(Filesystem $file)
    {
        // select correct config file (command line or package config)
        $configDelimiter = (Config::get('lazy-strings.csv_url') != null) ? '.' : '::';

        $this->csvUrl = Config::get('lazy-strings' . $configDelimiter . 'csv_url');
        $this->sheets = Config::get('lazy-strings' . $configDelimiter . 'sheets');
        $this->targetFolder = Config::get('lazy-strings' . $configDelimiter . 'target_folder');
        $this->stringsRoute = Config::get('lazy-strings' . $configDelimiter . 'strings_route');

        $this->file = $file;
        $this->localePath = app_path() . '/lang';

        $this->stringsMetadata['refreshed_by'] = Request::server('DOCUMENT_ROOT');
        $this->stringsMetadata['refreshed_on'] = date(DATE_RFC822, time());
    }

    /**
     * Generates the copy from the sheets
     * Language files and JSON for storage
     *
     * @return void
     **/
    public function generateStrings()
    {
        // TODO: check that $this->csvUrl has the correct format?
        LazyValidator::validateSheets($this->sheets);

        foreach($this->sheets as $locale => $csvId) {
            $localeStrings = array();

            // create locale directories (if any)
            if (!$this->file->exists($this->localePath . '/' . $locale)) {
                $this->file->makeDirectory($this->localePath . '/' . $locale, 0777);
            }

            // if array is provided append the sheets to the same locale
            if (is_array($csvId)) {
                foreach($csvId as $id) {
                    $csvStrings = $this->getCopyCsv($this->csvUrl . '&single=true&gid=' . $id);
                    $localeStrings = array_merge($localeStrings, $csvStrings);
                }
            }

            // locale has a single sheet
            else {
                $localeStrings = $this->getCopyCsv($this->csvUrl . '&single=true&gid=' . $csvId);
            }

            // create strings in language file
            $stringsFile = $this->localePath . '/' . $locale . '/' . $this->languageFilename . '.php';
            $formattedCsvStrings = '<?php return ' . var_export($localeStrings, true) . ';';

            $this->file->put($stringsFile, $formattedCsvStrings);

            // save strings in JSON for storage
            $this->jsonStrings($localeStrings,
                               $this->targetFolder, $locale . '.json');
        }
    }

    /**
     * Get strings from Google Doc in a pretty array
     *
     * @param string
     * @return array
     **/
    public function getCopyCsv($csvUrl)
    {
        $fileOpen = fopen($csvUrl, 'r');
        $strings = array();

        if ($fileOpen !== false) {
            while (($csvFile = fgetcsv($fileOpen, 1000, ',')) !== false) {
                if ($csvFile[0] != 'id') {
                    foreach($csvFile as $csvRow) {
                        if ($csvRow) {
                            $strings[$csvFile[0]] = $csvRow;
                        }
                    }
                }
            }

            fclose($fileOpen);
        }

        return $strings;
    }

    /**
     * Save strings in a JSON file for storage
     *
     * @param array
     * @param string
     * @param string
     * @return void
     **/
    private function jsonStrings($strings, $folder, $file)
    {
        $stringsPath = storage_path() . '/' . $folder;

        if (!$this->file->exists($stringsPath)) {
            $this->file->makeDirectory($stringsPath, 0777);
        }

        $stringsFile = $stringsPath . '/' . $file;
        $jsonStrings = json_encode($strings, JSON_PRETTY_PRINT);

        $this->file->put($stringsFile, $jsonStrings);
    }

    /**
     * Get the tabs of doc spreadsheet
     *
     * @return array
     **/
    public function getSheets()
    {
        return $this->sheets;
    }

    /**
     * Get the strings generation route name
     *
     * @return string
     **/
    public function getStringsRoute()
    {
        return $this->stringsRoute;
    }

    /**
     * Get string generation metadata
     *
     * @return array
     **/
    public function getStringsMetadata()
    {
        return $this->stringsMetadata;
    }
}
