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

$routeName = $this->app['lazy-strings']->getRoute();

Route::get($routeName, function () {
    $lazyStrings = $this->app['lazy-strings'];
    $lazyStrings->generate();

    $metadata = $lazyStrings->getMetadata();

    $viewData['refreshedBy'] = $metadata['refreshedBy'];
    $viewData['refreshedOn'] = $metadata['refreshedOn'];

    return View::make('lazy-strings::lazy', $viewData);
});
