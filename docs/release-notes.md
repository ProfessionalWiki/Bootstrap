## Release Notes

### MediaWiki Bootstrap 4.6.2

Released on June 6, 2023.

* Allow using SCSS 3.x dependency (thanks @robis24)

### MediaWiki Bootstrap 4.6.1

Released on June 4, 2023.

* Upgraded Bootstrap from 4.6.1 to 4.6.2 (thanks @malberts)

### MediaWiki Bootstrap 4.6.0

Released on March 30, 2022.

* Upgraded Bootstrap from 4.6.0 to 4.6.1 (thanks @malberts)

### MediaWiki Bootstrap 4.5.0

Released on April 10, 2021.

* Upgraded Bootstrap from 4.3.1 to 4.6.0 (thanks @malberts)
* Upgraded Popper to 1.16.1 (thanks @malberts)

### MediaWiki Bootstrap 4.4.3

Released on April 1, 2021.

* Fixed issue about the registered version

### MediaWiki Bootstrap 4.4.2

Released on April 1, 2021.

* Fixed another tooltip issue (thanks @pierreboutet)

### MediaWiki Bootstrap 4.4.1

Released on December 15, 2020.

* Fixed tooltip issue (thanks @pierreboutet)

### MediaWiki Bootstrap 4.4.0

Released on September 4, 2020.

* Restored 4.2.1 release

### MediaWiki Bootstrap 4.3.x

Broken version, do not use

### MediaWiki Bootstrap 4.2.1

Released on June 10, 2020.

* Fixed 4.2.0 regression that broke calls to `addCacheTriggerFile` from `LocalSettings.php`

### MediaWiki Bootstrap 4.2.0

Released on May 7, 2020.

* Added support for installation without Composer (thanks @cicalese)

### MediaWiki Bootstrap 4.1.0

Released on April 21, 2020.

* Fixed loading of style sheets and JS files when using the extension without the Chameleon skin

### MediaWiki Bootstrap 4.0

Released on April 29, 2019.

* Use Bootstrap 4
* Depends on MW 1.27+, PHP 5.6+

### MediaWiki Bootstrap 1.3.0

Released on January 15, 2019,

* Drop dependency on oyejorge/less.php compiler, just depend on the one used by
  MediaWiki core instead
* Raise minimum MW version to 1.27+

### MediaWiki Bootstrap 1.2.4

Released on April 29, 2018.

Fixes:
* Set local base path from `$wgExtensionsDirectory`

### MediaWiki Bootstrap 1.2.3

Released on November 30, 2017.

Fixes:
* Use correct local base path when is `$wgScriptPath` empty, i.e. when MW is
  installed at the root level of the domain.

### MediaWiki Bootstrap 1.2.2

Released on November 26, 2017.

Fixes:
*  Unbreak the extension when vendor/ is blocked<br>
   (concerns MediaWiki 1.27.4/1.28.3/1.29.2/1.30 and up, see https://phabricator.wikimedia.org/T180231)

### MediaWiki Bootstrap 1.2.1

Released on March 6, 2017.

Fixes:
* Correct version number reported on Special:Version

### MediaWiki Bootstrap 1.2.0

Released on March 5, 2017.

Fixes:
* Do not use oyejorge/less.php 1.7.0.13, it's incompatible with PHP 5.3

Other changes:
* Change required package: twitter/bootstrap -> twbs/bootstrap

### MediaWiki Bootstrap 1.1.5

Released on January 27, 2016.

Fixes:
* Use standard MW config vars to find the twitter/bootstrap directory

### MediaWiki Bootstrap 1.1.4

Released on January 15, 2016.

Fixes:
* Fix missing position for Bootstrap Styles Resource Loader module
* Switch less compiler to the one MW core uses

### MediaWiki Bootstrap 1.1.3

Released on March 3, 2015.

Fixes:
* Fix error: Cannot use object of type Closure as array in SetupAfterCache.php

### MediaWiki Bootstrap 1.1.2

Released on December 2, 2014.

Fixes:
* Fix removing of Less compiler class from autoloader

### MediaWiki Bootstrap 1.1.1

Released on December 1, 2014.

Fixes:
* Fix removing of lessc compiler class from autoloader:
  This led to a server error on MW from 1.25 onwards when it was trying to
  compile one of its own less files.

### MediaWiki Bootstrap 1.1.0

Released on November 22, 2014.

New features:
* Ability to monitor multiple files for cache invalidation of styles
* In addition to `LocalSettings.php` also monitor `composer.lock`: This will
  invalidate styles on every `composer update`
* Use relative paths to enable installation of the extension in other
  directories then the standard `.../extensions`

Other changes:
* Improve documentation

### MediaWiki Bootstrap 1.0.1

Released on September 16, 2014.

Fixes:
* Fix autoloading so MW core can load it's own Less compiler

Other changes:
* Switch less compiler vendor and raise minimum required Less compiler version

### MediaWiki Bootstrap 1.0

Released on August 15, 2014.

Complete rework of extension

Changes:
* Switch to a standard-compliant Less compiler
* Introduce Composer installation
* Improve error handling of the caching mechanism
* Add tests

### MediaWiki Bootstrap 0.1

Released on October 13, 2013.

First working version
* loads the Bootstrap 3 framework
* caching of styles per user
* basic error handling
