#!/usr/bin/env php
<?php
/*
 * This script checks for any site health problems seen by MainWP and notifies if any are found.
 */

include 'helpers.php';

// get health data
$site_health = retrieve_data( 'sites/health-score' );
if ( !$site_health ) {
	return;
}

// check site health statuses
$bad = false;
foreach ( $site_health as $status ) {
	if ( 'Good' != $status ) {
		$bad = true;
		break 1;
	}
}

// notify about health
if ( $bad ) {
	$config = dash_config();
	shell_exec( "terminal-notifier -title 'WP Site Health'  -message 'Some sites need a health checkup.' -open {$config['site_url']}/wp-admin/admin.php?page=MonitoringSites -group net.tenseg.MainWP.health" );
}