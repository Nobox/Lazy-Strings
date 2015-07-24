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

Route::group(['namespace' => 'Nobox\LazyStrings\Http\Controllers', 'prefix' => 'lazy'], function () {
    $routeName = $this->app['lazy-strings']->getRoute();

    Route::get($routeName, [
        'as' => 'lazy.deploy',
        'uses' => 'LazyStringsController@deploy'
    ]);
});
