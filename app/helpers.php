<?php
/**
 * 通用添加返回参数
 */
	if(!function_exists('responseJson')){
		function responseJson($json,$message='',$code=20000,$status=200){
			$returnJson = ['data'=>$json,'message'=>$message,'code'=>$code];
			return response()->json($returnJson,$status);
		}
	}
/**
 * 集合或者json 取字段转为数组
 */
	if(!function_exists('collectToFieldArray')){
		function collectToFieldArray($collect,String $field){
			if(empty($field))return $collect;
			$array = [];
			foreach($collect as $key=>$value){
				array_push($array, $value[$field]);
			}
			return $array;
		}
	}
/**
 * 链接参数处理为key
 */
	if(!function_exists('urlHaddleKey')){
		function urlHaddleKey(String $url){
			if(empty($url))return $url;
			$urlArr = str_split($url);
			if(current($urlArr) === '/')unset($urlArr[0]);
			if(end($urlArr) === '/')unset($urlArr[0]);
			return str_replace('/','.',implode('',$urlArr));
		}
	}
/**
 * 分析枚举类型配置值 格式 a:名称1,b:名称2
 */
	if(!function_exists('parse_config_attr')){
		function parse_config_attr($string) {
		    $array = preg_split('/[,;\r\n]+/', trim($string, ",;\r\n"));
		    if(strpos($string,':')){
		        $value  =   array();
		        foreach ($array as $val) {
		            list($k, $v) = explode(':', $val);
		            $value[$k]   = $v;
		        }
		    }else{
		        $value  =   $array;
		    }
		    return $value;
		}
	}

?>