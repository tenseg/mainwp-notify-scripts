#!/usr/bin/env php
<?php
/*
 * This script checks for any updates seen by MainWP and notifies if any are found.
 */

include 'helpers.php';

// get update data
$site_updates = retrieve_data( 'updates/available-updates' );
if ( !$site_updates ) {
	return;
}

// count updates
$num_updates = 0;
$update_keys = [
	'wp_core',
	'plugins',
	'themes',
	'translation',
];
foreach ( $site_updates as $site ) {
	$up = [];
	foreach ( $update_keys as $key ) {
		if ( !empty( $site[$key] ) ) {
			$up[$key] = count( $site[$key] );
		}
	}
	if ( !empty( $up ) ) {
		$up_count = 0;
		foreach ( $up as $u ) {
			$up_count = $up_count + $u;
		}
		$num_updates = $num_updates + $up_count;
	}
}

// notify about updates
if ( $num_updates ) {
	$config = dash_config();
	shell_exec( "terminal-notifier -title 'WP Updates Waiting'  -message 'There are {$num_updates} updates waiting to be installed.' -open {$config['site_url']}/wp-admin/admin.php?page=mainwp_tab -group net.tenseg.MainWP.updates" );
}