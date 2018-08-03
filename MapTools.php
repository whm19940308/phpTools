<?php

header('content-type:text/html;charset=utf-8');

/**
 * Class MapTools
 * 地图工具类
 */

class MapTools{


    // 通过经纬度获取 详细地址
    // http://lbsyun.baidu.com/index.php?title=webapi/guide/changeposition
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


}