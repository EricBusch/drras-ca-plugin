<?php

defined( 'ABSPATH' ) || exit;

/**
 * Returns the URL for a Post or Page in WordPress based on the routes
 * defined in DRRAS_ROUTES.
 *
 * @param string $page
 *
 * @return string
 */
function drras_get_permalink( string $page ): string {
	return DRRAS_ROUTES[ $page ] ? get_the_permalink( DRRAS_ROUTES[ $page ] ) : home_url();
}

/**
 * Return a list of Team Members who are owners.
 *
 * @return array An array of Business Owners.
 */
function drras_get_owners(): array {
	return get_posts( [
		'post_type'        => 'team-member',
		'orderby'          => 'menu_order',
		'order'            => 'ASC',
		'numberposts'      => 99,
		'meta_key'         => 'is_owner',
		'meta_value'       => 'yes',
		'suppress_filters' => true,
	] );
}

/**
 * Return a list of Team Members who are owners.
 *
 * @return array An array of Business Owners.
 */
function drras_get_employees(): array {
	return get_posts( [
		'post_type'        => 'team-member',
		'orderby'          => 'menu_order',
		'order'            => 'ASC',
		'numberposts'      => 99,
		'meta_key'         => 'is_owner',
		'meta_value'       => 'no',
		'suppress_filters' => true,
	] );
}

/**
 * Return an array of Non-Surgical Treatments
 *
 * @return array
 */
function drras_get_nonsurgical_treatments(): array {

	static $posts = null;

	if ( $posts === null ) {

		$posts = get_posts( [
			'post_type'        => 'nonsurgicaltreatment',
			'orderby'          => 'menu_order',
			'order'            => 'ASC',
			'numberposts'      => 99,
			'suppress_filters' => true,
		] );

	}

	return $posts;
}

/**
 * Return an array of Surgical Treatments
 *
 * @return array
 */
function drras_get_surgical_treatments(): array {

	static $posts = null;

	if ( $posts === null ) {

		$posts = get_posts( [
			'post_type'        => 'surgicaltreatment',
			'orderby'          => 'menu_order',
			'order'            => 'ASC',
			'numberposts'      => 99,
			'suppress_filters' => true,
		] );

	}

	return $posts;
}

function drras_get_skin_types(): array {

	static $posts = null;

	if ( $posts === null ) {

		$posts = get_posts( [
			'post_type'        => 'skin',
			'orderby'          => 'post_title',
			'order'            => 'ASC',
			'numberposts'      => 99,
			'suppress_filters' => true,
		] );

	}

	return $posts;
}

function drras_display_acf_image( string $field, int $post_id, string $size = 'thumbnail', array $attr = [] ): string {
	$image = get_field( $field, $post_id );

	return $image ? wp_get_attachment_image( $image['ID'], $size, false, $attr ) : '';
}

function drras_get_acf_image_field_url( string $field, int $post_id, string $size = 'thumbnail' ): string {
	$image = get_field( $field, $post_id );

	return $image ? wp_get_attachment_image_url( $image['ID'], $size ) : '';
}

function drras_get_acf_image_sub_field_url( string $field, string $size = 'medium_large' ): string {
	$image = get_sub_field( $field );

	return $image ? wp_get_attachment_image_url( $image['ID'], $size ) : '';
}

function drras_get_acf_options_image_field_url( string $field, string $size = 'thumbnail' ): string {
	$image = get_field( $field, 'options' );

	return $image ? wp_get_attachment_image_url( $image['ID'], $size ) : '';
}

function drras_get_post_field( string $field, $default = null ): mixed {
	$value = get_field( $field );

	return empty( $value ) ? $default : $value;
}

function drras_get_phone_href( string $phone_number ): string {
	$digits = preg_replace( '/[^0-9]/', '', $phone_number );

	return sprintf( 'tel:+1%d', $digits );
}

function drras_get_email_href( string $email_address ): string {
	return sprintf( 'mailto:%s', trim( $email_address ) );
}

function drras_phrase( string $field, array $replacement_pairs = [] ): string {
	$field = str_starts_with( $field, 'phrase_' ) ? $field : 'phrase_' . $field;

	return strtr( get_field( $field, 'options' ), $replacement_pairs );
}

function drras_kses( string $content ): string {
	return wp_kses( $content, wp_kses_allowed_html( 'post' ) );
}

function drras_kses_e( string $content ): void {
	echo drras_kses( $content );
}

function drras_get_next_surgicaltreatment( int $current_surgicaltreatment_id ): ?WP_Post {
	$treatments = drras_get_surgical_treatments();

	return drras_get_next_object( $treatments, $current_surgicaltreatment_id );
}

function drras_get_prev_surgicaltreatment( int $current_surgicaltreatment_id ): ?WP_Post {
	$treatments = drras_get_surgical_treatments();

	return drras_get_prev_object( $treatments, $current_surgicaltreatment_id );
}

/**
 * @param WP_Post[] $objects An array of WP_Post objects.
 * @param int $current_object_id The ID of the current object that we will base the "next" object off of.
 *
 * @return WP_Post|null The next WP_Post or null if none found.
 */
function drras_get_next_object( array $objects, int $current_object_id ): ?WP_Post {

	if ( empty( $objects ) ) {
		return null;
	}

	$count = count( $objects );

	foreach ( $objects as $key => $object ) {
		if ( $object->ID === $current_object_id ) {
			return $objects[ $key + 1 ] ?? $objects[0];
		}
	}

	return $objects[ $count - 1 ];
}

function drras_get_prev_object( array $objects, int $current_object_id ): ?WP_Post {

	if ( empty( $objects ) ) {
		return null;
	}

	$count = count( $objects );

	foreach ( $objects as $key => $object ) {
		if ( $object->ID === $current_object_id ) {
			$index = $key - 1;
			$index = $index < 0 ? $count - 1 : $index;

			return $objects[ $index ];
		}
	}

	return $objects[0];
}

function drras_get_menu_item_markup( WP_Post $item, array $attributes = [] ): string {

	$format = '<a href="%s"%s>%s</a>';

	$url     = esc_url( $item->url );
	$content = $item->title;

	$id      = esc_attr( $attributes['id'] ?? '' );
	$classes = esc_attr( trim( implode( ' ', $item->classes ) . ' ' . $attributes['class'] ) );
	$title   = esc_attr( trim( $item->attr_title ) );
	$target  = esc_attr( trim( $item->target ) );

	$attrs = [];

	if ( $id ) {
		$attrs[] = 'id="' . $id . '"';
	}

	if ( $classes ) {
		$attrs[] = 'class="' . $classes . '"';
	}

	if ( $title ) {
		$attrs[] = 'title="' . $title . '"';
	}

	if ( $target ) {
		$attrs[] = 'target="' . $target . '"';
	}

	$attrs = empty( $attrs ) ? '' : ' ' . implode( $attrs );

	return sprintf(
		$format,
		$url,
		$attrs,
		$content
	);
}

function drras_boldify_rassouli( string $text ): string {

	$replacement_pairs = [
		'A Rassouli'        => '<strong>A Rassouli</strong>',
		'A. Rassouli'       => '<strong>A. Rassouli</strong>',
		'Alipasha Rassouli' => '<strong>Alipasha Rassouli</strong>',
		'Rassouli A'        => '<strong>Rassouli A</strong>',
	];

	return strtr( $text, $replacement_pairs );
}