# Lazy Strings

Laravel 4 package that creates localized strings from a Google Docs Spreadsheet.

[![Build Status](https://travis-ci.org/Nobox/Lazy-Strings.svg?branch=master)](https://travis-ci.org/Nobox/Lazy-Strings)

## Installation
Add Lazy Strings to your composer.json file.

```json
"require": {
  "nobox/lazy-strings": "dev-master"
}
```

Run the following composer command to install Lazy Strings.
```bash
composer update
```

## Register Lazy Strings
Register Lazy Strings service provider in the `providers` array located in `app/config/app.php`
```php
'providers' => array(
    // ...

    'Nobox\LazyStrings\LazyStringsServiceProvider'
)
```

## Publish assets
This package uses some pretty CSS and JS from bootstrap.
```bash
php artisan asset:publish nobox/lazy-strings
```

## Create configuration file
There are two ways to create your configuration file. You can use the included artisan command `php artisan lazy:config` to generate the configuration file with a series of questions or use the package config file with `php artisan config:publish nobox/lazy-strings`. Each configuration item is described below.

- `csv_url` Add the Google spreadsheet published url under `File -> Publish to the web...`, replace `pubhtml` with `export?format=csv` at the end and use `http` on the url. Remember that this document must be available to anyone. Use `Public on the web` on your `Sharing settings`. If not, Lazy Strings won't be able to parse it.
```php
'csv_url' => 'http://docs.google.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/export?format=csv'
```

- `target_folder` This folder will be in your `app/storage` path and it just saves a backup of your strings in `JSON` format. By default is `lazy-strings`.
```php
'target_folder' => 'lazy-strings'
```

- `strings_route` This is the route that will be used to generate the strings. Visit `http://my-app.com/lazy/build-copy` and your strings will be updated. By default is `lazy/build-copy`.
```php
'strings_route' => 'lazy/build-copy'
```

- `sheets` Here you'll specify all the sheets in your Google doc (if it's more than one) with their id, each separated by locale. Use an array if using more than one sheet for a locale. Example:
```php
'sheets' => array(
    'en' => array(0, 1626663029),
    'es' => 1329731586,
    'pt' => 1443604037
)
```
You can take the id from the spreadsheet using the `gid` variable from your Google doc url. For example, in this spreadsheet: https://docs.google.com/a/nobox.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/edit#gid=1626663029 the id is `1626663029`.

## How it works
Lazy Strings uses an `id => value` convention to access the copy, it generates an `app.php` file inside the specified language locale folder. You can see an example doc here: https://docs.google.com/a/nobox.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/edit#gid=0.

| id            | value         |
| ------------- | ------------- |
| foo           | Hello!        |
| lazy          | LazyStrings   |
| laravel       | PHP Framework |

In this doc you can access the first row in your view like this:
```php
trans('app.foo') // returns "Hello!"
```

Or in your controller like this:
```php
Lang::get('app.foo'); // returns "Hello!"
```

## Generate your strings
Each time you need to generate your strings just visit the specified `strings_route` in your configuration. For example: `http://my-app.com/lazy/build-copy`

You can also use the included artisan command `php artisan lazy:deploy`. It will do exactly the same. This is perfect when you're deploying your application with Forge.
