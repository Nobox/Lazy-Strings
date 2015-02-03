<?php namespace Nobox\LazyStrings\Commands;

use Nobox\LazyStrings\LazyStrings;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class LazyDeployCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lazy:deploy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy LazyStrings.';

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
        $this->info('Deploying...');

        $lazyStrings = $this->laravel['lazy-strings'];
        $lazyStrings->generate();

        $this->info('Lazy Strings is now deployed.');
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