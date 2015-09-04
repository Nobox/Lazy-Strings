# Lazy Strings

Laravel 5 package that creates localized strings from a Google Docs Spreadsheet.

[![Build Status](https://travis-ci.org/Nobox/Lazy-Strings.svg?branch=development)](https://travis-ci.org/Nobox/Lazy-Strings)

## Installation
Add Lazy Strings to your composer.json file.

```bash
composer require nobox/lazy-strings
```

## Notes on Laravel versions
If you're using Laravel `5.0` you should use version `v1.1.*`. This is the last version that supports Laravel `5.0`. Here's a rundown:

| Laravel Version     | LazyStrings Version to use    |
| ------------------- | ----------------------------- |
| 5.1                 | ^3.0                          |
| 5.0                 | 1.1.*                         |
| 4.0                 | dev-laravel-4                 |

## Register Lazy Strings
Register Lazy Strings service provider in the `providers` array located in `config/app.php`
```php
'providers' => [
    Nobox\LazyStrings\LazyStringsServiceProvider::class
]
```

## Publish configuration and assets
This package uses some basic configuration and pretty CSS and JS from bootstrap.
```bash
php artisan vendor:publish
```

## Configuration
Configuration is pretty simple, each configuration item is described below.

- `csv-url` Add the Google spreadsheet published url under `File -> Publish to the web...`, replace `pubhtml` with `export?format=csv` at the end and use `http` on the url. Remember that this document must be available to anyone. Use `Public on the web` on your `Sharing settings`. If not, Lazy Strings won't be able to parse it.
```php
'csv-url' => 'http://docs.google.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/export?format=csv'
```

- `target-folder` This folder will be in your `storage` folder and it just saves a backup of your strings in `JSON` format. By default is `lazy-strings`.
```php
'target-folder' => 'lazy-strings'
```

- `strings-route` This is the route that will be used to generate the strings. Visit `http://my-app.com/lazy/build-copy` and your strings will be updated. By default is `build-copy`. The route will always be under the `lazy` prefix.
```php
'strings-route' => 'build-copy'
```

- `sheets` Here you'll specify all the sheets in your Google doc (if it's more than one) with their id, each separated by locale. Use an array if using more than one sheet for a locale. Example:
```php
'sheets' => [
    'en' => [0, 1626663029],
    'es' => 1329731586,
    'pt' => 1443604037
]
```
You can take the id from the spreadsheet using the `gid` variable from your Google doc url. For example, in this spreadsheet: https://docs.google.com/a/nobox.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/edit#gid=1626663029 the id is `1626663029`.

## How it works
Lazy Strings uses an `id => value` convention to access the copy, it generates an `lazy.php` file inside the specified language locale folder. You can see an example doc here: https://docs.google.com/a/nobox.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/edit#gid=0.

| id            | value         |
| ------------- | ------------- |
| foo           | Hello!        |
| lazy          | LazyStrings   |
| laravel       | PHP Framework |

In this doc you can access the first row in your view like this:
```php
trans('lazy.foo'); // returns "Hello!"
```

Or in your controller like this:
```php
Lang::get('lazy.foo'); // returns "Hello!"
```

## Generate your strings
Each time you need to generate your strings just visit the specified `strings-route` in your configuration. The route will always be under the `lazy` prefix. For example: `http://my-app.com/lazy/build-copy`

You can also use the included artisan command `php artisan lazy:deploy`. It will do exactly the same. This is perfect when you're deploying your application with Forge or Envoyer.

## Still using Laravel 4?
Refer to the [laravel-4](https://github.com/Nobox/Lazy-Strings/tree/laravel-4) branch.
