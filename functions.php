<?php


/**
 * 支持多个参数
 * 格式化打印数据
 */
function dump() {

    $args = func_get_args();
    foreach ($args as $val) {
        echo '<pre style="color: red">';
        var_dump($val);
        echo '</pre>';
    }

}


/**
 * 支持多个参数
 * 格式化打印数据 下断点
 */
function dd() {

    $args = func_get_args();
    foreach ($args as $val) {
        echo '<pre style="color: red">';
        var_dump($val);
        echo '</pre>';
    }
    exit;

}
