## Release Notes

### Bootstrap 1.1

Released on 22-Nov-2014

New features:
* Ability to monitor multiple files for cache invalidation of styles
* In addition to `LocalSettings.php` also monitor `composer.lock`: This will
  invalidate styles on every `composer update`
* Use relative paths to enable installation of the extension in other
  directories then the standard `.../extensions`

Other changes:
* Improve documentation

### Bootstrap 1.0.1

Released on 16-Sep-2014

Fixes:
* Fix autoloading so MW core can load it's own Less compiler

Other changes:
* Switch less compiler vendor and raise minimum required Less compiler version

### Bootstrap 1.0

Released on 15-Aug-2014

Complete rework of extension

Changes:
* Switch to a standard-compliant Less compiler
* Introduce Composer installation
* Improve error handling of the caching mechanism
* Add tests

### Bootstrap 0.1

Released on 13-Oct-13

First working version
* loads the Bootstrap 3 framework
* caching of styles per user
* basic error handling
