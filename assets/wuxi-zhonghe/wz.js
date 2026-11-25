/* 无锡众和码头辅助靠泊系统 · 岸端管理平台（仿真演示）
 * 每 2 s 读取 ?wz=state；帧号变化时再取 ?wz=frame。没有来源的数据显示“未接入”，不编造。
 * UE 实时运行时（state 里有 20 s 内的视频段）改为每 1 s 轮询，逐段取 ?wz=seg&n= 连续播放 5 帧/秒视频，
 * 叠加框与读数按视频时间取该段内的测距结果。 */
(function () {
	'use strict';
	var BASE = window.WZ_BASE || '/wuxizhonghe/';
	var POLL_MS = 2000, POLL_VIDEO_MS = 1000;
	var lastSeq = -1;
	var latest = null;
	var $ = function (id) { return document.getElementById(id); };

	function esc(s) {
		return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
			return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
		});
	}
	function num(v, d) { return (typeof v === 'number' && isFinite(v)) ? v.toFixed(d) : null; }
	function seqName(q) { var m = /seq(\d+)$/.exec(q || ''); return m ? '靠泊序列 ' + m[1] : q; }
	function pad(n) { return (n < 10 ? '0' : '') + n; }
	function hms(sec) { var t = new Date(sec * 1000); return pad(t.getHours()) + ':' + pad(t.getMinutes()) + ':' + pad(t.getSeconds()); }
	function ymdhms(t) { return t.getFullYear() + '-' + pad(t.getMonth() + 1) + '-' + pad(t.getDate()) + '  ' + pad(t.getHours()) + ':' + pad(t.getMinutes()) + ':' + pad(t.getSeconds()); }

	var GRADE = { green: '绿色（可信）', yellow: '黄色（参考）', red: '红色（无效）' };
	var WEATHER = { sunny: '晴', cloudy: '多云', clear: '晴', rain: '雨', fog: '雾' };
	var TOD = { day: '白天', dawn: '清晨', dusk: '黄昏', night: '夜间', noon: '中午' };

	/* ---------- 时钟 ---------- */
	function tick() { $('wz-clock').textContent = ymdhms(new Date()); }
	tick(); setInterval(tick, 1000);

	/* ---------- 链路状态 ---------- */
	function linkInfo(payload) {
		var age = payload.received_at ? payload.server_time - payload.received_at : null;
		var s = payload.state;
		if (age === null || !s) return { text: '未连接', cls: 'bad', age: null, mode: 'off' };
		var mode = s.source === 'live' ? 'live' : 'replay';
		if (age <= 15) return { text: mode === 'live' ? '正常（UE 实时）' : '正常（回放）', cls: 'good', age: age, mode: mode };
		if (age <= 60) return { text: '延迟', cls: 'warn', age: age, mode: mode };
		return { text: '中断', cls: 'bad', age: age, mode: 'off' };
	}

	/* ---------- 左栏 ---------- */
	function renderLeft(s, link) {
		var active = s ? s.berth : 2;
		var html = '';
		for (var b = 1; b <= 3; b++) {
			var isActive = b === active && link.mode !== 'off';
			var tag = isActive ? '<span class="wz-tag busy">靠泊中</span>' : (b === active ? '<span class="wz-tag">无画面</span>' : '<span class="wz-tag sim">未仿真</span>');
			var cam = b === active ? (link.mode === 'off' ? '中断' : '在线（仿真）') : '未接入';
			html += '<div class="wz-berth' + (b === active ? ' active' : '') + '"><div class="wz-berth-head"><b>' + b + '号泊位</b>' + tag + '</div>' +
				'<p>摄像头：' + cam + '</p><p>超声波阵列：未接入</p></div>';
		}
		$('wz-berths').innerHTML = html;
		var on = link.mode !== 'off';
		var dev = [
			['高位视觉摄像头（UE5 仿真上位相机）', on ? 'on' : 'off', on ? '在线' : '离线'],
			['测距链（检测 · 掩膜 · 稠密立体）', on ? 'on' : 'off', on ? '运行' : '停止'],
			['超声波传感器', 'na', '未接入'],
			['通信配电单元', 'na', '未接入'],
			['超声波水位计', 'na', '未接入'],
			['风速风向仪（已有）', 'na', '未接入']
		];
		$('wz-devices').innerHTML = dev.map(function (d) {
			return '<li><span>' + esc(d[0]) + '</span><span class="wz-st ' + d[1] + '">' + d[2] + '</span></li>';
		}).join('');
	}

	/* ---------- 画面与叠加 ---------- */
	function renderVideo(s, link) {
		var live = $('wz-live');
		live.className = 'wz-video-live ' + link.mode;
		$('wz-live-text').textContent = !s ? '等待画面' : (link.mode === 'live' ? 'LIVE ' : (link.mode === 'replay' ? '回放 ' : '中断 ')) + hms(s.at || 0);
		$('wz-video-title').textContent = (s ? s.berth : 2) + '号泊位 · 高位视觉实时画面' + (s && s.sequence ? '（' + seqName(s.sequence) + (s.frame ? ' · 第 ' + s.frame + ' 帧' : '') + '）' : '');
		var svg = $('wz-overlay');
		if (!s || !s.box) { svg.innerHTML = ''; }
		else {
			svg.setAttribute('viewBox', '0 0 1000 1000');
			var b = s.box, x = b[0] * 1000, y = b[1] * 1000, w = (b[2] - b[0]) * 1000, h = (b[3] - b[1]) * 1000;
			var col = s.grade === 'red' ? '#e5383b' : '#57b4ff';
			var label = '靠泊船舶' + (s.ship ? ' · ' + s.ship : '') + (num(s.score, 2) ? ' · ' + num(s.score, 2) : '');
			svg.innerHTML = '<rect x="' + x + '" y="' + y + '" width="' + w + '" height="' + h + '" fill="none" stroke="' + col + '" stroke-width="3" vector-effect="non-scaling-stroke"/>';
			var tag = document.getElementById('wz-boxtag');
			if (!tag) { tag = document.createElement('div'); tag.id = 'wz-boxtag'; $('wz-video').appendChild(tag); }
			tag.style.cssText = 'position:absolute;left:' + (b[0] * 100) + '%;top:calc(' + (b[1] * 100) + '% - 26px);background:' + col + ';color:#fff;font-size:13px;padding:2px 8px;border-radius:4px;max-width:60%;white-space:nowrap;overflow:hidden;text-overflow:ellipsis';
			if (b[1] < 0.09) tag.style.top = 'calc(' + (b[1] * 100) + '% + 44px)';
			if (b[1] < 0.09) tag.style.left = 'calc(' + (b[0] * 100) + '% + 8px)';
			tag.textContent = label;
		}
		if (!s || !s.box) { var t = document.getElementById('wz-boxtag'); if (t) t.remove(); }
		var d = s ? s.distance : {};
		var whole = num(d && d.whole, 2);
		$('wz-video-dist').innerHTML = s ? (
			'船艏距离 <b>' + (num(d.fore, 2) || '—') + '</b> m &nbsp; 整船最近 <b>' + (whole || '—') + '</b> m &nbsp; 船艉距离 <b>' + (num(d.aft, 2) || '—') + '</b> m') : '';
		var sc = s ? s.scene : null;
		$('wz-video-env').innerHTML = s ? (
			'靠泊速度 <b>' + (num(s.speed === null ? null : Math.abs(s.speed), 2) || '—') + '</b> m/s<br>' + (sc && num(sc.water_level, 2) ? '水位 <b>' + num(sc.water_level, 2) + '</b> m' : '水位 未接入') + ' &nbsp; 风速 未接入') : '';
	}

	function loadFrame(seq) {
		var img = $('wz-img');
		var next = new Image();
		next.onload = function () { img.src = next.src; img.hidden = false; $('wz-empty').hidden = true; };
		next.src = BASE + '?wz=frame&s=' + encodeURIComponent(seq);
	}

	/* ---------- 卡片 ---------- */
	function renderCards(s, link, payload) {
		var range = s && num(s.range_max, 0) ? s.range_max : 5;
		var whole = s ? s.distance.whole : null;
		$('wz-dist').innerHTML = num(whole, 2) ? num(whole, 2) + '<small> m</small>' : '—';
		$('wz-dist-bar').style.width = num(whole, 2) ? Math.max(0, Math.min(100, whole / range * 100)) + '%' : '0';
		var note = '';
		if (s) {
			note = '质量等级：' + (GRADE[s.grade] || s.grade);
			if (s.grade === 'red' && s.reason) note += '（' + (s.reason === 'beyond_range' ? '超出 0~5 m 测量范围' : s.reason === 'outlier_rejected' ? '帧间跳变，已剔除' : s.reason === 'no_dense_reading' ? '本帧无可用读数' : s.reason) + '）';
			if (num(s.truth, 2)) note += ' · 仿真真值 ' + num(s.truth, 2) + ' m';
		}
		$('wz-dist-note').textContent = note;
		var sp = s ? s.speed : null;
		$('wz-speed').innerHTML = num(sp, 2) ? num(Math.abs(sp), 2) + '<small> m/s</small>' : '—';
		$('wz-speed-bar').style.width = num(sp, 2) ? Math.min(100, Math.abs(sp) / 0.5 * 100) + '%' : '0';
		$('wz-speed-note').textContent = num(sp, 2) ? (sp < 0 ? '正在靠近码头' : sp > 0 ? '正在离开码头' : '静止') + '（由帧间校核滤波得到）' : '等待连续读数';
		var lk = $('wz-link'); lk.textContent = link.text; lk.className = link.cls;
		$('wz-period').textContent = s && num(s.period_s, 1) ? '约 ' + num(s.period_s, 1) + ' s' : '≤ 5 s';
		$('wz-age').textContent = link.age === null ? '—' : (link.age < 2 ? '刚刚' : link.age + ' 秒前');
	}

	/* ---------- 右栏 ---------- */
	function renderRight(s, payload) {
		var sc = s ? s.scene : null;
		var cells = [
			['水位（仿真）', sc && num(sc.water_level, 2) ? num(sc.water_level, 2) + '<small> m</small>' : null],
			['风速', null],
			['天气（仿真）', sc && sc.weather ? esc(WEATHER[sc.weather] || sc.weather) : null],
			['时段（仿真）', sc && sc.time_of_day ? esc(TOD[sc.time_of_day] || sc.time_of_day) : null]
		];
		$('wz-env').innerHTML = cells.map(function (c) {
			return '<div><span>' + c[0] + '</span>' + (c[1] ? '<b>' + c[1] + '</b>' : '<b class="na">未接入</b>') + '</div>';
		}).join('');
		$('wz-log-title').textContent = '靠泊监测记录（' + (s ? s.berth : 2) + '号泊位）';
		var rows = (payload.history || []).slice(0, 10).map(function (r) {
			var cls = r.grade === 'yellow' ? ' class="y"' : (r.grade === 'red' ? ' class="r"' : '');
			return '<tr><td>' + hms(r.at || 0) + (r.source === 'replay' ? ' <small>回放</small>' : '') + '</td><td>' + (num(r.fore, 2) || '—') + '</td><td' + cls + '>' + (num(r.whole, 2) || '—') + '</td><td>' + (num(r.aft, 2) || '—') + '</td><td>' + (num(r.speed === null ? null : Math.abs(r.speed), 2) || '—') + '</td></tr>';
		});
		$('wz-log').innerHTML = rows.join('') || '<tr><td colspan="5" style="text-align:center;color:#6a7a8c">暂无记录</td></tr>';
	}

	/* ---------- 实时视频（5 帧/秒 H.264 段，双 video 元素无缝切换） ---------- */
	var SEG_STALE_S = 20, BUF_START = 2, BUF_MAX = 4;
	var lastSegN = 0, fetching = false, pend = [], buf = [], cur = null, vids = [], active = 0, vstate = null, vkey = '';

	function segFresh(p) {
		var s = (p && p.segments) || [];
		return s.length > 0 && p.server_time - s[s.length - 1].at <= SEG_STALE_S;
	}
	function makeVideos() {
		if (vids.length) return;
		var box = $('wz-video'), svg = $('wz-overlay');
		for (var i = 0; i < 2; i++) {
			var v = document.createElement('video');
			v.muted = true; v.playsInline = true; v.setAttribute('playsinline', ''); v.setAttribute('muted', '');
			v.preload = 'auto'; v.className = 'wz-vid'; v.hidden = true;
			v.addEventListener('ended', onEnded);
			box.insertBefore(v, svg);
			vids.push(v);
		}
	}
	function queueSegments(p) {
		var list = p.segments || [];
		if (!lastSegN && list.length > 3) lastSegN = list[list.length - 4].n;   // join near the live edge
		list.forEach(function (m) { if (m.n > lastSegN) { lastSegN = m.n; pend.push(m); } });
		pump();
	}
	function pump() {
		if (fetching || !pend.length) return;
		var m = pend.shift();
		fetching = true;
		fetch(BASE + '?wz=seg&n=' + m.n, { credentials: 'same-origin' })
			.then(function (r) { if (!r.ok) throw new Error(String(r.status)); return r.blob(); })
			.then(function (b) {
				buf.push({ meta: m, url: URL.createObjectURL(b) });
				while (buf.length > BUF_MAX) URL.revokeObjectURL(buf.shift().url);   // keep the delay bounded
				if (cur) preload(); else if (buf.length >= BUF_START) startNext();
			})
			.catch(function () {})
			.then(function () { fetching = false; pump(); });
	}
	function preload() {
		var v = vids[1 - active];
		if (buf.length && v.dataset.n !== String(buf[0].meta.n)) { v.src = buf[0].url; v.dataset.n = String(buf[0].meta.n); v.load(); }
	}
	function startNext() {
		if (!buf.length) { cur = null; return; }          // stall: the last frame stays on screen
		var seg = buf.shift(), v = vids[1 - active], old = vids[active];
		if (v.dataset.n !== String(seg.meta.n)) { v.src = seg.url; v.dataset.n = String(seg.meta.n); }
		v.hidden = false;
		var pr = v.play();
		if (pr && pr.catch) pr.catch(function () {});
		if (old !== v) old.hidden = true;
		if (cur) URL.revokeObjectURL(cur.url);
		active = 1 - active;
		cur = seg;
		$('wz-img').hidden = true;
		$('wz-empty').hidden = true;
		preload();
	}
	function onEnded(e) { if (e.target === vids[active]) startNext(); }
	function syncState() {
		if (cur && vids.length) {
			var t = vids[active].currentTime, s = null;
			cur.meta.states.forEach(function (r) { if (r.t <= t + 0.05) s = r.state; });
			if (s) {
				var k = cur.meta.n + ':' + s.seq;
				if (k !== vkey) { vkey = k; vstate = s; if (latest) render(latest); }
			}
		}
		requestAnimationFrame(syncState);
	}
	requestAnimationFrame(syncState);

	function render(payload) {
		var video = segFresh(payload);
		var s = video && vstate ? vstate : payload.state;
		var link = linkInfo(payload);
		renderLeft(s, link);
		renderVideo(s, link);
		renderCards(s, link, payload);
		renderRight(s, payload);
		$('wz-period').textContent = video ? '视频 5 帧/秒 · 测距 1 次/秒' : $('wz-period').textContent;
		if (video) return;
		vids.forEach(function (v) { v.hidden = true; });
		if (s && s.seq !== lastSeq) { lastSeq = s.seq; loadFrame(s.seq); }
	}

	function poll() {
		var video = false;
		fetch(BASE + '?wz=state&t=' + Date.now(), { credentials: 'same-origin', cache: 'no-store' })
			.then(function (r) {
				if (r.status === 401) { location.reload(); throw new Error('logged out'); }
				return r.json();
			})
			.then(function (p) {
				latest = p;
				video = segFresh(p);
				if (video) { makeVideos(); queueSegments(p); }
				render(p);
			})
			.catch(function () { if (latest) render(latest); })
			.then(function () { setTimeout(poll, video ? POLL_VIDEO_MS : POLL_MS); });
	}
	render({ state: null, history: [], received_at: 0, server_time: 0 });
	poll();
})();
