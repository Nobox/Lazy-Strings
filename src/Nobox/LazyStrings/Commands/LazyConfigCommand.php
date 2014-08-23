<?php namespace Nobox\LazyStrings\Commands;

use Nobox\LazyStrings\Validators\LazyValidator;
use Nobox\LazyStrings\Generators\LazyConfigGenerator;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class LazyConfigCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lazy:config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initial configuration for LazyStrings.';

    /**
    * Example of a google doc URL
    *
    * @var string
    */
    protected $docURLExample = 'http://docs.google.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/export?format=csv';

    /**
     * Default Lazy Strings route
     *
     * @var string
     **/
    protected $defaultRoute = 'lazy/build-copy';

    /**
     * Default folder to store JSON files
     *
     * @var string
     **/
    protected $defaultTargetFolder = 'lazy-strings';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $selectedDocUrl = $this->ask('What\'s your google doc url? Ex. (' . $this->docURLExample . ') ');

        if (!LazyValidator::validateDocUrl($selectedDocUrl)) {
            $this->error('Please provide a valid google doc url.');
            $this->info('Please try again with "lazy:config"');
            return;
        }

        $this->info('Google doc url is: "' . $selectedDocUrl . '"');

        $selectedRoute = $this->ask('What\'s your default generation route? (Default is: "' . $this->defaultRoute . '")');

        if (is_null($selectedRoute)) {
            $selectedRoute = $this->defaultRoute;
        }

        $this->info('Selected route is: "' . $selectedRoute . '"');

        $selectedTargetFolder = $this->ask('What\'s your target folder name for storage? (Default is: "' . $this->defaultTargetFolder . '")');

        if (is_null($selectedTargetFolder)) {
            $selectedTargetFolder = $this->defaultTargetFolder;
        }

        $this->info('Selected target folder is: "' . $selectedTargetFolder . '"');

        // Generate config file.
        $configData = array(
            'doc_url' => $selectedDocUrl,
            'target_folder' => $selectedTargetFolder,
            'strings_route' => $selectedRoute
        );

        $configGenerator = new LazyConfigGenerator();
        $configGenerator->createConfig($configData);

        $this->info('Config file created.');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array();
    }

}
