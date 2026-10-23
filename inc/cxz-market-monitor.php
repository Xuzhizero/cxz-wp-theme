<?php
/**
 * /market：市场监控看板（市场部领导专用，只读）
 *
 * - 数据：taishiji 上的招标监控流水线每天运行后，把整理好的快照签名推送到 /wp-json/mm/v1/snapshot。
 * - 登录：独立的账号密码，不用 WordPress 账号；密码只以 password_hash 存在数据库里。
 * - 本仓库是公开的：这里只有一次性初始化令牌的 SHA-256，没有任何密钥或密码。
 *
 * @package CXZ_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CXZ_MM_VERSION', '1.0.0' );
define( 'CXZ_MM_PATH', '/market' );
define( 'CXZ_MM_COOKIE', 'cxz_mm_session' );
define( 'CXZ_MM_SESSION_TTL', 12 * HOUR_IN_SECONDS );
define( 'CXZ_MM_MAX_FAILS', 5 );
define( 'CXZ_MM_LOCK_TTL', 15 * MINUTE_IN_SECONDS );
define( 'CXZ_MM_MAX_BODY', 2 * 1024 * 1024 );
define( 'CXZ_MM_CLOCK_SKEW', 300 );

// 一次性初始化令牌的 SHA-256（令牌本身只在 taishiji 的本地配置里）。测试站可在 wp-config.php 里覆盖。
if ( ! defined( 'CXZ_MM_SETUP_TOKEN_SHA256' ) ) {
	define( 'CXZ_MM_SETUP_TOKEN_SHA256', '41347cd4e5d5d6da8c3f35a6a31d15e90552ab12d4ce5bcda4fdb5f3a9f60f7f' );
}

/* ------------------------------------------------------------------ 存储 */

function cxz_mm_get( $key, $default = '' ) {
	return get_option( 'cxz_mm_' . $key, $default );
}

function cxz_mm_set( $key, $value ) {
	// autoload = false：这些值只在 /market 和推送接口里用到
	if ( false === get_option( 'cxz_mm_' . $key, false ) ) {
		add_option( 'cxz_mm_' . $key, $value, '', 'no' );
	} else {
		update_option( 'cxz_mm_' . $key, $value, false );
	}
}

function cxz_mm_is_setup(): bool {
	return cxz_mm_get( 'secret' ) !== '' && cxz_mm_get( 'pass_hash' ) !== '';
}

function cxz_mm_client_key(): string {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? (string) $_SERVER['REMOTE_ADDR'] : '';
	return substr( hash( 'sha256', $ip . wp_salt( 'nonce' ) ), 0, 32 );
}

/* ------------------------------------------------------------------ 登录会话 */

function cxz_mm_session_sig( string $user, int $exp ): string {
	// 密码哈希参与签名：改密码后旧会话全部失效
	$key = wp_salt( 'logged_in' ) . '|' . cxz_mm_get( 'secret' );
	return hash_hmac( 'sha256', $user . '|' . $exp . '|' . cxz_mm_get( 'pass_hash' ), $key );
}

function cxz_mm_set_cookie( string $value, int $exp ) {
	setcookie(
		CXZ_MM_COOKIE,
		$value,
		array(
			'expires'  => $exp,
			'path'     => CXZ_MM_PATH,
			'secure'   => is_ssl(),
			'httponly' => true,
			'samesite' => 'Lax',
		)
	);
}

function cxz_mm_logged_in(): bool {
	if ( ! cxz_mm_is_setup() || empty( $_COOKIE[ CXZ_MM_COOKIE ] ) ) {
		return false;
	}
	$parts = explode( '|', (string) wp_unslash( $_COOKIE[ CXZ_MM_COOKIE ] ) );
	if ( count( $parts ) !== 3 ) {
		return false;
	}
	list( $user, $exp, $sig ) = $parts;
	$exp = (int) $exp;
	if ( $exp < time() || $user !== cxz_mm_get( 'user' ) ) {
		return false;
	}
	return hash_equals( cxz_mm_session_sig( $user, $exp ), $sig );
}

function cxz_mm_fail_count(): int {
	return (int) get_transient( 'cxz_mm_fail_' . cxz_mm_client_key() );
}

function cxz_mm_try_login( string $user, string $pass ): string {
	if ( ! cxz_mm_is_setup() ) {
		return '看板尚未完成初始化。';
	}
	if ( cxz_mm_fail_count() >= CXZ_MM_MAX_FAILS ) {
		return '输错次数过多，请 15 分钟后再试。';
	}
	$ok = hash_equals( (string) cxz_mm_get( 'user' ), $user ) && password_verify( $pass, (string) cxz_mm_get( 'pass_hash' ) );
	if ( ! $ok ) {
		$key   = 'cxz_mm_fail_' . cxz_mm_client_key();
		$fails = cxz_mm_fail_count() + 1;
		set_transient( $key, $fails, CXZ_MM_LOCK_TTL );
		$left = CXZ_MM_MAX_FAILS - $fails;
		return $left > 0 ? sprintf( '用户名或密码不正确（还可以再试 %d 次）。', $left ) : '输错次数过多，请 15 分钟后再试。';
	}
	delete_transient( 'cxz_mm_fail_' . cxz_mm_client_key() );
	$exp = time() + CXZ_MM_SESSION_TTL;
	cxz_mm_set_cookie( $user . '|' . $exp . '|' . cxz_mm_session_sig( $user, $exp ), $exp );
	return '';
}

/* ------------------------------------------------------------------ 页面 */

function cxz_mm_asset( string $name ): string {
	// 文件修改时间作版本号：主题更新后浏览器不会用旧的 CSS/JS
	$file = get_stylesheet_directory() . '/assets/market-monitor/' . $name;
	$ver  = file_exists( $file ) ? (string) filemtime( $file ) : CXZ_MM_VERSION;
	return get_stylesheet_directory_uri() . '/assets/market-monitor/' . $name . '?v=' . $ver;
}

function cxz_mm_is_request(): bool {
	$uri  = isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '';
	$path = parse_url( $uri, PHP_URL_PATH );
	return is_string( $path ) && rtrim( $path, '/' ) === CXZ_MM_PATH;
}

function cxz_mm_no_cache() {
	nocache_headers();
	header( 'X-Robots-Tag: noindex, nofollow, noarchive' );
	header( 'X-Frame-Options: DENY' );
	header( 'Referrer-Policy: no-referrer' );
	do_action( 'litespeed_control_set_nocache', 'market monitor is private' );
}

add_action( 'template_redirect', function () {
	if ( ! cxz_mm_is_request() ) {
		return;
	}
	status_header( 200 );
	cxz_mm_no_cache();

	$error = '';
	if ( 'POST' === ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) {
		$action = isset( $_POST['mm_action'] ) ? (string) $_POST['mm_action'] : '';
		if ( 'logout' === $action ) {
			cxz_mm_set_cookie( '', time() - YEAR_IN_SECONDS );
			wp_safe_redirect( home_url( CXZ_MM_PATH . '/' ) );
			exit;
		}
		if ( 'login' === $action ) {
			$user  = isset( $_POST['mm_user'] ) ? trim( (string) wp_unslash( $_POST['mm_user'] ) ) : '';
			$pass  = isset( $_POST['mm_pass'] ) ? (string) wp_unslash( $_POST['mm_pass'] ) : '';
			$error = cxz_mm_try_login( $user, $pass );
			if ( '' === $error ) {
				// POST 后重定向，刷新页面不会重复提交
				wp_safe_redirect( home_url( CXZ_MM_PATH . '/' ) );
				exit;
			}
		}
	}

	if ( cxz_mm_logged_in() ) {
		$snapshot = cxz_mm_get( 'snapshot', '' );
		include get_stylesheet_directory() . '/inc/market-monitor/view-dashboard.php';
	} else {
		include get_stylesheet_directory() . '/inc/market-monitor/view-login.php';
	}
	exit;
}, 0 );

/* ------------------------------------------------------------------ 推送接口 */

function cxz_mm_verify_signature( WP_REST_Request $req ) {
	$secret = (string) cxz_mm_get( 'secret' );
	if ( '' === $secret ) {
		return new WP_Error( 'mm_not_setup', 'not set up', array( 'status' => 403 ) );
	}
	$body = (string) $req->get_body();
	if ( strlen( $body ) > CXZ_MM_MAX_BODY ) {
		return new WP_Error( 'mm_too_large', 'body too large', array( 'status' => 413 ) );
	}
	$ts  = (int) $req->get_header( 'x_mm_timestamp' );
	$sig = (string) $req->get_header( 'x_mm_signature' );
	// 时间戳单位为毫秒；必须严格递增，同一请求不能重放
	if ( abs( (int) round( microtime( true ) * 1000 ) - $ts ) > CXZ_MM_CLOCK_SKEW * 1000 || $ts <= (int) cxz_mm_get( 'last_ts', 0 ) ) {
		return new WP_Error( 'mm_stale', 'stale or replayed request', array( 'status' => 401 ) );
	}
	if ( ! hash_equals( hash_hmac( 'sha256', $ts . '.' . $body, $secret ), $sig ) ) {
		return new WP_Error( 'mm_bad_signature', 'bad signature', array( 'status' => 401 ) );
	}
	cxz_mm_set( 'last_ts', $ts );
	return true;
}

function cxz_mm_set_login( $user, $pass ) {
	$user = trim( (string) $user );
	$pass = (string) $pass;
	if ( '' === $user || strlen( $pass ) < 6 ) {
		return new WP_Error( 'mm_bad_login', 'user required, password at least 6 characters', array( 'status' => 400 ) );
	}
	cxz_mm_set( 'user', $user );
	cxz_mm_set( 'pass_hash', password_hash( $pass, PASSWORD_DEFAULT ) );
	return true;
}

add_action( 'rest_api_init', function () {
	// 一次性初始化：带令牌设置推送密钥和登录账号；完成后此接口永久拒绝
	register_rest_route( 'mm/v1', '/setup', array(
		'methods'             => 'POST',
		'permission_callback' => '__return_true',
		'callback'            => function ( WP_REST_Request $req ) {
			if ( cxz_mm_get( 'secret' ) !== '' ) {
				return new WP_Error( 'mm_done', 'already set up', array( 'status' => 403 ) );
			}
			$tries = (int) get_transient( 'cxz_mm_setup_tries' );
			if ( $tries >= 20 ) {
				return new WP_Error( 'mm_locked', 'too many attempts', array( 'status' => 429 ) );
			}
			set_transient( 'cxz_mm_setup_tries', $tries + 1, HOUR_IN_SECONDS );
			$p = $req->get_json_params();
			if ( ! is_array( $p ) || ! hash_equals( CXZ_MM_SETUP_TOKEN_SHA256, hash( 'sha256', (string) ( $p['token'] ?? '' ) ) ) ) {
				return new WP_Error( 'mm_bad_token', 'bad token', array( 'status' => 401 ) );
			}
			$secret = (string) ( $p['secret'] ?? '' );
			if ( strlen( $secret ) < 32 ) {
				return new WP_Error( 'mm_bad_secret', 'secret too short', array( 'status' => 400 ) );
			}
			$ok = cxz_mm_set_login( $p['user'] ?? '', $p['password'] ?? '' );
			if ( is_wp_error( $ok ) ) {
				return $ok;
			}
			cxz_mm_set( 'secret', $secret );
			return array( 'ok' => true );
		},
	) );

	// 改登录账号或密码（签名）
	register_rest_route( 'mm/v1', '/login', array(
		'methods'             => 'POST',
		'permission_callback' => 'cxz_mm_verify_signature',
		'callback'            => function ( WP_REST_Request $req ) {
			$p  = json_decode( (string) $req->get_body(), true );
			$ok = cxz_mm_set_login( $p['user'] ?? '', $p['password'] ?? '' );
			return is_wp_error( $ok ) ? $ok : array( 'ok' => true );
		},
	) );

	// 数据快照（签名）
	register_rest_route( 'mm/v1', '/snapshot', array(
		'methods'             => 'POST',
		'permission_callback' => 'cxz_mm_verify_signature',
		'callback'            => function ( WP_REST_Request $req ) {
			$body = (string) $req->get_body();
			$data = json_decode( $body, true );
			if ( ! is_array( $data ) || empty( $data['generated_at'] ) || ! isset( $data['tenders'] ) || ! is_array( $data['tenders'] ) ) {
				return new WP_Error( 'mm_bad_snapshot', 'bad snapshot', array( 'status' => 400 ) );
			}
			cxz_mm_set( 'snapshot', $body );
			cxz_mm_set( 'snapshot_at', current_time( 'mysql' ) );
			return array( 'ok' => true, 'tenders' => count( $data['tenders'] ) );
		},
	) );

	// 健康检查（签名）：返回最近一次收到快照的时间
	register_rest_route( 'mm/v1', '/status', array(
		'methods'             => 'POST',
		'permission_callback' => 'cxz_mm_verify_signature',
		'callback'            => function () {
			return array(
				'ok'          => true,
				'version'     => CXZ_MM_VERSION,
				'user'        => cxz_mm_get( 'user' ),
				'snapshot_at' => cxz_mm_get( 'snapshot_at' ),
			);
		},
	) );
} );
