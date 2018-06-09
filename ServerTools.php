<?php


class ServerTools{


    /**
     * 判断当前服务器系统
     * @return string
     */
    public static function getOS()
    {
        if (PATH_SEPARATOR == ':') {
            return 'Linux';
        } else {
            return 'Windows';
        }
    }


    /**
     * @desc memory_get_usage() 可以分析内存占用空间
     * @desc 在实际WEB开发中，可以用PHP memory_get_usage()比较各个方法占用内存的高低，来选择使用哪种占用内存小的方法
     * @return string
     */
    function getMemoryUsage() {

        $memory  = ( ! function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2).'MB';
        return $memory;

    }
    //    echo '开始内存：'.getMemoryUsage(), '';
    //    $tmp = str_repeat('hello', 1000);
    //    echo '运行后内存：'.getMemoryUsage(), '';
    //    unset($tmp);
    //    echo '回到正常内存：'.getMemoryUsage();


    /**
     * IE浏览器判断
     */

    function isIE() {

        $useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
        if((strpos($useragent, 'opera') !== false) || (strpos($useragent, 'konqueror') !== false)){
            return false;
        }
        if(strpos($useragent, 'msie ') !== false){
            return true;
        }
        return false;

    }


	function getip() {
		
		$ip = '';
		$ip = $_SERVER['REMOTE_ADDR'];
		if(isset($_SERVER['HTTP_CDN_SRC_IP'])) {
			$ip = $_SERVER['HTTP_CDN_SRC_IP'];
		} elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
			foreach ($matches[0] AS $xip) {
				if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
					$ip = $xip;
					break;
				}
			}
		}
		return $ip;
		
	}

}