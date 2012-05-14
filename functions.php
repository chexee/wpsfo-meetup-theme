<?php

function wpsfo_custom_header_size( $args ) {
	$args['height'] = 250;
	return $args;
}
add_filter( 'p2_custom_header_args', 'wpsfo_custom_header_size' );