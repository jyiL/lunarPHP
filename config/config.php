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


//版本号
define('LUNARPHP_VERSION', '1.0');
define('LUNARPHP_VERSION_DATE', '2017-08-18');