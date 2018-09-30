<?php

header('content-type:text/html;charset=utf-8');

/**
 * 字符串相关操作的工具类
 * Class StringTools
 */

class StringTools{


    /**
     * @desc 字符串截取，支持中文和其他编码, 字符串截取是一个开发者都要面对的基本技能，毕竟你要处理数据，支持中文和其他编码
     * @desc 这里的关键，$charset="utf-8"，对中文支持是很重要的！不然出现一些？号，就要挨批了！
     * @param [string] $str  [字符串]
     * @param integer $start [起始位置]
     * @param integer $length [截取长度]
     * @param string $charset [字符串编码]
     * @param boolean $suffix [是否有省略号]
     * @return [type] [description]
     */
    public function mSubStr($str, $start=0, $length=15, $charset="utf-8", $suffix=true) {
        if(function_exists("mb_substr")) {
            return mb_substr($str, $start, $length, $charset);
        } elseif(function_exists('iconv_substr')) {
            return iconv_substr($str,$start,$length,$charset);
        }
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
        if($suffix) {
            return $slice."…";
        }
        return $slice;
    }
    /*
    使用方法如下：
        echo mSubStr($str, $start=0, $length=15, $charset="utf-8", $suffix=true)
    */



    /**
     * @desc PHP利用正则表达式实现手机号码中间4位用星号替换显示
     * @param $phone
     * @return null|string|string[]
     */
    function hideTel($phone){
        $IsWhat = preg_match('/(0[0-9]{2,3}[-]?[2-9][0-9]{6,7}[-]?[0-9]?)/i',$phone); //固定电话
        if($IsWhat == 1){
            return preg_replace('/(0[0-9]{2,3}[-]?[2-9])[0-9]{3,4}([0-9]{3}[-]?[0-9]?)/i','$1****$2',$phone);
        }else{
            return  preg_replace('/(1[358]{1}[0-9])[0-9]{4}([0-9]{4})/i','$1****$2',$phone);
        }
    }
    /*
    $phone = "13966778888";
    echo hideTel($phone);
    */



    /**
     * @desc 获取指定文件的后缀名
     * @param array $input 输入的数组
     * @param string $columnKey 指定数组列明
     * @param null $indexKey
     * @return array
     */
    function getFileExt($file) {
        //SPLFileInfo类的getExtension方法，需要5.3.6+版本
        if(version_compare(PHP_VERSION,'5.3.6','>=')){
            $fileInfo = new splFileInfo($file);
            return $fileInfo->getExtension();
        }else{
            $fileInfo = pathinfo($file);
            return $fileInfo['extension'];
        }
    }
    /*
    $file = 'C:\Users\Administrator\Desktop\新建文件夹\127.0.0.1\test.txt';
    //输出 txt
    echo getFileExt($file);
    */



    /**
     * @desc 判断字符串是utf-8 还是gb2312
     * @param $str
     * @param string $default
     * @return string
     */
    public static function is_utf8_gb2312($str, $default = 'gb2312')
    {
        $str = preg_replace("/[\x01-\x7F]+/", "", $str);
        if (empty($str)) return $default;
        $preg =  array(
            "gb2312" => "/^([\xA1-\xF7][\xA0-\xFE])+$/", //正则判断是否是gb2312
            "utf-8" => "/^[\x{4E00}-\x{9FA5}]+$/u",      //正则判断是否是汉字(utf8编码的条件了)，这个范围实际上已经包含了繁体中文字了
        );
        if ($default == 'gb2312') {
            $option = 'utf-8';
        } else {
            $option = 'gb2312';
        }
        if (!preg_match($preg[$default], $str)) {
            return $option;
        }
        $str = @iconv($default, $option, $str);
        //不能转成 $option, 说明原来的不是 $default
        if (empty($str)) {
            return $option;
        }
        return $default;
    }



    /**
     * @desc utf-8和gb2312自动转化
     * @param $string
     * @param string $outEncoding
     * @return string
     */
    public static function safeEncoding($string,$outEncoding = 'UTF-8')
    {
        $encoding = "UTF-8";
        for($i = 0; $i < strlen ( $string ); $i ++) {
            if (ord ( $string {$i} ) < 128)
                continue;
            if ((ord ( $string {$i} ) & 224) == 224) {
                // 第一个字节判断通过
                $char = $string {++ $i};
                if ((ord ( $char ) & 128) == 128) {
                    // 第二个字节判断通过
                    $char = $string {++ $i};
                    if ((ord ( $char ) & 128) == 128) {
                        $encoding = "UTF-8";
                        break;
                    }
                }
            }
            if ((ord ( $string {$i} ) & 192) == 192) {
                // 第一个字节判断通过
                $char = $string {++ $i};
                if ((ord ( $char ) & 128) == 128) {
                    // 第二个字节判断通过
                    $encoding = "GB2312";
                    break;
                }
            }
        }
        if (strtoupper ( $encoding ) == strtoupper ( $outEncoding ))
            return $string;
        else
            return @iconv ( $encoding, $outEncoding, $string );
    }




    /**
     * @desc 将用户名进行处理，中间用星号表示，可用于中文，https://www.whmblog.cn/php/40.html
     * @param $user_name
     * @return string
     */
    public function subStrCut($user_name){

        //获取字符串长度
        $strlen = mb_strlen($user_name, 'utf-8');
        //如果字符创长度小于2，不做任何处理
        if($strlen<2){
            return $user_name;
        }else{
            //mb_substr — 获取字符串的部分
            $firstStr = mb_substr($user_name, 0, 1, 'utf-8');
            $lastStr = mb_substr($user_name, -1, 1, 'utf-8');
            //str_repeat — 重复一个字符串
            return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($user_name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;
        }

    }



    /**
     * @desc 随机字符串生成
     * @param int $len 生成的字符串长度
     * @return string
     */
    function randomString($len = 6) {
        $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9"
        );
        $charsLen = count($chars) - 1;
        shuffle($chars);    // 将数组打乱
        $output = "";
        for ($i = 0; $i < $len; $i++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }


    /**
     * @desc 随机字符
     * @param number $length 长度
     * @param string $type 类型
     * @param number $convert 转换大小写
     * @return string
     */
    function random($length=6, $type='string', $convert=0){

        $config = array(
            'number'=>'1234567890',
            'letter'=>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'string'=>'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789',
            'all'=>'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'
        );

        if(!isset($config[$type])) $type = 'string';
        $string = $config[$type];

        $code = '';
        $strlen = strlen($string) -1;
        for($i = 0; $i < $length; $i++){
            $code .= $string{mt_rand(0, $strlen)};
        }
        if(!empty($convert)){
            $code = ($convert > 0)? strtoupper($code) : strtolower($code);
        }
        return $code;
    }


    /**
     * @desc 查询字符是否存在于某字符串
     * @param $haystack 字符串
     * @param $needle 要查找的字符
     * @return bool
     */
    function strExists($haystack, $needle)
    {
        return !(strpos($haystack, $needle) === FALSE);
    }



    /**
     * @desc 实现中文字串截取无乱码的方法
     * @param $string
     * @param $start
     * @param $length
     * @return string
     */
    function getSubstr($string, $start, $length) {
        if(mb_strlen($string,'utf-8')>$length){
            $str = mb_substr($string, $start, $length,'utf-8');
            return $str.'...';
        }else{
            return $string;
        }
    }


    /**
     * @desc 手机号隐藏中间
     * @param $mobile
     * @return mixed
     */
    function mobileHide($mobile){

        return substr_replace($mobile,'****',3,4);

    }


    /**
     * 获取整条字符串汉字拼音首字母
     * @param $zh
     * @return string
     */
    function pinyin_long($zh){
        $ret = "";
        $s1 = iconv("UTF-8","gb2312", $zh);
        $s2 = iconv("gb2312","UTF-8", $s1);
        if($s2 == $zh){$zh = $s1;}
        for($i = 0; $i < strlen($zh); $i++){
            $s1 = substr($zh,$i,1);
            $p = ord($s1);
            if($p > 160){
                $s2 = substr($zh,$i++,2);
                $ret .= getFirstCharter($s2);
            }else{
                $ret .= $s1;
            }
        }
        return $ret;
    }


}

