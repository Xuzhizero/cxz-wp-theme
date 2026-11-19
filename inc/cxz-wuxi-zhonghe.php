<?php
/**
 * /wuxizhonghe：无锡众和码头辅助靠泊系统 · 岸端管理平台（仿真演示）
 *
 * - 画面与数据：taishiji 上的 UE5 仿真与测距链（检测 → 掩膜 → 稠密立体 → 帧间校核）每 ≤5 s 把上位相机画面（JPEG）
 *   和结果签名推送到 /wp-json/wz/v1/frame；本页只读展示，浏览器每 2 s 轮询。
 * - 登录：独立账号密码，不用 WordPress 账号；密码只以 password_hash 存在数据库里。
 * - 本仓库是公开的：这里只有一次性初始化令牌的 SHA-256，没有任何密钥或密码。
 * - 结构与 /market（inc/cxz-market-monitor.php）相同，名字前缀 wz。
 *
 * @package CXZ_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CXZ_WZ_VERSION', '1.0.0' );
define( 'CXZ_WZ_PATH', '/wuxizhonghe' );
define( 'CXZ_WZ_COOKIE', 'cxz_wz_session' );
define( 'CXZ_WZ_SESSION_TTL', 12 * HOUR_IN_SECONDS );
define( 'CXZ_WZ_MAX_FAILS', 5 );
define( 'CXZ_WZ_LOCK_TTL', 15 * MINUTE_IN_SECONDS );
define( 'CXZ_WZ_MAX_BODY', 3 * 1024 * 1024 );
define( 'CXZ_WZ_MAX_JPEG', 1536 * 1024 );
define( 'CXZ_WZ_CLOCK_SKEW', 300 );
define( 'CXZ_WZ_HISTORY', 60 );

// 一次性初始化令牌的 SHA-256（令牌本身只在 taishiji 的本地配置里）。测试站可在 wp-config.php 里覆盖。
if ( ! defined( 'CXZ_WZ_SETUP_TOKEN_SHA256' ) ) {
	define( 'CXZ_WZ_SETUP_TOKEN_SHA256', 'd1533141584a549175970f93b026bce6aad985c622082b8cbacbf347dece3f57' );
}

/* ------------------------------------------------------------------ 存储 */

function cxz_wz_get( $key, $default = '' ) {
	return get_option( 'cxz_wz_' . $key, $default );
}

function cxz_wz_set( $key, $value ) {
	if ( false === get_option( 'cxz_wz_' . $key, false ) ) {
		add_option( 'cxz_wz_' . $key, $value, '', 'no' );
	} else {
		update_option( 'cxz_wz_' . $key, $value, false );
	}
}

function cxz_wz_is_setup(): bool {
	return cxz_wz_get( 'secret' ) !== '' && cxz_wz_get( 'pass_hash' ) !== '';
}

function cxz_wz_client_key(): string {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? (string) $_SERVER['REMOTE_ADDR'] : '';
	return substr( hash( 'sha256', $ip . wp_salt( 'nonce' ) ), 0, 32 );
}

/* ------------------------------------------------------------------ 登录会话 */

function cxz_wz_session_sig( string $user, int $exp ): string {
	// 密码哈希参与签名：改密码后旧会话全部失效
	$key = wp_salt( 'logged_in' ) . '|' . cxz_wz_get( 'secret' );
	return hash_hmac( 'sha256', $user . '|' . $exp . '|' . cxz_wz_get( 'pass_hash' ), $key );
}

function cxz_wz_set_cookie( string $value, int $exp ) {
	setcookie(
		CXZ_WZ_COOKIE,
		$value,
		array(
			'expires'  => $exp,
			'path'     => CXZ_WZ_PATH,
			'secure'   => is_ssl(),
			'httponly' => true,
			'samesite' => 'Lax',
		)
	);
}

function cxz_wz_logged_in(): bool {
	if ( ! cxz_wz_is_setup() || empty( $_COOKIE[ CXZ_WZ_COOKIE ] ) ) {
		return false;
	}
	$parts = explode( '|', (string) wp_unslash( $_COOKIE[ CXZ_WZ_COOKIE ] ) );
	if ( count( $parts ) !== 3 ) {
		return false;
	}
	list( $user, $exp, $sig ) = $parts;
	$exp = (int) $exp;
	if ( $exp < time() || $user !== cxz_wz_get( 'user' ) ) {
		return false;
	}
	return hash_equals( cxz_wz_session_sig( $user, $exp ), $sig );
}

function cxz_wz_fail_count(): int {
	return (int) get_transient( 'cxz_wz_fail_' . cxz_wz_client_key() );
}

function cxz_wz_try_login( string $user, string $pass ): string {
	if ( ! cxz_wz_is_setup() ) {
		return '平台尚未完成初始化。';
	}
	if ( cxz_wz_fail_count() >= CXZ_WZ_MAX_FAILS ) {
		return '输错次数过多，请 15 分钟后再试。';
	}
	$ok = hash_equals( (string) cxz_wz_get( 'user' ), $user ) && password_verify( $pass, (string) cxz_wz_get( 'pass_hash' ) );
	if ( ! $ok ) {
		$key   = 'cxz_wz_fail_' . cxz_wz_client_key();
		$fails = cxz_wz_fail_count() + 1;
		set_transient( $key, $fails, CXZ_WZ_LOCK_TTL );
		$left = CXZ_WZ_MAX_FAILS - $fails;
		return $left > 0 ? sprintf( '用户名或密码不正确（还可以再试 %d 次）。', $left ) : '输错次数过多，请 15 分钟后再试。';
	}
	delete_transient( 'cxz_wz_fail_' . cxz_wz_client_key() );
	$exp = time() + CXZ_WZ_SESSION_TTL;
	cxz_wz_set_cookie( $user . '|' . $exp . '|' . cxz_wz_session_sig( $user, $exp ), $exp );
	return '';
}

/* ------------------------------------------------------------------ 页面 */

function cxz_wz_asset( string $name ): string {
	$file = get_stylesheet_directory() . '/assets/wuxi-zhonghe/' . $name;
	$ver  = file_exists( $file ) ? (string) filemtime( $file ) : CXZ_WZ_VERSION;
	return get_stylesheet_directory_uri() . '/assets/wuxi-zhonghe/' . $name . '?v=' . $ver;
}

function cxz_wz_is_request(): bool {
	$uri  = isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '';
	$path = parse_url( $uri, PHP_URL_PATH );
	return is_string( $path ) && rtrim( $path, '/' ) === CXZ_WZ_PATH;
}

function cxz_wz_no_cache() {
	nocache_headers();
	header( 'X-Robots-Tag: noindex, nofollow, noarchive' );
	header( 'X-Frame-Options: DENY' );
	header( 'Referrer-Policy: no-referrer' );
	header( 'X-Content-Type-Options: nosniff' );
	do_action( 'litespeed_control_set_nocache', 'wuxi zhonghe platform is private' );
}

/** 页面轮询用的状态：最新一帧的结果 + 最近记录。 */
function cxz_wz_state_payload(): array {
	$state = json_decode( (string) cxz_wz_get( 'state', '' ), true );
	return array(
		'ok'          => true,
		'server_time' => time(),
		'received_at' => (int) cxz_wz_get( 'received_at', 0 ),
		'state'       => is_array( $state ) ? $state : null,
		'history'     => array_values( (array) json_decode( (string) cxz_wz_get( 'history', '[]' ), true ) ),
	);
}

add_action( 'template_redirect', function () {
	if ( ! cxz_wz_is_request() ) {
		return;
	}
	status_header( 200 );
	cxz_wz_no_cache();

	$error = '';
	if ( 'POST' === ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) {
		$action = isset( $_POST['wz_action'] ) ? (string) $_POST['wz_action'] : '';
		if ( 'logout' === $action ) {
			cxz_wz_set_cookie( '', time() - YEAR_IN_SECONDS );
			wp_safe_redirect( home_url( CXZ_WZ_PATH . '/' ) );
			exit;
		}
		if ( 'login' === $action ) {
			$user  = isset( $_POST['wz_user'] ) ? trim( (string) wp_unslash( $_POST['wz_user'] ) ) : '';
			$pass  = isset( $_POST['wz_pass'] ) ? (string) wp_unslash( $_POST['wz_pass'] ) : '';
			$error = cxz_wz_try_login( $user, $pass );
			if ( '' === $error ) {
				wp_safe_redirect( home_url( CXZ_WZ_PATH . '/' ) );
				exit;
			}
		}
	}

	$what = isset( $_GET['wz'] ) ? (string) $_GET['wz'] : '';
	if ( 'state' === $what || 'frame' === $what ) {
		if ( ! cxz_wz_logged_in() ) {
			status_header( 401 );
			header( 'Content-Type: application/json; charset=utf-8' );
			echo wp_json_encode( array( 'ok' => false, 'error' => 'login required' ) );
			exit;
		}
		if ( 'state' === $what ) {
			header( 'Content-Type: application/json; charset=utf-8' );
			echo wp_json_encode( cxz_wz_state_payload(), JSON_UNESCAPED_UNICODE );
			exit;
		}
		$jpeg = base64_decode( (string) cxz_wz_get( 'frame', '' ), true );
		if ( false === $jpeg || '' === $jpeg ) {
			status_header( 404 );
			exit;
		}
		header( 'Content-Type: image/jpeg' );
		header( 'Content-Length: ' . strlen( $jpeg ) );
		echo $jpeg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- binary JPEG from a signed push
		exit;
	}

	if ( cxz_wz_logged_in() ) {
		include get_stylesheet_directory() . '/inc/wuxi-zhonghe/view-dashboard.php';
	} else {
		include get_stylesheet_directory() . '/inc/wuxi-zhonghe/view-login.php';
	}
	exit;
}, 0 );

/* ------------------------------------------------------------------ 推送接口 */

function cxz_wz_verify_signature( WP_REST_Request $req ) {
	$secret = (string) cxz_wz_get( 'secret' );
	if ( '' === $secret ) {
		return new WP_Error( 'wz_not_setup', 'not set up', array( 'status' => 403 ) );
	}
	$body = (string) $req->get_body();
	if ( strlen( $body ) > CXZ_WZ_MAX_BODY ) {
		return new WP_Error( 'wz_too_large', 'body too large', array( 'status' => 413 ) );
	}
	$ts  = (int) $req->get_header( 'x_wz_timestamp' );
	$sig = (string) $req->get_header( 'x_wz_signature' );
	// 时间戳单位为毫秒；必须严格递增，同一请求不能重放
	if ( abs( (int) round( microtime( true ) * 1000 ) - $ts ) > CXZ_WZ_CLOCK_SKEW * 1000 || $ts <= (int) cxz_wz_get( 'last_ts', 0 ) ) {
		return new WP_Error( 'wz_stale', 'stale or replayed request', array( 'status' => 401 ) );
	}
	if ( ! hash_equals( hash_hmac( 'sha256', $ts . '.' . $body, $secret ), $sig ) ) {
		return new WP_Error( 'wz_bad_signature', 'bad signature', array( 'status' => 401 ) );
	}
	cxz_wz_set( 'last_ts', $ts );
	return true;
}

function cxz_wz_set_login( $user, $pass ) {
	$user = trim( (string) $user );
	$pass = (string) $pass;
	if ( '' === $user || strlen( $pass ) < 8 ) {
		return new WP_Error( 'wz_bad_login', 'user required, password at least 8 characters', array( 'status' => 400 ) );
	}
	cxz_wz_set( 'user', $user );
	cxz_wz_set( 'pass_hash', password_hash( $pass, PASSWORD_DEFAULT ) );
	return true;
}

/** 推送来的状态只保留已知字段，数值转成数字，字符串截短。 */
function cxz_wz_clean_state( array $s ): array {
	$num = function ( $v ) {
		return ( is_int( $v ) || is_float( $v ) ) && is_finite( (float) $v ) ? (float) $v : null;
	};
	$str = function ( $v, $n = 80 ) {
		return is_string( $v ) ? mb_substr( $v, 0, $n ) : '';
	};
	$box = null;
	if ( isset( $s['box'] ) && is_array( $s['box'] ) && count( $s['box'] ) === 4 ) {
		$box = array_map( function ( $v ) use ( $num ) { return max( 0.0, min( 1.0, (float) $num( $v ) ) ); }, array_values( $s['box'] ) );
	}
	$scene = isset( $s['scene'] ) && is_array( $s['scene'] ) ? $s['scene'] : array();
	$dist  = isset( $s['distance'] ) && is_array( $s['distance'] ) ? $s['distance'] : array();
	return array(
		'seq'        => (int) ( $s['seq'] ?? 0 ),
		'at'         => (int) ( $s['at'] ?? 0 ),
		'source'     => in_array( $s['source'] ?? '', array( 'live', 'replay' ), true ) ? $s['source'] : 'replay',
		'berth'      => max( 1, min( 3, (int) ( $s['berth'] ?? 2 ) ) ),
		'sequence'   => $str( $s['sequence'] ?? '' ),
		'frame'      => $str( $s['frame'] ?? '', 40 ),
		'ship'       => $str( $s['ship'] ?? '' ),
		'box'        => $box,
		'score'      => $num( $s['score'] ?? null ),
		'distance'   => array(
			'whole' => $num( $dist['whole'] ?? null ),
			'fore'  => $num( $dist['fore'] ?? null ),
			'aft'   => $num( $dist['aft'] ?? null ),
		),
		'grade'      => in_array( $s['grade'] ?? '', array( 'green', 'yellow', 'red' ), true ) ? $s['grade'] : 'red',
		'reason'     => $str( $s['reason'] ?? '' ),
		'speed'      => $num( $s['speed'] ?? null ),
		'truth'      => $num( $s['truth'] ?? null ),
		'range_max'  => $num( $s['range_max'] ?? null ),
		'period_s'   => $num( $s['period_s'] ?? null ),
		'scene'      => array(
			'weather'     => $str( $scene['weather'] ?? '', 20 ),
			'time_of_day' => $str( $scene['time_of_day'] ?? '', 20 ),
			'water_level' => $num( $scene['water_level'] ?? null ),
		),
		'camera'     => $str( $s['camera'] ?? '', 40 ),
		'width'      => (int) ( $s['width'] ?? 0 ),
		'height'     => (int) ( $s['height'] ?? 0 ),
	);
}

add_action( 'rest_api_init', function () {
	register_rest_route( 'wz/v1', '/setup', array(
		'methods'             => 'POST',
		'permission_callback' => '__return_true',
		'callback'            => function ( WP_REST_Request $req ) {
			if ( cxz_wz_get( 'secret' ) !== '' ) {
				return new WP_Error( 'wz_done', 'already set up', array( 'status' => 403 ) );
			}
			$tries = (int) get_transient( 'cxz_wz_setup_tries' );
			if ( $tries >= 20 ) {
				return new WP_Error( 'wz_locked', 'too many attempts', array( 'status' => 429 ) );
			}
			set_transient( 'cxz_wz_setup_tries', $tries + 1, HOUR_IN_SECONDS );
			$p = $req->get_json_params();
			if ( ! is_array( $p ) || ! hash_equals( CXZ_WZ_SETUP_TOKEN_SHA256, hash( 'sha256', (string) ( $p['token'] ?? '' ) ) ) ) {
				return new WP_Error( 'wz_bad_token', 'bad token', array( 'status' => 401 ) );
			}
			$secret = (string) ( $p['secret'] ?? '' );
			if ( strlen( $secret ) < 32 ) {
				return new WP_Error( 'wz_bad_secret', 'secret too short', array( 'status' => 400 ) );
			}
			$ok = cxz_wz_set_login( $p['user'] ?? '', $p['password'] ?? '' );
			if ( is_wp_error( $ok ) ) {
				return $ok;
			}
			cxz_wz_set( 'secret', $secret );
			return array( 'ok' => true );
		},
	) );

	register_rest_route( 'wz/v1', '/login', array(
		'methods'             => 'POST',
		'permission_callback' => 'cxz_wz_verify_signature',
		'callback'            => function ( WP_REST_Request $req ) {
			$p  = json_decode( (string) $req->get_body(), true );
			$ok = cxz_wz_set_login( $p['user'] ?? '', $p['password'] ?? '' );
			return is_wp_error( $ok ) ? $ok : array( 'ok' => true );
		},
	) );

	// 一帧：{"state": {...}, "jpeg": "<base64>"}（签名）
	register_rest_route( 'wz/v1', '/frame', array(
		'methods'             => 'POST',
		'permission_callback' => 'cxz_wz_verify_signature',
		'callback'            => function ( WP_REST_Request $req ) {
			$p = json_decode( (string) $req->get_body(), true );
			if ( ! is_array( $p ) || ! isset( $p['state'] ) || ! is_array( $p['state'] ) ) {
				return new WP_Error( 'wz_bad_frame', 'state required', array( 'status' => 400 ) );
			}
			$state = cxz_wz_clean_state( $p['state'] );
			if ( isset( $p['jpeg'] ) ) {
				$jpeg = base64_decode( (string) $p['jpeg'], true );
				if ( false === $jpeg || strlen( $jpeg ) > CXZ_WZ_MAX_JPEG || substr( $jpeg, 0, 3 ) !== "\xFF\xD8\xFF" ) {
					return new WP_Error( 'wz_bad_jpeg', 'jpeg must be base64 JPEG under 1.5 MB', array( 'status' => 400 ) );
				}
				cxz_wz_set( 'frame', base64_encode( $jpeg ) );
			}
			cxz_wz_set( 'state', wp_json_encode( $state, JSON_UNESCAPED_UNICODE ) );
			cxz_wz_set( 'received_at', time() );
			$hist = (array) json_decode( (string) cxz_wz_get( 'history', '[]' ), true );
			array_unshift( $hist, array(
				'at'     => $state['at'],
				'source' => $state['source'],
				'whole'  => $state['distance']['whole'],
				'fore'   => $state['distance']['fore'],
				'aft'    => $state['distance']['aft'],
				'speed'  => $state['speed'],
				'grade'  => $state['grade'],
			) );
			cxz_wz_set( 'history', wp_json_encode( array_slice( $hist, 0, CXZ_WZ_HISTORY ) ) );
			return array( 'ok' => true, 'seq' => $state['seq'] );
		},
	) );

	register_rest_route( 'wz/v1', '/status', array(
		'methods'             => 'POST',
		'permission_callback' => 'cxz_wz_verify_signature',
		'callback'            => function () {
			return array(
				'ok'          => true,
				'version'     => CXZ_WZ_VERSION,
				'user'        => cxz_wz_get( 'user' ),
				'received_at' => (int) cxz_wz_get( 'received_at', 0 ),
			);
		},
	) );
} );
