<?php

/**
 *  app版本校验工具类
 *  Class VersionTools
 *  desc   例如记录app版本，或某些版本数据，如果使用1.0.0这种版本格式记录入库，在需要筛选查询时会比较麻烦。
 *  而把版本字符串转为数字保存，可以方便版本间的比较和筛选。
 *  例如：要查询3.0.1 与 10.0.1之间的版本，因为3.0.1比10.0.1大(字符串比较)，因此需要处理才可以查询。
 *  而把 3.0.1 和 10.0.1 先转为数字 30001 和 100001来比较查询，则很方便。
 *  版本处理类，提供版本与数字互相转换，方便入库后进行比较筛选
 *  public  versionToInteger  将版本转为数字
 *  public  integerToVersion  将数字转为版本
 *  public  check             检查版本格式是否正确
 *  public  compare           比较两个版本的值
 */

class VersionTools{

    /**
     * @desc 将版本转为数字
     * @param  String $version 版本
     * @return Int
     */
    public function versionToInteger($version){

        if($this->check($version)){
            list($major, $minor, $sub) = explode('.', $version);
            $integer_version = $major*10000 + $minor*100 + $sub;
            return intval($integer_version);
        }else{
            throw new ErrorException('version Validate Error');
        }

    }

    /**
     * @desc 将数字转为版本
     * @param  Int     $version_code 版本的数字表示
     * @return String
     */
    public function integerToVersion($version_code){

        if(is_numeric($version_code) && $version_code>=10000){
            $version = array();
            $version[0] = (int)($version_code/10000);
            $version[1] = (int)($version_code%10000/100);
            $version[2] = $version_code%100;
            return implode('.', $version);
        }else{
            throw new ErrorException('version code Validate Error');
        }

    }

    /**
     * @desc 检查版本格式是否正确
     * @param  String  $version 版本
     * @return Boolean
     */
    public function check($version){

        $ret = preg_match('/^[0-9]{1,3}\.[0-9]{1,2}\.[0-9]{1,2}$/', $version);
        return $ret? true : false;

    }

    /**
     * @desc 比较两个版本的值
     * @param  String  $version1  版本1
     * @param  String  $version2  版本2
     * @return Int                -1:1<2, 0:相等, 1:1>2
     */
    public function compare($version1, $version2){

        if($this->check($version1) && $this->check($version2)){

            $version1_code = $this->versionToInteger($version1);
            $version2_code = $this->versionToInteger($version2);

            if($version1_code>$version2_code){
                return 1;
            }elseif($version1_code<$version2_code){
                return -1;
            }else{
                return 0;
            }

        }else{
            throw new ErrorException('version1 or version2 Validate Error');
        }

    }

}


/**
 * 使用示例

$obj = new VersionTools();

$version = '2.7.1';
// 版本转数字
$version_code = $obj->versionToInteger($version);
var_dump($version_code);  // 20701

// 数字转版本
$version = $obj->integerToVersion($version_code);
var_dump($version); // 2.7.1

// 检查版本
$version = '1.1.a';
var_dump($obj->check($version)); // false

// 比较两个版本
$version1 = '2.9.9';
$version2 = '2.09.09';

$result = $obj->compare($version1, $version2);
var_dump($result); // 0


 */