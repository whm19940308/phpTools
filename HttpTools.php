<?php

header('content-type:text/html;charset=utf-8');

class HttpTools{


    // 说明：获取当前页面的URL
    // echo getCurrentPageURL();
    function getCurrentPageURL()
    {
        $pageURL = 'http';
        if (!empty($_SERVER['HTTPS'])) {$pageURL .= "s";}
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }


}