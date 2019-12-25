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
            $filename .= '.' . $postfix;
            $path .= $filename;
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



    /**
     * @desc 将图片转成base64字符串
     * @param string $filename 图片地址
     * @return string
     */
    public function imageToBase64($filename = ''){

        $base64 = '';
        if(file_exists($filename)){
            if($fp = fopen($filename,"rb", 0))
            {
                $img = fread($fp,filesize($filename));
                fclose($fp);
                $base64 = 'data:image/jpg/png/gif;base64,'.chunk_split(base64_encode($img));
            }
        }
        return $base64;

    }


    /**
     * @desc 根据图片数组, 拼接成微信九宫格式拼图 https://blog.csdn.net/guo_qiangqiang/article/details/100043508     http://www.voidcn.com/article/p-ehjdfrmi-td.html
     * @param  array   $pic_list [带拼成的图片数组]
     * @param  integer $bg_w     [背景图片宽度]
     * @param  integer $bg_h     [背景图片高度]
     * @param  string  $format   [阿里云图片获取参数]
     * @return [type]            [返回一个拼接好的图片（路径）]
     */
    public function mosaicGroupAvatar($pic_list = array(),$bg_w = 500,$bg_h=500,$format="@0e_320w_320h_0c_0i_1o_90Q_1x.jpg"){

        $pic_list       = array(
            'https://www.baidu.com/img/bd_logo1.png',
            'https://www.baidu.com/img/bd_logo1.png',
            'https://www.baidu.com/img/bd_logo1.png',
            'https://www.baidu.com/img/bd_logo1.png',
            'https://www.baidu.com/img/bd_logo1.png',
            'https://www.baidu.com/img/bd_logo1.png',
            'https://www.baidu.com/img/bd_logo1.png',
            'https://www.baidu.com/img/bd_logo1.png',
            'https://www.baidu.com/img/bd_logo1.png'
        );

        if(empty($pic_list) || !is_array($pic_list)){
            return false;
        }

        $pic_list = array_slice($pic_list, 0, 9); // 只操作前9个图片
        $background = imagecreatetruecolor($bg_w,$bg_h); // 背景图片
        // int imagecolorallocate ( resource $image , int $red , int $green , int $blue ) 为一幅图像分配颜色
        $color   = imagecolorallocate($background, 202, 201, 201); // 为真彩色画布创建白色背景，再设置为透明
        imagefill($background, 0, 0, $color);           //区域填充
        imagecolortransparent($background, $color);     // 将某个颜色定义为透明色

        $pic_count  = count($pic_list);
        $lineArr    = array();  // 需要换行的位置
        $space_x    = 3;
        $space_y    = 3;
        $line_x  = 0;
        switch($pic_count) {
            case 1: // 正中间
                $start_x = intval($bg_w/4);  // 开始位置X
                $start_y = intval($bg_h/4);  // 开始位置Y
                $pic_w   = intval($bg_w/2); // 宽度
                $pic_h   = intval($bg_h/2); // 高度
                break;
            case 2: // 中间位置并排
                $start_x = 2;
                $start_y = intval($bg_h/4) + 3;
                $pic_w   = intval($bg_w/2) - 5;
                $pic_h   = intval($bg_h/2) - 5;
                $space_x = 5;
                break;
            case 3:
                $start_x = 124;   // 开始位置X
                $start_y = 5;    // 开始位置Y
                $pic_w   = intval($bg_w/2) - 5; // 宽度
                $pic_h   = intval($bg_h/2) - 5; // 高度
                $lineArr = array(2);
                $line_x  = 4;
                break;
            case 4:
                $start_x = 4;    // 开始位置X
                $start_y = 5;    // 开始位置Y
                $pic_w   = intval($bg_w/2) - 5; // 宽度
                $pic_h   = intval($bg_h/2) - 5; // 高度
                $lineArr = array(3);
                $line_x  = 4;
                break;
            case 5:
                $start_x = 85.5;   // 开始位置X
                $start_y = 85.5;   // 开始位置Y
                $pic_w   = intval($bg_w/3) - 5; // 宽度
                $pic_h   = intval($bg_h/3) - 5; // 高度
                $lineArr = array(3);
                $line_x  = 5;
                break;
            case 6:
                $start_x = 5;    // 开始位置X
                $start_y = 85.5;   // 开始位置Y
                $pic_w   = intval($bg_w/3) - 5; // 宽度
                $pic_h   = intval($bg_h/3) - 5; // 高度
                $lineArr = array(4);
                $line_x  = 5;
                break;
            case 7:
                $start_x = 166.5;   // 开始位置X
                $start_y = 5;    // 开始位置Y
                $pic_w   = intval($bg_w/3) - 5; // 宽度
                $pic_h   = intval($bg_h/3) - 5; // 高度
                $lineArr = array(2,5);
                $line_x  = 5;
                break;
            case 8:
                $start_x = 80.5;   // 开始位置X
                $start_y = 5;    // 开始位置Y
                $pic_w   = intval($bg_w/3) - 5; // 宽度
                $pic_h   = intval($bg_h/3) - 5; // 高度
                $lineArr = array(3,6);
                $line_x  = 5;
                break;
            case 9:
                $start_x = 5;    // 开始位置X
                $start_y = 5;    // 开始位置Y
                $pic_w   = intval($bg_w/3) - 5; // 宽度
                $pic_h   = intval($bg_h/3) - 5; // 高度
                $lineArr = array(4,7);
                $line_x  = 5;
                break;
        }

        foreach( $pic_list as $k=>$pic_path ) {
            $kk = $k + 1;
            if ( in_array($kk, $lineArr) ) {
                $start_x    = $line_x;
                $start_y    = $start_y + $pic_h + $space_y;
            }

            $pic_info = pathinfo($pic_path);
            switch($pic_info['extension'])
            {
                case "png":
                    $resource = imagecreatefrompng($pic_path.$format);
                    break;
                case "jpg":
                    $resource = imagecreatefromjpeg($pic_path.$format);
                    break;
                case "jpeg":
                    $resource = imagecreatefromjpeg($pic_path.$format);
                    break;
                case "gif":
                    $resource = imagecreatefromgif($pic_path.$format);
                    break;
            }
            // $start_x,$start_y copy图片在背景中的位置
            // 0,0 被copy图片的位置   $pic_w,$pic_h copy后的高度和宽度
            // // 最后两个参数为原始图片宽度和高度，倒数两个参数为copy时的图片宽度和高度
            imagecopyresized($background,$resource, $start_x, $start_y, 0, 0, $pic_w, $pic_h, imagesx($resource), imagesy($resource));
            $start_x = $start_x + $pic_w + $space_x;
        }

        $file_name = date('YmdHis').rand(100,999);
        $imagePath = './'.$file_name.'.jpg';
        // 保存图像为 $imagePath.'$fname'.'.jpg'
        $res = imagejpeg($background, $imagePath);  // imagejpeg($background,'./public/$uid_.$group.jpg');
        if ($res === false) {
            return false;
        }
        // 释放内存
        imagedestroy($background);

        return $imagePath;

    }


    /**
     * @desc 判断一个url是否是图片链接, true 是 , false 否
     * @param string $img_url
     * @return bool
     */
    public function isImgUrl($img_url = ''){

        if(!preg_match('/.*(\.png|\.jpg|\.jpeg|\.gif)$/', $img_url)){
            return true;
        }else{
            $header = get_headers($img_url, 1);
            if(!empty($header['Content-Type'])){
                if(strstr($header['Content-Type'], 'image/')){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

    }


}