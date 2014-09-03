<?php namespace Nobox\LazyStrings;

use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;

use Nobox\LazyStrings\Commands\LazyConfigCommand;
use Nobox\LazyStrings\Commands\LazyDeployCommand;

class LazyStringsServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events
     *
     * @return void
     */
    public function boot()
    {
        $this->package('nobox/lazy-strings');

        include __DIR__ . '/../../routes.php';
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // add LazyStrings class to app container
        $this->app['lazy-strings'] = $this->app->share(function($app) {
            return new LazyStrings(new Filesystem);
        });

        // add class alias
        $this->app->booting(function() {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('LazyStrings', 'Nobox\LazyStrings\LazyStrings');
        });

        // register `lazy:config` command
        $this->app['command.lazyconfig'] = $this->app->share(function($app) {
            return new LazyConfigCommand();
        });

        // register `lazy:deploy` command
        $this->app['command.lazydeploy'] = $this->app->share(function($app) {
            return new LazyDeployCommand();
        });

        $this->commands('command.lazyconfig');
        $this->commands('command.lazydeploy');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('lazy-strings');
    }

}
