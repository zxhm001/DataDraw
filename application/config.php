<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------
    //密码加盐
    'salt'                   => 'sdshare',
    // 应用命名空间
    'app_namespace'          => 'app',
    // 应用调试模式
    'app_debug'              => true,
    // 应用Trace
    'app_trace'              => false,
    // 应用模式状态
    'app_status'             => '',
    // 是否支持多模块
    'app_multi_module'       => true,
    // 入口自动绑定模块
    'auto_bind_module'       => false,
    // 注册的根命名空间
    'root_namespace'         => [],
    // 扩展函数文件
    'extra_file_list'        => [THINK_PATH . 'helper' . EXT],
    // 默认输出类型
    'default_return_type'    => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler'  => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'      => 'callback',
    // 默认时区
    'default_timezone'       => 'PRC',
    // 是否开启多语言
    'lang_switch_on'         => false,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'         => '',
    // 默认语言
    'default_lang'           => 'zh-cn',
    // 应用类库后缀
    'class_suffix'           => false,
    // 控制器类后缀
    'controller_suffix'      => false,

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module'         => 'index',
    // 禁止访问模块
    'deny_module_list'       => ['common'],
    // 默认控制器名
    'default_controller'     => 'Index',
    // 默认操作名
    'default_action'         => 'index',
    // 默认验证器
    'default_validate'       => '',
    // 默认的空控制器名
    'empty_controller'       => 'Error',
    // 操作方法后缀
    'action_suffix'          => '',
    // 自动搜索控制器
    'controller_auto_search' => false,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // PATHINFO变量名 用于兼容模式
    'var_pathinfo'           => 's',
    'url_convert'    =>  false,
    // 兼容PATH_INFO获取
    'pathinfo_fetch'         => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr'          => '/',
    // URL伪静态后缀
    'url_html_suffix'        => '',
    // URL普通方式参数 用于自动生成
    'url_common_param'       => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type'         => 0,
    // 是否开启路由
    'url_route_on'           => true,
    // 路由使用完整匹配
    'route_complete_match'   => false,
    // 路由配置文件（支持配置多个）
    'route_config_file'      => ['route'],
    // 是否强制使用路由
    'url_route_must'         => false,
    // 域名部署
    'url_domain_deploy'      => false,
    // 域名根，如thinkphp.cn
    'url_domain_root'        => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'            => false,
    // 默认的访问控制器层
    'url_controller_layer'   => 'controller',
    // 表单请求类型伪装变量
    'var_method'             => '_method',
    // 表单ajax伪装变量
    'var_ajax'               => '_ajax',
    // 表单pjax伪装变量
    'var_pjax'               => '_pjax',
    // 是否开启请求缓存 true自动缓存 支持设置请求缓存规则
    'request_cache'          => false,
    // 请求缓存有效期
    'request_cache_expire'   => null,
    // 全局请求缓存排除规则
    'request_cache_except'   => [],

    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------

    'template'               => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 模板路径
        'view_path'    => '',
        // 模板后缀
        'view_suffix'  => 'html',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}',
        // 标签库标签开始标记
        'taglib_begin' => '{',
        // 标签库标签结束标记
        'taglib_end'   => '}',
    ],

    // 视图输出字符串内容替换
    'view_replace_str'       => [],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl'    => "application/index/view/error.html",

    // +----------------------------------------------------------------------
    // | 异常及错误设置
    // +----------------------------------------------------------------------

    // 异常页面的模板文件
    'exception_tmpl'         => THINK_PATH . 'tpl' . DS . 'think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'          => '很抱歉,出现错误 :(',
    // 显示错误信息
    'show_error_msg'         => false,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle'       => '',

    // +----------------------------------------------------------------------
    // | 日志设置
    // +----------------------------------------------------------------------

    'log'                    => [
        // 日志记录方式，内置 file socket 支持扩展
        'type'  => 'File',
        // 日志保存目录
        'path'  => LOG_PATH,
        // 日志记录级别
        'level' => ["error"],
    ],

    // +----------------------------------------------------------------------
    // | Trace设置 开启 app_trace 后 有效
    // +----------------------------------------------------------------------
    'trace'                  => [
        // 内置Html Console 支持扩展
        'type' => 'Html',
    ],

    // +----------------------------------------------------------------------
    // | 缓存设置
    // +----------------------------------------------------------------------

    'cache'                  => [
        // 驱动方式
        'type'   => 'File',
        // 缓存保存目录
        'path'   => CACHE_PATH,
        // 缓存前缀
        'prefix' => '',
        // 缓存有效期 0表示永久缓存
        'expire' => 0,
    ],

    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    'session'                => [
        'id'             => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix'         => 'sd',
        // 驱动方式 支持redis memcache memcached
        'type'           => '',
        // 是否自动开启 SESSION
        'auto_start'     => true,
    ],

    // +----------------------------------------------------------------------
    // | Cookie设置
    // +----------------------------------------------------------------------
    'cookie'                 => [
        // cookie 名称前缀
        'prefix'    => '',
        // cookie 保存时间
        'expire'    => 0,
        // cookie 保存路径
        'path'      => '/',
        // cookie 有效域名
        'domain'    => '',
        //  cookie 启用安全传输
        'secure'    => false,
        // httponly设置
        'httponly'  => '',
        // 是否使用 setcookie
        'setcookie' => true,
    ],

    //分页配置
    'paginate'               => [
        'type'      => 'bootstrap',
        'var_page'  => 'page',
        'list_rows' => 15,
    ],
    // +----------------------------------------------------------------------
    // | 验证码设置
    // +----------------------------------------------------------------------
    'captcha'=>[
        'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
        // 验证码字符集合
        'expire'   => 1800,
        // 验证码过期时间（s）
        'useZh'    => false,
        // 使用背景图片
        'fontSize' => 25,
        // 验证码字体大小(px)
        'useCurve' => true,
        // 是否画混淆曲线
        'useNoise' => true,
        // 是否添加杂点
        'imageH'   => 0,
        // 验证码图片高度
        'imageW'   => 0,
        // 验证码图片宽度
        'length'   => 4,
        // 验证码位数
        'fontttf'  => '',
        // 验证码字体，不设置随机获取
        'bg'       => [243, 251, 254],
        // 背景颜色
        'reset'    => true],
    // +----------------------------------------------------------------------
    // | 支付宝设置
    // +----------------------------------------------------------------------
    'alipay'=>[
        //应用ID,您的APPID。
		'app_id' => "2019010262714670",
		//商户私钥
		'merchant_private_key' => "MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCAoQBf8c7c3SYrEVl1dCmSgjM1COS2uvbzWSpuyDlEoLAfEq9mx5GtfeSavD+DGcoWa6z3wp/qyRcP51Svmt0yOI5MdM/GDWNnjgQYgwbwgEeCC+dAtlX4Mz2ftmoou+S554Iaq2HJGypr0J3zQ8qWxpbJPZnRLG0oYLZRcAkgMZwo9tgyczq7hPhWHKNxmJncCBdGYnpIp2hvOVKVXkHf8xeUUeBJ36HT2aEtaW+GnEu3GVGnKBwJz3uwxAXaYosTsYP1ZvgRnUtn+ZwGWgAE3TTIriIQkLYI8T1G25eh2owd0g7TMouosB57QUV+A41QMW6q+LkkhAMwSCVyeeW1AgMBAAECggEAWrYwV3lmU2g6pKDFoAld0A62Ii61bRCJW7CENkBJ8tYHnsJEpI0VdW6VK3Kar9AeLpWdE9VThfZupy6TEIx8dhBgehbe+GM0uTD0Pn0ZBb7RryrJ2V89XZ51VQ8F6tgnQ9u6ayh1+6eG0SX8XjRuyMogSKeCtHtKN/GMYf5K0UJeNWMzIRlKCN5N97zX3RGae9//AUAaAFn8u2YqAxHzAVvvS+AkTbWWg2wa0t00UarSLNP6URhuAKVskMOpvKdAvW9QIxn1zRe3Ue10wq18lW0vmfJRxBq/AiB8EO0lI5ZK9a08IHcqbHAxlYWAnQHT4GK9yDO5K3ORsMbMkcv5AQKBgQD5oaxLSTsov6dco6W2UiqBNxlMLpLZRkqlXQd0kjgFXgA0a4Xg6+AgPDS5kq92+3SsLghdurxQZ41vawz9z+Fa4RJ3V9fcygK+EYqWvUBmKBj/e6w9VKUANYOPHOl1QBYNf4j6gt1kuHIjMrGxMXHPOlksAcVIwmqQa7ZKnLVXFQKBgQCD6RF/B1lDuRBXKzWlbUcVDtL4xYHHAQh2x94y6CFn7QXcNuyh02+UmLRHjl0CZepfyKnuydqRly7o3b9NyTSv+lhXWEyZyKzn7INjn99B9DKSbT6f07PFh5HN7XQfV62/oob5DXswVbC/tTfit4vmSTWw1jxIXPow7gVKYB38IQKBgDRMPK5hHzXAQS3VUwhJWoJLqs8dCsLeSREv1joD0By/vsc8p4WhpQjb1Cf0pTIGKEFSO4p3brBhoW3wPX6HKK1Dbfz2uFCXOc/cGO7Po0hDqkkL/d+zdgX/MBqxce+Qh444Y9gnxn4tbiwVmMiIlVFW5gukK1K8+FpdVol3Hz6pAoGAE1e30xK2yjF27fFOGoXdqH7V/NipYQ8LuK8yK/DSBTVCaLo9FLW2ZOnHdb6wcMuVBJqeIH/E5xhuLoNlq/hXG37wOU6fXOWKRS/vTEqDKF6wk7wlNLaMY+ivPq0VHAt1VOZ2OEr7x2ipVFM0cLBNeWU+1EF18X6AyW/9opxZiUECgYEA7gPB5PqMsKKfiAa9NH81ESvIt1TbhUeLyNNn1ESUlnV6RoF6b0XCkLDLsAqAcTF21+oM8j2owRO4MT8pCKsRdn4ODr+Ks5xGk1g0YbbMK6gW3cesoB/TqKP1lDSEhjKuJBndseUzCA2i801oEXJDLoTR3kOro3s4Skm0Ag9YfGA=",		
		//异步通知地址
		'notify_url' => "https://www.myshuju.net/pay/alipay_notify",		
		//同步跳转
		'return_url' => "https://www.myshuju.net/pay/alipay_return",
		//编码格式
		'charset' => "UTF-8",
		//签名方式
		'sign_type'=>"RSA2",
		//支付宝网关//https://openapi.alipay.com/gateway.do
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAvoEx1Wut+IcR6Oo3uRmC/jONERgSm8ZBHyoLSP+nx/F3E/aV+5cUw/FtO7rtjUvJX6lxBgaNyWkU4+tAkzLR047ZRIiNhueSfwMiJ5x1+EkWsI89O4ZqRNZkx9w5//0oac5lyk2SI/1fm78yrT0/GoqmGF+aTmpQUB8gaxCL/+Dv2FYG4De7PMAOKWT1K5V09ibuIH1+OLQfn8Lcymd6Vo9f/THz1M5h+grgKI4v/Ms9kdLW2fWF8HfTAbWaR9URePLVNy11IvurWkPKRK65eXBI5rQzJ3r9g+OFTHlWcuIanQUomFP5SuTo6kKqwZ6ptsejmtqpQZ8ucHCAKAiRvwIDAQAB",
    ],
    // +----------------------------------------------------------------------
    // | 微信支付设置
    // +----------------------------------------------------------------------
    'wxpay'=>[
        //绑定支付的APPID（必须配置，开户邮件中可查看）
        'app_id'=>"wx426b3015555a46be",
        //商户号（必须配置，开户邮件中可查看）
        'merchanti_d'=>"1900009851",
        //支付回调url
        'notify_url'=>"",
        //签名和验证签名方式， 支持md5和sha256方式
        'sign_type'=>"HMAC-SHA256",
        //商户支付密钥，参考开户邮件设置（必须配置，登录商户平台自行设置）, 请妥善保管， 避免密钥泄露
        'app_secret'=>"7813490da6f1265e4901ffb80afaa36f",
        //公众帐号secert（仅JSAPI支付的时候需要配置， 登录公众平台，进入开发者中心可设置）， 请妥善保管， 避免密钥泄露
        'key'=>"8934e7d15453e97507ef794cf7b0519d",

    ],
];
