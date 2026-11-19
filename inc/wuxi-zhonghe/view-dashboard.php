<?php
/**
 * /wuxizhonghe 平台页（V13 图 2 布局）。数据由 wz.js 轮询 ?wz=state 与 ?wz=frame 填充。
 *
 * @package CXZ_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$base = home_url( CXZ_WZ_PATH . '/' );
?><!doctype html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>无锡众和码头辅助靠泊系统 · 岸端管理平台</title>
<link rel="stylesheet" href="<?php echo esc_url( cxz_wz_asset( 'wz.css' ) ); ?>">
</head>
<body class="wz-dash-page">
<header class="wz-top">
	<div class="wz-brand">
		<span class="wz-logo" aria-hidden="true">泊</span>
		<h1>无锡众和码头辅助靠泊系统</h1>
		<span class="wz-sub">岸端管理平台（简易版）</span>
		<span class="wz-sim-badge" title="画面来自 UE5 仿真场景，场景与真实泊位尚未对齐">仿真演示 · UE5</span>
	</div>
	<div class="wz-top-right">
		<span id="wz-clock">--</span>
		<span class="wz-user">● <?php echo esc_html( (string) cxz_wz_get( 'user' ) ); ?></span>
		<form method="post" action="<?php echo esc_url( $base ); ?>">
			<input type="hidden" name="wz_action" value="logout">
			<button type="submit" class="wz-btn-ghost">退出</button>
		</form>
	</div>
</header>

<div class="wz-grid">
	<aside class="wz-left">
		<h2>泊位列表</h2>
		<div class="wz-berths" id="wz-berths"></div>
		<h2>设备状态</h2>
		<ul class="wz-devices" id="wz-devices"></ul>
	</aside>

	<main class="wz-center">
		<section class="wz-video">
			<div class="wz-video-frame" id="wz-video">
				<img id="wz-img" alt="高位视觉画面" hidden>
				<svg id="wz-overlay" class="wz-overlay" preserveAspectRatio="none" aria-hidden="true"></svg>
				<div class="wz-video-title" id="wz-video-title">2号泊位 · 高位视觉实时画面</div>
				<div class="wz-video-live" id="wz-live"><span class="dot"></span><span id="wz-live-text">等待画面</span></div>
				<div class="wz-video-dist" id="wz-video-dist"></div>
				<div class="wz-video-env" id="wz-video-env"></div>
				<div class="wz-video-empty" id="wz-empty">还没有收到画面。UE 仿真或回放运行后，画面每 5 秒内更新一次。</div>
			</div>
		</section>
		<section class="wz-cards">
			<div class="wz-card">
				<h3 id="wz-dist-title">靠泊距离（整船最近点，0~5 m）</h3>
				<div class="wz-kv"><span>当前距离</span><b id="wz-dist">—</b></div>
				<div class="wz-bar"><span id="wz-dist-bar"></span></div>
				<div class="wz-note" id="wz-dist-note"></div>
			</div>
			<div class="wz-card">
				<h3>靠泊速度（基于图像测距推算）</h3>
				<div class="wz-kv"><span>当前速度</span><b id="wz-speed">—</b></div>
				<div class="wz-bar green"><span id="wz-speed-bar"></span></div>
				<div class="wz-note" id="wz-speed-note"></div>
			</div>
			<div class="wz-card">
				<h3>数据链路（UE5 仿真 → 平台）</h3>
				<div class="wz-kv"><span>链路状态</span><b id="wz-link">—</b></div>
				<div class="wz-kv"><span>数据更新周期</span><b id="wz-period">—</b></div>
				<div class="wz-kv"><span>最近一次更新</span><b id="wz-age">—</b></div>
			</div>
		</section>
	</main>

	<aside class="wz-right">
		<h2>环境监测</h2>
		<div class="wz-env" id="wz-env"></div>
		<h2 id="wz-log-title">靠泊监测记录（2号泊位）</h2>
		<table class="wz-log">
			<thead><tr><th>时间</th><th>船艏<br>(m)</th><th>整船<br>(m)</th><th>船艉<br>(m)</th><th>速度<br>(m/s)</th></tr></thead>
			<tbody id="wz-log"></tbody>
		</table>
		<p class="wz-disclaimer">本页为仿真演示：画面来自 UE5 仿真场景（场景与真实泊位尚未对齐），距离与速度由测距链实时计算。船艏、船艉距离尚未实现，显示“—”；没有数据来源的项目显示“未接入”。</p>
	</aside>
</div>
<script>window.WZ_BASE = <?php echo wp_json_encode( $base ); ?>;</script>
<script src="<?php echo esc_url( cxz_wz_asset( 'wz.js' ) ); ?>"></script>
</body>
</html>
