<?php

defined( 'ABSPATH' ) || exit;

/**
 * Set up for Helo.
 *
 * @param $phpmailer
 *
 * @return void
 */
function helo( $phpmailer ): void {
	if ( WP_ENVIRONMENT_TYPE === 'local' ) {
		$phpmailer->isSMTP();
		$phpmailer->Host     = '127.0.0.1';
		$phpmailer->SMTPAuth = true;
		$phpmailer->Port     = 2525;
		$phpmailer->Username = 'Mailbox-Name';
		$phpmailer->Password = '';
	}
}

add_action( 'phpmailer_init', 'helo' );

/**
 * Remove reCAPTCHA badge from appearing in the bottom right corner of every page which
 * doesn't have a Contact Form 7 form on it.
 */
add_action( 'wp_print_scripts', function () {
	global $post;
	if ( is_a( $post, 'WP_Post' ) && ! has_shortcode( $post->post_content, 'contact-form-7' ) ) {
		wp_dequeue_script( 'google-recaptcha' );
		wp_dequeue_script( 'wpcf7-recaptcha' );
	}
} );

/**
 * Add ACF Options and Suboptions to admin sidebar.
 */
add_action( 'acf/init', function () {

	if ( ! function_exists( 'acf_add_options_sub_page' ) ) {
		return;
	}

	// Add parent.
	$parent = acf_add_options_page( [
		'menu_title' => __( 'Options' ),
		'page_title' => __( 'Global Options' ),
		'redirect'   => false,
	] );

	// Add Footer Options subpage.
	acf_add_options_sub_page( [
		'menu_title'  => __( 'Contact' ),
		'page_title'  => __( 'Contact Options' ),
		'parent_slug' => $parent['menu_slug'],
	] );

	// Add Footer Options subpage.
	acf_add_options_sub_page( [
		'menu_title'  => __( 'Footer' ),
		'page_title'  => __( 'Footer Options' ),
		'parent_slug' => $parent['menu_slug'],
	] );

	// Add Footer Options subpage.
	acf_add_options_sub_page( [
		'menu_title'  => __( 'Consultation' ),
		'page_title'  => __( 'Consultation Options' ),
		'parent_slug' => $parent['menu_slug'],
	] );

	// Add Banner Options subpage.
	acf_add_options_sub_page( [
		'menu_title'  => __( 'Banner' ),
		'page_title'  => __( 'Banner Options' ),
		'parent_slug' => $parent['menu_slug'],
	] );

	// Add Phrase Options subpage.
	acf_add_options_sub_page( [
		'menu_title'  => __( 'Phrases' ),
		'page_title'  => __( 'Phrase Options' ),
		'parent_slug' => $parent['menu_slug'],
	] );

	// Add Phrase Options subpage.
	acf_add_options_sub_page( [
		'menu_title'  => __( 'Certifications' ),
		'page_title'  => __( 'Certification Options' ),
		'parent_slug' => $parent['menu_slug'],
	] );
} );


