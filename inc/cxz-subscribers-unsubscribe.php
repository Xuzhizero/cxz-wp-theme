<?php
/**
 * CXZ Subscribers – One-click unsubscribe handler.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'template_redirect', 'cxz_handle_unsubscribe', 5 );

function cxz_handle_unsubscribe() {
    if ( ! isset( $_GET['cxz_unsubscribe'] ) ) {
        return;
    }

    $token = sanitize_text_field( $_GET['cxz_unsubscribe'] );

    // Validate token format: 64-char hex string
    if ( ! preg_match( '/^[a-f0-9]{64}$/', $token ) ) {
        wp_die(
            '<h2>Invalid Link</h2><p>This unsubscribe link is invalid or malformed.</p>',
            'Invalid Unsubscribe Link',
            array( 'response' => 400 )
        );
    }

    global $wpdb;
    $table  = cxz_subscribers_table_name();
    $result = $wpdb->update(
        $table,
        array( 'status' => 'unsubscribed' ),
        array( 'unsubscribe_token' => $token, 'status' => 'active' ),
        array( '%s' ),
        array( '%s', '%s' )
    );

    if ( $result > 0 ) {
        wp_die(
            '<div style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', sans-serif; max-width: 500px; margin: 80px auto; text-align: center; color: #333;">'
            . '<h2 style="margin-bottom: 10px;">Unsubscribed Successfully</h2>'
            . '<p style="color: #666;">You have been removed from the mailing list and will no longer receive notifications.</p>'
            . '<p><a href="' . esc_url( home_url( '/' ) ) . '" style="color: #ff4612; text-decoration: none;">Back to CoolXuZhi.tech</a></p>'
            . '</div>',
            'Unsubscribed',
            array( 'response' => 200 )
        );
    } else {
        wp_die(
            '<div style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', sans-serif; max-width: 500px; margin: 80px auto; text-align: center; color: #333;">'
            . '<h2 style="margin-bottom: 10px;">Already Unsubscribed</h2>'
            . '<p style="color: #666;">This email has already been unsubscribed or the link is no longer valid.</p>'
            . '<p><a href="' . esc_url( home_url( '/' ) ) . '" style="color: #ff4612; text-decoration: none;">Back to CoolXuZhi.tech</a></p>'
            . '</div>',
            'Already Unsubscribed',
            array( 'response' => 200 )
        );
    }
}
