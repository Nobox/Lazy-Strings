<?php

namespace Nobox\LazyStrings;

use Exception;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Request;
use Nobox\LazyStrings\Helpers\Str;
use Nobox\LazyStrings\Validators\LazyValidator;

class LazyStrings
{
    const VERSION = '4.0.0';

    /**
     * Google doc url.
     *
     * @var string
     */
    private $csvUrl;

    /**
     * Tabs of doc spreadsheet.
     *
     * @var array
     */
    private $sheets;

    /**
     * Folder where the JSON strings will be stored.
     *
     * @var string
     */
    private $targetFolder;

    /**
     * Strings generation route.
     *
     * @var string
     */
    private $route;

    /**
     * Path to locale folder.
     *
     * @var string
     */
    private $localePath;

    /**
     * Filename for the generated language file.
     *
     * @var string
     */
    private $languageFilename = 'lazy';

    /**
     * Some basic data when strings are generated.
     *
     * @var array
     */
    private $metadata = [];

    /**
     * Filesystem implementation.
     *
     * @var Illuminate\Filesystem\Filesystem
     */
    private $file;

    /**
     * String helper.
     *
     * @var Nobox\LazyStrings\Helpers\Str
     */
    private $str;

    /**
     * Lazy validator.
     *
     * @var Nobox\LazyStrings\Validators\LazyValidator
     */
    private $validator;

    /**
     * Initial setup. Setting values from config files.
     *
     * @param Illuminate\Filesystem\Filesystem $file Filesystem implementation.
     * @param Nobox\LazyStrings\Validators\LazyValidator $validator Lazy validator.
     * @param Nobox\LazyStrings\Helpers\Str $str String helper.
     *
     * @return void
     */
    public function __construct(Filesystem $file, LazyValidator $validator, Str $str)
    {
        $this->csvUrl = config('lazy-strings.csv-url');
        $this->sheets = config('lazy-strings.sheets');
        $this->targetFolder = config('lazy-strings.target-folder');
        $this->route = config('lazy-strings.strings-route');

        $this->file = $file;
        $this->str = $str;
        $this->validator = $validator;
        $this->localePath = base_path() . '/resources/lang';

        $this->metadata['refreshedBy'] = Request::server('DOCUMENT_ROOT');
        $this->metadata['refreshedOn'] = date(DATE_RFC822, time());
    }

    /**
     * Generates the copy from the sheets.
     * Language files and JSON for storage.
     *
     * @return array
     */
    public function generate()
    {
        // validate doc url and sheets
        if (!$this->validator->validateDocUrl($this->csvUrl)) {
            throw new Exception('Provided doc url is not valid.');
        }

        $this->validator->validateSheets($this->sheets);

        $strings = [];

        foreach ($this->sheets as $locale => $csvId) {
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
     * Parse provided csv document.
     *
     * @param string $csvUrl Url of google doc.
     *
     * @return array
     */
    private function parse($csvUrl)
    {
        $csvFile = fopen($csvUrl, 'r');
        $strings = [];

        if ($csvFile !== false) {
            while (($row = fgetcsv($csvFile, 1000, ',')) !== false) {
                if ($row[0] !== 'id') {
                    $id = $this->str->strip($row[0]);
                    $value = $row[1];

                    if ($this->str->hasDots($id)) {
                        $strings = array_replace_recursive($strings, $this->str->convertToArray($id, $value));
                    } else {
                        $strings[$id] = $value;
                    }
                }
            }

            fclose($csvFile);
        }

        return $strings;
    }

    /**
     * Append sheet array by locale.
     * If array is provided append the sheets to the same locale.
     *
     * @param string/array $csvId Id of csv doc.
     *
     * @return array
     */
    private function localize($csvId)
    {
        $strings = [];
        $urlPart = '&single=true&gid=';

        if (is_array($csvId)) {
            foreach ($csvId as $id) {
                $parsed = $this->parse($this->csvUrl . $urlPart . $id);
                $strings = array_merge($strings, $parsed);
            }
        } else {
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
     *
     * @return void
     */
    private function backup(array $strings, $folder, $file)
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
     * @param string $path The folder path.
     *
     * @return void
     */
    private function createDirectory($path)
    {
        if (!$this->file->exists($path)) {
            $this->file->makeDirectory($path, 0777);
        }
    }

    /**
     * Get the tabs of doc spreadsheet.
     *
     * @return array
     */
    public function getSheets()
    {
        return $this->sheets;
    }

    /**
     * Get the strings generation route name.
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Get string generation metadata.
     *
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Set the google doc url.
     *
     * @param string $url The google doc url.
     *
     * @return void
     */
    public function setCsvUrl($url)
    {
        $this->csvUrl = $url;
    }

    /**
     * Set the tabs of doc spreadsheet.
     *
     * @param array $sheets The array of sheets id's.
     *
     * @return void
     */
    public function setSheets(array $sheets)
    {
        $this->sheets = $sheets;
    }
}
