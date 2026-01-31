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

// ===== 加载核心样式和脚本（来自 Chennative.ai）=====
add_action( 'wp_enqueue_scripts', function () {
	if ( is_front_page() || is_page( 'zh-cn' ) ) {
		// 首页由 front-page.php 直接输出并手动引入外部资源，
		// /zh-cn/ 由 page-zh-cn.php 直接输出并手动引入本地化资源，
		// 避免在这里重复 enqueue 导致脚本/样式重复加载（并被 LiteSpeed 进一步改写）。
		return;
	}
	wp_enqueue_style(
		'cxz-chennative-style',
		'https://Chennative.ai/styles.css',
		array(),
		'190'
	);
	wp_enqueue_script(
		'cxz-chennative-script',
		'https://Chennative.ai/assets/script.js',
		array(),
		null,
		true
	);
}, 1 );

// ===== 首页：彻底移除所有干扰样式 =====
add_action( 'wp_enqueue_scripts', function () {
	if ( ! ( is_front_page() || is_home() || is_page( 'zh-cn' ) ) ) {
		return;
	}

	// WordPress 核心样式
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
	wp_dequeue_style( 'core-block-supports' );

	// Astra 主题样式（如果存在）
	wp_dequeue_style( 'astra-theme-css' );
	wp_dequeue_style( 'astra-addon-css' );
	wp_dequeue_style( 'astra-builder-layout' );

	// 其他可能的干扰
	wp_dequeue_style( 'dashicons' );

	// Elementor / 其他插件样式（尽量只影响首页）
	// 说明：Elementor 会在特定页面（page-id）自动注入大量 CSS/JS，容易覆盖我们自定义首页的布局与配色。
	global $wp_styles;
	if ( isset( $wp_styles ) && is_object( $wp_styles ) && ! empty( $wp_styles->queue ) ) {
		foreach ( (array) $wp_styles->queue as $handle ) {
			$h = (string) $handle;
			if (
				strpos( $h, 'elementor' ) !== false ||
				strpos( $h, 'font-awesome' ) !== false ||
				strpos( $h, 'uagb' ) !== false ||
				strpos( $h, 'starter-templates' ) !== false
			) {
				wp_dequeue_style( $h );
			}
		}
	}

	// Elementor / 其他插件脚本（只在首页移除，避免注入前端运行时代码）
	global $wp_scripts;
	if ( isset( $wp_scripts ) && is_object( $wp_scripts ) && ! empty( $wp_scripts->queue ) ) {
		foreach ( (array) $wp_scripts->queue as $handle ) {
			$h = (string) $handle;
			// 注意：不要移除 jquery-core（某些环境可能仍依赖），这里只去掉 elementor 等注入脚本
			if (
				strpos( $h, 'elementor' ) !== false ||
				strpos( $h, 'starter-templates' ) !== false
			) {
				wp_dequeue_script( $h );
			}
		}
	}

}, 100 );

// ===== 移除 wp_head 中的内联样式 =====
add_action( 'wp_head', function () {
	if ( ! ( is_front_page() || is_home() || is_page( 'zh-cn' ) ) ) {
		return;
	}

	// 移除 Astra 内联 CSS（如果存在）
	remove_action( 'wp_head', 'astra_load_inline_css', 20 );

}, 1 );

// ===== 首页：移除 Astra 的 body 类干扰 =====
add_filter( 'body_class', function ( $classes ) {
	if ( is_front_page() || is_home() || is_page( 'zh-cn' ) ) {
		// 保留必要的类，移除 Astra 特定类
		$remove = array( 'ast-', 'astra-', 'site-', 'elementor-' );
		foreach ( $classes as $key => $class ) {
			foreach ( $remove as $prefix ) {
				if ( strpos( $class, $prefix ) === 0 ) {
					unset( $classes[ $key ] );
				}
			}
		}
	}
	return $classes;
} );

// ===== 移除 WordPress Emoji 脚本（减少干扰）=====
add_action( 'init', function () {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
} );
