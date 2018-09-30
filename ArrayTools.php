<?php


header('content-type:text/html;charset=utf-8');

/**
 * 数组工具类
 * Class ArrayTools
 */

class ArrayTools{


    /**
     * @desc： 二维数组去掉重复值
     * @param array $array2D
     * @return multitype:
     */
    public function arrayUniqueFb($array2D){

        $temp = array();
        foreach ($array2D as $v){
            $v=join(',',$v);//降维,也可以用implode,将一维数组转换为用逗号连接的字符串
            $temp[]=$v;
        }
        $temp=array_unique($temp);//去掉重复的字符串,也就是重复的一维数组
        foreach ($temp as $k => $v){
            $temp[$k]=explode(',',$v);//再将拆开的数组重新组装
        }
        return $temp;

    }


    /**
     * @desc 去除二维数组中的重复项
     * @param $array
     * @return array
     */
    public function removeArrayDuplicate($array){
        $result=array();
        for($i=0;$i<count($array);$i++){
            $source=$array[$i];
            if(array_search($source,$array)==$i && $source<>"" ){
                $result[]=$source;
            }
        }
        return $result;
    }
    /*
    $arr=array("1"=>array("a","b "),"2"=>array("a","c"),"3"=>array("a","b"));
    $arr=remove_duplicate($arr);
    print_r($arr);
    */


    /**
     * @desc 二维数组根据字段进行排序
     * @params array $array 需要排序的二维数组
     * @params string $field 排序的字段
     * @params string $sort 排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
     */
    public function arraySequence($array, $field, $sort = 'SORT_DESC')
    {
        $arrSort = array();
        foreach ($array as $uniqid => $row) {
            foreach ($row as $key => $value) {
                $arrSort[$key][$uniqid] = $value;
            }
        }
        array_multisort($arrSort[$field], constant($sort), $array);
        return $array;
    }



    /**
     * @desc 数组转xml
     * @param $arr
     * @param int $level
     * @return mixed|string
     */
    public function arrayToXml($arr, $level = 1) {

        $s = $level == 1 ? "<xml>" : '';
        foreach ($arr as $tagname => $value) {
            //元素为数组或者不为数组的处理
            if (!is_array($value)) {
                $s .= "<{$tagname}>" . (!is_numeric($value) ? '<![CDATA[' : '') . $value . (!is_numeric($value) ? ']]>' : '') . "</{$tagname}>";
            } else {
                $s .= "<{$tagname}>" . array2xml($value, $level + 1) . "</{$tagname}>";
            }
        }
        //过滤不合法的字符串
        $s = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);
        return $level == 1 ? $s . "</xml>" : $s;

    }


    /**
     * @desc 过滤数组元素前后空格 (支持多维数组)
     * @param $array 要过滤的数组
     * @return array|string
     */
    public function trimArrayElement($array){

        if(!is_array($array))
            return trim($array);
        return array_map('trim_array_element',$array);

    }


    /**
     * @desc 将二维数组以元素的某个值作为键 并归类数组
     * array( array('name'=>'aa','type'=>'pay'), array('name'=>'cc','type'=>'pay') )
     * array('pay'=>array( array('name'=>'aa','type'=>'pay') , array('name'=>'cc','type'=>'pay') ))
     * @param $arr 数组
     * @param $key 分组值的key
     * @return array
     */
    public function groupSameKey($arr,$key){

        $new_arr = array();
        foreach($arr as $k=>$v ){
            $new_arr[$v[$key]][] = $v;
        }
        return $new_arr;

    }



    /**
     * @desc 多个数组的笛卡尔积
     * @param $data
     * @return array
     */
    public function combineDiKa() {

        $data = func_get_args();
        $data = current($data);
        $cnt = count($data);
        $result = array();
        $arr1 = array_shift($data);
        foreach($arr1 as $key=>$item)
        {
            $result[] = array($item);
        }

        foreach($data as $key=>$item)
        {
            $result = $this->combineArray($result,$item);
        }
        return $result;

    }


    /**
     * @desc 两个数组的笛卡尔积
     * @param $arr1
     * @param $arr2
     * @return array
     */
    function combineArray($arr1,$arr2) {

        $result = array();
        foreach ($arr1 as $item1)
        {
            foreach ($arr2 as $item2)
            {
                $temp = $item1;
                $temp[] = $item2;
                $result[] = $temp;
            }
        }
        return $result;

    }



    /**
     * @desc 多维数组转化为一维数组
     * @param $array 多维数组
     * @return array 一维数组
     */
    function arrayMulti2single($array)
    {
        static $result_array = array();
        foreach ($array as $value) {
            if (is_array($value)) {
                $this->arrayMulti2single($value);
            } else
                $result_array [] = $value;
        }
        return $result_array;
    }



    /**
     * @二维数组排序
     * @param $arr
     * @param $keys
     * @param string $type
     * @return array
     */
    public function arrayMulti2sort($arr, $keys, $type = 'desc')
    {

        $key_value = $new_array = array();
        foreach ($arr as $k => $v) {
            $key_value[$k] = $v[$keys];
        }
        if ($type == 'asc') {
            asort($key_value);
        } else {
            arsort($key_value);
        }
        reset($key_value);
        foreach ($key_value as $k => $v) {
            $new_array[$k] = $arr[$k];
        }
        return $new_array;

    }


    /**
     * @param $arr
     * @param $key_name
     * @return array
     * 将数据库中查出的列表以指定的 id 作为数组的键名
     */
    public function convertArrKey($arr, $key_name)
    {
        $arr2 = array();
        foreach($arr as $key => $val){
            $arr2[$val[$key_name]] = $val;
        }
        return $arr2;
    }



    /**
     * @desc 将数据库中查出的列表以指定的 id 作为数组的键名
     * @param $array
     * @param $key_name
     * @param $key_name_two
     * @return array
     */
    public function _convertArrKey($array, $key_name, $key_name_two)
    {
        $re = array();
        $arr = array();
        foreach($array as $a) {
            $tmp_v = $a;
            unset($tmp_v[$key_name]);
            if(isset($re[$a[$key_name]])) {
                $re[$a[$key_name]][] = $tmp_v;
            }else{
                $re[$a[$key_name]] = array($tmp_v);
            }
        }
        foreach($re as $key=>$val){
            $arr[]=array(
                $key_name => $key,
                $key_name_two => $val
//                'goods' => $val
            );
        }
        return $arr;
    }




    /**
     * @desc 对象转数组，PHP stdClass Object转array
     * @param $array
     * @return array
     */
    public function object_array($array) {

        if(is_object($array)) {
            $array = (array)$array;
        } if(is_array($array)) {
            foreach($array as $key => $value) {
                $array[$key] = $this->object_array($value);
            }
        }
        return $array;

    }


}