<?php

header('content-type:text/html;charset=utf-8');

/**
 * Class ZipTools
 * Zip压缩加压工具类
 */

class ZipTools{




    /**
     * @desc  解压 从zip压缩文件中提取文件
     * @param $filename
     * @param $path
     * @return bool
     */
    public function unzip($filename, $path ){

//        $filename = $_SERVER['DOCUMENT_ROOT'].'/whm.zip';
//        $path = $_SERVER['DOCUMENT_ROOT'].'/';

        $zip = new ZipArchive;
        if ($zip->open($filename) === TRUE) {//中文文件名要使用ANSI编码的文件格式

            $zip->extractTo($path);//提取全部文件
            //$zip->extractTo('/my/destination/dir/', array('pear_item.gif', 'testfromfile.php'));//提取部分文件
            $zip->close();

            return true;

        } else {

            return false;

        }

    }


    /**
     * @desc 对相应目录文件进行压缩
     * @param $path
     * @param $zip
     */
    /** 使用示例
    $zip = new ZipArchive();
    $path = $_SERVER['DOCUMENT_ROOT'];
    $zipName = $_SERVER['DOCUMENT_ROOT'].'/zip'."/rsa0.zip";
    if($zip->open($zipName, ZipArchive::CREATE) === TRUE){
        addFileToZip($path, $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
        $zip->close(); //关闭处理的zip文件
    }
     */
    public function addFileToZip($path,$zip){


        $handler = opendir($path); //打开当前文件夹由$path指定。
        while(($filename=readdir($handler))!==false){

            if($filename != "." && $filename != ".."){

                //文件夹文件名字为'.'和'..'，不要对他们进行操作
                if(is_dir($path."/".$filename)){

                    // 如果读取的某个对象是文件夹，则递归
                    $this->addFileToZip($path."/".$filename, $zip);

                }else{ //将文件加入zip对象

                    $zip->addFile($path."/".$filename);

                }

            }

        }
        @closedir($path);

    }


}