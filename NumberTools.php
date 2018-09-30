<?php

header('content-type:text/html;charset=utf-8');

/**
 * 数字工具类
 * Class NumberTools
 */

class NumberTools{

    
    /**
     * @desc 不足时几位数时，前面补零
     * @param $len
     * @param $number
     * @return string
     */
    public function fillZero($len = 0, $number = 0){

        return sprintf("%0".$len."d", $number);//生成4位数，不足前面补0

    }


    /**
     * @desc 转换字节数为其他单位
     * @param	string	$file_size	字节大小
     * @return	string	返回大小
     */
    function sizeCount($file_size = 0) {

        if ($file_size >= 1073741824) {
            $file_size = round($file_size / 1073741824 * 100) / 100 .' GB';
        } elseif ($file_size >= 1048576) {
            $file_size = round($file_size / 1048576 * 100) / 100 .' MB';
        } elseif($file_size >= 1024) {
            $file_size = round($file_size / 1024 * 100) / 100 . ' KB';
        } else {
            $file_size = $file_size.' Bytes';
        }
        return $file_size;

    }


}