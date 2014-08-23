<?php

/*
|--------------------------------------------------------------------------
| LazyStrings Routes
|--------------------------------------------------------------------------
|
| Route used to generate strings, will display a message if
| the strings are generated succesfully.
|
*/

$stringsRoute = (is_array($this->app['lazy-strings']->getStringsRoute())) ? 'lazy/build-copy' : $this->app['lazy-strings']->getStringsRoute();

Route::get($stringsRoute, function () {
    $lazyStrings = $this->app['lazy-strings'];
    $lazyStrings->generateStrings();

    $metadata = $lazyStrings->getStringsMetadata();

    $viewData['refreshed_by'] = $metadata['refreshed_by'];
    $viewData['refreshed_on'] = $metadata['refreshed_on'];

    return View::make('lazy-strings::lazy', $viewData);
});
