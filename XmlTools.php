<?php

header('content-type:text/html;charset=utf-8');

class XmlTools{

    /*
	* 将xml转换成数组
	* $xml_str是xml字符串
	*/
	function  xmlToArray($xml_str)
	{
		//禁止XML实体扩展攻击	
		libxml_disable_entity_loader(true);
		//拒绝包含HTML结构(避免出现html解析攻击);
		if (preg_match('/(\<\!DOCTYPE|\<\!ENTITY)/i', $string)) {
			return false;
		}
		
		//返回的数组对象
		$result=array();
		
		//LIBXML_NOCDATA - 将 CDATA 设置为文本节点,微信支付的xml,可以自己定义
		$xmlobj=simplexml_load_string($xml_str, 'SimpleXMLElement',LIBXML_NOCDATA);
		
		//是否是SimpleXMLElement对象
		if($xmlobj instanceof  SimpleXMLElement)
		{
			$result=json_decode(json_encode($xmlobj),true);

		}

		return $result;

	}


}