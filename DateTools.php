<?php

header('content-type:text/html;charset=utf-8');

//设置中国时间为默认时区
date_default_timezone_set('PRC');

/**
 * 时间工具类
 * Class DateTools
 */

class DateTools{

    /**
     * @desc 得到某天凌晨零点的时间戳
     * @param string $str
     * @return int
     */
    public function getSomeZeroTimeStamp($str='today'){

        switch ($str)
        {
            case 'today':   // 今天凌晨零点的时间戳
                return strtotime(date("Y-m-d"),time());
                break;
            case 'yesterday':   // 昨天 即 今天凌晨零点的时间戳 减去 一天的秒数
                return strtotime(date("Y-m-d"),time())-3600*24;
                break;
            case 'tomorrow':    // 明天 即 今天凌晨零点的时间戳 加上 一天的秒数
                return strtotime(date("Y-m-d"),time())+3600*24;
                break;
            case 'month_first': // 这个月第一天凌晨零点的时间戳
                return strtotime(date("Y-m"),time());
                break;
            case 'year_first':  // 这一年第一天凌晨零点的时间戳
                return strtotime(date("Y-01"),time());
                break;
            default:   // 默认为今天凌晨零点的时间戳
                return strtotime(date("Y-m-d"),time());
        }

    }


    /**
     * @desc 友好时间显示
     * @param $time
     * @return bool|string
     */
    function friendDate($time)
    {
        if (!$time)
            return false;
        $f_date = '';
        $d = time() - intval($time);
        $ld = $time - mktime(0, 0, 0, 0, 0, date('Y')); //得出年
        $md = $time - mktime(0, 0, 0, date('m'), 0, date('Y')); //得出月
        $byd = $time - mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')); //前天
        $yd = $time - mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')); //昨天
        $dd = $time - mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天
        $td = $time - mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')); //明天
        $atd = $time - mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')); //后天
        if ($d == 0) {
            $f_date = '刚刚';
        } else {
            switch ($d) {
                case $d < $atd:
                    $f_date = date('Y年m月d日', $time);
                    break;
                case $d < $td:
                    $f_date = '后天' . date('H:i', $time);
                    break;
                case $d < 0:
                    $f_date = '明天' . date('H:i', $time);
                    break;
                case $d < 60:
                    $f_date = $d . '秒前';
                    break;
                case $d < 3600:
                    $f_date = floor($d / 60) . '分钟前';
                    break;
                case $d < $dd:
                    $f_date = floor($d / 3600) . '小时前';
                    break;
                case $d < $yd:
                    $f_date = '昨天' . date('H:i', $time);
                    break;
                case $d < $byd:
                    $f_date = '前天' . date('H:i', $time);
                    break;
                case $d < $md:
                    $f_date = date('m月d日 H:i', $time);
                    break;
                case $d < $ld:
                    $f_date = date('m月d日', $time);
                    break;
                default:
                    $f_date = date('Y年m月d日', $time);
                    break;
            }
        }
        return $f_date;
    }



}