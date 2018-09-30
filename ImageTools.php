<?php

header('content-type:text/html;charset=utf-8');

/**
 * Class ImageTools
 * 图片工具类
 */

class ImageTools{


    /**
     * @desc Base64生成图片文件,自动解析格式
     * @param $base64 可以转成图片的base64字符串
     * @param $path 绝对路径
     * @param $filename 生成的文件名
     * @return array 返回的数据，当返回status==1时，代表base64生成图片成功，其他则表示失败
     */
    public function base64ToImage($base64, $path, $filename) {
        
        $res = array();
        //匹配base64字符串格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64, $result)) {
            //保存最终的图片格式
            $postfix = $result[2];
            $base64 = base64_decode(substr(strstr($base64, ','), 1));
            $filename = $filename . '.' . $postfix;
            $path = $path . $filename;
            //创建图片
            if (file_put_contents($path, $base64)) {
                $res['status'] = 1;
                $res['filename'] = $filename;
            } else {
                $res['status'] = 2;
                $res['err'] = 'Create img failed!';
            }
        } else {
            $res['status'] = 2;
            $res['err'] = 'Not base64 char!';
        }

        return $res;
        
    }


}