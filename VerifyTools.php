<?php

header('content-type:text/html;charset=utf-8');

/**
 * 校验工具类，如验证ip、手机、邮箱等
 * Class VerifyTools
 */

class VerifyTools{


    /**
     * @desc 判断是否为合法的ip地址
     * @param string $ip ip地址
     * @return bool|int 不合法则返回false，合法则返回4（IPV4）或6（IPV6）
     */
    function isIPAddress($ip)
    {
        $ipv4Regex = '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/';

        $ipv6Regex = '/^(((?=.*(::))(?!.*\3.+\3))\3?|([\dA-F]{1,4}(\3|:\b|$)|\2))(?4){5}((?4){2}|(((2[0-4]|1\d|[1-9])?\d|25[0-5])\.?\b){4})$/i';

        if (preg_match($ipv4Regex, $ip))
            return 4;

        if (false !== strpos($ip, ':') && preg_match($ipv6Regex, trim($ip, ' []')))
            return 6;

        return false;
    }


    /**
     * @desc 验证邮箱格式
     * @param $email
     * @return bool
     */
    public function isValidEmail($email)
    {
        $check = false;
        if(filter_var($email,FILTER_VALIDATE_EMAIL))
        {
            $check = true;
        }
        return $check;
    }


    /**
     * @desc 判断是否为手机访问
     * @return  boolean
     */
    function isMobile() {

        $sp_is_mobile = false;

        if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
            $sp_is_mobile = false;
        } elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false // many mobile devices (all iPhone, iPad, etc.)
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
            || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
            $sp_is_mobile = true;
        } else {
            $sp_is_mobile = false;
        }

        return $sp_is_mobile;

    }

    /**
     * @desc 判断是否为微信访问
     * @return boolean
     */
    function isWeiXin(){

        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        }
        return false;

    }


    /**
     * @desc 检查手机号码格式
     * @param $mobile 手机号码
     */
    function checkMobile($mobile){

        if(preg_match('/1[0123456789]\d{9}$/',$mobile))
            return true;
        return false;

    }


    /**
     * @desc 检查固定电话
     * @param $mobile
     * @return bool
     */
    function checkTelephone($mobile){

        if(preg_match('/^([0-9]{3,4}-)?[0-9]{7,8}$/',$mobile))
            return true;
        return false;

    }

    /**
     * @desc 当前请求是否是https
     * @return type
     */
    public function isHttps()
    {
        return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] && $_SERVER['HTTPS'] != 'off';
    }


}