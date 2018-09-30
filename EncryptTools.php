<?php

header('content-type:text/html;charset=utf-8');

/**
 * 加密工具类
 * Class EncryptTools
 */

class EncryptTools{


    /**
     * @desc 加密uid
     * @param $uid
     * @return string
     */
    function setEncryptUid($uid){

        //vendor('Encrypt.XDeode');
        include "./Vendor/Encrypt/XDeode.php";
        $obj = new \XDeode(18,520.1314);
        return $obj->encode($uid);

    }


    /**
     * @desc 加密uid
     * @param $uid
     * @return int
     */
    function getEncryptUid($uid){

//        vendor('Encrypt.XDeode');
        include "./Vendor/Encrypt/XDeode.php";
        $obj = new \XDeode(18,520.1314);

        //echo $obj->encode(1);exit;
        $uid = $obj->decode($uid);
        return intval($uid);

    }

}