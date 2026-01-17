# cxz-wp-theme

CoolXuzhi 个人网站的 WordPress 自定义主题。

## 主题结构

```
cxz-wp-theme/
├── style.css           # 主题声明文件（WordPress 必需）
├── functions.php       # 主题功能定义（加载资源、移除干扰样式等）
├── index.php           # 默认模板（WP 必需）
├── header.php          # 通用页头模板
├── footer.php          # 通用页脚模板
├── front-page.php      # 首页模板（独立设计，不使用 header/footer）
└── assets/
    ├── image-17.jpg    # 首页 Hero 背景图
    ├── Profile.jpg     # 个人头像
    ├── css/            # 样式文件
    └── js/             # 脚本文件
```

## 首页模板说明

`front-page.php` 是一个**独立设计的首页模板**：

- **不调用** `get_header()` / `get_footer()`，避免主题默认导航/容器影响首页布局
- 包含完整的 HTML 结构、内联 CSS/JS
- 引用 Chennative.ai 的远程样式和脚本资源
- 本地图片使用 `get_stylesheet_directory_uri()` 引用

## 外部资源依赖

当前主题依赖以下远程资源（后续可本地化）：

- `https://Chennative.ai/styles.css` - 主样式表
- `https://Chennative.ai/assets/script.js` - 主脚本
- `https://Chennative.ai/assets/flashload.js` - 页面加载优化
- `https://Chennative.ai/media/*` - 部分图片资源

## functions.php 功能

- **主题基础配置**：自动 Feed 链接、标题标签、缩略图、HTML5 支持
- **远程资源加载**：通过 `wp_enqueue_style/script` 加载 Chennative.ai 资源
- **首页干扰样式移除**：在首页移除 WordPress 核心样式和 Astra 主题样式
- **body 类过滤**：移除 Astra 相关的 body class
- **Emoji 脚本移除**：减少不必要的脚本加载

---

## Hostinger 中 Git 绑定/部署指引

参考 Hostinger 官方教程（hPanel Git 部署与自动部署）：

- `https://www.hostinger.com/support/1583302-how-to-deploy-a-git-repository-in-hostinger/`

### 1) 在 Hostinger（hPanel）绑定仓库并首次部署

在 hPanel 中进入：**Websites → Manage → Git**（侧边栏搜索 Git）。

- **Repository Address**：填你的 GitHub 仓库地址
- **Branch**：选择 `main`
- **Install Path**：建议留空（会部署到 `/public_html`）
- **重要**：Install Path 指向的目录 **必须是空目录**，否则部署会失败（官方文档强调这一点）

创建完成后：

- 点击 **Deploy** 进行首次部署

### 2) 开启自动部署（Auto Deployment）

在 Git 页面点击 **Auto-Deployment**，Hostinger 会提供一个 **Webhook URL**。

### 3) 在 GitHub 仓库配置 Webhook（push 自动触发部署）

在 GitHub 仓库中进入：**Settings → Webhooks → Add webhook**，按以下方式填写：

- **Payload URL**：粘贴 Hostinger 提供的 Webhook URL
- **Content type**：选择 `application/json`
- **Which events**：选择 "Just the push event"
- **Active**：勾选启用

保存后，之后每次对 `main` 分支执行 `git push`，都会触发 Hostinger 自动拉取并部署（具体行为以 Hostinger 的 build output 为准）。
