<?php namespace Nobox\LazyStrings\Generators;

use Illuminate\Filesystem\Filesystem;

class LazyConfigGenerator
{
    /**
     * Filesystem instance
     *
     * @var Filesystem
     **/
    protected $file;

    /**
     * Location of where the config file will be stored
     *
     * @var string
     **/
    protected $path;

    function __construct(Filesystem $file)
    {
        $this->file = $file;
        $this->path = app_path() . '/config/lazy-strings.php';
    }

    /**
     * Generate file from template
     *
     * @param type array $data
     * @return boolean
     **/
    public function create($data)
    {
        $template = $this->getTemplate($data);

        if (!$this->file->exists($this->path)) {
            return $this->file->put($this->path, $template);
        }

        return false;
    }

    /**
     * Checks if the config file already exists
     *
     * @return boolean
     **/
    public function exists()
    {
        return $this->file->exists($this->path);
    }

    /**
     * Get's a template file to generate
     *
     * @param array $data
     * @return string
     **/
    public function getTemplate($data)
    {
        $template = $this->file->get(__DIR__ . '/templates/config.txt');

        $search = array(
            '{{csv_url}}',
            '{{target_folder}}',
            '{{strings_route}}'
        );

        $replace = array(
            $data['doc_url'],
            $data['target_folder'],
            $data['strings_route']
        );

        return str_replace($search, $replace, $template);
    }
}
