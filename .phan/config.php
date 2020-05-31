<?php
$cfg = require __DIR__ . '/../vendor/mediawiki/mediawiki-phan-config/src/config.php';

$IP = getenv( 'MW_INSTALL_PATH' ) !== false
	? str_replace( '\\', '/', getenv( 'MW_INSTALL_PATH' ) )
	: __DIR__ . '/../../..';

$scss_dir = $IP . "/vendor/mediawiki/scss";

if ( !file_exists( $scss_dir ) ) {
	$scss_dir = __DIR__ . '/../vendor/mediawiki/scss';
}

if ( file_exists( $scss_dir ) ) {

	$cfg['directory_list'] = array_merge(
		$cfg['directory_list'],
		[
			$scss_dir,
		]
	);

	$cfg['exclude_analysis_directory_list'] = array_merge(
		$cfg['exclude_analysis_directory_list'],
		[
			$scss_dir,
		]
	);
}

return $cfg;
