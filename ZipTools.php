<?php

header('content-type:text/html;charset=utf-8');

/**
 * Class ZipTools
 * Zip压缩解压工具类
 */

class ZipTools{


    //    使用示例
    //    $zip = new ZipArchive();
    //    $path = $_SERVER['DOCUMENT_ROOT'];
    //    $zipName = $_SERVER['DOCUMENT_ROOT']."/20180826.zip";
    //
    //    if($zip->open($zipName, ZipArchive::CREATE) === TRUE){
    //    addFileToZip($path, $zip); // 调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
    //    $zip->close(); // 关闭处理的zip文件
    //    }
    /**
     * @desc 对相应目录文件进行压缩
     * @param $path
     * @param $zip
     **/
    public function addFileToZip($path,$zip){

        // 转化 \ 为 / ，适应 windows
        $file_tools = new FileTools();
        $path = $file_tools->dirPath($path);

        // 判断是不是目录，是的话递归进入
        if(is_dir($path)) {
            $handler = opendir($path); //打开当前文件夹由$path指定。
            while (($filename = readdir($handler)) !== false) {
                if ($filename != "." && $filename != "..") {
                    //文件夹文件名字为'.'和'..'，不要对他们进行操作
                    if (is_dir($path . "/" . $filename)) {
                        // 如果读取的某个对象是文件夹，则递归
                        $this->addFileToZip($path . "/" . $filename, $zip);
                    } else { //将文件加入zip对象
                        $zip->addFile($path . "/" . $filename);
                    }
                }
            }
        }else{
            $zip->addFile($path);
        }
        @closedir($path);

        echo 'zip压缩成功';

    }



    // php 从zip压缩文件中提取文件
    // 使用示例：
    // $filename = $_SERVER['DOCUMENT_ROOT'].'/unzip.zip';
    // $path = $_SERVER['DOCUMENT_ROOT'].'/unzip';
    // unZip($filename,$path );
    /**
     * @desc 对相应目录文件进行压缩
     * @param $path
     * @param $zip
     **/
    public function unZip($filename = '', $path = ''){

        //将文件名和路径转成windows系统默认的gb2312编码，否则将会读取不到
        $filename = iconv("utf-8","gb2312",$filename);
        $path = iconv("utf-8","gb2312",$path);

        if (!file_exists($filename)) {
            return false;
        }

        $zip = new ZipArchive;
        if ($zip->open($filename) === TRUE) {//中文文件名要使用ANSI编码的文件格式

            $zip->extractTo($path);//提取全部文件
            $zip->close();
            echo 'zip解压成功';

        } else {

            echo 'zip解压失败或没有这个zip文件';

        }

    }


}