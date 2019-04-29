## Release Notes

### Bootstrap 4.0

Released on 29-Apr-2019

Changes:
* Use Bootstrap 4
* Depends on MW 1.27+, PHP 5.6+

### Bootstrap 1.3.0

Released on 15-Jan-2019

* Drop dependency on oyejorge/less.php compiler, just depend on the one used by
  MediaWiki core instead 
* Raise minimum MW version to 1.27+

### Bootstrap 1.2.4

Released on 29-Apr-2018

Fixes:
* Set local base path from `$wgExtensionsDirectory`

### Bootstrap 1.2.3

Released on 30-Nov-2017

Fixes:
* Use correct local base path when is `$wgScriptPath` empty, i.e. when MW is
  installed at the root level of the domain.

### Bootstrap 1.2.2

Released on 26-Nov-2017

Fixes:
*  Unbreak the extension when vendor/ is blocked<br>
   (concerns MediaWiki 1.27.4/1.28.3/1.29.2/1.30 and up, see https://phabricator.wikimedia.org/T180231)

### Bootstrap 1.2.1

Released on 06-Mar-2017

Fixes:
* Correct version number reported on Special:Version

### Bootstrap 1.2

Released on 05-Mar-2017

Fixes:
* Do not use oyejorge/less.php 1.7.0.13, it's incompatible with PHP 5.3

Other changes:
* Change required package: twitter/bootstrap -> twbs/bootstrap

### Bootstrap 1.1.5

Released on 27-Jan-2016

Fixes:
* Use standard MW config vars to find the twitter/bootstrap directory

### Bootstrap 1.1.4

Released on 15-Jan-2016

Fixes:
* Fix missing position for Bootstrap Styles Resource Loader module
* Switch less compiler to the one MW core uses

### Bootstrap 1.1.3

Released on 03-Mar-2015

Fixes:
* Fix error: Cannot use object of type Closure as array in SetupAfterCache.php

### Bootstrap 1.1.2

Released on 02-Dec-2014

Fixes:
* Fix removing of Less compiler class from autoloader

### Bootstrap 1.1.1

Released on 01-Dec-2014

Fixes:
* Fix removing of lessc compiler class from autoloader:
  This led to a server error on MW from 1.25 onwards when it was trying to
  compile one of its own less files.

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
