<?php

defined( 'ABSPATH' ) || exit;

/**
 * Change "Add Title" placeholder for Custom Post Types on Add New pages.
 */
add_filter( 'enter_title_here', function ( $title ): string {

	$screen = get_current_screen();

	$titles = [
		'team-member'          => 'Enter person\'s full name',
		'skin'                 => 'Enter skin type name',
		'surgicaltreatment'    => 'Enter treatment name',
		'nonsurgicaltreatment' => 'Enter treatment name',
	];

	return $titles[ $screen->post_type ] ?? $title;
} );

/**
 * Returns a different thumbnail ID for all images which have the Featured Image field
 * disabled and has been replaced with an ACF image field.
 */
add_filter( 'post_thumbnail_id', function ( $thumbnail_id, $post ): int {

	$post = get_post( $post );

	if ( ! $post ) {
		return false;
	}

	$fields = [
		'team-member'          => 'image',
		'skin'                 => 'content_image',
		'surgicaltreatment'    => 'content_image',
		'nonsurgicaltreatment' => 'content_image',
	];

	if ( ! array_key_exists( $post->post_type, $fields ) ) {
		return $thumbnail_id;
	}

	$image = get_field( $fields[ $post->post_type ], $post->ID );

	if ( ! $image ) {
		return $thumbnail_id;
	}

	/**
	 * Filters the post thumbnail ID.
	 *
	 * @param int|false $thumbnail_id Post thumbnail ID or false if the post does not exist.
	 * @param int|WP_Post|null $post Post ID or WP_Post object. Default is global `$post`.
	 *
	 * @since 1.0.0
	 */
	return (int) apply_filters( 'drras_post_thumbnail_id', $image['ID'], $post );

}, 10, 2 );

