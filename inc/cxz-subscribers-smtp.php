<?php
/**
 * CXZ Subscribers – SMTP configuration for wp_mail via PHPMailer.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'phpmailer_init', 'cxz_configure_smtp' );

function cxz_configure_smtp( $phpmailer ) {
    $host = get_option( 'cxz_smtp_host', '' );
    $user = get_option( 'cxz_smtp_username', '' );
    $pass = get_option( 'cxz_smtp_password', '' );

    // Only configure SMTP if credentials are set
    if ( empty( $host ) || empty( $user ) || empty( $pass ) ) {
        return;
    }

    $phpmailer->isSMTP();
    $phpmailer->Host       = $host;
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Port       = intval( get_option( 'cxz_smtp_port', 587 ) );
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->Username   = $user;
    $phpmailer->Password   = $pass;
    $phpmailer->From       = get_option( 'cxz_smtp_from_email', $user );
    $phpmailer->FromName   = get_option( 'cxz_smtp_from_name', 'CoolXuZhi' );
}

/**
 * Send a notification email to a single subscriber about a new post.
 *
 * @param string $to_email          Subscriber email address.
 * @param int    $post_id           The published post ID.
 * @param string $unsubscribe_token The subscriber's unsubscribe token.
 * @return bool Whether the email was sent successfully.
 */
function cxz_send_notification_email( $to_email, $post_id, $unsubscribe_token ) {
    $post = get_post( $post_id );
    if ( ! $post ) {
        return false;
    }

    $title     = get_the_title( $post );
    $permalink = get_permalink( $post );
    $excerpt   = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( strip_tags( $post->post_content ), 55 );

    $unsubscribe_url = home_url( '/?cxz_unsubscribe=' . $unsubscribe_token );

    $subject = 'New post: ' . $title;

    $body = '<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; color: #333;">
  <h2 style="margin: 0 0 10px;">' . esc_html( $title ) . '</h2>
  <p style="color: #666; line-height: 1.6;">' . esc_html( $excerpt ) . '</p>
  <p><a href="' . esc_url( $permalink ) . '" style="color: #ff4612; text-decoration: none; font-weight: 600;">Read more &rarr;</a></p>
  <hr style="border: none; border-top: 1px solid #e6e6e6; margin: 30px 0 15px;">
  <p style="font-size: 12px; color: #999;">
    You are receiving this because you subscribed at CoolXuZhi.tech.<br>
    <a href="' . esc_url( $unsubscribe_url ) . '" style="color: #999;">Unsubscribe</a>
  </p>
</body>
</html>';

    $headers = array( 'Content-Type: text/html; charset=UTF-8' );

    return wp_mail( $to_email, $subject, $body, $headers );
}
