# Lazy Strings

Create localized strings from a Google Docs Spreadsheet.

[![Build Status](https://travis-ci.org/Nobox/Lazy-Strings.svg?branch=master)](https://travis-ci.org/Nobox/Lazy-Strings)

## Installation
Add Lazy Strings to your composer.json file.

```bash
composer require nobox/lazy-strings
```

## Usage
Create an instance of LazyStrings with the following settings.
```php
$lazyStrings = new LazyStrings([
    'url' => 'http://docs.google.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/export?format=csv',
    'sheets' => [
        'en' => 0,
    ],
    'target' => 'path/to/strings/folder',
    'backup' => 'path/to/strings/folder',
    'nested' => true
]);
```

And generate your strings with the `generate();` method.
```php
$lazyStrings->generate();
```

## Settings
Each setting key item is described below.

### `url`
Add the Google spreadsheet published url. This should be done with `File -> Publish to the web...`, replace `pubhtml` with `export?format=csv` at the end and use `http` on the url. Remember that this document must be available to anyone. Use `Public on the web` on your `Sharing settings`. If not, Lazy Strings won't be able to parse it.
```php
'url' => 'http://docs.google.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/export?format=csv'
```

### `sheets`
Here you'll specify all the sheets in your Google doc (if it's more than one) with their id, each separated by locale. Use an array if using more than one sheet for a locale. Example:
```php
'sheets' => [
    'en' => [0, 1626663029],
    'es' => 1329731586,
    'pt' => 1443604037
]
```
You can take the id from the spreadsheet using the `gid` variable from your Google doc url. For example, in this spreadsheet: https://docs.google.com/a/nobox.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/edit#gid=1626663029 the id is `1626663029`.

### `target`
Here you must specify a path where to store your translations.
```php
'target' => 'path/to/strings/folder'
```
Using these settings it will generate the following translation files in `path/to/strings/folder`.
```
├── folder/
│   ├── en/
│   │   ├── lazy.php
│   ├── es/
│   │   ├── lazy.php
│   ├── pt/
│   │   ├── lazy.php
```

### `backup`
Here you must specify a path where to store your translations in JSON format. More like a "backup" of your strings.
```php
'target' => 'path/to/strings/folder'
```
Using these settings it will generate the following translation files in `path/to/strings/folder`.
```
├── folder/
│   ├── es.json
│   ├── en.json
│   ├── pt.json
```

### `nested`
Specify whether or not you want your translations array to be nested.
```php
'nested' => true
```

If you use the nested setting as `true` your translations will look like something like this:
```php
<?php return array (
    'title' => 'Your page title',
    'tagline' => 'Your page tagline',
    'laravel' => 'PHP Framework',
    'header' => array (
        'hero' => array (
            'headline' => 'Hero headlines',
            'subject' => 'Main hero subject',
        ),
    ),
);
```

And like this with `false`.
```php
<?php return array (
    'title' => 'Your page title',
    'tagline' => 'Your page tagline',
    'laravel' => 'PHP Framework',
    'header.hero.headline' => 'Hero headlines',
    'header.hero.subject' => 'Main hero subject',
);
```

## How it works
Lazy Strings uses an `id => value` convention to access the copy, it generates a `lazy.php` file inside the specified language locale folder. You can see an example doc [here](https://docs.google.com/a/nobox.com/spreadsheets/d/1V_cHt5Fe4x9XwVepvlXB39sqKXD3xs_QbM-NppkrE4A/edit#gid=0).

| id                   | value             |
| -------------------- | ----------------- |
| title                | Your page title   |
| tagline              | Your page tagline |
| laravel              | PHP Framework     |
| header.hero.headline | Hero headlines    |
| header.hero.subject  | Main hero subject |

Using this example doc (with nested translations) you can access the first row like this:
```php
$locale = 'en';
$strings = require 'path/to/strings/folder/'.$locale.'/lazy.php';
echo $strings['title']; // Returns "Your page title"
```

## License
MIT
