lunarPHP
===============
[![Latest Stable Version](https://poser.pugx.org/jyil/lunar-php/v/stable)](https://packagist.org/packages/jyil/lunar-php)
[![Total Downloads](https://poser.pugx.org/jyil/lunar-php/downloads)](https://packagist.org/packages/jyil/lunar-php)
[![Latest Unstable Version](https://poser.pugx.org/jyil/lunar-php/v/unstable)](https://packagist.org/packages/jyil/lunar-php)
[![License](https://poser.pugx.org/jyil/lunar-php/license)](https://packagist.org/packages/jyil/lunar-php)


## About lunarPHP

根据以下开源项目修改整合封装的一个php易经六十四卦排盘类库，只需要引入路口文件就可以简单的调用方法求出卦象结果

- [64divine](https://github.com/tc31/64divine).



> 运行环境要求PHP5.4以上。

## 目录结构

初始的目录结构如下：

~~~
├─core           		类库核心文件
│  ├─Calendar.lrp.php
│  ├─GanZhi.php
│  ├─Hexagrams.lrp.php
│  ├─Logger.lrp.php
│  ├─Lunar.lrp.php
│  └─Model.lrp.php
│
├─config                系统配置文件
│  └─config.php
│
├─database              数据库文件
│  └─gua.sql
│
├─language              语言包
│  └─language.php
│
├─logs               	日志信息
│  └─xxx.log
│
├─autoloader.php        自动加载文件
├─example.php           示例文件
├─composer.json         composer 定义文件
├─lunarPHP.php          入口文件
├─README.md             README 文件
~~~

## License

lunarPHP is open-sourced software licensed under the WTFPL license.