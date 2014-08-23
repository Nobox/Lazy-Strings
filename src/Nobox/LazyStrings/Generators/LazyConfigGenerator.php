<?php namespace Nobox\LazyStrings\Generators;

use Illuminate\Filesystem\Filesystem;

class LazyConfigGenerator
{
    /**
     * File to generate
     *
     * @var Filesystem
     **/
    protected $file;

    function __construct()
    {
        $this->file = new Filesystem();
    }

    /**
     * Generate file from template
     *
     * @param type array $data
     * @return boolean
     **/
    public function createConfig($data)
    {
        $path = app_path() . '/config/lazy-strings.php';
        $template = $this->getConfigTemplate($data);

        if (!$this->file->exists($path)) {
            return $this->file->put($path, $template);
        }

        return FALSE;
    }

    /**
     * Get's a template file to generate
     *
     * @param array $data
     * @return string
     **/
    public function getConfigTemplate($data)
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

    /**
     * Set the Filesystem instance
     *
     * @param Filesystem $file
     * @return void
     **/
    public function setFilesystem($file)
    {
        $this->file = $file;
    }
}
