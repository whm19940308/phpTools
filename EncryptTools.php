<?php

header('content-type:text/html;charset=utf-8');

/**
 * 加密工具类
 * Class EncryptTools
 */

class EncryptTools{


    // 说明：加密uid
    function setEncryptUid($uid){

        //vendor('Encrypt.XDeode');
        include "./Vendor/Encrypt/XDeode.php";
        $obj = new \XDeode(18,520.1314);
        return $obj->encode($uid);

    }

    // 说明：解密uid
    function getEncryptUid($uid){

//        vendor('Encrypt.XDeode');
        include "./Vendor/Encrypt/XDeode.php";
        $obj = new \XDeode(18,520.1314);

        //echo $obj->encode(1);exit;
        $uid = $obj->decode($uid);
        return intval($uid);

    }

}