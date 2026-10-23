<?php
/**
 * /market 登录页。变量：$error
 *
 * @package CXZ_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$locked = cxz_mm_fail_count() >= CXZ_MM_MAX_FAILS;
?><!doctype html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>市场监控看板 · 登录</title>
<link rel="stylesheet" href="<?php echo esc_url( cxz_mm_asset( 'mm.css' ) ); ?>">
</head>
<body class="mm-login-page">
<main class="mm-login">
	<div class="mm-login-brand">
		<span class="mm-logo" aria-hidden="true"></span>
		<div>
			<h1>市场监控看板</h1>
			<p>招标信息 · 每日自动更新</p>
		</div>
	</div>
	<form method="post" action="<?php echo esc_url( home_url( CXZ_MM_PATH . '/' ) ); ?>" class="mm-login-form" autocomplete="on">
		<input type="hidden" name="mm_action" value="login">
		<label for="mm_user">用户名</label>
		<input id="mm_user" name="mm_user" type="text" autocomplete="username" required autofocus
			value="<?php echo isset( $_POST['mm_user'] ) ? esc_attr( wp_unslash( $_POST['mm_user'] ) ) : ''; ?>">
		<label for="mm_pass">密码</label>
		<input id="mm_pass" name="mm_pass" type="password" autocomplete="current-password" required>
		<?php if ( '' !== $error ) : ?>
			<p class="mm-login-error" role="alert"><?php echo esc_html( $error ); ?></p>
		<?php elseif ( $locked ) : ?>
			<p class="mm-login-error" role="alert">输错次数过多，请 15 分钟后再试。</p>
		<?php endif; ?>
		<button type="submit">登录</button>
	</form>
	<p class="mm-login-foot">仅限授权人员访问</p>
</main>
</body>
</html>
