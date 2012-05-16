<?php

function wpsfo_custom_header_size( $args ) {
	$args['height'] = 250;
	return $args;
}
add_filter( 'p2_custom_header_args', 'wpsfo_custom_header_size' );

// Make a request to the meetup.com API
function _wpsfo_next_meetup_request() {
	$transient_key = 'wpsfo_next_meetup';

	if( $next_meetup = get_transient( $transient_key ) )
		return $next_meetup;

	// Don't use an API key, see http://www.meetup.com/meetup_api/auth/#keysign
	$signed_url = 'http://api.meetup.com/2/events?group_id=1174738&status=upcoming&_=1337201528652&order=time&desc=false&offset=0&format=json&page=20&fields=&sig_id=6435625&sig=1247b10e42c7dde387a70d392a1680bf62a2b6e0';

	// Make the request
	$response = wp_remote_get( $signed_url );

	// Cache failed requests but not for long, just in case something goes crazy with the API
	if( is_wp_error( $response ) || !isset( $response['body'] ) ) {
		set_transient( $transient_key, false, 60 );
		return false;
	}

	// Get the next meetup's data
	$data = json_decode( $response['body'] );

	// Make sure the data we want is there
	if( !isset( $data->results ) || !isset( $data->results[0] ) ) {
		set_transient( $transient_key, false, 60 );
		return false;	
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
	if( !$next_meetup )
		return '';

	// Timestamps are returned in miliseconds for reasons I'll never understand
	$meetup_timestamp = ( $next_meetup->time + $next_meetup->utc_offset ) / 1000;

	switch( $param ) {
		case 'name':  return $next_meetup->name;
		case 'url':   return $next_meetup->event_url;
		case 'month': return date( 'M', $meetup_timstamp );
		case 'day':   return date( 'j', $meetup_timestamp );
	}
}