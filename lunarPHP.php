<?php

namespace LunarPHP;

//session_start();
//引入配置文件
include_once __DIR__.'/config/config.php';
//引入自动载入函数
include_once __DIR__.'/autoloader.php';
//引入语言文件
include_once __DIR__.'/language/zh-cn.php';
//调用自动载入函数
AutoLoader::register();
