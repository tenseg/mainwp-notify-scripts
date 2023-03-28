#!/usr/bin/env php
<?php
/*
 * This script checks for any site security problems seen by MainWP and notifies if any are found.
 */

include 'helpers.php';

// get health data
$site_security = retrieve_data( 'sites/security-issues' );
// file_put_contents( '/Users/alex/tmp/sec.json', json_encode( $site_security, JSON_PRETTY_PRINT ) );
if ( !$site_security ) {
	return;
}

// some starting expectations
$insecure = false;
$secure_values = [
	'listing'             => 'Y',
	'wp_version'          => 'Y',
	'rsd'                 => 'Y',
	'wlw'                 => 'Y',
	'db_reporting'        => 'Y',
	'php_reporting'       => 'Y',
	'versions'            => 'N',
	'registered_versions' => 'N',
	'admin'               => 'Y',
	'readme'              => 'Y',
	'wp_uptodate'         => 'Y',
	'phpversion_matched'  => 'Y',
	'sslprotocol'         => 'Y',
	'debug_disabled'      => 'Y',
];

// check for deviations from secure values
foreach ( $site_security as $site_id => $checks ) {
	foreach ( $checks as $check => $value ) {
		if ( $value != $secure_values[$check] ) {
			// special case debug_disabled to also check if we are on SpinupWP
			if ( 'debug_disabled' == $check ) {
				$active_plugins = retrieve_data( 'site/site-active-plugins', ['site_id' => $site_id] );
				if ( $active_plugins ) {
					$plugin_names = array_map( function ( $plugin ) {
						return $plugin['name'];
					}, $active_plugins );
					if ( !in_array( 'SpinupWP', $plugin_names ) ) {
						$insecure = true;
						break 2;
					}
				} else {
					$insecure = true;
					break 2;
				}
			} else {
				$insecure = true;
				break 2;
			}
		}
	}
}

// notify about security
if ( $insecure ) {
	$config = dash_config();
	shell_exec( "terminal-notifier -title 'WP Site Security'  -message 'Some sites need their security reviewed.' -open {$config['site_url']}/wp-admin/admin.php?page=mainwp_tab -group net.tenseg.MainWP.security" );
}