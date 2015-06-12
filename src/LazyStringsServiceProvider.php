<?php namespace Nobox\LazyStrings;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Nobox\LazyStrings\Commands\LazyDeployCommand;
use Nobox\LazyStrings\Helpers\Str;
use Nobox\LazyStrings\Validators\LazyValidator;

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
        $public = __DIR__ . '/../public';

        $this->loadViewsFrom($views, 'lazy-strings');

        $this->publishes([
            $config => config_path('lazy-strings.php'),
            $public => base_path('public/vendor/nobox/lazy-strings'),
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
            return new LazyStrings(new Filesystem, new LazyValidator, new Str);
        });

        // register `lazy:deploy` command
        $this->app->bind('command.lazy-deploy', function ($app) {
            return new LazyDeployCommand();
        });

        $this->commands('command.lazy-deploy');
    }
}
