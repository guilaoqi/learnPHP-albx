# 学习php构建阿里百秀项目后台

体验地址：http://47.100.203.109:8081/admin/

- 用户名：593448764@qq.com
- 密码：guilaoqi

##  涉及技术

- PHP
- apche搭建
- 数据库：mysql
- js操作：Jquery
- UI：Bootstrap
- 前端渲染模板：jsrender
- 字体库：font-awesome
- 时间格式化：moment.js
- 进度条：nprogress
- 分页：twbs-pagination
- 富文本编辑器：ueditor

## 项目结构

│  config.php     配置文件，定义一些常量      
│  detail.html    详情页      
│  functions.php  定义一些通用操作的函数（数据库操作函数、文件上传的保存等）      
│  index.html     主页      
│  list.html      列表页      
│  load_list.php  列表页数据从数据库加载数据      
│       
├─admin    后台操作      
│  │  categories-delete.php   执行删除分类的数据库操作      
│  │  categories.php          分类页、分类编辑和添加操作               
│  │  comments.php          评论操作页      
│  │  index.php             主页面      
│  │  login.php             登录页      
│  │  nav-menus.php         导航菜单编辑页      
│  │  password-reset.php     密码重设页      
│  │  post-add.php           文章发表页      
│  │  posts-delete.php       删除文章操作      
│  │  posts.php             所有文章页      
│  │  profile.php           个人中心编辑页      
│  │  settings.php           网站设置页      
│  │  slides.php             网站轮播图图片设置页      
│  │  users.php              用户管理页      
│  │        
│  ├─api      
│  │      approve_comment.php   评论通过数据库操作   
│  │      avatar.php            登录页头像链接查询的数据库操作   
│  │      change_password.php   密码更改的数据库操作   
│  │      delete_comment.php    删除评论的数据库操作   
│  │      delete_user.php       删除用户的数据库操作   
│  │      edit_load_user.php    编辑用户的数据操作   
│  │      edit_setting.php      编辑站点信息操作   
│  │      get_comment.php       获取评论数据操作   
│  │      get_user.php          获取用户数据操作   
│  │      menus.php             获取站点信息操作   
│  │      receive_img.php       本地保存上传的图片   
│  │      reject_comment.php    驳回评论操作   
│  │         
│  └─inc   
│          aside.php        公共侧边栏   
│          navbar.php       公共导航栏   
│             
└─static   
    ├─assets   
    │  ├─css  样式文件   
    │  │      admin.css   
    │  │      style.css   
    │  │         
    │  ├─img   静态图片文件   
    │  │      default.png   
    │  │      logo.png   
    │  │         
    │  └─vendors  引用的外部资源   
    │      └─xxx   
    │                     
    └─upload  上传文件夹   

   