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
    protected $configPath;

    function __construct(Filesystem $file)
    {
        $this->file = $file;
        $this->configPath = app_path() . '/config/lazy-strings.php';
    }

    /**
     * Generate file from template
     *
     * @param type array $data
     * @return boolean
     **/
    public function createConfig($data)
    {
        $template = $this->getConfigTemplate($data);

        if (!$this->configExists()) {
            return $this->file->put($this->configPath, $template);
        }

        return FALSE;
    }

    /**
     * Checks if the config file already exists
     *
     * @return boolean
     **/
    public function configExists()
    {
        return $this->file->exists($this->configPath);
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
}
