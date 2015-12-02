# Changelog

#### v3.1.0 `2015-12-02`
- `Added`
    - Generated php language files are now nested arrays. Useful to loop through copy lines. Support is up to 5 dimension levels. See [#7](https://github.com/Nobox/Lazy-Strings/issues/7)

#### v3.0.0 `2015-09-04`
- `Change`
    - Namespace on which strings are stored has been changed. Strings are now accessible via `trans('lazy.foo');`

#### v2.0.1 `2015-09-03`
- `Fixed`
    - Force first and second column values to be the only ones used. See [#6](https://github.com/Nobox/Lazy-Strings/issues/6) for more details.
    - Improved and added more tests.

#### v2.0.0 `2015-07-24`
- `Change`
    - Default build route will always be under the `lazy` prefix.
    - Browser deployment is now using a controller. This will enable route caching.

#### v1.2.0 `2015-07-08`
- `Added`
    - Support for Laravel `5.1.*`

#### v1.1.1 `2015-06-12`
- `Fixed`
    - Newlines are stripped from string id's. See [#5](https://github.com/Nobox/Lazy-Strings/issues/5)
    - Internal refactoring.

#### v1.1.0 `2015-02-03`
- `Added`
    - Support for Laravel `5.0.*`
    - Removed command that generates config file, artisan takes better care of this.

#### v1.0.3 `2015-02-02`
- Last release to support Laravel `4.2.*`
- `Fixed`
    - Stricter validation of sheets url.
    - Internal refactor.

#### v1.0.2 `2014-11-11`
- `Fixed`
    - The existence of the config file is checked before being created.
    - Minor updates on documentation and tests.

#### v1.0.1 `2014-08-23`
- `Fixed`
    - Fixed bug where the configuration from package config folder was not loaded correctly.
    - Default storage folder is now called `lazy-strings`
    - Updates on documentation

#### v1.0.0 `2014-08-23`
- First release.
