<?php

header('content-type:text/html;charset=utf-8');

/**
 * 数字工具类
 * Class NumberTools
 */

class NumberTools{


    // 不足时前页补零
    public function fillZero($len, $number){

        return sprintf("%0".$len."d", $number);//生成4位数，不足前面补0

    }


    /**
     * 转换字节数为其他单位
     * @param	string	$filesize	字节大小
     * @return	string	返回大小
     */
    function sizeCount($filesize) {

        if ($filesize >= 1073741824) {
            $filesize = round($filesize / 1073741824 * 100) / 100 .' GB';
        } elseif ($filesize >= 1048576) {
            $filesize = round($filesize / 1048576 * 100) / 100 .' MB';
        } elseif($filesize >= 1024) {
            $filesize = round($filesize / 1024 * 100) / 100 . ' KB';
        } else {
            $filesize = $filesize.' Bytes';
        }
        return $filesize;

    }


}