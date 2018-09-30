<?php

header('content-type:text/html;charset=utf-8');

/**
 * 操作xml相关的工具类
 * Class XmlTools
 */

class XmlTools{


    /**
     * @desc xml转数组
     * @param $xml
     * @return mixed
     */
    public function xmlToArray($xml){

        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $xml_string = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xml_string),true);

        return $val;

    }


    /**
     * @desc 数组转xml
     * @param $arr
     * @param int $level
     * @return null|string|string[]
     */
    public function array2xml($arr, $level = 1) {

        $s = $level == 1 ? "<xml>" : '';
        foreach ($arr as $tagname => $value) {
            if (is_numeric($tagname)) {
                $tagname = $value['TagName'];
                unset($value['TagName']);
            }
            if (!is_array($value)) {
                $s .= "<{$tagname}>" . (!is_numeric($value) ? '<![CDATA[' : '') . $value . (!is_numeric($value) ? ']]>' : '') . "</{$tagname}>";
            } else {
                $s .= "<{$tagname}>" . $this->array2xml($value, $level + 1) . "</{$tagname}>";
            }
        }
        $s = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);
        return $level == 1 ? $s . "</xml>" : $s;

    }

    /**
     * @desc xml转数组
     * @param $xml
     * @return array|mixed|string
     */
    public function xml2array($xml) {

        if (empty($xml)) {
            return array();
        }
        $result = array();
        $xml_obj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        if($xml_obj instanceof SimpleXMLElement) {
            $result = json_decode(json_encode($xml_obj), true);
            if (is_array($result)) {
                return $result;
            } else {
                return '';
            }
        } else {
            return $result;
        }

    }




}