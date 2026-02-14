<?php
/**
 * CXZ Subscribers – Auto-notify subscribers when a new post is published.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'transition_post_status', 'cxz_notify_subscribers_on_publish', 10, 3 );

function cxz_notify_subscribers_on_publish( $new_status, $old_status, $post ) {
    // Only trigger when a post transitions TO published for the first time
    if ( $new_status !== 'publish' || $old_status === 'publish' ) {
        return;
    }

    // Only for regular posts
    if ( $post->post_type !== 'post' ) {
        return;
    }

    // Prevent duplicate sends
    if ( get_post_meta( $post->ID, '_cxz_notification_sent', true ) ) {
        return;
    }
    update_post_meta( $post->ID, '_cxz_notification_sent', '1' );

    // Query all active subscribers
    global $wpdb;
    $table       = cxz_subscribers_table_name();
    $subscribers = $wpdb->get_results(
        "SELECT email, unsubscribe_token FROM {$table} WHERE status = 'active'"
    );

    if ( empty( $subscribers ) ) {
        return;
    }

    foreach ( $subscribers as $subscriber ) {
        cxz_send_notification_email( $subscriber->email, $post->ID, $subscriber->unsubscribe_token );
    }
}
