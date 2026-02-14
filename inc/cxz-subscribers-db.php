<?php
/**
 * CXZ Subscribers – Database table creation and helpers.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'CXZ_SUBSCRIBERS_DB_VERSION', '1.0' );

/**
 * Return the full table name.
 */
function cxz_subscribers_table_name() {
    global $wpdb;
    return $wpdb->prefix . 'cxz_subscribers';
}

/**
 * Create / upgrade the subscribers table (called on theme activation).
 */
function cxz_subscribers_create_table() {
    global $wpdb;

    $table           = cxz_subscribers_table_name();
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE {$table} (
        id              BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        email           VARCHAR(255)        NOT NULL,
        unsubscribe_token VARCHAR(64)       NOT NULL,
        status          VARCHAR(20)         NOT NULL DEFAULT 'active',
        created_at      DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id),
        UNIQUE KEY email (email),
        UNIQUE KEY unsubscribe_token (unsubscribe_token),
        KEY status (status)
    ) {$charset_collate};";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );

    update_option( 'cxz_subscribers_db_version', CXZ_SUBSCRIBERS_DB_VERSION );
}
