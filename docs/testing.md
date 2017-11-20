## Testing

This extension provides unit tests that can be run by a [continuous integration
platform][travis] or manually by executing the `mw-phpunit-runner.php` script or
[`phpunit`][mw-testing] together with the PHPUnit configuration file found in
the root directory of the extension.
```sh
php mw-phpunit-runner.php [options]
```

Useful optional parameters:
```
--coverage-html ../../../report
--debug
```

[travis]: https://travis-ci.org/wikimedia/mediawiki-skins-chameleon
[mw-testing]: https://www.mediawiki.org/wiki/Manual:PHP_unit_testing
