<?php

/*
|--------------------------------------------------------------------------
| LazyStrings Routes
|--------------------------------------------------------------------------
|
| Route use to generate strings, will display a message if
| the strings are generated succesfully.
|
*/

Route::get($this->app['lazy-strings']->getStringsRoute(), function () {
    $lazyStrings = $this->app['lazy-strings'];
    $lazyStrings->generateStrings();
});
