<?php
// Chinese landing page: create a WordPress Page with slug "zh-cn" to use this template automatically.
?>
<!doctype html>
<html lang="zh-CN" class="mode-light">
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>CoolXuZhi</title>
  <meta name="description" content="" />
  <link rel="canonical" href="<?php echo esc_url( home_url( '/zh-cn/' ) ); ?>" />

  <script>
    // 仅用于首屏提前设置 mode-light/mode-dark，避免闪烁
    (function () {
      var lsKey = "color-mode-pref";
      var pref = null;
      try { pref = localStorage.getItem(lsKey); } catch (e) {}
      pref = pref || "os";
      var mode = pref;
      if (mode === "os") {
        mode = window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
      }
      var html = document.documentElement;
      html.classList.remove("mode-light", "mode-dark");
      html.classList.add(mode === "dark" ? "mode-dark" : "mode-light");
      html.classList.add("mode-preference-" + pref);
    })();
  </script>
  <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/cxz-home.css?ver=20260125-2" />
  <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/cxz-home.js?ver=20260125-2" defer></script>

  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<header class="header">
  <div class="container-big">
    <div class="row">
      <div class="header__inner col col-12">
        <div class="logo">
          <a class="logo__link" href="<?php echo esc_url( home_url( '/' ) ); ?>">
              <img class="logo__image" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/logo.svg" alt="CoolXuZhi"/>
          </a>
        </div>
        <div class="hamburger" onclick="menuToggle()" id="hamburger">
          <div></div>
        </div>
        <nav class="main-nav" aria-label="Main menu" id="menuList">
          <div class="main-nav__box">
            <ul class="nav__list list-reset">
              <li class="nav__item">
                <a href="<?php echo esc_url( home_url( '/disclaimer/' ) ); ?>" class="nav__link nav__disclaimer">关于我们</a>
              </li>
            </ul>
          </div>
          <div class="nav-button">
            <div class="color-mode-toggler">
              <button type="button" class="color-mode-toggle" onclick="toggleColorMode()" aria-label="切换主题">
                <span class="color-mode-toggle__label" id="color-mode-toggle-label">暗色</span>
              </button>
            </div>
            <div class="nav-lang">
              <a class="lang-label" role="label" onclick="toggleLanguageDropdown()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="14" height="14" fill="currentColor" style="vertical-align:middle"><path d="M414.39 97.74A224 224 0 1097.61 414.52 224 224 0 10414.39 97.74zM64 256.13a191.63 191.63 0 016.7-50.31c7.34 15.8 18 29.45 25.25 45.66 9.37 20.84 34.53 15.06 45.64 33.32 9.86 16.21-.67 36.71 6.71 53.67 5.36 12.31 18 15 26.72 24 8.91 9.08 8.72 21.52 10.08 33.36a305.36 305.36 0 007.45 41.27c0 .1 0 .21.08.31C117.8 411.13 64 339.8 64 256.13zm192 192a193.12 193.12 0 01-32-2.68c.11-2.71.16-5.24.43-7 2.43-15.9 10.39-31.45 21.13-43.35 10.61-11.74 25.15-19.68 34.11-33 8.78-13 11.41-30.5 7.79-45.69-5.33-22.44-35.82-29.93-52.26-42.1-9.45-7-17.86-17.82-30.27-18.7-5.72-.4-10.51.83-16.18-.63-5.2-1.35-9.28-4.15-14.82-3.42-10.35 1.36-16.88 12.42-28 10.92-10.55-1.41-21.42-13.76-23.82-23.81-3.08-12.92 7.14-17.11 18.09-18.26 4.57-.48 9.7-1 14.09.68 5.78 2.14 8.51 7.8 13.7 10.66 9.73 5.34 11.7-3.19 10.21-11.83-2.23-12.94-4.83-18.21 6.71-27.12 8-6.14 14.84-10.58 13.56-21.61-.76-6.48-4.31-9.41-1-15.86 2.51-4.91 9.4-9.34 13.89-12.27 11.59-7.56 49.65-7 34.1-28.16-4.57-6.21-13-17.31-21-18.83-10-1.89-14.44 9.27-21.41 14.19-7.2 5.09-21.22 10.87-28.43 3-9.7-10.59 6.43-14.06 10-21.46 1.65-3.45 0-8.24-2.78-12.75q5.41-2.28 11-4.23a15.6 15.6 0 008 3c6.69.44 13-3.18 18.84 1.38 6.48 5 11.15 11.32 19.75 12.88 8.32 1.51 17.13-3.34 19.19-11.86 1.25-5.18 0-10.65-1.2-16a190.83 190.83 0 01105 32.21c-2-.76-4.39-.67-7.34.7-6.07 2.82-14.67 10-15.38 17.12-.81 8.08 11.11 9.22 16.77 9.22 8.5 0 17.11-3.8 14.37-13.62-1.19-4.26-2.81-8.69-5.42-11.37a193.27 193.27 0 0118 14.14c-.09.09-.18.17-.27.27-5.76 6-12.45 10.75-16.39 18.05-2.78 5.14-5.91 7.58-11.54 8.91-3.1.73-6.64 1-9.24 3.08-7.24 5.7-3.12 19.4 3.74 23.51 8.67 5.19 21.53 2.75 28.07-4.66 5.11-5.8 8.12-15.87 17.31-15.86a15.4 15.4 0 0110.82 4.41c3.8 3.94 3.05 7.62 3.86 12.54 1.43 8.74 9.14 4 13.83-.41a192.12 192.12 0 019.24 18.77c-5.16 7.43-9.26 15.53-21.67 6.87-7.43-5.19-12-12.72-21.33-15.06-8.15-2-16.5.08-24.55 1.47-9.15 1.59-20 2.29-26.94 9.22-6.71 6.68-10.26 15.62-17.4 22.33-13.81 13-19.64 27.19-10.7 45.57 8.6 17.67 26.59 27.26 46 26 19.07-1.27 38.88-12.33 38.33 15.38-.2 9.81 1.85 16.6 4.86 25.71 2.79 8.4 2.6 16.54 3.24 25.21a158 158 0 004.74 30.07A191.75 191.75 0 01256 448.13z"/></svg>&nbsp;<span>中文</span>
              </a>
              <div id="lang-selector">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">English</a>
                <a href="<?php echo esc_url( home_url( '/zh-cn/' ) ); ?>" class="active">中</a>
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
      <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/image-17.jpg" alt="CoolXuZhi" loading="lazy">
    </div>

    <div class="hero-image__content">
      <div class="container">
        <div class="row">
          <div class="col col-12">
            <div class="hero__inner">
              <h1 class="hero__title">CoolXuZhi</h1>
              <div class="homepage-intro">
                <div class="homepage-intro__content">
                  <h3>完成祖国统一的伟大历史使命，推动我国主要矛盾的解决。</h3>
                  <h3>在海洋的广阔中锻造硅基骏马，理解海洋世界的丰富与复杂。</h3>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php /* 下面内容暂时保持与英文一致（只做文字翻译，不改结构） */ ?>
  <div class="container">
    <div class="row">
      <div class="tag-group">
        <div class="container">
          <div class="row">
            <div class="col col-12">
              <h2 class="tag-group__title">信念</h2>
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
                    <a href="https://CoolXuzhi.com/twilight-of-management-ai-native-enterprise">I. 水面 L5 自主航行将先于陆上自动驾驶</a>
                  </h2>
                  <p class="article__description">水面 L5 级无人化作业将比陆上 L5 自动驾驶更早落地与普及。</p>
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
                    <a href="https://CoolXuzhi.com/skeuomorphism-trap-ai-native-liquid-business">II. 巴甫洛夫式机器人的出现</a>
                  </h2>
                  <p class="article__description">具备犬类级别 IQ/EQ、可接受巴甫洛夫式训练的机器人已经近在眼前。</p>
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
                    <a href="https://CoolXuzhi.com/human-responsibility-in-ai-native-era">III. 自动化水面机器人：回收利用的接口</a>
                  </h2>
                  <p class="article__description">回收利用革命将是 21 世纪能源革命的重要组成部分。</p>
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
              <h2 class="tag-group__title">关于我</h2>
            </div>
          </div>

          <div class="row">
            <div class="col col-12">
              <div class="about-me__row">
                <div class="about-me__image-wrap">
                  <img class="about-me__image" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/Profile.jpg" alt="Profile">
                </div>
                <div class="about-me__text">
                  <p>生活对我而言也有许多维度。</p>
                  <p>我是一名从事数字孪生与智能海事系统的工程师，也是穿梭在仿真、控制与可视化之间的项目协调者，长期在技术、系统与真实世界应用的交汇处持续建设。</p>
                  <p>多年技术实践与项目责任让我逐渐意识到：在复杂之下，人生是在理解系统——理解事物如何运作、如何产生不确定性，以及如何以清晰与耐心构建秩序。</p>
                  <p>在这里，我记录与分享关于技术、工作与自我成长的持续探索。</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</main>

<?php wp_footer(); ?>
</body>
</html>

