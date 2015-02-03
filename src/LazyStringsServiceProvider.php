<?php namespace Nobox\LazyStrings;

use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;

use Nobox\LazyStrings\Commands\LazyDeployCommand;

class LazyStringsServiceProvider extends ServiceProvider
{

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $views = __DIR__ . '/../views';
        $config = __DIR__ . '/../config/lazy-strings.php';
        $routes = __DIR__ . '/routes.php';

        $this->loadViewsFrom($views, 'lazy-strings');

        $this->publishes([
            $config => config_path('lazy-strings.php'),
        ]);

        include $routes;
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        // add LazyStrings class to app container
        $this->app->bind('lazy-strings', function ($app) {
            return new LazyStrings(new Filesystem);
        });

        // register `lazy:deploy` command
        $this->app->bind('command.lazy-deploy', function ($app) {
            return new LazyDeployCommand();
        });

        $this->commands('command.lazy-deploy');
    }
}
