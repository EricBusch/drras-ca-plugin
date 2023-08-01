<?php

defined( 'ABSPATH' ) || exit;

add_shortcode( 'google_map_embed', function ( $atts ) {

	$atts = shortcode_atts( [
		'width'  => '100%',
		'height' => '400px',
	], $atts, 'google_map_embed' );

	$html = get_field( 'google_map_embed', 'options' );

	if ( empty( $html ) ) {
		return '';
	}

	$map = [
		'/width=".*?"/'  => sprintf( 'width="%s"', $atts['width'] ),
		'/height=".*?"/' => sprintf( 'height="%s"', $atts['height'] ),
	];

	return preg_replace( array_keys( $map ), array_values( $map ), $html );
} );
