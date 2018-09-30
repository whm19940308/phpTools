<?php

// 引入两个核心文件
require_once './_autoload.php';
require_once './functions.php';

// 使用示例
// 如调用DateTools工具类里的方法，调用示例如下
$date_tools = new DateTools();
var_dump($date_tools->getSomeZeroTimeStamp('today'));