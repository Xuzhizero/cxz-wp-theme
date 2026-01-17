# cxz-wp-theme
my-wp-theme

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
- **Which events**：选择 “Just the push event”
- **Active**：勾选启用

保存后，之后每次对 `main` 分支执行 `git push`，都会触发 Hostinger 自动拉取并部署（具体行为以 Hostinger 的 build output 为准）。
