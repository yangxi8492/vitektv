<?php
class xmlParser {
	function xml2array ($strXml) {
		$pos = strpos($strXml, 'xml');
		if ($pos) {
			$xmlCode=simplexml_load_string($strXml,'SimpleXMLElement', LIBXML_NOCDATA);
			$arrayCode=$this->get_object_vars_final($xmlCode);
			return $arrayCode ;
		} else {
			return '';
		}
	}
	
	function get_object_vars_final($obj){
		if(is_object($obj)){
			$obj=get_object_vars($obj);
		}
		
		if(is_array($obj)){
			foreach ($obj as $key=>$value){
				$obj[$key]=$this->get_object_vars_final($value);
			}
		}
		return $obj;
	}
}
?>