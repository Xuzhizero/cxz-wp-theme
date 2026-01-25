/* CXZ Home scripts (ported from ~/temp/front-page.php and adapted to WP theme) */
(function () {
  "use strict";

  // ===== Color mode bootstrap (ported from original inline script) =====
  var lsKey = "color-mode-pref";

  window._hb = window._hb || {
    __domain: "blogs.hyvor.com",
    subdomain: "coolxuzhi",
    color_modes: "both",
    color_mode_default: "os",
    base_url: ""
  };

  window._hb.getColorModePreference = function () {
    var ls = null;
    try {
      ls = localStorage.getItem(lsKey);
    } catch (e) {}
    return ls || window._hb.color_mode_default;
  };

  window._hb.getColorMode = function (pref) {
    var mode = pref || window._hb.getColorModePreference();
    if (mode === "os") {
      mode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
    }
    return mode;
  };

  window._hb.changeColorMode = function (pref, noSave) {
    var renderMode = window._hb.getColorMode(pref);
    var a, r;
    if (renderMode === "dark") {
      a = "mode-dark";
      r = "mode-light";
    } else {
      a = "mode-light";
      r = "mode-dark";
    }

    var html = document.documentElement;
    html.classList.remove(r, "mode-preference-light", "mode-preference-dark", "mode-preference-os");
    html.classList.add(a, "mode-preference-" + pref);

    if (!noSave) {
      try {
        localStorage.setItem(lsKey, pref);
      } catch (e) {}
    }

    window.dispatchEvent(
      new CustomEvent("hb:colorModeChanged", {
        detail: { mode: renderMode }
      })
    );
  };

  window._hb.dataApi = function (version, endpoint, params, onSuccess, onError) {
    var url =
      "//" +
      window._hb.__domain +
      "/api/data/" +
      version +
      "/" +
      window._hb.subdomain +
      "/" +
      endpoint +
      (params ? "?" + new URLSearchParams(params || {}).toString() : "");
    var xhr = new XMLHttpRequest();
    xhr.onload = function () {
      try {
        onSuccess(JSON.parse(this.responseText));
      } catch (e) {
        onError && onError(e);
      }
    };
    xhr.onerror = function () {
      onError && onError();
    };
    xhr.open("GET", url, true);
    xhr.send();
    return xhr;
  };

  // Initialize asap to reduce flicker
  window._hb.changeColorMode(
    window._hb.color_modes !== "both" ? window._hb.color_modes : window._hb.getColorModePreference(),
    true
  );

  // ===== UI interactions =====
  function $(sel) {
    return document.querySelector(sel);
  }

  function updateColorModeToggleLabel() {
    var el = $("#color-mode-toggle-label");
    if (!el) return;
    var isDark = document.documentElement.classList.contains("mode-dark");
    // 显示“下一步将切换到什么模式”
    el.textContent = isDark ? "Light" : "Dark";
  }

  function toggleColorModeImpl() {
    var isDark = document.documentElement.classList.contains("mode-dark");
    window._hb.changeColorMode(isDark ? "light" : "dark");
    updateColorModeToggleLabel();
  }

  function menuToggleImpl() {
    var menu = $("#menuList");
    if (!menu) return;
    menu.classList.toggle("is-open");
  }

  function toggleLanguageDropdownImpl() {
    var dd = $("#lang-selector");
    if (!dd) return;
    dd.classList.toggle("is-open");
  }

  function openSearch() {
    var w = $(".search-wrapper");
    if (!w) return;
    w.classList.add("is-active");
    var input = w.querySelector(".search-field");
    if (input) input.focus();
  }

  function closeSearch() {
    var w = $(".search-wrapper");
    if (!w) return;
    w.classList.remove("is-active");
  }

  // Expose for inline onclick handlers already in markup
  window.menuToggle = menuToggleImpl;
  window.toggleLanguageDropdown = toggleLanguageDropdownImpl;
  window.toggleColorMode = toggleColorModeImpl;

  // Init label and keep it in sync
  updateColorModeToggleLabel();
  window.addEventListener("hb:colorModeChanged", updateColorModeToggleLabel);

  // ===== Header visibility: only show at very top =====
  (function () {
    var header = document.querySelector(".header");
    if (!header) return;
    var lastHidden = null;
    function syncHeaderVisibility() {
      var y = window.scrollY || 0;
      var shouldHide = y > 10;
      if (lastHidden === shouldHide) return;
      lastHidden = shouldHide;
      header.classList.toggle("is-hidden", shouldHide);
      if (shouldHide) {
        // close mobile menu / dropdowns when leaving top
        var menu = $("#menuList");
        if (menu) menu.classList.remove("is-open");
        var dd = $("#lang-selector");
        if (dd) dd.classList.remove("is-open");
        closeSearch();
      }
    }
    syncHeaderVisibility();
    window.addEventListener("scroll", syncHeaderVisibility, { passive: true });
  })();

  document.addEventListener("click", function (e) {
    var target = e.target;
    if (!target) return;

    // Open search
    if (target.closest && target.closest(".nav-search-button")) {
      e.preventDefault();
      openSearch();
      return;
    }

    // Close search
    if (target.closest && target.closest(".search-wrapper-close")) {
      e.preventDefault();
      closeSearch();
      return;
    }

    // Clicking outside language dropdown closes it
    var dd = $("#lang-selector");
    var label = $(".lang-label");
    if (dd && dd.classList.contains("is-open") && label) {
      var clickInDd = dd.contains(target) || label.contains(target);
      if (!clickInDd) dd.classList.remove("is-open");
    }
  });

  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") closeSearch();
  });
})();

