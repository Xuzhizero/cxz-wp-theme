<?php
/**
 * Theme functions and definitions
 *
 * @package CXZ_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Theme setup
 */
function cxz_wp_theme_setup() {
	// Add theme support for various features
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
}
add_action( 'after_setup_theme', 'cxz_wp_theme_setup' );

/**
 * Enqueue scripts and styles
 */
function cxz_wp_theme_scripts() {
	wp_enqueue_style( 'cxz-wp-theme-style', get_stylesheet_uri(), array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'cxz_wp_theme_scripts' );
