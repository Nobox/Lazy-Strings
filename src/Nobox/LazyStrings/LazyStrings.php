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
    private $route;

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
    private $metadata = array();

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
        $this->route = Config::get('lazy-strings' . $configDelimiter . 'strings_route');

        $this->file = $file;
        $this->localePath = app_path() . '/lang';

        $this->metadata['refreshed_by'] = Request::server('DOCUMENT_ROOT');
        $this->metadata['refreshed_on'] = date(DATE_RFC822, time());
    }

    /**
     * Generates the copy from the sheets
     * Language files and JSON for storage
     *
     * @return array
     **/
    public function generate()
    {
        $strings = array();

        // TODO: check that $this->csvUrl has the correct format?
        LazyValidator::validateSheets($this->sheets);

        foreach($this->sheets as $locale => $csvId) {
            // create locale directories (if any)
            $this->createDirectory($this->localePath . '/' . $locale);

            $localized = $this->localize($csvId);
            $strings[$locale] = $localized;

            // create strings in language file
            $stringsFile = $this->localePath . '/' . $locale . '/' . $this->languageFilename . '.php';
            $phpFormatted = '<?php return ' . var_export($localized, true) . ';';

            $this->file->put($stringsFile, $phpFormatted);

            // save strings in storage
            $this->backup($localized, $this->targetFolder, $locale . '.json');
        }

        return $strings;
    }

    /**
     * Parse provided csv document
     *
     * @param string $csvUrl Url of google doc
     * @return array
     **/
    private function parse($csvUrl)
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
     * Append sheet array by locale
     *
     * @param string/array $csvId Id of csv doc
     * @return array
     **/
    private function localize($csvId)
    {
        $strings = array();
        $urlPart = '&single=true&gid=';

        // if array is provided append the sheets to the same locale
        if (is_array($csvId)) {
            foreach($csvId as $id) {
                $parsed = $this->parse($this->csvUrl . $urlPart . $id);
                $strings = array_merge($strings, $parsed);
            }
        }

        // locale has a single sheet
        else {
            $strings = $this->parse($this->csvUrl . $urlPart . $csvId);
        }

        return $strings;
    }

    /**
     * Save backup strings in storage (JSON format)
     *
     * @param array $strings Parsed strings
     * @param string $folder Folder to store strings
     * @param string $file Strings filename
     * @return void
     **/
    private function backup($strings, $folder, $file)
    {
        $stringsPath = storage_path() . '/' . $folder;

        $this->createDirectory($stringsPath);

        $stringsFile = $stringsPath . '/' . $file;
        $jsonStrings = json_encode($strings, JSON_PRETTY_PRINT);

        $this->file->put($stringsFile, $jsonStrings);
    }

    /**
     * Create the specified directory.
     * Check if it exists first.
     *
     * @param string $path The folder path
     * @return void
     **/
    private function createDirectory($path)
    {
        if (!$this->file->exists($path)) {
            $this->file->makeDirectory($path, 0777);
        }
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
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Get string generation metadata
     *
     * @return array
     **/
    public function getMetadata()
    {
        return $this->metadata;
    }
}
