<?php

namespace Nobox\LazyStrings;

use Exception;
use Nobox\LazyStrings\Str;
use Nobox\LazyStrings\Validator;

class LazyStrings
{
    const VERSION = '5.0.1';

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
     * Path where the strings array file will be stored.
     * Separated by locale.
     *
     * @var string
     */
    private $target;

    /**
     * Path where the strings in json format will be stored.
     *
     * @var string
     */
    private $backup;

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
     * String helper.
     *
     * @var Nobox\LazyStrings\Str
     */
    private $str;

    /**
     * Lazy validator.
     *
     * @var Nobox\LazyStrings\Validator
     */
    private $validator;

    /**
     * Initial setup.
     *
     * @param array $settings
     *
     * @return void
     */
    public function __construct(array $settings)
    {
        $this->csvUrl = $settings['url'];
        $this->sheets = $settings['sheets'];
        $this->target = $settings['target'];
        $this->backup = $settings['backup'];
        $this->nested = $settings['nested'];
        $this->str = new Str();
        $this->validator = new Validator();
        $this->metadata['refreshedBy'] = $_SERVER['DOCUMENT_ROOT'];
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
            // create locale directories
            $this->createDirectory($this->target . '/' . $locale);

            $localized = $this->localize($csvId);
            $strings[$locale] = $localized;

            // create strings in language file
            $stringsFile = $this->target . '/' . $locale . '/' . $this->languageFilename . '.php';
            $phpFormatted = '<?php return ' . var_export($localized, true) . ';';

            file_put_contents($stringsFile, $phpFormatted);

            // save strings in json format
            $this->backup($localized, $this->backup, $locale . '.json');
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
                if ($row[0] !== '' && $row[0] !== 'id') {
                    $id = $this->str->strip($row[0]);
                    $value = $row[1];

                    if ($this->nested) {
                        if ($this->str->hasDots($id)) {
                            $strings = array_replace_recursive($strings, $this->str->convertToArray($id, $value));
                        } else {
                            $strings[$id] = $value;
                        }
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
     * Save backup strings in JSON format
     *
     * @param array $strings
     * @param string $path
     * @param string $filename
     *
     * @return void
     */
    private function backup(array $strings, $path, $filename)
    {
        $stringsFile = $path . '/' . $filename;
        $jsonStrings = json_encode($strings, JSON_PRETTY_PRINT);

        $this->createDirectory($path);

        file_put_contents($stringsFile, $jsonStrings);
    }

    /**
     * Create the specified directory.
     * Check if it exists first.
     *
     * @param string $path
     *
     * @return void
     */
    private function createDirectory($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0777);
        }
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
}
