<?php
// 独立文章页：硕士经历陈述 (双语版)
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
    /* 语言切换相关样式 */
    .lang-content { display: none; }
    .lang-content.active { display: block; }
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
            </ul>
          </div>
          <div class="nav-button">
            <div class="color-mode-toggler">
              <button type="button" class="color-mode-toggle" onclick="toggleColorMode()" aria-label="Toggle color mode">
                <span class="color-mode-toggle__label" id="color-mode-toggle-label">Dark</span>
              </button>
            </div>
            <div class="nav-lang">
              <a class="lang-label" role="label" onclick="toggleArticleLanguage(event)" id="article-lang-toggle" style="cursor: pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="14" height="14" fill="currentColor" style="vertical-align:middle"><path d="M414.39 97.74A224 224 0 1097.61 414.52 224 224 0 10414.39 97.74zM64 256.13a191.63 191.63 0 016.7-50.31c7.34 15.8 18 29.45 25.25 45.66 9.37 20.84 34.53 15.06 45.64 33.32 9.86 16.21-.67 36.71 6.71 53.67 5.36 12.31 18 15 26.72 24 8.91 9.08 8.72 21.52 10.08 33.36a305.36 305.36 0 007.45 41.27c0 .1 0 .21.08.31C117.8 411.13 64 339.8 64 256.13zm192 192a193.12 193.12 0 01-32-2.68c.11-2.71.16-5.24.43-7 2.43-15.9 10.39-31.45 21.13-43.35 10.61-11.74 25.15-19.68 34.11-33 8.78-13 11.41-30.5 7.79-45.69-5.33-22.44-35.82-29.93-52.26-42.1-9.45-7-17.86-17.82-30.27-18.7-5.72-.4-10.51.83-16.18-.63-5.2-1.35-9.28-4.15-14.82-3.42-10.35 1.36-16.88 12.42-28 10.92-10.55-1.41-21.42-13.76-23.82-23.81-3.08-12.92 7.14-17.11 18.09-18.26 4.57-.48 9.7-1 14.09.68 5.78 2.14 8.51 7.8 13.7 10.66 9.73 5.34 11.7-3.19 10.21-11.83-2.23-12.94-4.83-18.21 6.71-27.12 8-6.14 14.84-10.58 13.56-21.61-.76-6.48-4.31-9.41-1-15.86 2.51-4.91 9.4-9.34 13.89-12.27 11.59-7.56 49.65-7 34.1-28.16-4.57-6.21-13-17.31-21-18.83-10-1.89-14.44 9.27-21.41 14.19-7.2 5.09-21.22 10.87-28.43 3-9.7-10.59 6.43-14.06 10-21.46 1.65-3.45 0-8.24-2.78-12.75q5.41-2.28 11-4.23a15.6 15.6 0 008 3c6.69.44 13-3.18 18.84 1.38 6.48 5 11.15 11.32 19.75 12.88 8.32 1.51 17.13-3.34 19.19-11.86 1.25-5.18 0-10.65-1.2-16a190.83 190.83 0 01105 32.21c-2-.76-4.39-.67-7.34.7-6.07 2.82-14.67 10-15.38 17.12-.81 8.08 11.11 9.22 16.77 9.22 8.5 0 17.11-3.8 14.37-13.62-1.19-4.26-2.81-8.69-5.42-11.37a193.27 193.27 0 0118 14.14c-.09.09-.18.17-.27.27-5.76 6-12.45 10.75-16.39 18.05-2.78 5.14-5.91 7.58-11.54 8.91-3.1.73-6.64 1-9.24 3.08-7.24 5.7-3.12 19.4 3.74 23.51 8.67 5.19 21.53 2.75 28.07-4.66 5.11-5.8 8.12-15.87 17.31-15.86a15.4 15.4 0 0110.82 4.41c3.8 3.94 3.05 7.62 3.86 12.54 1.43 8.74 9.14 4 13.83-.41a192.12 192.12 0 019.24 18.77c-5.16 7.43-9.26 15.53-21.67 6.87-7.43-5.19-12-12.72-21.33-15.06-8.15-2-16.5.08-24.55 1.47-9.15 1.59-20 2.29-26.94 9.22-6.71 6.68-10.26 15.62-17.4 22.33-13.81 13-19.64 27.19-10.7 45.57 8.6 17.67 26.59 27.26 46 26 19.07-1.27 38.88-12.33 38.33 15.38-.2 9.81 1.85 16.6 4.86 25.71 2.79 8.4 2.6 16.54 3.24 25.21a158 158 0 004.74 30.07A191.75 191.75 0 01256 448.13z"/></svg>&nbsp;<span id="article-lang-label">English</span>
              </a>
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
    <!-- 中文标题 -->
    <h1 class="article-page__title lang-content" data-lang="zh">个人硕士期间工作经历陈述</h1>
    <!-- 英文标题 -->
    <h1 class="article-page__title lang-content" data-lang="en">A Chronicle of My Travails during the Years of Master's Study</h1>

    <!-- 中文内容 -->
    <div class="article-page__body lang-content" data-lang="zh">
      <p>我在本科的时候，虽然求学于海洋机器人强校，但我那时对海洋机器人其实并没有太多的接触，那时我对人工智能、机器学习的兴趣反倒是十分浓厚。我的本科毕设做的是预测眼部高血压项目，就与深度学习有关。</p>

      <p>我真正与海洋机器人结缘开始于研究生阶段。我的研究生课题组致力于开发出一款60cm直径的水陆两栖球形机器人（后来增加到80cm）。第一次组会让我印象很深，组会上，我的导师给大家（主要还是给刚进组的研一小朋友）展示了波士顿动力创始人马克·拉博特的一句话："对于一个工程师来说，只有经历了工程的全过程，从提出概念，做出工程原型，找到应用场景，最终变成了一个产品，你才能说真正懂了这项技术"。我后来一直铭记着马克·拉博特这句话，它对我后来在课题中的一系列行为决策起到了价值观一般的导向作用。</p>

      <p>刚加入团队时，水面业务组刚成立不久，十分缺少人手，我就经常在水面组里帮忙。当时水面组里，没有一个人是海洋工程背景出身，包括我在内，大家对水面机器人都几乎毫不了解，所以虽然当时大体确定了研发目标——让球能够在江和海里都可以到达期望目标点并停留在目标点附近，实际路径与规划路径越贴近越好——但其实连一些主要零部件的选型都没有定下来。所以我在进入水面组以后不久，主动物色了一家在江苏苏州的无人船厂商，以一个采购商的身份借机前往这家厂商调研，确定一个成熟的水面商用机器人上面主要设备的供应商，以及最重要的，现在市面上的实船下水演示效果已经能达到什么样了。在那里，我录下他们的演示视频，记录下他们的设备型号和参数，带回来供团队参考。那之后我就顺理成章负责包括螺旋桨、GPS板卡在内的零部件选型，这便是我接触海洋机器人的开端，后来便一发不可收拾。</p>

      <p>我研一花了很大的力气在零部件的选型上，特别是螺旋桨的选型。螺旋桨选型麻烦的地方在于，厂家产品手册里提供的推力参数可能是虚夸的，所以买来后必须经过推力测量，以确定其真正的推力和功率的对应曲线。研一做的另一件事是接手即将毕业的师兄要传承的项目，网枪项目，涉及基于深度学习的行人识别算法，与我的本科毕设有一定关联。项目已有功能是，机载摄像头视野中一旦出现人，就会自动识别，搭载网枪的云台会转到对应位置，当距离目标够近时，网枪发射。后来我又做了改进，在得到人的距离和偏角以后，让算法能够求取目标在机器人坐标系下的坐标，用于路径规划和目标跟踪任务。</p>

      <p>研二上时，我们基本确定了无人艇主流的控制框架作为我们球形机器人水面运动导航控制框架。为了快速试错和迭代，搜寻网上开源的无人艇代码作为研发起点。由于网上的整套系统的注释大多很糟糕，试错成本高，所以我们最终选择了根据框架中的子构件分别寻找对应的开源代码，修改以后组装起来——因为小模块的已有成熟代码相对多很多。为了验证这套系统，也使用开源代码搭建了仿真环境，过程中我了解了水面机器人动力学和运动学模型。在仿真中路径跟踪的效果不错。</p>

      <p>研二下，我将算法移植到我们专门购买的无人船中，下湖实验，验证相应算法。在湖中运行情况良好，和仿真中差别不大。之所以没有直接迁移到球内，是因为到了研二时，球体防水问题依旧解决不了，不敢贸然下水。当时防水的办法是在球所有能想到的缝隙上涂玻璃胶，然后使用充气泵往一个气孔打气，使用串接的气压表查看漏气情况，同时用气泡水逐个地方喷洒，查看漏气位置。这样的方法，效率低下。为了加速研发进程，老师建议我搭建新的迷你版水球整机（直径50cm）用于快速迭代，它无论是在防水繁琐程度上还是在重量上都有极大的优势，自此，我的研发进程开始迈入快车道。实验中发现，球在跟踪路径时在航向控制上总是会出现超调，其调节时间比无人船要高很多，严重影响了路径跟踪控制的效果，原因在于，球形的结构使得其在变向上比船形容易太多，惯性也大，这样反而对稳定的航向控制提出了挑战。后来，我们发现轮式机器人领域有一种基于动态滑窗算法(DWA)的导航框架，它在速度和角速度空间中随机采样形成一对对组合，根据运动学模型，模拟若采用每一对组合之后的预期位置,再基于目标函数打分，并选择分值最优的组合，这个框架摒弃了传统的基于航向控制的思路，且不需要调整很多参数，我们决定迁移过来试试。</p>

      <p>研三上时，大球防水问题在机械结构做了改进以后，得到彻底解决，新算法应用在大小球中都很成功，但场景仅限于湖面上。为了检验算法在大海中的效果，我事先开发了远程键盘遥控的功能，进一步提升研发效率。作为先锋队，我带着迷你水球到温州的海港，完成了海试实验。相关成果发到了中国自动化大会论文《Adaptive Path Following Control Algorithm Design for Spherical Robots in Surface Navigation》，一作，得到录用和《Research on Amphibious Spherical Robot with water surface posture self-stabilization》三作，得到录用，并发表了专利《一种球形机器人水面运动自适应航向控制方法》一作，进入实审和《基于自适应变前向距离的球形机器人水面运动控制方法》二作，进入实审。目前，机器人能实现的效果是：可以通过电脑操控机器人直走、转弯、记录经纬度；根据事先定好的点，球可以在水中巡航，最终停留在目标位置附近，并保持给定的朝向。</p>

      <p>由于最大速度的参数可变，我引入了一个算法，可以根据给定期望速度，根据左右晃动剧烈程度、俯仰角和漂移的程度来自动确定适合当前情况的最大螺旋桨脉宽值，该算法正在改进中。</p>

      <p>总的来说，我的硕士阶段做的更偏重于工程而非学术，节奏也更像是创业团队里的节奏，特点是需要频繁团队合作，很多时候甚至要主动承担起技术工人的工作，科研创新程度不算很高，但是却让我对"顶层设计"有了更具体的理解，也熟悉了工程全过程。很感谢我的指导老师，他反复强调的"从工程中来，到工程中去"的思维模式已深深烙印在我的脑海里。我在硕士期间学会了从设计到制造的快速迭代，但我也深知自己在把数学分析工具与工程跟顶层设计思维融合上做的还很不够。我希望去更深入的了解水体环境对机器人的作用效果，希望自己未来能站在更高的维度把智能制造这件事情做好。我希望把自己的仅有的青春泼洒在技术的落地和商业化上，而不是留在象牙塔里。</p>
    </div>

    <!-- 英文内容 (莎士比亚风格) -->
    <div class="article-page__body lang-content" data-lang="en">
      <p>In mine undergraduate years, though I did pursue learning at an institution most renowned for its mastery of ocean-faring automatons, I had in truth scant congress with such marine mechanicals. Rather, 'twas artificial intelligence and the learning of machines that most inflamed my passion. My final project as an undergraduate scholar—concerning the prognostication of ocular hypertension—was indeed bound up with that art called deep learning.</p>

      <p>My true acquaintance with marine robots did commence in earnest during my graduate studies. The research consortium unto which I was apprenticed did endeavor to fashion an amphibious spherical automaton, sixty centimeters in diameter at first, later swelling to fourscore. The first assembly meeting made a most lasting impression upon me. My tutor did present unto us—chiefly unto the freshly arrived first-year apprentices—a maxim from Marc Raibert, founder of Boston Dynamics: "For an engineer, only when thou hast traversed the entire course of engineering—from proposing the concept, to fashioning the prototype, to discovering the application, and at last to birthing a product—canst thou claim true understanding of the art." This saying of Raibert I kept ever in my heart, and it served as a compass for all my subsequent decisions within the project.</p>

      <p>When first I joined the fellowship, the surface operations division had but lately been established and was sorely wanting in hands. Thus did I oft lend mine aid. In that division, not one soul—myself included—hailed from a background in ocean engineering; we were nigh ignorant of surface-navigating robots. Though we had broadly fixed our goal—to wit, that the sphere should reach its appointed destination upon rivers and seas alike, and remain thereabouts, with the actual path cleaving as near as may be to the planned course—yet even the selection of principal components remained undecided. Therefore, not long after entering the surface group, I did of mine own volition seek out a manufacturer of unmanned vessels in Suzhou, Jiangsu. Presenting myself as a prospective purchaser, I ventured thither to ascertain what suppliers furnished equipment for mature commercial surface robots, and—most vitally—what manner of performance their vessels could demonstrate upon the water. There I recorded their demonstration by moving picture, noted down model numbers and parameters, and brought all back for the team's consideration. Thereafter I was entrusted with the selection of sundry components, including propellers and GPS modules. Thus began my intercourse with marine robots, and thereafter I could not be stayed.</p>

      <p>In my first year of graduate study, I expended much toil upon component selection, especially propellers. The vexation thereof lay in this: the thrust specifications in manufacturers' catalogues might be exaggerated. Hence, after purchase, each must needs be tested by measurement to establish the true correspondence betwixt thrust and power. Another undertaking of that year was to take up a project bequeathed by a graduating elder fellow—the net-gun project—which involved a pedestrian recognition algorithm grounded in deep learning, and thus bore some kinship to my undergraduate thesis. The existing functionality was thus: once a human figure appeared in the view of the onboard camera, 'twould be automatically recognized, and the gimbal bearing the net-gun would turn to the corresponding position; when the target drew nigh enough, the net-gun discharged. I later made improvements, so that having obtained the distance and bearing angle of the person, the algorithm could compute the target's coordinates within the robot's own frame of reference, thereby serving path planning and target tracking tasks.</p>

      <p>In the first half of my second year, we settled upon the mainstream control framework used for unmanned surface vessels as the framework for our spherical robot's surface navigation. To hasten trial and iteration, we sought open-source code for unmanned vessels as our starting point. Because the annotations in online integrated systems were generally wretched, and the cost of trial and error was high, we at last chose to seek open-source code for each sub-component of the framework separately, modify them, and then assemble them together—for mature code for small modules was far more plentiful. To verify the system, we also erected a simulation environment using open-source code; in this process I came to understand the dynamics and kinematics models of surface robots. The path-following performance in simulation was satisfactory.</p>

      <p>In the latter half of my second year, I transplanted the algorithm unto an unmanned vessel we had specially purchased, and conducted experiments upon a lake to verify the algorithm. The performance upon the lake was good, differing little from simulation. The reason we did not transplant directly into the sphere was that by the second year, the problem of waterproofing the sphere remained unsolved, and we dared not rashly venture into the water. The method of waterproofing at that time was to apply glass adhesive to every conceivable seam of the sphere, then to use an air pump to force air into an opening, and to observe a pressure gauge connected in series to detect leakage, whilst simultaneously spraying bubble-water upon each area to discover the leak's location. Such a method was most inefficient. To accelerate the research process, my instructor proposed that I construct a new miniature water-sphere (50 cm diameter) for rapid iteration. This had great advantages both in the tediousness of waterproofing and in weight. Thenceforth my research did proceed at a gallop. In experiments, 'twas discovered that when the sphere tracked a path, there was always overshoot in heading control, and the settling time was far longer than for an unmanned vessel, gravely impairing the path-following control. The cause was this: the spherical structure made it far easier for it to change direction than a ship-shaped hull, and its inertia was also great; thus, paradoxically, stable heading control became a challenge. Later we discovered that in the field of wheeled robots there existeth a navigation framework based upon the Dynamic Window Approach (DWA). This algorithm doth randomly sample pairs of velocity and angular velocity, and according to the kinematic model, simulate the expected position after adopting each pair, then score them based upon an objective function, and select the combination with the best score. This framework abandons the traditional approach based on heading control and requires the adjustment of fewer parameters. We resolved to attempt its migration.</p>

      <p>In the first half of my third year, after improvements were made to the mechanical structure, the waterproofing problem of the large sphere was thoroughly solved. The new algorithm succeeded in both large and small spheres, but the scenarios were limited to lake surfaces. To test the algorithm's performance in the ocean, I first developed a remote keyboard control function, thereby further enhancing research efficiency. As a vanguard, I brought the miniature water-sphere to a harbor in Wenzhou and completed sea trials. The related achievements were submitted to the China Automation Conference as papers: "Adaptive Path Following Control Algorithm Design for Spherical Robots in Surface Navigation" (first author, accepted) and "Research on Amphibious Spherical Robot with water surface posture self-stabilization" (third author, accepted). Patents were also filed: "An Adaptive Heading Control Method for Surface Motion of Spherical Robots" (first author, entered substantive examination) and "A Control Method for Surface Motion of Spherical Robots Based on Adaptive Variable Forward Distance" (second author, entered substantive examination). At present, the robot can achieve the following: it can be controlled by computer to move straight, turn, and record latitude and longitude; according to pre-set points, the sphere can cruise upon the water, ultimately remaining near the target position and maintaining a given orientation.</p>

      <p>Because the maximum speed parameter is variable, I introduced an algorithm that can, given a desired speed, automatically determine the maximum propeller pulse-width value suitable to current conditions, based upon the severity of lateral rocking, pitch angle, and degree of drift. This algorithm is presently being refined.</p>

      <p>In sum, my work during the master's degree was more inclined toward engineering than toward scholarship; the rhythm was more akin to that of a startup team. The characteristics included frequent teamwork; oft I was even required to take on the labor of a technician. The degree of scientific innovation was not exceedingly high, yet it gave me a more concrete understanding of "top-level design" and familiarized me with the entire engineering process. I am most grateful to my instructor, whose oft-repeated maxim—"From engineering come, to engineering go"—hath been deeply imprinted upon my mind. During my master's studies I learned rapid iteration from design to manufacture, yet I know full well that I have not yet sufficiently integrated mathematical analytical tools with engineering and top-level design thinking. I hope to gain a deeper understanding of the effects of aquatic environments upon robots, and I hope that in the future I may stand at a higher vantage and do well the work of intelligent manufacturing. I wish to pour out my sole youth upon the landing and commercialization of technology, and not to remain within the ivory tower.</p>
    </div>
  </div>

  <div class="article-page__footer">
    <!-- 中文返回链接 -->
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="article-page__back lang-content" data-lang="zh">&larr; 返回主页</a>
    <!-- 英文返回链接 -->
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="article-page__back lang-content" data-lang="en">&larr; Back to Home</a>
  </div>
</main>

<script>
(function() {
  // ===== 获取当前语言偏好 =====
  function getCurrentLang() {
    // 1. 优先从 URL 参数读取 (?lang=zh / ?lang=en)
    var urlParams = new URLSearchParams(window.location.search);
    var urlLang = urlParams.get('lang');
    if (urlLang === 'zh' || urlLang === 'en') {
      try { localStorage.setItem('ui-lang-pref', urlLang); } catch (e) {}
      return urlLang;
    }

    // 2. 从 localStorage 读取
    try {
      var stored = localStorage.getItem('ui-lang-pref');
      if (stored === 'zh' || stored === 'en') {
        return stored;
      }
    } catch (e) {}
    
    // 3. 从 <html lang> 推断
    var htmlLang = document.documentElement.getAttribute('lang') || '';
    if (htmlLang.toLowerCase().indexOf('zh') === 0) {
      return 'zh';
    }
    return 'en';
  }

  // ===== 设置语言 =====
  function setArticleLanguage(lang) {
    // 保存到 localStorage
    try {
      localStorage.setItem('ui-lang-pref', lang);
    } catch (e) {}

    // 更新所有 data-lang 内容块
    var allLangContent = document.querySelectorAll('[data-lang]');
    for (var i = 0; i < allLangContent.length; i++) {
      var el = allLangContent[i];
      var elLang = el.getAttribute('data-lang');
      if (elLang === lang) {
        el.classList.add('active');
        el.style.display = '';
      } else {
        el.classList.remove('active');
        el.style.display = 'none';
      }
    }

    // 更新右上角语言标签
    var langLabel = document.getElementById('article-lang-label');
    if (langLabel) {
      langLabel.textContent = (lang === 'zh') ? '中' : 'English';
    }
  }

  // ===== 语言切换函数 (挂到全局) =====
  window.toggleArticleLanguage = function(event) {
    if (event) event.preventDefault();
    var currentLang = getCurrentLang();
    var newLang = (currentLang === 'zh') ? 'en' : 'zh';
    setArticleLanguage(newLang);
  };

  // ===== 页面加载时初始化语言 =====
  var initLang = getCurrentLang();
  setArticleLanguage(initLang);
})();
</script>

<?php wp_footer(); ?>
</body>
</html>
