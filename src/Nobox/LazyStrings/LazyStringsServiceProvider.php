<?php namespace Nobox\LazyStrings;

use Illuminate\Support\ServiceProvider;
use Nobox\LazyStrings\Commands\LazyConfigCommand;

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
            return new LazyStrings();
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

        $this->commands('command.lazyconfig');
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
