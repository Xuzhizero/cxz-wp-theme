<?php
/**
 * /wuxizhonghe 登录页。变量：$error
 *
 * @package CXZ_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$locked = cxz_wz_fail_count() >= CXZ_WZ_MAX_FAILS;
?><!doctype html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>无锡众和码头辅助靠泊系统 · 登录</title>
<link rel="stylesheet" href="<?php echo esc_url( cxz_wz_asset( 'wz.css' ) ); ?>">
</head>
<body class="wz-login-page">
<main class="wz-login">
	<div class="wz-login-brand">
		<span class="wz-logo" aria-hidden="true">泊</span>
		<div>
			<h1>无锡众和码头辅助靠泊系统</h1>
			<p>岸端管理平台（简易版）· 仿真演示</p>
		</div>
	</div>
	<form method="post" action="<?php echo esc_url( home_url( CXZ_WZ_PATH . '/' ) ); ?>" class="wz-login-form" autocomplete="on">
		<input type="hidden" name="wz_action" value="login">
		<label for="wz_user">用户名</label>
		<input id="wz_user" name="wz_user" type="text" autocomplete="username" required autofocus
			value="<?php echo isset( $_POST['wz_user'] ) ? esc_attr( wp_unslash( $_POST['wz_user'] ) ) : ''; ?>">
		<label for="wz_pass">密码</label>
		<input id="wz_pass" name="wz_pass" type="password" autocomplete="current-password" required>
		<?php if ( '' !== $error ) : ?>
			<p class="wz-login-error" role="alert"><?php echo esc_html( $error ); ?></p>
		<?php elseif ( $locked ) : ?>
			<p class="wz-login-error" role="alert">输错次数过多，请 15 分钟后再试。</p>
		<?php endif; ?>
		<button type="submit">登录</button>
	</form>
	<p class="wz-login-foot">仅限授权人员访问</p>
</main>
</body>
</html>
