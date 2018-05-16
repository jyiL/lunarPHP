<?php

namespace LunarPHP;

/**
 * 系统主配置文件.
 * @Author: ljy
 * @Date: 17-08-18
 */

/**
 * 数据库配置
 */
$db_conf = array(
    'dbms'          =>  'mysql',    // 数据库类型
    'serverName'    =>  '127.0.0.1',    // 地址
    'dbName'        =>  'demo',    // 数据库
    'user'          =>  'root',    // 账号
    'pass'          =>  'root',    // 密码
);

define('DB_CONFIG',json_encode($db_conf));

/**
 * 日志配置
 */
$log_conf = array(
    'log_file'    =>    'logs',
    'separator'   =>    '^_^',
);

define('LOG_CONF',json_encode($log_conf));


// 当前路径
define('SITE_PATH', dirname(dirname(__FILE__)) . "/");

/**
 * json数据 
 */
$gua_json = file_get_contents(SITE_PATH.'/database/gua.json');
$constellation_json = file_get_contents(SITE_PATH.'/database/constellation.json');

define('GUA_DATA', $gua_json);
define('CONSTELLATION_DATA', $constellation_json);


//版本号
define('LUNARPHP_VERSION', '1.6');
define('LUNARPHP_VERSION_DATE', '2018-05-16');