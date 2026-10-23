/* 市场监控看板（/market）：根据页面内嵌的快照渲染。快照格式见 zlbx-tender-monitor 仓 src/pipeline/publish.py。 */
(function () {
	'use strict';
	var raw = document.getElementById('mm-data');
	if (!raw) { return; }
	var D = JSON.parse(raw.textContent || '{}');
	var app = document.getElementById('mm-app');
	var tip = document.getElementById('mm-tip');
	var tenders = D.tenders || [];
	var runs = D.runs || [];
	var today = (D.today || new Date().toISOString().slice(0, 10));

	/* ---------- 工具 ---------- */
	function esc(s) {
		return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
			return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
		});
	}
	function safeUrl(u) { return /^https?:\/\//i.test(u || '') ? u : ''; }
	function month(d) { return String(d || '').slice(0, 7); }
	function days(a, b) { return Math.round((Date.parse(b) - Date.parse(a)) / 86400000); }
	function fmtMoney(w) {
		if (w == null || w === '' || isNaN(w)) { return '未写预算'; }
		w = Number(w);
		return w >= 10000 ? (w / 10000).toFixed(2).replace(/\.?0+$/, '') + ' 亿元' : (w % 1 ? w.toFixed(1) : w) + ' 万元';
	}
	function fmtDate(d) { return d ? (+d.slice(5, 7)) + '月' + (+d.slice(8, 10)) + '日' : ''; }
	function stars(n) { n = n || 1; return '★★★'.slice(0, n) + '☆☆☆'.slice(0, 3 - n); }
	function due(t) {
		// 报名截止优先，其次开标时间
		var d = (t.signup || t.deadline || '').slice(0, 10);
		if (!d) { return null; }
		return { date: d, left: days(today, d), kind: t.signup ? '报名截止' : '开标' };
	}
	function sum(arr, f) { return arr.reduce(function (s, x) { return s + (Number(f(x)) || 0); }, 0); }
	function countBy(arr, f) {
		var m = {};
		arr.forEach(function (x) { var k = f(x) || '未注明'; m[k] = (m[k] || 0) + 1; });
		return Object.keys(m).map(function (k) { return [k, m[k]]; }).sort(function (a, b) { return b[1] - a[1]; });
	}

	/* ---------- 月份 ---------- */
	var months = {};
	runs.forEach(function (r) { months[month(r.date)] = 1; });
	tenders.forEach(function (t) { months[month(t.found)] = 1; });
	var monthList = Object.keys(months).filter(Boolean).sort().reverse();
	var sel = document.getElementById('mm-month');
	sel.innerHTML = monthList.map(function (m) {
		return '<option value="' + m + '">' + m.slice(0, 4) + '年' + (+m.slice(5)) + '月</option>';
	}).join('') + '<option value="all">全部</option>';
	var current = monthList[0] || 'all';
	sel.value = current;
	sel.addEventListener('change', function () { current = sel.value; render(); });

	if (D.generated_at) {
		document.getElementById('mm-updated').textContent = '数据更新于 ' + D.generated_at.slice(0, 16);
	}

	/* ---------- 渲染 ---------- */
	var tableState = { q: '', city: '', stage: '', section: '', sort: 'found', dir: -1 };

	function render() {
		var inMonth = function (d) { return current === 'all' || month(d) === current; };
		var T = tenders.filter(function (t) { return inMonth(t.found); });
		var R = runs.filter(function (r) { return inMonth(r.date); });
		var rec = T.filter(function (t) { return t.section === 'recommend'; })
			.sort(function (a, b) { return (b.score || 0) - (a.score || 0); });
		var upcoming = tenders.map(function (t) { return { t: t, d: due(t) }; })
			.filter(function (x) { return x.d && x.d.left >= 0; })
			.sort(function (a, b) { return a.d.left - b.d.left; });
		var label = current === 'all' ? '全部时间' : (current.slice(0, 4) + '年' + (+current.slice(5)) + '月');

		var html = '';
		html += kpis(T, R, rec, upcoming, label);
		html += '<div class="mm-grid-2">' + recCard(rec, label) + dueCard(upcoming) + '</div>';
		html += dailyCard(R, T, label);
		html += '<div class="mm-grid-3">' +
			barsCard('地区分布', '按城市统计的相关公告', countBy(T, function (t) { return t.city; }).slice(0, 8)) +
			barsCard('公告阶段', '采购意向 → 预招标 → 招标', countBy(T, function (t) { return t.stage; })) +
			barsCard('预算区间', '按公告写明的预算', budgetBands(T)) + '</div>';
		html += buyersCard(T);
		html += tableCard();
		html += foot();
		app.innerHTML = html;
		bindTable();
		bindDaily();
		drawTable();
	}

	function kpis(T, R, rec, upcoming, label) {
		var scanned = sum(R, function (r) { return r.scanned; });
		var soon = upcoming.filter(function (x) { return x.d.left <= 7; }).length;
		var money = sum(T, function (t) { return t.amount_wan; });
		return '<section class="mm-kpis" aria-label="' + esc(label) + '概览">' +
			kpi('推荐关注', rec.length, '条', label + '，系统推荐', true) +
			kpi('相关公告', T.length, '条', '推荐 + 供参考') +
			kpi('涉及预算', money >= 10000 ? (money / 10000).toFixed(2) : Math.round(money), money >= 10000 ? '亿元' : '万元', '相关公告预算合计') +
			kpi('扫描公告', scanned, '条', R.length + ' 个工作日自动检索') +
			kpi('7 天内截止', soon, '条', '报名或开标') +
			'</section>';
	}
	function kpi(k, v, unit, note, accent) {
		return '<div class="mm-kpi' + (accent ? ' is-accent' : '') + '"><div class="k">' + esc(k) + '</div>' +
			'<div class="v">' + esc(v) + '<small>' + esc(unit) + '</small></div><div class="n">' + esc(note) + '</div></div>';
	}

	function tags(t) {
		var out = [];
		if (t.key_customer) { out.push('<span class="mm-tag is-key">重点客户</span>'); }
		if (t.progress) {
			out.push('<span class="mm-tag is-progress">项目进展：' + esc(t.progress.prev_stage || '前一阶段') +
				(t.progress.prev_date ? '（' + esc(fmtDate(t.progress.prev_date)) + '）' : '') + ' → ' + esc(t.stage || '') + '</span>');
		}
		if (t.stage) { out.push('<span class="mm-tag">' + esc(t.stage) + '</span>'); }
		if (t.city) { out.push('<span class="mm-tag">' + esc(t.city) + '</span>'); }
		out.push('<span class="mm-tag">' + esc(fmtMoney(t.amount_wan)) + '</span>');
		if (t.customer_type) { out.push('<span class="mm-tag is-ref">' + esc(t.customer_type) + '</span>'); }
		return '<div class="mm-tags">' + out.join('') + '</div>';
	}
	function dueText(t) {
		var d = due(t);
		if (!d) { return '公告未写截止时间'; }
		if (d.left < 0) { return d.kind + ' ' + fmtDate(d.date) + '（已过）'; }
		return d.kind + ' ' + fmtDate(d.date) + '（<span class="mm-due' + (d.left <= 5 ? ' is-soon' : '') + '">' +
			(d.left === 0 ? '今天' : '还剩 ' + d.left + ' 天') + '</span>）';
	}
	function titleLink(t, cls) {
		var u = safeUrl(t.url);
		return u ? '<a class="' + cls + '" href="' + esc(u) + '" target="_blank" rel="noopener noreferrer">' + esc(t.title) + '</a>'
			: '<span class="' + cls + '">' + esc(t.title) + '</span>';
	}

	function recCard(rec, label) {
		var body = rec.length ? '<div class="mm-recs">' + rec.map(function (t) {
			return '<article class="mm-rec"><div class="mm-rec-head"><span class="mm-stars" title="推荐等级">' + stars(t.stars) + '</span></div>' +
				titleLink(t, 'mm-rec-title') +
				'<div class="mm-rec-meta">采购单位：' + esc(t.buyer || '未注明') + ' · 发现于 ' + esc(fmtDate(t.found)) + ' · ' + dueText(t) + '</div>' +
				(t.summary ? '<div class="mm-rec-sum">' + esc(t.summary) + '</div>' : '') + tags(t) + '</article>';
		}).join('') + '</div>' : '<p class="mm-none">' + esc(label) + '暂无推荐关注的公告。</p>';
		return '<section class="mm-card"><h2>推荐关注</h2><p class="mm-sub">' + esc(label) +
			' · 按推荐等级排序；★★★ 最高，综合产品相关度、预算、采购单位和地区</p>' + body + '</section>';
	}

	function dueCard(upcoming) {
		var list = upcoming.slice(0, 8);
		var body = list.length ? '<ul class="mm-list">' + list.map(function (x) {
			var t = x.t, d = x.d;
			return '<li><div class="d' + (d.left <= 5 ? ' is-soon' : '') + '"><b>' + d.left + '</b><span>天</span></div>' +
				'<div class="t">' + titleLink(t, '') + '<small>' + esc(d.kind) + ' ' + esc(fmtDate(d.date)) + ' · ' +
				esc(t.city || '') + ' · ' + esc(fmtMoney(t.amount_wan)) + (t.section === 'recommend' ? ' · 推荐' : '') + '</small></div></li>';
		}).join('') + '</ul>' : '<p class="mm-none">目前没有未截止的公告。</p>';
		return '<section class="mm-card"><h2>即将截止</h2><p class="mm-sub">全部相关公告中尚未截止的，按剩余天数排序</p>' + body + '</section>';
	}

	/* 每日检索量：浅色柱 = 扫描公告数，深色柱 = 其中的相关公告（同一坐标轴） */
	var dailyRows = [];
	function dailyCard(R, T, label) {
		var byDay = {};
		T.forEach(function (t) { byDay[t.found] = (byDay[t.found] || 0) + 1; });
		dailyRows = R.slice().sort(function (a, b) { return a.date < b.date ? -1 : 1; }).map(function (r) {
			return { date: r.date, scanned: r.scanned || 0, relevant: byDay[r.date] || 0, rec: r.recommended || 0 };
		});
		if (current === 'all' && dailyRows.length > 62) { dailyRows = dailyRows.slice(-62); }
		var n = dailyRows.length;
		if (!n) {
			return '<section class="mm-card"><h2>每日检索</h2><p class="mm-none">' + esc(label) + '没有运行记录。</p></section>';
		}
		// 按实际宽度绘制，手机上坐标文字也保持 11px
		var W = Math.max(280, Math.round(app.clientWidth - 70)), H = 220, L = 34, B = 26, top = 10;
		var max = Math.max.apply(null, dailyRows.map(function (r) { return r.scanned; }).concat([1]));
		var step = niceStep(max), ymax = Math.ceil(max / step) * step;
		var bw = (W - L) / n, barW = Math.max(4, Math.min(28, bw * 0.6));
		var y = function (v) { return top + (H - top - B) * (1 - v / ymax); };
		var svg = '<svg class="mm-chart" id="mm-daily" viewBox="0 0 ' + W + ' ' + H + '" role="img" aria-label="每日扫描公告数与其中相关公告数">';
		for (var g = 0; g <= ymax; g += step) {
			svg += '<line class="grid" x1="' + L + '" x2="' + W + '" y1="' + y(g) + '" y2="' + y(g) + '"/>' +
				'<text x="' + (L - 6) + '" y="' + (y(g) + 4) + '" text-anchor="end">' + g + '</text>';
		}
		var every = Math.ceil(n / Math.max(4, Math.floor(W / 70)));
		dailyRows.forEach(function (r, i) {
			var cx = L + bw * i + bw / 2, x = cx - barW / 2;
			svg += '<rect class="hit" data-i="' + i + '" x="' + (L + bw * i) + '" y="' + top + '" width="' + bw + '" height="' + (H - top - B) + '"/>';
			svg += roundedBar('bar-2', x, y(r.scanned), barW, y(0) - y(r.scanned));
			if (r.relevant) { svg += roundedBar('bar', x, y(r.relevant), barW, y(0) - y(r.relevant)); }
			if (i % every === 0) {
				svg += '<text x="' + cx + '" y="' + (H - 8) + '" text-anchor="middle">' + (+r.date.slice(5, 7)) + '/' + (+r.date.slice(8, 10)) + '</text>';
			}
		});
		svg += '</svg>';
		return '<section class="mm-card"><h2>每日检索</h2><p class="mm-sub">' + esc(label) +
			' · 每个工作日上午自动检索一次；把鼠标移到柱子上看当天数字</p>' +
			'<div class="mm-legend"><span><i style="background:#a9c8ef"></i>扫描公告</span><span><i style="background:#2a78d6"></i>其中相关</span></div>' +
			svg + '</section>';
	}
	function roundedBar(cls, x, y, w, h) {
		if (h <= 0) { return ''; }
		var r = Math.min(4, w / 2, h);
		return '<path class="' + cls + '" d="M' + x + ',' + (y + h) + 'V' + (y + r) + 'Q' + x + ',' + y + ' ' + (x + r) + ',' + y +
			'H' + (x + w - r) + 'Q' + (x + w) + ',' + y + ' ' + (x + w) + ',' + (y + r) + 'V' + (y + h) + 'Z"/>';
	}
	function niceStep(max) {
		var raw = max / 4, p = Math.pow(10, Math.floor(Math.log10(raw || 1))), n = raw / p;
		return (n <= 1 ? 1 : n <= 2 ? 2 : n <= 5 ? 5 : 10) * p;
	}
	function bindDaily() {
		var svg = document.getElementById('mm-daily');
		if (!svg) { return; }
		svg.addEventListener('pointermove', function (e) {
			var i = e.target.getAttribute && e.target.getAttribute('data-i');
			if (i == null) { tip.hidden = true; return; }
			var r = dailyRows[+i];
			tip.innerHTML = '<b>' + esc(fmtDate(r.date)) + '</b>扫描公告 ' + r.scanned + ' 条<br>其中相关 ' + r.relevant + ' 条<br>推荐关注 ' + r.rec + ' 条';
			tip.hidden = false;
			var x = Math.min(e.clientX + 14, window.innerWidth - tip.offsetWidth - 8);
			tip.style.left = x + 'px';
			tip.style.top = (e.clientY + 14) + 'px';
		});
		svg.addEventListener('pointerleave', function () { tip.hidden = true; });
	}

	function budgetBands(T) {
		var bands = [['50 万以下', 0, 50], ['50–200 万', 50, 200], ['200–1000 万', 200, 1000], ['1000 万以上', 1000, Infinity]];
		var out = bands.map(function (b) {
			return [b[0], T.filter(function (t) { return t.amount_wan != null && t.amount_wan >= b[1] && t.amount_wan < b[2]; }).length];
		});
		out.push(['未写预算', T.filter(function (t) { return t.amount_wan == null; }).length]);
		return out;
	}
	function barsCard(title, sub, rows) {
		var max = Math.max.apply(null, rows.map(function (r) { return r[1]; }).concat([1]));
		var body = rows.length && max ? '<div class="mm-hbars">' + rows.map(function (r) {
			return '<div class="mm-hbar" title="' + esc(r[0]) + '：' + r[1] + ' 条"><span class="l">' + esc(r[0]) + '</span>' +
				'<span class="track"><span class="fill" style="width:' + (r[1] / max * 100) + '%;' + (r[1] ? '' : 'display:none') + '"></span></span>' +
				'<span class="c">' + r[1] + '</span></div>';
		}).join('') + '</div>' : '<p class="mm-none">暂无数据</p>';
		return '<section class="mm-card"><h2>' + esc(title) + '</h2><p class="mm-sub">' + esc(sub) + '</p>' + body + '</section>';
	}

	function buyersCard(T) {
		var m = {};
		T.forEach(function (t) {
			var k = t.buyer || '未注明';
			m[k] = m[k] || { n: 0, money: 0, key: false, type: t.customer_type || '' };
			m[k].n += 1; m[k].money += Number(t.amount_wan) || 0; m[k].key = m[k].key || !!t.key_customer;
		});
		var rows = Object.keys(m).map(function (k) { return [k, m[k]]; })
			.sort(function (a, b) { return (b[1].key - a[1].key) || (b[1].n - a[1].n) || (b[1].money - a[1].money); }).slice(0, 10);
		var body = rows.length ? '<div class="mm-table-wrap"><table class="mm-table" style="min-width:520px"><thead><tr><th>采购单位</th><th>类型</th><th>公告数</th><th>预算合计</th></tr></thead><tbody>' +
			rows.map(function (r) {
				return '<tr><td>' + esc(r[0]) + (r[1].key ? ' <span class="mm-tag is-key">重点客户</span>' : '') + '</td><td>' + esc(r[1].type || '—') +
					'</td><td class="num">' + r[1].n + '</td><td class="num">' + esc(r[1].money ? fmtMoney(r[1].money) : '—') + '</td></tr>';
			}).join('') + '</tbody></table></div>' : '<p class="mm-none">暂无数据</p>';
		return '<section class="mm-card"><h2>采购单位</h2><p class="mm-sub">重点客户在前，其余按公告数排序（前 10）</p>' + body + '</section>';
	}

	/* ---------- 全部记录 ---------- */
	function options(list, all) {
		return '<option value="">' + all + '</option>' + list.map(function (v) { return '<option>' + esc(v) + '</option>'; }).join('');
	}
	function uniq(f) {
		var m = {};
		tenders.forEach(function (t) { var v = f(t); if (v) { m[v] = 1; } });
		return Object.keys(m).sort();
	}
	function tableCard() {
		return '<section class="mm-card"><h2>全部记录</h2><p class="mm-sub">推荐关注与供参考的全部公告（不受上方月份限制）；点表头排序</p>' +
			'<div class="mm-filter">' +
			'<input type="search" id="mm-q" placeholder="搜索标题、采购单位" value="' + esc(tableState.q) + '" aria-label="搜索">' +
			'<select id="mm-f-city" aria-label="城市">' + options(uniq(function (t) { return t.city; }), '全部城市') + '</select>' +
			'<select id="mm-f-stage" aria-label="阶段">' + options(uniq(function (t) { return t.stage; }), '全部阶段') + '</select>' +
			'<select id="mm-f-section" aria-label="类别"><option value="">推荐 + 供参考</option><option value="recommend">只看推荐</option><option value="reference">只看供参考</option></select>' +
			'</div><div class="mm-table-wrap"><table class="mm-table"><thead><tr>' +
			th('found', '发现日期') + th('stars', '等级') + th('title', '公告') + th('buyer', '采购单位') + th('city', '城市') +
			th('stage', '阶段') + th('amount_wan', '预算') + th('due', '截止') +
			'</tr></thead><tbody id="mm-tbody"></tbody></table></div><div class="mm-count" id="mm-count"></div></section>';
	}
	function th(k, label) { return '<th data-k="' + k + '" scope="col">' + label + '</th>'; }
	function bindTable() {
		var q = document.getElementById('mm-q');
		q.addEventListener('input', function () { tableState.q = q.value; drawTable(); });
		[['mm-f-city', 'city'], ['mm-f-stage', 'stage'], ['mm-f-section', 'section']].forEach(function (p) {
			var el = document.getElementById(p[0]);
			el.value = tableState[p[1]];
			el.addEventListener('change', function () { tableState[p[1]] = el.value; drawTable(); });
		});
		Array.prototype.forEach.call(app.querySelectorAll('.mm-table th[data-k]'), function (h) {
			h.addEventListener('click', function () {
				var k = h.getAttribute('data-k');
				tableState.dir = tableState.sort === k ? -tableState.dir : (k === 'title' || k === 'buyer' || k === 'city' || k === 'stage' ? 1 : -1);
				tableState.sort = k;
				drawTable();
			});
		});
	}
	function drawTable() {
		var s = tableState, q = s.q.trim().toLowerCase();
		var rows = tenders.filter(function (t) {
			return (!q || (String(t.title) + String(t.buyer)).toLowerCase().indexOf(q) >= 0) &&
				(!s.city || t.city === s.city) && (!s.stage || t.stage === s.stage) && (!s.section || t.section === s.section);
		});
		var key = function (t) {
			if (s.sort === 'due') { var d = due(t); return d ? d.date : ''; }
			if (s.sort === 'stars') { return t.score || 0; }
			return t[s.sort] == null ? '' : t[s.sort];
		};
		rows.sort(function (a, b) {
			var x = key(a), y = key(b);
			return (x > y ? 1 : x < y ? -1 : 0) * s.dir;
		});
		document.getElementById('mm-tbody').innerHTML = rows.map(function (t) {
			var d = due(t);
			return '<tr><td class="nowrap">' + esc(t.found) + '</td><td class="nowrap"><span class="mm-stars">' + stars(t.stars) + '</span>' +
				(t.section === 'reference' ? '<br><span class="mm-tag is-ref">供参考</span>' : '') + '</td><td>' + titleLink(t, '') +
				(t.key_customer ? ' <span class="mm-tag is-key">重点客户</span>' : '') + '</td><td>' + esc(t.buyer || '') + '</td><td class="nowrap">' +
				esc(t.city || '') + '</td><td class="nowrap">' + esc(t.stage || '') + '</td><td class="num">' + esc(t.amount_wan == null ? '—' : fmtMoney(t.amount_wan)) +
				'</td><td class="nowrap">' + (d ? esc(fmtDate(d.date)) + (d.left >= 0 ? '<br><small class="mm-due' + (d.left <= 5 ? ' is-soon' : '') + '">还剩 ' + d.left + ' 天</small>' : '<br><small>已过</small>') : '—') + '</td></tr>';
		}).join('') || '<tr><td colspan="8" class="mm-none">没有符合条件的公告</td></tr>';
		document.getElementById('mm-count').textContent = '共 ' + rows.length + ' 条';
		Array.prototype.forEach.call(app.querySelectorAll('.mm-table th[data-k]'), function (h) {
			h.setAttribute('aria-sort', h.getAttribute('data-k') === s.sort ? (s.dir > 0 ? 'ascending' : 'descending') : 'none');
		});
	}

	function foot() {
		var sc = D.scope || {};
		return '<footer class="mm-foot">' + (sc.text ? esc(sc.text) + '<br>' : '') +
			'数据来源：知了标讯公开招标信息，每个工作日上午自动检索、筛选和打分' +
			(D.generated_at ? ' · 更新于 ' + esc(D.generated_at.slice(0, 16)) : '') + '</footer>';
	}

	render();
	var lastW = app.clientWidth, timer = null;
	window.addEventListener('resize', function () {
		clearTimeout(timer);
		timer = setTimeout(function () {
			if (Math.abs(app.clientWidth - lastW) > 40) { lastW = app.clientWidth; render(); }
		}, 200);
	});
}());
