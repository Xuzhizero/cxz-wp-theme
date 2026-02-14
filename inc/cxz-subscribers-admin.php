<?php
/**
 * CXZ Subscribers – WordPress admin page (SMTP settings + subscriber list).
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'admin_menu', 'cxz_subscribers_admin_menu' );
add_action( 'admin_init', 'cxz_register_smtp_settings' );

/**
 * Register the admin menu page.
 */
function cxz_subscribers_admin_menu() {
    add_menu_page(
        'Newsletter Subscribers',
        'Subscribers',
        'manage_options',
        'cxz-subscribers',
        'cxz_subscribers_admin_page',
        'dashicons-email-alt',
        30
    );
}

/**
 * Register SMTP settings.
 */
function cxz_register_smtp_settings() {
    register_setting( 'cxz_smtp_settings', 'cxz_smtp_host' );
    register_setting( 'cxz_smtp_settings', 'cxz_smtp_port' );
    register_setting( 'cxz_smtp_settings', 'cxz_smtp_username' );
    register_setting( 'cxz_smtp_settings', 'cxz_smtp_password' );
    register_setting( 'cxz_smtp_settings', 'cxz_smtp_from_email' );
    register_setting( 'cxz_smtp_settings', 'cxz_smtp_from_name' );
}

/**
 * Render the admin page.
 */
function cxz_subscribers_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'smtp';

    // Handle subscriber deletion
    if ( $active_tab === 'subscribers' && isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['id'] ) ) {
        check_admin_referer( 'cxz_delete_subscriber_' . $_GET['id'] );
        global $wpdb;
        $table = cxz_subscribers_table_name();
        $wpdb->delete( $table, array( 'id' => intval( $_GET['id'] ) ), array( '%d' ) );
        echo '<div class="notice notice-success"><p>Subscriber deleted.</p></div>';
    }

    // Handle test email
    if ( $active_tab === 'smtp' && isset( $_POST['cxz_send_test_email'] ) ) {
        check_admin_referer( 'cxz_smtp_settings-options' );
        $admin_email = get_option( 'admin_email' );
        $result = wp_mail(
            $admin_email,
            'CXZ Newsletter – Test Email',
            '<p>This is a test email from your CoolXuZhi.tech newsletter system. SMTP is working correctly!</p>',
            array( 'Content-Type: text/html; charset=UTF-8' )
        );
        if ( $result ) {
            echo '<div class="notice notice-success"><p>Test email sent to <strong>' . esc_html( $admin_email ) . '</strong>. Please check your inbox.</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>Failed to send test email. Please check your SMTP settings.</p></div>';
        }
    }

    ?>
    <div class="wrap">
        <h1>Newsletter Subscribers</h1>

        <h2 class="nav-tab-wrapper">
            <a href="?page=cxz-subscribers&tab=smtp"
               class="nav-tab <?php echo $active_tab === 'smtp' ? 'nav-tab-active' : ''; ?>">
                SMTP Settings
            </a>
            <a href="?page=cxz-subscribers&tab=subscribers"
               class="nav-tab <?php echo $active_tab === 'subscribers' ? 'nav-tab-active' : ''; ?>">
                Subscriber List
            </a>
        </h2>

        <?php
        if ( $active_tab === 'smtp' ) {
            cxz_render_smtp_tab();
        } else {
            cxz_render_subscribers_tab();
        }
        ?>
    </div>
    <?php
}

/**
 * Render the SMTP settings tab.
 */
function cxz_render_smtp_tab() {
    ?>
    <form method="post" action="options.php">
        <?php settings_fields( 'cxz_smtp_settings' ); ?>
        <table class="form-table">
            <tr>
                <th><label for="cxz_smtp_host">SMTP Host</label></th>
                <td>
                    <input type="text" id="cxz_smtp_host" name="cxz_smtp_host"
                           value="<?php echo esc_attr( get_option( 'cxz_smtp_host', 'smtp.gmail.com' ) ); ?>"
                           class="regular-text">
                </td>
            </tr>
            <tr>
                <th><label for="cxz_smtp_port">SMTP Port</label></th>
                <td>
                    <input type="number" id="cxz_smtp_port" name="cxz_smtp_port"
                           value="<?php echo esc_attr( get_option( 'cxz_smtp_port', '587' ) ); ?>"
                           class="small-text">
                </td>
            </tr>
            <tr>
                <th><label for="cxz_smtp_username">Username (Email)</label></th>
                <td>
                    <input type="email" id="cxz_smtp_username" name="cxz_smtp_username"
                           value="<?php echo esc_attr( get_option( 'cxz_smtp_username', '' ) ); ?>"
                           class="regular-text">
                    <p class="description">Your Gmail address.</p>
                </td>
            </tr>
            <tr>
                <th><label for="cxz_smtp_password">Password (App Password)</label></th>
                <td>
                    <input type="password" id="cxz_smtp_password" name="cxz_smtp_password"
                           value="<?php echo esc_attr( get_option( 'cxz_smtp_password', '' ) ); ?>"
                           class="regular-text">
                    <p class="description">
                        Use a Gmail <strong>App Password</strong> (not your regular password).<br>
                        Generate one at: Google Account &rarr; Security &rarr; 2-Step Verification &rarr; App passwords.
                    </p>
                </td>
            </tr>
            <tr>
                <th><label for="cxz_smtp_from_email">From Email</label></th>
                <td>
                    <input type="email" id="cxz_smtp_from_email" name="cxz_smtp_from_email"
                           value="<?php echo esc_attr( get_option( 'cxz_smtp_from_email', '' ) ); ?>"
                           class="regular-text">
                    <p class="description">Defaults to the SMTP username if left empty.</p>
                </td>
            </tr>
            <tr>
                <th><label for="cxz_smtp_from_name">From Name</label></th>
                <td>
                    <input type="text" id="cxz_smtp_from_name" name="cxz_smtp_from_name"
                           value="<?php echo esc_attr( get_option( 'cxz_smtp_from_name', 'CoolXuZhi' ) ); ?>"
                           class="regular-text">
                </td>
            </tr>
        </table>
        <?php submit_button( 'Save Settings' ); ?>
    </form>

    <hr>
    <h3>Send Test Email</h3>
    <p>Sends a test email to <strong><?php echo esc_html( get_option( 'admin_email' ) ); ?></strong> to verify SMTP is working.</p>
    <form method="post">
        <?php wp_nonce_field( 'cxz_smtp_settings-options' ); ?>
        <input type="hidden" name="cxz_send_test_email" value="1">
        <?php submit_button( 'Send Test Email', 'secondary' ); ?>
    </form>
    <?php
}

/**
 * Render the subscriber list tab.
 */
function cxz_render_subscribers_tab() {
    global $wpdb;
    $table = cxz_subscribers_table_name();

    $per_page = 20;
    $paged    = isset( $_GET['paged'] ) ? max( 1, intval( $_GET['paged'] ) ) : 1;
    $offset   = ( $paged - 1 ) * $per_page;

    $total       = intval( $wpdb->get_var( "SELECT COUNT(*) FROM {$table}" ) );
    $subscribers = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM {$table} ORDER BY created_at DESC LIMIT %d OFFSET %d",
            $per_page,
            $offset
        )
    );
    $total_pages = ceil( $total / $per_page );

    $active_count = intval( $wpdb->get_var( "SELECT COUNT(*) FROM {$table} WHERE status = 'active'" ) );
    ?>
    <p>
        <strong>Total:</strong> <?php echo $total; ?> subscribers
        (<strong><?php echo $active_count; ?></strong> active)
    </p>

    <table class="widefat striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Status</th>
                <th>Subscribed</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ( empty( $subscribers ) ) : ?>
                <tr><td colspan="5">No subscribers yet.</td></tr>
            <?php else : ?>
                <?php foreach ( $subscribers as $sub ) : ?>
                    <tr>
                        <td><?php echo intval( $sub->id ); ?></td>
                        <td><?php echo esc_html( $sub->email ); ?></td>
                        <td>
                            <span style="color: <?php echo $sub->status === 'active' ? '#25b864' : '#999'; ?>;">
                                <?php echo esc_html( $sub->status ); ?>
                            </span>
                        </td>
                        <td><?php echo esc_html( $sub->created_at ); ?></td>
                        <td>
                            <a href="<?php echo wp_nonce_url(
                                admin_url( 'admin.php?page=cxz-subscribers&tab=subscribers&action=delete&id=' . intval( $sub->id ) ),
                                'cxz_delete_subscriber_' . intval( $sub->id )
                            ); ?>"
                               onclick="return confirm('Delete this subscriber?');"
                               style="color: #dc3232;">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if ( $total_pages > 1 ) : ?>
        <div class="tablenav bottom">
            <div class="tablenav-pages">
                <?php
                echo paginate_links( array(
                    'base'    => add_query_arg( 'paged', '%#%' ),
                    'format'  => '',
                    'current' => $paged,
                    'total'   => $total_pages,
                ) );
                ?>
            </div>
        </div>
    <?php endif;
}
