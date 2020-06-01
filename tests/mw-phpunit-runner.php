<?php

/**
 * Lazy script to invoke the MediaWiki phpunit runner
 *
 * php mw-phpunit-runner.php [options]
 */

// @codingStandardsIgnoreFile
if ( php_sapi_name() !== 'cli' ) {
	die( 'Not an entry point' );
}

print( "\nMediaWiki phpunit runnner ... \n" );

function isReadablePath( $path ) {
	if ( is_readable( $path ) ) {
		return $path;
	}

	throw new RuntimeException( "Expected an accessible {$path} path" );
}

function addArguments( $args ) {
	$arguments = [];

	for ( $arg = reset( $args ); $arg !== false; $arg = next( $args ) ) {

		if ( $arg === basename( __FILE__ ) ) {
			continue;
		}

		$arguments[] = $arg;
	}

	return $arguments;
}

$IP = getenv( 'MW_INSTALL_PATH' ) !== false
	? str_replace( '\\', '/', getenv( 'MW_INSTALL_PATH' ) )
	: __DIR__ . '/../../..';

$mw = isReadablePath( $IP . "/tests/phpunit/phpunit.php" );

passthru( "php {$mw} " . implode( ' ', addArguments( $GLOBALS['argv'] ) ) );
