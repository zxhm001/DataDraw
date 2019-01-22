![logo_white.png](source/logo_white.png)

# 数字绘

在线线框图、流程图、网络图、组织结构图、UML、BPMN绘制网站，绘制完成之后可以导出成图片、SVG、XML，也可以保存在云端并能分享给其他用户。  

另外使用Cloudreve的分组实现了会员功能，对系统中的模板和更多的绘图组件进行了会员可用的限制。成为会员可以使用邀请好友和支付的方式。

## 网站

[数字绘](https://www.myshuju.net)

## 轮子

- [Cloudreve](https://github.com/HFO4/Cloudreve) 基于ThinkPHP构建的网盘系统，能够助您以较低成本快速搭建起公私兼备的网盘。

- [mxGraph](https://github.com/jgraph/mxgraph) 一个使用SVG和HTML来渲染的JavaScript图形绘制库。

- [draw.io](https://github.com/jgraph/drawio) 基于mxGraph库做的制图网站，本站主要使用了他的模板和绘图组件

## 部署

### 1. Clone本项目

```
git clone https://gitee.com/zxhm/DataDraw.git
cd DataDraw
```

### 2. 使用Composer安装扩展包

```
composer install
```

### 3. 配置数据和支付参数

将根目录下的mysql.sql到入到你的数据库，编辑application/database_sample.php文件，填写数据库信息，并重命名为database.php，编辑application/config_sample.php文件，主要修改最后部分的支付宝支付和微信支付信息，并重命名为config.php。  

系统中使用的模板数据有点多，单独分了sql文件导出，放在了/static/editor/templates里面，需要使用模板的需要导入这个数据。

### 4. 目录权限

runtime目录需要写入权限，如果你使用本地存储，public 目录也需要有写入权限

### 5. URL重写

对于Apache服务器，请确保
- httpd.conf配置文件中加载了mod_rewrite.so模块
- AllowOverride None 将None改为 All`

项目目录下的.htaccess已经配置好重写规则，如有需求酌情修改.

对于Nginx服务器，以下是一个可供参考的配置：

```
location / {
   if (!-e $request_filename) {
   rewrite  ^(.*)$  /index.php?s=/$1  last;
   break;
    }
 }
```

如果你的应用安装在二级目录，Nginx的伪静态方法设置如下，其中youdomain是所在的目录名称。

```
location /youdomain/ {
    if (!-e $request_filename){
        rewrite  ^/youdomain/(.*)$  /youdomain/index.php?s=/$1  last;
    }
}
```

### 6. 后续操作

到此步时，系统已基本可以正常运行，但还需要进行一些后续操作.

- 登录后台（初始用户名 admin@datadraw.net 初始密码 admin 后台URl http://你的域名/Admin, 登录后到设置>基本设置中检查站点URL是否正确）
- 到用户管理页修改初始用户密码

## 待完善

- [ ] mxGraph 的PHP后台代码是四年前的版本，很不完善，BUG很多，需要照着Java版本给重写一遍（Java版也是不能用的，放弃）
- [x] 激活邮件重新发送
- [ ] 接入微信支付
- [ ] 少部分绘图组件有问题
- [ ] 实现图形预览与其他云存储的结合
- [ ] 分享界面优化
- [ ] 首页分享
- [ ] 主界面预览
- [x] 主界面跳回设置的文件夹
- [x] 绘图界面返回
- [x] 修复第三方图片无法生导出图片的问题


## 鸣谢

感谢 [大胃熊](https://gitee.com/athlon128)兄弟指出和修正多级目录的问题

## 许可证

GPLV3
