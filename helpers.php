<?php

/**
 * Get dash api config data
 *
 * @return false|array of data
 */
function dash_config() {
	static $config = false;
	if ( !$config && file_exists( __DIR__ . '/config.json' ) ) {
		$config = json_decode( file_get_contents( __DIR__ . '/config.json' ), true );
	}
	return $config;
}

/**
 * Get data from the MainWP API
 *
 * @param string $path endpoint route
 * @return mixed false if error, otherwise retturned data
 */
function retrieve_data( $path = '' ) {
	if ( $config = dash_config() ) {
		$url = $config['site_url'] . '/wp-json/mainwp/v1/' . $path . "?consumer_key={$config['consumer_key']}&consumer_secret={$config['consumer_secret']}";

		$client = curl_init( $url );
		curl_setopt( $client, CURLOPT_RETURNTRANSFER, true );
		$response = curl_exec( $client );

		return json_decode( $response, true );
	}
	return false;
}