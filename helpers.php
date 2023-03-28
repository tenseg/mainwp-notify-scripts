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
 * @param array $args additional arguments for the api
 * @return mixed false if error, otherwise retturned data
 */
function retrieve_data( $path = '', $args = [] ) {
	if ( $config = dash_config() ) {
		$url = $config['site_url'] . '/wp-json/mainwp/v1/' . $path . "?consumer_key={$config['consumer_key']}&consumer_secret={$config['consumer_secret']}";

		if ( count( $args ) ) {
			$get_string = '';
			foreach ( $args as $key => $value ) {
				$get_string .= "&$key=$value";
			}
			$url .= $get_string;
		}

		$client = curl_init( $url );
		curl_setopt( $client, CURLOPT_RETURNTRANSFER, true );
		$response = curl_exec( $client );

		return json_decode( $response, true );
	}
	return false;
}