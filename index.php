<?php

require_once './_autoload.php';



/**
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

dump($_SERVER);