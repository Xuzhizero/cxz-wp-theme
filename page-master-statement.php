<?php
// 独立文章页：硕士经历陈述
?>
<!doctype html>
<html <?php language_attributes(); ?> class="mode-light">
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>硕士经历陈述 – CoolXuZhi</title>
  <meta name="description" content="个人硕士期间工作经历陈述：从深度学习到水陆两栖球形机器人" />
  <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/favicon.svg" />
  <meta property="og:site_name" content="CoolXuZhi" />
  <meta property="og:type" content="article" />
  <meta property="og:title" content="硕士经历陈述 – CoolXuZhi" />

  <script>
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
  <style>
    body{margin:0;padding:0}
    .logo__image{width:36px;height:36px;border-radius:10px;object-fit:cover}
    .header{position:fixed;top:0;left:0;right:0;z-index:80;background:transparent}
  </style>
  <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/cxz-home.css?ver=20260215-1" />
  <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/css/cxz-article.css?ver=20260215-1" />
  <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/cxz-home.js?ver=20260215-1" defer></script>

  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<header class="header">
  <div class="container-big">
    <div class="row">
      <div class="header__inner col col-12">
        <div class="logo">
          <a class="logo__link" href="<?php echo esc_url( home_url( '/' ) ); ?>">
              <img class="logo__image" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/logo.svg" alt="CoolXuZhi" width="36" height="36"/>
          </a>
        </div>
        <div class="hamburger" onclick="menuToggle()" id="hamburger">
          <div></div>
        </div>
        <nav class="main-nav" aria-label="Main menu" id="menuList">
          <div class="main-nav__box">
            <ul class="nav__list list-reset">
              <li class="nav__item">
                <a href="<?php echo esc_url( home_url( '/disclaimer/' ) ); ?>" class="nav__link nav__disclaimer">Our Team</a>
              </li>
            </ul>
          </div>
          <div class="nav-button">
            <div class="color-mode-toggler">
              <button type="button" class="color-mode-toggle" onclick="toggleColorMode()" aria-label="Toggle color mode">
                <span class="color-mode-toggle__label" id="color-mode-toggle-label">Dark</span>
              </button>
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

<main class="article-page">
  <div class="article-page__container">
    <h1 class="article-page__title">个人硕士期间工作经历陈述</h1>

    <div class="article-page__body">
      <p>我在本科的时候，虽然求学于海洋机器人强校，但我那时对海洋机器人其实并没有太多的接触，那时我对人工智能、机器学习的兴趣反倒是十分浓厚。我的本科毕设做的是预测眼部高血压项目，就与深度学习有关。</p>

      <p>我真正与海洋机器人结缘开始于研究生阶段。我的研究生课题组致力于开发出一款60cm直径的水陆两栖球形机器人（后来增加到80cm）。第一次组会让我印象很深，组会上，我的导师给大家（主要还是给刚进组的研一小朋友）展示了波士顿动力创始人马克·拉博特的一句话："对于一个工程师来说，只有经历了工程的全过程，从提出概念，做出工程原型，找到应用场景，最终变成了一个产品，你才能说真正懂了这项技术"。我后来一直铭记着马克·拉博特这句话，它对我后来在课题中的一系列行为决策起到了价值观一般的导向作用。</p>

      <p>刚加入团队时，水面业务组刚成立不久，十分缺少人手，我就经常在水面组里帮忙。当时水面组里，没有一个人是海洋工程背景出身，包括我在内，大家对水面机器人都几乎毫不了解，所以虽然当时大体确定了研发目标——让球能够在江和海里都可以到达期望目标点并停留在目标点附近，实际路径与规划路径越贴近越好——但其实连一些主要零部件的选型都没有定下来。所以我在进入水面组以后不久，主动物色了一家在江苏苏州的无人船厂商，以一个采购商的身份借机前往这家厂商调研，确定一个成熟的水面商用机器人上面主要设备的供应商，以及最重要的，现在市面上的实船下水演示效果已经能达到什么样了。在那里，我录下他们的演示视频，记录下他们的设备型号和参数，带回来供团队参考。那之后我就顺理成章负责包括螺旋桨、GPS板卡在内的零部件选型，这便是我接触海洋机器人的开端，后来便一发不可收拾。</p>

      <p>我研一花了很大的力气在零部件的选型上，特别是螺旋桨的选型。螺旋桨选型麻烦的地方在于，厂家产品手册里提供的推力参数可能是虚夸的，所以买来后必须经过推力测量，以确定其真正的推力和功率的对应曲线。研一做的另一件事是接手即将毕业的师兄要传承的项目，网枪项目，涉及基于深度学习的行人识别算法，与我的本科毕设有一定关联。项目已有功能是，机载摄像头视野中一旦出现人，就会自动识别，搭载网枪的云台会转到对应位置，当距离目标够近时，网枪发射。后来我又做了改进，在得到人的距离和偏角以后，让算法能够求取目标在机器人坐标系下的坐标，用于路径规划和目标跟踪任务。</p>

      <p>研二上时，我们基本确定了无人艇主流的控制框架作为我们球形机器人水面运动导航控制框架。为了快速试错和迭代，搜寻网上开源的无人艇代码作为研发起点。由于网上的整套系统的注释大多很糟糕，试错成本高，所以我们最终选择了根据框架中的子构件分别寻找对应的开源代码，修改以后组装起来——因为小模块的已有成熟代码相对多很多。为了验证这套系统，也使用开源代码搭建了仿真环境，过程中我了解了水面机器人动力学和运动学模型。在仿真中路径跟踪的效果不错。</p>

      <p>研二下，我将算法移植到我们专门购买的无人船中，下湖实验，验证相应算法。在湖中运行情况良好，和仿真中差别不大。之所以没有直接迁移到球内，是因为到了研二时，球体防水问题依旧解决不了，不敢贸然下水。当时防水的办法是在球所有能想到的缝隙上涂玻璃胶，然后使用充气泵往一个气孔打气，使用串接的气压表查看漏气情况，同时用气泡水逐个地方喷洒，查看漏气位置。这样的方法，效率低下。为了加速研发进程，老师建议我搭建新的迷你版水球整机（直径50cm）用于快速迭代，它无论是在防水繁琐程度上还是在重量上都有极大的优势，自此，我的研发进程开始迈入快车道。实验中发现，球在跟踪路径时在航向控制上总是会出现超调，其调节时间比无人船要高很多，严重影响了路径跟踪控制的效果，原因在于，球形的结构使得其在变向上比船形容易太多，惯性也大，这样反而对稳定的航向控制提出了挑战。后来，我们发现轮式机器人领域有一种基于动态滑窗算法(DWA)的导航框架，它在速度和角速度空间中随机采样形成一对对组合，根据运动学模型，模拟若采用每一对组合之后的预期位置，再基于目标函数打分，并选择分值最优的组合，这个框架摒弃了传统的基于航向控制的思路，且不需要调整很多参数，我们决定迁移过来试试。</p>

      <p>研三上时，大球防水问题在机械结构做了改进以后，得到彻底解决，新算法应用在大小球中都很成功，但场景仅限于湖面上。为了检验算法在大海中的效果，我事先开发了远程键盘遥控的功能，进一步提升研发效率。作为先锋队，我带着迷你水球到温州的海港，完成了海试实验。相关成果发到了中国自动化大会论文《Adaptive Path Following Control Algorithm Design for Spherical Robots in Surface Navigation》，一作，得到录用和《Research on Amphibious Spherical Robot with water surface posture self-stabilization》三作，得到录用，并发表了专利《一种球形机器人水面运动自适应航向控制方法》一作，进入实审和《基于自适应变前向距离的球形机器人水面运动控制方法》二作，进入实审。目前，机器人能实现的效果是：可以通过电脑操控机器人直走、转弯、记录经纬度；根据事先定好的点，球可以在水中巡航，最终停留在目标位置附近，并保持给定的朝向。</p>

      <p>由于最大速度的参数可变，我引入了一个算法，可以根据给定期望速度，根据左右晃动剧烈程度、俯仰角和漂移的程度来自动确定适合当前情况的最大螺旋桨脉宽值，该算法正在改进中。</p>

      <p>总的来说，我的硕士阶段做的更偏重于工程而非学术，节奏也更像是创业团队里的节奏，特点是需要频繁团队合作，很多时候甚至要主动承担起技术工人的工作，科研创新程度不算很高，但是却让我对"顶层设计"有了更具体的理解，也熟悉了工程全过程。很感谢我的指导老师，他反复强调的"从工程中来，到工程中去"的思维模式已深深烙印在我的脑海里。我在硕士期间学会了从设计到制造的快速迭代，但我也深知自己在把数学分析工具与工程跟顶层设计思维融合上做的还很不够。我希望去更深入的了解水体环境对机器人的作用效果，希望自己未来能站在更高的维度把智能制造这件事情做好。我希望把自己的仅有的青春泼洒在技术的落地和商业化上，而不是留在象牙塔里。</p>
    </div>
  </div>

  <div class="article-page__footer">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="article-page__back">&larr; Back to Home</a>
  </div>
</main>

<?php wp_footer(); ?>
</body>
</html>
