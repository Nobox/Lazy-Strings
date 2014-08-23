<?php namespace Nobox\LazyStrings\Facades;

use Illuminate\Support\Facades\Facade;

class LazyStrings extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'lazy-strings'; }

}
