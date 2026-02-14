<?php
/**
 * CXZ Subscribers – AJAX handler for subscription requests.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_ajax_cxz_subscribe',        'cxz_handle_subscribe_ajax' );
add_action( 'wp_ajax_nopriv_cxz_subscribe', 'cxz_handle_subscribe_ajax' );

function cxz_handle_subscribe_ajax() {
    // 1. Nonce check
    check_ajax_referer( 'cxz_subscribe_nonce', 'nonce' );

    // 2. Rate limiting (1 request per 60s per IP)
    $ip_hash  = md5( isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : 'unknown' );
    $rate_key = 'cxz_sub_rate_' . $ip_hash;
    if ( get_transient( $rate_key ) ) {
        wp_send_json_error( array( 'code' => 'rate_limited' ) );
    }
    set_transient( $rate_key, 1, 60 );

    // 3. Validate email
    $email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
    if ( ! is_email( $email ) ) {
        wp_send_json_error( array( 'code' => 'invalid_email' ) );
    }

    // 4. Check existing subscriber
    global $wpdb;
    $table    = cxz_subscribers_table_name();
    $existing = $wpdb->get_row(
        $wpdb->prepare( "SELECT id, status FROM {$table} WHERE email = %s", $email )
    );

    if ( $existing ) {
        if ( $existing->status === 'active' ) {
            wp_send_json_error( array( 'code' => 'already_subscribed' ) );
        }
        // Reactivate unsubscribed user
        $new_token = bin2hex( random_bytes( 32 ) );
        $wpdb->update(
            $table,
            array( 'status' => 'active', 'unsubscribe_token' => $new_token ),
            array( 'id' => $existing->id ),
            array( '%s', '%s' ),
            array( '%d' )
        );
        wp_send_json_success();
    }

    // 5. Insert new subscriber
    $token = bin2hex( random_bytes( 32 ) );
    $wpdb->insert(
        $table,
        array(
            'email'             => $email,
            'unsubscribe_token' => $token,
            'status'            => 'active',
        ),
        array( '%s', '%s', '%s' )
    );

    wp_send_json_success();
}
