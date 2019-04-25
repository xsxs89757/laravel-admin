<?php
/**
 * 通用添加返回参数
 */
	if(!function_exists('responseJson')){
		function responseJson($json,$message='',$code=20000,$status=200){
			$returnJson = ['data'=>$json,$message=$message,'code'=>$code];
			return response()->json($returnJson,$status);
		}
	}


?>