<?php

// 引入两个核心文件
require_once './_autoload.php';
require_once './functions.php';

// 使用示例
// 如调用DateTools工具类里的方法，调用示例如下
$date_tools = new DateTools();
var_dump($date_tools->getSomeZeroTimeStamp('today'));

// 如调用ImageTools工具类里的方法，调用示例如下
$image_tools = new ImageTools();
$img_url = 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1577247036771&di=54224f170a7f8dfe7d30384f2ca9d2f0&imgtype=0&src=http%3A%2F%2Fimg.zcool.cn%2Fcommunity%2F01047a5a5494d1a8012113c791ffcb.jpg%401280w_1l_2o_100sh.jpg';
var_dump($image_tools->isImgUrl($img_url));