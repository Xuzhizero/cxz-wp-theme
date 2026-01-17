<?php
// 独立首页：不调用主题 get_header/get_footer，避免主题导航/容器影响布局
?>
<!doctype html>
<html <?php language_attributes(); ?> class="mode-light">
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>CoolXuzhi</title>
  <meta name="description" content="" />
  <link rel="canonical" href="https://CoolXuzhi.com" />
  <link rel="shortcut icon" href="https://Chennative.ai/media/blog-icon-2.svg" />
  <meta property="og:site_name" content="CoolXuzhi" />
  <meta property="og:type" content="article" />
  <meta property="og:title" content="CoolXuzhi" />
  <meta property="og:locale" content="en" />

  <!-- ===== 关键修复1：CSS必须在head内 ===== -->
  <style>
    /* ===== WordPress 环境对齐修正（关键） ===== */
    html, body {
      background: #000 !important;
      margin: 0 !important;
      padding: 0 !important;
      width: 100%;
      height: 100%;
    }
    body {
      overflow-x: hidden;
      font-smoothing: antialiased;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }
    .hero-image,
    .hero-image::before,
    .hero-image::after {
      background-color: transparent !important;
    }
    .hero-image::after {
      content: "";
      position: absolute;
      inset: 0;
      background: radial-gradient(
        ellipse at center,
        rgba(0,0,0,0.25) 0%,
        rgba(0,0,0,0.55) 60%,
        rgba(0,0,0,0.75) 100%
      );
      pointer-events: none;
    }
    .hero,
    .container,
    .content,
    main {
      position: relative;
      z-index: 2;
    }
    .container,
    .container-big {
      max-width: none !important;
    }
    body, p, h1, h2, h3, h4 {
      text-rendering: optimizeLegibility;
    }
    /* ===== 修正结束 ===== */

    :root {
      --base-font-family: Libre Baskerville, Noto Sans,-apple-system, BlinkMacSystemFont, Segoe UI, Ubuntu, Cantarell, Helvetica, Arial, sans-serif;
      --heading-font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Ubuntu, Cantarell, Helvetica, Arial, sans-serif;
      --base-font-size: 18px;
      --base-font-style: normal;
      --base-font-weight: normal;
      --base-line-height: 1.8;
    }
    .mode-light:root {
      --color-accent:  #FF4612;
      --background-color: #FAF9F6;
      --background-alt-color: #fffefa;
      --background-gradient-start: rgba(250,249,246, 0.25);
      --background-gradient-end: rgba(250,249,246, 1);
      --text-color:  #635F5A;
      --text-alt-color:  #f3f3f3;
      --heading-font-color:  #1c0f05;
      --link-color:  #1c0a05;
      --link-color-hover:  #ff2a2a;
      --border-color:  #e6e6e6;
      --code-color:  #eb5757;
      --code-background-color:  #e6e6e6;
    }
    .mode-dark:root {
      --color-accent:  #E26E41;
      --background-color:  #1F1500;
      --background-alt-color:  #141312;
      --background-gradient-start: rgba(26,26,28, 0.25);
      --background-gradient-end: rgba(26,26,28, 1);
      --text-color:  #f0f0f0;
      --text-alt-color: #fff;
      --heading-font-color:  #f0f0f0;
      --link-color:  #f0f0f0;
      --link-color-hover:  #E07E38;
      --border-color:  #303237;
      --code-color:  #ababab;
      --code-background-color:  #454545;
    }
    
    /* Tall Cards for Belief Section Only */
    .custom-tall-card {
        min-height: 550px !important;
        display: flex !important;
        flex-direction: column !important;
    }
    .custom-tall-card .article__inner {
        flex: 1 !important;
        height: 100% !important;
    }
    .custom-tall-card .article__description {
        display: block !important;
        white-space: normal !important;
        overflow: visible !important;
        text-overflow: clip !important;
        -webkit-line-clamp: unset !important;
        height: auto !important;
    }
    
    /* Footer Widget Alignment */
    .widget-row-aligned {
        display: flex;
        flex-wrap: wrap;
    }
    .widget-col-equal {
        display: flex;
        flex-direction: column;
    }
    .widget-box-equal {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 450px;
    }
    /* Newsletter iframe scaling */
    .newsletter-iframe-container {
        flex: 1;
        width: 100%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .newsletter-iframe {
        width: 74%;
        height: 74%;
        transform: scale(1.35);
        transform-origin: center center;
        border: none;
    }
  </style>

  <!-- ===== 关键修复2：颜色模式初始化脚本 ===== -->
  <script>
    window._hb = {
        __domain: "blogs.hyvor.com",
        subdomain: "coolxuzhi",
        color_modes: "both",
        color_mode_default: "os",
        base_url: "https://CoolXuzhi.com"
    };
    (function() {
        var lsKey = 'color-mode-pref';
        window._hb.changeColorMode = function(pref, noSave) {
            var renderMode = _hb.getColorMode(pref)
            var a, r;
            if (renderMode === 'dark') {
                a = 'mode-dark';
                r = 'mode-light';
            } else {
                a = 'mode-light';
                r = 'mode-dark';
            }
            var html = document.documentElement;
            html.classList.remove(r, 'mode-preference-light', 'mode-preference-dark', 'mode-preference-os');
            html.classList.add(a, 'mode-preference-' + pref);
            if (!noSave) {
                try {localStorage.setItem(lsKey, pref)} catch (e) {}
            }
            window.dispatchEvent(new CustomEvent("hb:colorModeChanged", {
                detail: { mode: renderMode }
            }))
        }
        window._hb.getColorModePreference = function() {
            var ls = null;
            try { ls = localStorage.getItem(lsKey) } catch (e) {}
            return ls || _hb.color_mode_default;
        }
        window._hb.getColorMode = function(pref) {
            var mode = pref || _hb.getColorModePreference();
            if (mode === 'os') {
                mode = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            return mode
        }
        _hb.changeColorMode(
            _hb.color_modes !== "both" ? _hb.color_modes : _hb.getColorModePreference(),
            true
        )
        window._hb.dataApi = function(version, endpoint, params, onSuccess, onError) {
            var url = "//" + _hb.__domain + "/api/data/" + version + "/" + _hb.subdomain + "/" + endpoint
                + (params ? ("?" + new URLSearchParams(params || {}).toString()) : "");
            var xhr = new XMLHttpRequest();
            xhr.onload = function() { onSuccess(JSON.parse(this.responseText)) }
            xhr.onerror = function() { onError && onError() }
            xhr.open('GET', url, true)
            xhr.send();
            return xhr;
        }
    })()
  </script>

  <link rel="stylesheet" href="https://Chennative.ai/styles.css?v=190" />
  <script src="https://Chennative.ai/assets/script.js"></script>

  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<header class="header">
  <div class="container-big">
    <div class="row">
      <div class="header__inner col col-12">
        <div class="logo">
          <a class="logo__link" href="https://CoolXuzhi.com">
              <img class="logo__image" src="https://Chennative.ai/media/blog-2.svg" alt="CoolXuzhi"/>
          </a>
        </div>
        <div class="hamburger" onclick="menuToggle()" id="hamburger">
          <div></div>
        </div>
        <nav class="main-nav" aria-label="Main menu" id="menuList">
          <div class="main-nav__box">
            <ul class="nav__list list-reset">
              <li class="nav__item">
                <a href="https://CoolXuzhi.com/disclaimer" class="nav__link nav__disclaimer">Our Team</a>
              </li>
            </ul>
          </div>
          <div class="nav-button">
            <div class="color-mode-toggler">
              <a onclick="_hb.changeColorMode('light')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="14" height="14" fill="currentColor" style="vertical-align:middle"><path d="M256 118a22 22 0 01-22-22V48a22 22 0 0144 0v48a22 22 0 01-22 22zM256 486a22 22 0 01-22-22v-48a22 22 0 0144 0v48a22 22 0 01-22 22zM369.14 164.86a22 22 0 01-15.56-37.55l33.94-33.94a22 22 0 0131.11 31.11l-33.94 33.94a21.93 21.93 0 01-15.55 6.44zM108.92 425.08a22 22 0 01-15.55-37.56l33.94-33.94a22 22 0 1131.11 31.11l-33.94 33.94a21.94 21.94 0 01-15.56 6.45zM464 278h-48a22 22 0 010-44h48a22 22 0 010 44zM96 278H48a22 22 0 010-44h48a22 22 0 010 44zM403.08 425.08a21.94 21.94 0 01-15.56-6.45l-33.94-33.94a22 22 0 0131.11-31.11l33.94 33.94a22 22 0 01-15.55 37.56zM142.86 164.86a21.89 21.89 0 01-15.55-6.44l-33.94-33.94a22 22 0 0131.11-31.11l33.94 33.94a22 22 0 01-15.56 37.55zM256 358a102 102 0 11102-102 102.12 102.12 0 01-102 102z"/></svg> <span>Light</span></a>
              <a onclick="_hb.changeColorMode('dark')"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="14" height="14" fill="currentColor" style="vertical-align:middle"><path d="M264 480A232 232 0 0132 248c0-94 54-178.28 137.61-214.67a16 16 0 0121.06 21.06C181.07 76.43 176 104.66 176 136c0 110.28 89.72 200 200 200 31.34 0 59.57-5.07 81.61-14.67a16 16 0 0121.06 21.06C442.28 426 358 480 264 480z"/></svg> <span>Dark</span></a>
            </div>
            <div class="nav-lang">
              <a class="lang-label" role="label" onclick="toggleLanguageDropdown()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="14" height="14" fill="currentColor" style="vertical-align:middle"><path d="M414.39 97.74A224 224 0 1097.61 414.52 224 224 0 10414.39 97.74zM64 256.13a191.63 191.63 0 016.7-50.31c7.34 15.8 18 29.45 25.25 45.66 9.37 20.84 34.53 15.06 45.64 33.32 9.86 16.21-.67 36.71 6.71 53.67 5.36 12.31 18 15 26.72 24 8.91 9.08 8.72 21.52 10.08 33.36a305.36 305.36 0 007.45 41.27c0 .1 0 .21.08.31C117.8 411.13 64 339.8 64 256.13zm192 192a193.12 193.12 0 01-32-2.68c.11-2.71.16-5.24.43-7 2.43-15.9 10.39-31.45 21.13-43.35 10.61-11.74 25.15-19.68 34.11-33 8.78-13 11.41-30.5 7.79-45.69-5.33-22.44-35.82-29.93-52.26-42.1-9.45-7-17.86-17.82-30.27-18.7-5.72-.4-10.51.83-16.18-.63-5.2-1.35-9.28-4.15-14.82-3.42-10.35 1.36-16.88 12.42-28 10.92-10.55-1.41-21.42-13.76-23.82-23.81-3.08-12.92 7.14-17.11 18.09-18.26 4.57-.48 9.7-1 14.09.68 5.78 2.14 8.51 7.8 13.7 10.66 9.73 5.34 11.7-3.19 10.21-11.83-2.23-12.94-4.83-18.21 6.71-27.12 8-6.14 14.84-10.58 13.56-21.61-.76-6.48-4.31-9.41-1-15.86 2.51-4.91 9.4-9.34 13.89-12.27 11.59-7.56 49.65-7 34.1-28.16-4.57-6.21-13-17.31-21-18.83-10-1.89-14.44 9.27-21.41 14.19-7.2 5.09-21.22 10.87-28.43 3-9.7-10.59 6.43-14.06 10-21.46 1.65-3.45 0-8.24-2.78-12.75q5.41-2.28 11-4.23a15.6 15.6 0 008 3c6.69.44 13-3.18 18.84 1.38 6.48 5 11.15 11.32 19.75 12.88 8.32 1.51 17.13-3.34 19.19-11.86 1.25-5.18 0-10.65-1.2-16a190.83 190.83 0 01105 32.21c-2-.76-4.39-.67-7.34.7-6.07 2.82-14.67 10-15.38 17.12-.81 8.08 11.11 9.22 16.77 9.22 8.5 0 17.11-3.8 14.37-13.62-1.19-4.26-2.81-8.69-5.42-11.37a193.27 193.27 0 0118 14.14c-.09.09-.18.17-.27.27-5.76 6-12.45 10.75-16.39 18.05-2.78 5.14-5.91 7.58-11.54 8.91-3.1.73-6.64 1-9.24 3.08-7.24 5.7-3.12 19.4 3.74 23.51 8.67 5.19 21.53 2.75 28.07-4.66 5.11-5.8 8.12-15.87 17.31-15.86a15.4 15.4 0 0110.82 4.41c3.8 3.94 3.05 7.62 3.86 12.54 1.43 8.74 9.14 4 13.83-.41a192.12 192.12 0 019.24 18.77c-5.16 7.43-9.26 15.53-21.67 6.87-7.43-5.19-12-12.72-21.33-15.06-8.15-2-16.5.08-24.55 1.47-9.15 1.59-20 2.29-26.94 9.22-6.71 6.68-10.26 15.62-17.4 22.33-13.81 13-19.64 27.19-10.7 45.57 8.6 17.67 26.59 27.26 46 26 19.07-1.27 38.88-12.33 38.33 15.38-.2 9.81 1.85 16.6 4.86 25.71 2.79 8.4 2.6 16.54 3.24 25.21a158 158 0 004.74 30.07A191.75 191.75 0 01256 448.13z"/></svg>&nbsp;<span>English</span>
              </a>
              <div id="lang-selector">
                <a href="https://CoolXuzhi.com" class="active">English</a>
                <a href="https://CoolXuzhi.com/zh-cn" class="">中</a>
              </div>
              <div id="lang-selector-closer" onclick="toggleLanguageDropdown()"></div>
            </div>
            <div class="nav-search">
              <a class="nav-search-button"><i aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16" fill="currentColor" style="vertical-align:middle"><path d="M456.69 421.39L362.6 327.3a173.81 173.81 0 0034.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 00327.3 362.6l94.09 94.09a25 25 0 0035.3-35.3zM97.92 222.72a124.8 124.8 0 11124.8 124.8 124.95 124.95 0 01-124.8-124.8z"/></svg></i></a>
            </div>
          </div>
        </nav>
      </div>
    </div>
  </div>
</header>

<main class="content" role="main" aria-label="Content">
  <div class="hero-image">
    <div class="hero-image__inner">
      <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/image-17.jpg" alt="CoolXuzhi" loading="lazy">
    </div>
  </div>
  
  <section class="hero">
    <div class="container">
      <div class="row">
        <div class="col col-12">
          <div class="hero__inner">
            <h1 class="hero__title">CoolXuzhi</h1>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="container">
    <div class="row">
      <div class="col col-12">
        <div class="homepage-intro">
          <div class="homepage-intro__content">
            <h3>Fulfill the great historic mission of motherland's wholeness.  Propel the resolution of our nation's primary contradictions.</h3>
            <h3>Forge silicon colts amid the ocean's vastness. Comprehend the marine world in all its richness.</h3>
          </div>
        </div>
      </div>
    </div>
    
    <div class="row">
      <div class="tag-group">
        <div class="container">
          <div class="row">
            <div class="col col-12">
              <h2 class="tag-group__title">Belief</h2>
            </div>
          </div>
          <div class="row" style="display: flex; flex-wrap: wrap; justify-content: center;">
            <div class="article col col-4 col-d-6 col-t-12 custom-tall-card">
              <a href="https://CoolXuzhi.com/twilight-of-management-ai-native-enterprise" class="article__image">
                <img src="https://Chennative.ai/media/zRQJ4nowj6zwrIdc.png" alt="I. L5 Unmanned Water Operations" loading="lazy">
              </a>
              <div class="article__inner">
                <div class="article__content">
                  <h2 class="article__title">
                    <a href="https://CoolXuzhi.com/twilight-of-management-ai-native-enterprise">I. L5 Maritime Autonomy Will Precede Land Driving</a>
                  </h2>
                  <p class="article__description">L5 unmanned operations on water will be deployed and popularized earlier than land-based L5 autonomous driving.</p>
                </div>
              </div>
            </div>

            <div class="article col col-4 col-d-6 col-t-12 custom-tall-card">
              <a href="https://CoolXuzhi.com/skeuomorphism-trap-ai-native-liquid-business" class="article__image">
                <img src="https://Chennative.ai/media/9ajDbDgyqeNqlZfc.png" alt="II. Pavlovian Robots" loading="lazy">
              </a>
              <div class="article__inner">
                <div class="article__content">
                  <h2 class="article__title">
                    <a href="https://CoolXuzhi.com/skeuomorphism-trap-ai-native-liquid-business">II. The Emergence of Pavlovian Robots</a>
                  </h2>
                  <p class="article__description">Robots with canine-level IQ and EQ, capable of accepting Pavlovian-style training, are already on the horizon.</p>
                </div>
              </div>
            </div>

            <div class="article col col-4 col-d-6 col-t-12 custom-tall-card">
              <a href="https://CoolXuzhi.com/human-responsibility-in-ai-native-era" class="article__image">
                <span class="featured"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="14" height="14" fill="currentColor" style="vertical-align:middle"><path d="M394 480a16 16 0 01-9.39-3L256 383.76 127.39 477a16 16 0 01-24.55-18.08L153 310.35 23 221.2a16 16 0 019-29.2h160.38l48.4-148.95a16 16 0 0130.44 0l48.4 149H480a16 16 0 019.05 29.2L359 310.35l50.13 148.53A16 16 0 01394 480z"/></svg></span>
                <img src="https://Chennative.ai/media/image-9.png" alt="III. Automated Surface Robots" loading="lazy">
              </a>
              <div class="article__inner">
                <div class="article__content">
                  <h2 class="article__title">
                    <a href="https://CoolXuzhi.com/human-responsibility-in-ai-native-era">III. Automated Surface Robots: The Recycling Interface</a>
                  </h2>
                   <p class="article__description">These robots will pioneer waste reuse. The recycling revolution will be a vital part of the 21st-century energy revolution.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="tag-group">
        <div class="container">
          <div class="row">
            <div class="col col-12">
              <h2 class="tag-group__title">About Me</h2>
            </div>
          </div>

          <div class="row">
            <div class="col col-12">
                <div style="display: flex; flex-wrap: wrap; align-items: flex-start; margin-bottom: 50px; margin-top: 20px;">
                    <div style="flex: 0 0 300px; padding-right: 40px; margin-bottom: 20px;">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/Profile.jpg" alt="Profile" style="width: 100%; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                    </div>
                    <div style="flex: 1; min-width: 300px; font-family: var(--base-font-family); color: var(--text-color); font-size: 18px; line-height: 1.8;">
                        <p style="margin-bottom: 1em;">Like for you, life has many aspects for me.</p>
                        <p style="margin-bottom: 1em;">I am an engineer working on digital twins and intelligent maritime systems, a project coordinator navigating between simulation, control, and visualization, and a long-term builder at the intersection of technology, systems, and real-world application. I work with models, code, virtual environments, and people—often at the same time.</p>
                        <p style="margin-bottom: 1em;">Through years of technical work and project responsibility, I've come to realize that beneath all complexity, life is about understanding systems: how things truly work, how uncertainty emerges, and how order can be built with clarity and patience.</p>
                        <p>Through this space, I share my ongoing exploration—of technology, work, and self-development—with those who value depth over noise, long-term growth over quick wins, and the possibility of building something meaningful while staying honest with oneself.</p>
                    </div>
                </div>
            </div>
          </div>

          <div class="row" style="display: flex; flex-wrap: wrap; justify-content: center;">
            
            <div class="article col col-4 col-d-6 col-t-12 custom-tall-card">
              <a href="#" class="article__image">
                <img src="https://Chennative.ai/media/image-7.png" alt="CSDN Blog" loading="lazy">
              </a>
              <div class="article__inner">
                <div class="article__content">
                  <div class="article__meta">
                    <time class="article__date">Nov 28, 2025</time>
                  </div>
                  <h2 class="article__title">
                    <a href="#">Check out my CSDN Technical Blog</a>
                  </h2>
                  <p class="article__description">Contains a series of articles revolving around robots and AI.</p>
                </div>
              </div>
            </div>

            <div class="article col col-4 col-d-6 col-t-12 custom-tall-card">
              <a href="#" class="article__image">
                <img src="https://Chennative.ai/media/image-7.png" alt="Yuque Docs" loading="lazy">
              </a>
              <div class="article__inner">
                <div class="article__content">
                  <div class="article__meta">
                    <time class="article__date">Dec 8, 2025</time>
                  </div>
                  <h2 class="article__title">
                    <a href="#">Check out my Yuque Personal Docs</a>
                  </h2>
                  <p class="article__description">Thoughts and feelings from work and life.</p>
                </div>
              </div>
            </div>

            <div class="article col col-4 col-d-6 col-t-12 custom-tall-card">
              <a href="#" class="article__image">
                <img src="https://Chennative.ai/media/image-7.png" alt="LinkedIn" loading="lazy">
              </a>
              <div class="article__inner">
                <div class="article__content">
                  <div class="article__meta">
                    <time class="article__date">Dec 26, 2025</time>
                  </div>
                  <h2 class="article__title">
                    <a href="#">Check out my LinkedIn Profile</a>
                  </h2>
                  <p class="article__description">Contains my personal career path and skills.</p>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>
</main>

<div class="search-wrapper">
  <div class="container">
    <div class="row">
      <div class="col col-12">
        <div class="search">
          <form class="search-form">
            <i aria-hidden="true" class="search-input-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16" fill="currentColor" style="vertical-align:middle"><path d="M456.69 421.39L362.6 327.3a173.81 173.81 0 0034.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 00327.3 362.6l94.09 94.09a25 25 0 0035.3-35.3zM97.92 222.72a124.8 124.8 0 11124.8 124.8 124.95 124.95 0 01-124.8-124.8z"/></svg></i>
            <input class="search-field" type="text" placeholder="Type to search…">
            <button class="search-button" type="submit">
              <i aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16" fill="currentColor" style="vertical-align:middle"><path d="M289.94 256l95-95A24 24 0 00351 127l-95 95-95-95a24 24 0 00-34 34l95 95-95 95a24 24 0 1034 34l95-95 95 95a24 24 0 0034-34z"/></svg></i>
            </button>
          </form>
          <div class="popular-wrapper">
            <h4 class="popular-title">Topics</h4>
            <span class="popular-tags post-tags">
                <a href="https://CoolXuzhi.com/tag/the-business-pillar">Business</a>
                <a href="https://CoolXuzhi.com/tag/the-religious-pillar">Religious</a>
                <a href="https://CoolXuzhi.com/tag/technology">Technology</a>
                <a href="https://CoolXuzhi.com/tag/the-awakening-pillar">Awakening</a>
                <a href="https://CoolXuzhi.com/tag/tanka">Tanka</a>
            </span>
          </div>
          <div class="search-result"></div>
        </div>
        <button class="search-wrapper-close" aria-label="Close"></button>
      </div>
    </div>
  </div>
</div>

<section class="footer-widgets">
  <div class="container">
    <div class="row">
      <div class="col col-12">
        <div class="widget-box">
          <div class="row widget-row-aligned">
            <div class="col col-4 col-d-6 col-t-12 widget-col-equal">
              <div class="widget widget-recent widget-box-equal">
                <div class="widget__head">
                  <h3 class="widget__title">Recent Posts</h3>
                </div>
                <div class="recent-post">
                  <a href="https://CoolXuzhi.com/ai-native-company-data-vs-privacy" class="recent-post__image" aria-hidden="true">
                    <img src="https://Chennative.ai/media/tankablog-01-1.png" alt="Log I. Open Source Log of an AI-Native Company: Data vs. Privacy" loading="lazy">
                  </a>
                  <div class="recent-post__content">
                    <time class="recent-post__date" datetime="30-12-2025">Dec 30, 2025</time>
                    <h4 class="recent-post__title"><a href="https://CoolXuzhi.com/ai-native-company-data-vs-privacy">Log I. Open Source Log of an AI-Native Company: Data vs. Privacy</a></h4>
                  </div>
                </div>
                <div class="recent-post">
                  <a href="https://CoolXuzhi.com/pure-land-civilizational-protocol-superintelligence" class="recent-post__image" aria-hidden="true">
                    <img src="https://Chennative.ai/media/image-11.png" alt="II. Pure Land" loading="lazy">
                  </a>
                  <div class="recent-post__content">
                    <time class="recent-post__date" datetime="29-12-2025">Dec 29, 2025</time>
                    <h4 class="recent-post__title"><a href="https://CoolXuzhi.com/pure-land-civilizational-protocol-superintelligence">II. Pure Land</a></h4>
                  </div>
                </div>
                <div class="recent-post">
                  <a href="https://CoolXuzhi.com/human-responsibility-in-ai-native-era" class="recent-post__image" aria-hidden="true">
                    <img src="https://Chennative.ai/media/image-9.png" alt="III. I Choose, I Shoulder, Therefore I Am" loading="lazy">
                  </a>
                  <div class="recent-post__content">
                    <time class="recent-post__date" datetime="26-12-2025">Dec 26, 2025</time>
                    <h4 class="recent-post__title"><a href="https://CoolXuzhi.com/human-responsibility-in-ai-native-era">III. I Choose, I Shoulder, Therefore I Am</a></h4>
                  </div>
                </div>
              </div>
            </div>
            <div class="col col-4 col-d-6 col-t-12 widget-col-equal">
              <div class="widget widget-newsletter widget-box-equal">
                <div class="widget__head">
                  <h3 class="widget__title">Newsletter</h3>
                </div>
                <div class="newsletter__content newsletter-iframe-container">
                  <iframe class="newsletter-iframe" src="https://mindful-palette-322177.framer.app/newsletter-chennative" frameborder="0" loading="lazy"></iframe>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<footer class="footer">
  <div class="container">
    <div class="row">
      <div class="col col-12">
        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; padding-top: 20px; padding-bottom: 20px;">
           
           <div style="text-align: left;">
              <h2 style="font-size: 48px; font-weight: bold; margin-bottom: 30px; color: var(--heading-font-color); font-family: 'Libre Baskerville', serif;">Contact</h2>
              <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 15px;">
                 
                 <li style="display: flex; align-items: center;">
                    <span style="width: 24px; height: 24px; display: inline-flex; justify-content: center; align-items: center; margin-right: 12px;">
                       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="24" height="24" fill="#0077b5"><path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.28c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/></svg>
                    </span>
                    <a href="#" style="font-size: 18px; color: var(--text-color); text-decoration: none;">Xuzhi's Linkedin</a>
                 </li>

                 <li style="display: flex; align-items: center;">
                    <span style="width: 24px; height: 24px; display: inline-flex; justify-content: center; align-items: center; margin-right: 12px;">
                       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="24" height="24" fill="#FF0000"><path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 42.155 48.284 48.477 42.591 11.412 213.371 11.412 213.371 11.412s170.78 0 213.371-11.412c23.497-6.322 42.003-24.827 48.284-48.477 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/></svg>
                    </span>
                    <a href="#" style="font-size: 18px; color: var(--text-color); text-decoration: none;">Follow Xuzhi on YouTube</a>
                 </li>

                 <li style="display: flex; align-items: center;">
                    <span style="width: 24px; height: 24px; display: inline-flex; justify-content: center; align-items: center; margin-right: 12px;">
                       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="24" height="24" fill="#C92027"><path d="M278.9 511.5l-61-17.7c-6.4-1.8-10-8.5-8.2-14.9L346.2 8.7c1.8-6.4 8.5-10 14.9-8.2l61 17.7c6.4 1.8 10 8.5 8.2 14.9L293.8 503.3c-1.8 6.4-8.5 10-14.9 8.2zm-114-112.2l60.9 17.7c6.4 1.8 10 8.5 8.2 14.9l-133.5 463.6c-1.8 6.4-8.5 10-14.9 8.2L24.7 486c-6.4-1.8-10-8.5-8.2-14.9l133.5-463.6c1.8-6.4 8.5-10 14.9-8.2zm369.3 0l-133.5 463.6c-1.8 6.4-8.5 10-14.9 8.2l-60.9-17.7c-6.4-1.8-10-8.5-8.2-14.9l133.5-463.6c1.8-6.4 8.5-10 14.9-8.2l60.9 17.7c6.4 1.8 10 8.5 8.2 14.9z"/></svg>
                    </span>
                    <a href="#" style="font-size: 18px; color: var(--text-color); text-decoration: none;">CSDN Technical Blog</a>
                 </li>

                 <li style="display: flex; align-items: center;">
                    <span style="width: 24px; height: 24px; display: inline-flex; justify-content: center; align-items: center; margin-right: 12px;">
                       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="24" height="24" fill="#25b864"><path d="M448 360V24c0-13.3-10.7-24-24-24H96C43 0 0 43 0 96v320c0 53 43 96 96 96h328c13.3 0 24-10.7 24-24v-16c0-7.5-3.5-14.3-8.9-18.7-4.2-15.4-4.2-59.3 0-74.7 5.4-4.3 8.9-11.1 8.9-18.6zM128 134c0-3.3 2.7-6 6-6h212c3.3 0 6 2.7 6 6v20c0 3.3-2.7 6-6 6H134c-3.3 0-6-2.7-6-6v-20zm0 64c0-3.3 2.7-6 6-6h212c3.3 0 6 2.7 6 6v20c0 3.3-2.7 6-6 6H134c-3.3 0-6-2.7-6-6v-20zm253.4 250H96c-17.7 0-32-14.3-32-32 0-17.6 14.4-32 32-32h285.4c-1.9 17.1-1.9 46.9 0 64z"/></svg>
                    </span>
                    <a href="#" style="font-size: 18px; color: var(--text-color); text-decoration: none;">Check out my Yuque Personal Docs</a>
                 </li>

                 <li style="display: flex; align-items: center;">
                    <span style="width: 24px; height: 24px; display: inline-flex; justify-content: center; align-items: center; margin-right: 12px;">
                       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="24" height="24" fill="#2196F3"><path d="M502.3 190.8c3.9-3.1 9.7-.2 9.7 4.7V400c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V195.6c0-5 5.7-7.8 9.7-4.7 22.4 17.4 52.1 39.5 154.1 113.6 21.1 15.4 56.7 47.8 92.2 47.6 35.7.3 72-32.8 92.3-47.6 102-74.1 131.6-96.3 154-113.7zM256 320c23.2.4 56.6-29.2 73.4-41.4 132.7-96.3 142.8-104.7 173.4-128.7 5.8-4.5 9.2-11.5 9.2-18.9v-19c0-26.5-21.5-48-48-48H48C21.5 64 0 85.5 0 112v19c0 7.4 3.4 14.3 9.2 18.9 30.6 23.9 40.7 32.4 173.4 128.7 16.8 12.2 50.2 41.8 73.4 41.4z"/></svg>
                    </span>
                    <a href="mailto:cxzzero@126.com" style="font-size: 18px; color: var(--text-color); text-decoration: none;">cxzzero@126.com</a>
                 </li>

              </ul>
           </div>

           <div style="text-align: right;">
              <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/Profile.jpg" alt="Profile Picture" style="max-width: 100%; width: 350px; height: auto; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
           </div>

        </div>
      </div>
    </div>
  </div>
</footer>

<script data-flashload-skip-script src="https://Chennative.ai/assets/flashload.js" async onload="__initFlashload()"></script>
<script data-flashload-skip-script>
  function __initFlashload() {
    Flashload.start({ basePath: "" })
  }
</script>

<?php wp_footer(); ?>
</body>
</html>