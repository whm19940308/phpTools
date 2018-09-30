<?php

header('content-type:text/html;charset=utf-8');

/**
 * Class MapTools
 * 地图工具类
 */

class MapTools{



    /**
     * @desc 通过经纬度获取 详细地址 http://lbsyun.baidu.com/index.php?title=webapi/guide/changeposition
     * @param $longitude 经度
     * @param $latitude 纬度
     * @return bool|string
     */
    public function getAddressByLngLat($longitude,$latitude){

        // 经纬度 目前我的手机的定位
        // $latitude = '22.59123';
        // $longitude = '113.9521';

        // 把企鹅经纬度 转成 百度经纬度
        // 解决百度地图偏差问题
        $url = 'http://api.map.baidu.com/geoconv/v1/?coords='.$longitude.','.$latitude.'&from=1&to=5&ak=ZQiFErjQB7inrGpx27M1GR5w3TxZ64k7';
        $lng_lat_data = file_get_contents($url);
        $lng_lat_data = json_decode($lng_lat_data,true);

        if($lng_lat_data['status']==0){
            $longitude = $lng_lat_data['result'][0]['x'];
            $latitude = $lng_lat_data['result'][0]['y'];
        }

        $url = 'http://api.map.baidu.com/geocoder/v2/?location='.$latitude.','.$longitude.'&output=json&pois=1&ak=ZQiFErjQB7inrGpx27M1GR5w3TxZ64k7';
        $get_url_data = file_get_contents($url);
        $get_url_data = json_decode($get_url_data,true);

        if($get_url_data['status']==0){
            $address = $get_url_data['result']['addressComponent']['province']
                .$get_url_data['result']['addressComponent']['city']
                .$get_url_data['result']['addressComponent']['district']
                .$get_url_data['result']['sematic_description'];

            return $address;
        }else{
            return false;
        }


    }


    /**
     * @desc 根据两个经纬度计算两地距离
     * @param $lat1 纬度1
     * @param $lng1 经度1
     * @param $lat2 纬度2
     * @param $lng2 经度2
     * @param int $len_type
     * @param int $decimal 1:m（米） or 2:km（千米）
     * @return float 两个距离保留几位小数点
     */
    public function getDistance($lat1, $lng1, $lat2, $lng2, $len_type = 1, $decimal = 2) {

        $pi = pi(); // 圆周率
        $er = 6378.1369999999997;
        $radLat1 = ($lat1 * $pi) / 180;
        $radLat2 = ($lat2 * $pi) / 180;
        $a = $radLat1 - $radLat2;
        $b = (($lng1 * $pi) / 180) - (($lng2 * $pi) / 180);
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + (cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))));
        $s = $s * $er;
        $s = round($s * 1000);
        if (1 < $len_type) {
            $s /= 1000;
        }
        return round($s, $decimal);

    }



}