<?php

header('content-type:text/html;charset=utf-8');

/**
 * 与http协议相关的工具类
 * Class HttpTools
 */

class HttpTools{


    /**
     * @desc 获取当前页面的URL
     * @return string
     */
    function getCurrentPageURL(){

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



    /**
     * @desc 获取当前页域名的URL
     * @return string
     */
    function getDomainName(){

        $pageURL = 'http';
        if (!empty($_SERVER['HTTPS'])) {$pageURL .= "s";}
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"].":";
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"];
        }
        return $pageURL;

    }


    /**
     * @desc 解析url并得到url中的参数
     * @param string $url
     * @return array
     */
    function convert_url_query($url = '')
    {
        $arr = parse_url($url);
        $query_arr = explode('&', $arr['query']);
        $params = array();
        if($query_arr){
            foreach ($query_arr as $param) {
                $item = explode('=', $param);
                $params[$item[0]] = $item[1];
            }
        }
        return $params;
    }


}