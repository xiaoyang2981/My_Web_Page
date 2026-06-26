# 我的开源个人主页

个人主页项目，包含博客、社区、OC展示、友链等功能模块。

## 结构

```
├── index.html              # 主页
├── admin/                  # 管理后台
│   ├── config.php          # 配置（用户名、密码）
│   ├── index.php           # 管理面板
│   └── setup.php           # 初始化 API
├── api/                    # API 接口
│   ├── index.php           # 数据读写 API
│   ├── saveconfig.php      # 账号配置保存
│   └── upload.php          # 文件上传
├── css/                    # 样式文件
├── data/                   # JSON 数据存储
├── html/                   # 子页面
│   ├── about.html          # 关于我
│   ├── admin.html          # 管理面板
│   ├── friend.html         # 朋友
│   ├── furl.html           # 友链
│   ├── login.html          # 登录
│   ├── oc.html             # OC 展示
│   ├── repositories.html   # 项目
│   └── setup.html          # 初始化页面
├── icon/                   # SVG 图标
├── img/                    # 图片
├── js/                     # JavaScript
└── config.json             # 用户配置（首次安装后生成）
```

## 部署

### 环境要求
- PHP 8.0+

### 启动
```bash
php -S 0.0.0.0:8080
```

### 首次使用
1. 访问 `http://localhost:8080/html/setup.html`
2. 设置管理员用户名和密码
3. 登录管理后台 `http://localhost:8080/html/login.html`

## 数据存储

所有数据以 JSON 文件形式存储在 `data/` 目录下，无需数据库。

## 开源协议

GNU General Public License v3.0
