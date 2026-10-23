<?php
/**
 * /market 看板。变量：$snapshot（推送来的 JSON 字符串）
 * 页面由 mm.js 根据内嵌的快照渲染；没有快照时显示提示。
 *
 * @package CXZ_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$data = json_decode( (string) $snapshot, true );
?><!doctype html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>市场监控看板</title>
<link rel="stylesheet" href="<?php echo esc_url( cxz_mm_asset( 'mm.css' ) ); ?>">
</head>
<body class="mm-dash-page">
<header class="mm-top">
	<div class="mm-top-inner">
		<div class="mm-brand">
			<span class="mm-logo" aria-hidden="true"></span>
			<div>
				<h1>市场监控看板</h1>
				<p id="mm-updated">招标信息 · 每日自动更新</p>
			</div>
		</div>
		<div class="mm-top-actions">
			<label class="mm-month">
				<span>月份</span>
				<select id="mm-month" aria-label="选择月份"></select>
			</label>
			<form method="post" action="<?php echo esc_url( home_url( CXZ_MM_PATH . '/' ) ); ?>">
				<input type="hidden" name="mm_action" value="logout">
				<button type="submit" class="mm-btn-ghost">退出</button>
			</form>
		</div>
	</div>
</header>
<main class="mm-main" id="mm-app">
	<?php if ( ! is_array( $data ) ) : ?>
		<section class="mm-card mm-empty">还没有收到数据。流水线每天上午运行后会自动更新。</section>
	<?php endif; ?>
</main>
<div class="mm-tip" id="mm-tip" role="status" hidden></div>
<?php if ( is_array( $data ) ) : ?>
<script type="application/json" id="mm-data"><?php echo wp_json_encode( $data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE ); ?></script>
<script src="<?php echo esc_url( cxz_mm_asset( 'mm.js' ) ); ?>"></script>
<?php endif; ?>
</body>
</html>
