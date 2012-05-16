<?php

function wpsfo_custom_header_size( $args ) {
	$args['height'] = 250;
	return $args;
}
add_filter( 'p2_custom_header_args', 'wpsfo_custom_header_size' );

// Make a request to the meetup.com API
function _wpsfo_next_meetup_request() {
	$transient_key = 'wpsfo_next_meetup';

	$next_meetup = get_transient( $transient_key );
	if( $next_meetup )
		return $next_meetup;

	// Build the meetup API request
	$api_base = 'http://api.meetup.com';
	$api_endpoint = '/2/events?';
	$api_query_args = array(
		'group_id' => '1174738',
		'status'   => 'upcoming',
		'order'    => 'time',
		'desc'     => 'false',
		'offset'   => '0',
		'format'   => 'json',
		'page'     => '1',
		'fields'   => '',
		'sig_id'   => '6435625',
		// Don't use an API key, see http://www.meetup.com/meetup_api/auth/#keysign
		'sig'      => 'f4629eeb1ec5c4807b3b57bfd3a681534c340b10',
	);
	$signed_url = $api_base . $api_endpoint . http_build_query( $api_query_args );

	// Make the request
	$response = wp_remote_get( $signed_url );

	// Cache failed requests but not for long, just in case something goes crazy with the API
	if( is_wp_error( $response ) || !isset( $response['body'] ) ) {
		$meetup_api_error = new WP_Error( 'meetup_api', 'Request failed' );
		set_transient( $transient_key, $meetup_api_error, 60 );
		return $meetup_api_error;
	}

	// Get the next meetup's data
	$data = json_decode( $response['body'] );

	// Make sure the data we want is there
	if( !isset( $data->results ) || !isset( $data->results[0] ) ) {
		$meetup_api_error = new WP_Error( 'meetup_api', 'Response data missing' );
		set_transient( $transient_key, $meetup_api_error, 60 );
		return $meetup_api_error;	
	}

	// Cache and return our result
	$next_meetup = $data->results[0];
	set_transient( $transient_key, $next_meetup, 60 * 10 );
	return $next_meetup;
}

// Get a particular piece of information about the meetup
function wpsfo_get_next_meetup( $param ) {
	$next_meetup = _wpsfo_next_meetup_request();

	// Check for failed requests
	if( is_wp_error( $next_meetup ) )
		return '';

	// Timestamps are returned in miliseconds for reasons I'll never understand
	$meetup_timestamp = ( $next_meetup->time + $next_meetup->utc_offset ) / 1000;

	switch( $param ) {
		case 'url':   return $next_meetup->event_url;
		case 'month': return date( 'M', $meetup_timstamp );
		case 'day':   return date( 'j', $meetup_timestamp );
		default:      return $next_meetup->$param;
	}
}