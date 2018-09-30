<?php

header('content-type:text/html;charset=utf-8');

/**
 * Class CommonTools
 * 公共工具类
 */

class CommonTools{

    /**
     * @desc 通过一个总金额和总个数，生成不同的红包金额，可用于微信发放红包
     * @param $total [你要发的红包总额]
     * @param int $num [发几个]，默认为10个
     * @return array [生成红包金额数组]
     */
    public function getRedGift($total, $num = 10)
    {

        $min = 0.01;
        $temp = array();
        $return = array();
        for ($i = 1; $i < $num; ++$i) {
            $safe_total = ($total - ($num - $i) * $min) / ($num - $i); //红包金额的最大值
            if ($safe_total < 0) break;
            $money = @mt_rand($min * 100, $safe_total * 100) / 100;//随机产生一个红包金额
            $total = $total - $money;// 剩余红包总额
            $temp[$i] = round($money, 2);//保留两位有效数字
        }
        $temp[$i] = round($total, 2);
        $return['money_sum'] = $temp;
        $return['new_total'] = array_sum($temp);
        
        return $return;

    }

}