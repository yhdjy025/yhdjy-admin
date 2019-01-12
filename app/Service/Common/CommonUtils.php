<?php

namespace App\Service\Common;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

/**
 * 系统工具服务
 * Class CommonUtils
 * @package service
 * @author hky
 * @date 2018/1/1
 */
class CommonUtils{

	/**
	 * 因为Laravel 5.4以后，会自动调用ConvertEmptyStringsToNull，把空字符串转化为null，造成问题
	 * @param $data
	 * @return array
	 */
	public static function convertNullToEmptyStrings($data){
		if(empty($data)){
			return $data;
		}

		foreach ($data as $col=>$val){
			if(is_null($val)){
				$data[$col] = '';
			}
		}

		return $data;
	}

	/**
	 * 获取重复的值，$key为空，是一维数组的值，$key不为空，二维数组，该键对应的值
	 * @param $arr
	 * @param $key
	 * @return multitype:|multitype:array
	 */
	public static function getDuplicate($arr, $key=''){
		$map = array();
		$dp = array();
		if(empty($arr) || !is_array($arr)){
			return $dp;
		}

		foreach ($arr as $data){
			$val = empty($key)? $data:$data[$key];

			if(array_key_exists($val, $map)){
				$dp[] = $val;
			}else{
				$map[$val] = $data;
			}
		}

		return array_unique($dp);
	}

	/**
	 * 转换成array
	 * @param $var
	 * @return multitype:|array|multitype:|string
	 */
	public static function format_array($var){
		if(empty($var)){
			return array();
		}else if(is_array($var)){
			return $var;
		}else if(is_numeric($var)){
			return array($var);
		}else if(is_string($var)){
			if(strpos($var, ',')){
				return explode(',', $var);
			}else{
				return array($var);
			}
		}else{
			return array();
		}
	}

	/**
	 * trim 字符串数组
	 * @param $var
	 * @return multitype:|multitype:string |string|unknown
	 */
	public static function trim($var){
		if(is_array($var)){
			$data = array();
			if(empty($var)){
				return $data;
			}


			foreach ($var as $v){
				if(is_string($v)){
					$data[] = trim($v);
				}
			}
			return $data;
		}else if(is_string($var)){
			return trim($var);
		}else{
			return $var;
		}
	}

	/**
	 * get_filed_array()
	 * 获取二维数组中指定键对应值的数组集合
	 * @param mixed $filed 指定键名或数组
	 * @param array $array 循环的二维数组
	 * @return array 返回二维数组
	 */
	public static function get_filed_array($filed, $array){
		$return =   array();
		if(is_array($array)){
			if(is_array($filed)){
				foreach($filed as $v){
					$return[$v] =   array();
				}
			}

			foreach($array as $val){
				if(is_array($filed)){
					foreach($filed as $v){
						$return[$v][]   =   isset($val[$v]) ? $val[$v] : '';
					}
				}else{
					$return[] = isset($val[$filed]) ? $val[$filed] : '';
				}
			}
		}
		return array_filter($return);
	}

	/**
	 * reverse_array()
	 * 从一个数组中拿出指定的字段作为键名，另外一个字段作为值生成数组
	 * @param mixed $array
	 * @param mixed $val_filter  作为值的字段
	 * @param string $key_filter 作为键名的字段 为空则为key值
	 * @param bool $isMulti 是否生成多维数组
	 * @return void
	 */
	public static function reverse_array($array, $val_filter, $key_filter = '', $isMulti = FALSE){
		$return     =   array();
		if(empty($array)){
			return $return; //增加拦截传递为空数组的情况
		}
		foreach($array as $key=>$val){
			$k  =   $key_filter ? $val[$key_filter] : $key;
			$v  =   $val_filter ? $val[$val_filter] : $val;
			if($isMulti){
				$return[$k][]   =   $v;
			}else{
				$return[$k]     =   $v;
			}
		}
		return array_filter($return);
	}

	/**
	 * 返回包含指定列的数组, 使用json值去重
	 * @param array $arr 多维数组
	 * @param $column_keys
	 */
	public static function array_columns($arr, $column_keys){
		$columns = array();
		$jsonArr = array();
    	if(empty($arr) || empty($column_keys)){
    		return $arr;
    	}

		foreach ($arr as $data){
			$column = self::sub_array($data, $column_keys);
			// column是固定顺序的，所以相同column，json一样
			$json = json_encode($column);
			if(!array_key_exists($json, $jsonArr)){
				$columns[] = $column;
				$jsonArr[$json] = 1;
			}
		}

		return $columns;
	}

	/**
	 * 返回数组的一部分
	 * @param $arr
	 * @param $keys
	 * @return multitype:|multitype:unknown
	 */
	public static function sub_array($arr, $keys=array()){
    	$sub = array();
    	if(empty($arr) || empty($keys)){
    		return $arr;
    	}

    	// 数组按照$keys的顺序
    	foreach($keys as $key){
    	    if(array_key_exists($key, $arr)){
    	        $sub[$key] = $arr[$key];
    		}
    	}
    	return $sub;
	}

    /**
     * 取得数组的值；$isMulti = TRUE是二维数组
     * @param array $arr
     * @param array $keys
     * @param boolean $isMulti
     * @return multitype:|multitype:unknown
     */
    public static function array_values($arr, $keys=array(), $isMulti=TRUE){
    	$values = array();
    	if(empty($arr)){
    		return $values;
    	}

    	foreach ($arr as $key=>$val){
    		if(empty($keys) || in_array($key, $keys)){
    			if($isMulti){
    				foreach($val as $ve){
    					$values[] = $ve;
    				}
    			}else{
    				$values[] = $val;
    			}
    		}
    	}

    	return $values;
    }

    /**
     * 数组合并,简单相加
     * @param array $arr1
     * @param array $arr2
     * @return array
     */
    public static function array_add(&$arr1, $arr2){

    	if(is_null($arr1)){
    		$arr1 = array();
    	}

    	if(empty($arr2)){
    		return $arr1;
    	}

    	foreach ($arr2 as $data){
    		$arr1[] = $data;
    	}

    	return $arr1;
    }

    /**
     * 数组合并,按主键设置
     * @param array $arr1
     * @param array $arr2
     * @return array
     */
    public static function array_push(&$arr1, $arr2){

    	if(is_null($arr1)){
    		$arr1 = array();
    	}

    	if(empty($arr2)){
    		return $arr1;
    	}

    	foreach ($arr2 as $key=>$val){
    		$arr1[$key] = $val;
    	}

    	return $arr1;
    }

    /**
     * 数组按照ukey去重
     * @param array $arr
     * @param array $ukey
     * @return array
     */
    public static function array_unique_special($arr, $ukey){

		$new_arr = array();
		$u_arr = array();
		if(empty($arr)){
			return $new_arr;
		}

		foreach($arr as $key=>$val){
			if(!array_key_exists($val[$ukey], $u_arr)){
				$new_arr[$key] = $val;
				$u_arr[$val[$ukey]] = 1;
			}
		}

    	return $new_arr;
    }

    /**
     * 判断是否以needle开头
     * @param $haystack
     * @param $needle
     * @return boolean
     */
    public static function startsWith($haystack, $needle){
    	return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;

    }

    /**
     * 判断是否以needle结尾
     * @param $haystack
     * @param $needle
     * @return boolean
     */
    public static function endsWith($haystack, $needle){
    	return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
    }

    /**
     * 得到文件锁
     * @param string $lockKey 文件锁key
     * @param int $blockTime 允许阻塞的时间(秒)
     * @return boolean
     */
    public static function lock($lockKey, $blockTime = 0) {
    	if(empty($lockKey)){
    		return false;
    	}
    	$startTime = time();
    	$blockTime = intval($blockTime);

    	$dir = RUNTIME_PATH."fileLock";
    	!is_dir($dir) && mkdir($dir);
    	$file = "{$dir}/{$lockKey}.txt";

    	do{
    		$handle = fopen($file, 'w+');
    		$isLock = flock($handle, LOCK_EX|LOCK_NB);
    		if(!$isLock ){
    			if($startTime + $blockTime < time()){
    				error_log("{$lockKey}:此文件已经被其他进程锁住 /r/n", 3, RUNTIME_PATH.'/log/lock.log');
    				return FALSE;
    			}
    			usleep(50 * 1000); //每50毫秒尝试一次上锁
    		}
    	}while(!$isLock);

    	$lockHandle = array('handle'=>$handle, 'file'=>$file);
    	$_SESSION['fileLockList'][$lockKey] = $lockHandle;
    	return $lockKey;
    }

    /**
     * 释放文件锁
     * @param array $lockHandle 包含文件路径和操作句柄的数组
     * @return boolean
     */
    public static function unlock($lockKey) {
    	if(isset($_SESSION['fileLockList'][$lockKey])){
    		$lockHandle = $_SESSION['fileLockList'][$lockKey];
    		@fclose($lockHandle['handle']);
    		@unlink($lockHandle['file']);
    		unset($_SESSION['fileLockList'][$lockKey]);
    	}
    }

    /**
     * 擦拭数字部分
     * @param $path
     * @param string $delimiter
     * @return string
     */
    public static function wipeNumber($path, $delimiter='/'){
    	$segments = explode($delimiter, $path);
    	$neatPath = '';

    	if(empty($segments)){
    		return '/';
    	}

    	foreach ($segments as $segment){
    		if(empty($segment) || is_numeric($segment)){
    			continue;
    		}
    		if(empty($neatPath)){
    			$neatPath = $segment;
    		}else{
    			$neatPath = $neatPath.'/'.$segment;
    		}
    	}

    	if(empty($neatPath)){
    		$neatPath = '/';
    	}
    	return $neatPath;
    }

    /**
     * 擦拭数字部分
     * @param $path
     * @param string $delimiter
     * @return string
     */
    public static function wipeBracket($path, $delimiter='/'){

    	// 去除开头的间隔符
    	while(!empty($delimiter) && !empty($path) && self::startsWith($path,$delimiter)){
    		$path = substr($path, strlen($delimiter));
    	}

    	$segments = explode($delimiter, $path);
    	$neatPath = '';

    	if(empty($segments)){
    		return '/';
    	}

    	foreach ($segments as $segment){
    		if(empty($segment) || (self::startsWith($segment, '{') && self::endsWith($segment, '}'))){
            	break;
            }

    		if(empty($neatPath)){
    			$neatPath = $segment;
    		}else{
    			$neatPath = $neatPath.'/'.$segment;
    		}
    	}

    	if(empty($neatPath)){
    		$neatPath = '/';
    	}
    	return $neatPath;
    }

    /**
     * 添加固定字符串
     * @param array $dataList
     * @param string $str
     * @param string $bKey
     * @param string $bHead
     * @return array|multitype:string unknown
     */
    public static function append($dataList, $str, $appendKey = TRUE, $appendHead = TRUE){
    	$nList = array();

    	if(empty($dataList)){
    		return $dataList;
    	}

    	foreach ($dataList as $key=>$value){
    		if(!$appendKey){
    			if(empty($str)){
    				$nList[$key] = $value;
    			}else if(empty($value)){
    				$nList[$key] = $str;
    			}else if($appendHead){
    			    $nList[$key] = $str.$value;
    			}else{
    			    $nList[$key] = $value.$str;
    			}
    		}else{
    			if(empty($str)){
    				$nList[$key] = $value;
    			}else if($appendHead){
    				$nList[$str.$key] = $value;
    			}else{
    				$nList[$key.$str] = $value;
    			}
    		}

    	}

    	return $nList;
    }

    /**
     * 对于每个数据进行处理
     * @param $dataList
     * @param $callable
     * @param $set
     */
    public static function each(&$dataList, $callable, $setdata=true){
    	if(empty($dataList) || empty($callable)){
    		return $dataList;
    	}
    	if( !is_array($dataList) &&
    	    !($dataList instanceof Collection)
    			&& !($dataList instanceof LengthAwarePaginator)
    			&& !($dataList instanceof Paginator)){
    		return $dataList;
    	}

    	$items = is_array($dataList)? $dataList:$dataList->all();
    	$datas = array();
    	foreach($items as $key=>$item){
    		$data = call_user_func($callable, $item);
    		$datas[$key] = $data;
    	}

    	if($setdata){
    		if(is_array($dataList)){
    			$dataList=$datas;
    		}else if($dataList instanceof LengthAwarePaginator
    				|| $dataList instanceof Paginator){
    			$dataList->setCollection(collect($datas));
    		}else {
    			// 其他的collection
    			$dataList = collect($datas);
    		}
    	}
    }

    /**
     * 获得两者时间差
     * @param $begin_time
     * @param $end_time
     */
    public static function get_timediff($begin_time,$end_time) {
    	if($begin_time < $end_time){
    		$starttime = $begin_time;
    		$endtime = $end_time;
    	}else{
    		$starttime = $end_time;
    		$endtime = $begin_time;
    	}
    	return $endtime-$starttime;
    }

    /**
     * 变为中文的时间写法
     * @param $time
     * @return string
     */
    public static function toChTime($time) {
    	$days = intval($time/86400);
    	//计算小时数
    	$remain = $time%86400;
    	$hours = intval($remain/3600);
    	//计算分钟数
    	$remain = $remain%3600;
    	$mins = intval($remain/60);
    	//计算秒数
    	$secs = $remain%60;
    	$chTime = "";
    	if($days > 0){
    		$chTime = $chTime."{$days}天";
    	}
    	if($hours > 0){
    		$chTime = $chTime."{$hours}时";
    	}
    	if($mins > 0){
    		$chTime = $chTime."{$mins}分";
    	}
    	if($secs > 0){
    		$chTime = $chTime."{$secs}秒";
    	}

    	if(empty($chTime)){
    		$chTime = "0秒";
    	}

    	return $chTime;
    }

    /**
     * JOIN array value
     * @param $glue
     * @param $pkL
     * @return multitype:|multitype:string unknown
     */
    public static function implodeValue($glue, $pkList){
    	$keys = array();
    	if(empty($pkList) || empty($glue)){
    		return $keys;
    	}

    	foreach($pkList as $pk){
    		if(empty($pk)){
    			$keys[]=$glue;
    		}
    		if(is_array($pk)){
    		    $keys[] = implode($glue, array_values($pk));
    		}else{
    			$keys[] = $pk;
    		}
    	}

    	return $keys;
    }

    /**
     * 判断两个数组是否相似
     * @param $mArr1
     * @param $mArr2
     * @param $keys
     * @return boolean
     */
    public static function array_similar($mArr1, $mArr2, $keys){
    	// 一个不为空
    	if(empty($mArr1) || empty($mArr2) || empty($keys)){
    		return false;
    	}

    	if(!is_array($mArr1) || !is_array($mArr2) || !is_array($keys)){
    		return false;
    	}

    	$is_similar = true;

    	foreach($keys as $key){
    		if($mArr1[$key] != $mArr2[$key]){
    			$is_similar = false;
    		}
    	}

    	return $is_similar;
    }

    /**
     * 异或加密
     * @param $str
	 * @param $key
     * @return string|boolean
     */
    public static function xorencrypt($str, $key){
    	$slen = strlen( $str );
    	$klen = strlen( $key );
    	$cipher = '';
    	for ($i=0;$i<$slen;$i=$i+$klen) {
    		$cipher .= substr( $str, $i, $klen )^$key;
    	}
    	return $cipher;
    }

    /**
     * 异或解密
     * @param $str
     * @param $key
     * @return string|boolean
     */
    public static function xordecrypt( $str, $key ){
    	$slen = strlen( $str );
    	$klen = strlen( $key );
    	$plain = '';
    	for ($i=0;$i<$slen;$i=$i+$klen) {
    		$plain .= $key^substr( $str, $i, $klen );
    	}
    	return $plain;
    }

    /**
     * 对象转数组
     * @param $obj
     * @return string
     */
    public static function object_to_array($obj){
	    return json_decode(json_encode($obj),true);;
	}

	/**
	 * 集合运算，求出交集，差集
	 * @param $set_n 新集合
	 * @param $set_o 旧集合
	 * @param $key 集合的键
	 */
	public static function set_operate($set_n, $set_o, $key){
		$arr_n = self::reverse_array($set_n, '', $key);
		$arr_o = self::reverse_array($set_o, '', $key);
		// cross:交集， diff：新增的、在n集不在o集，obs：废弃的、在o集不在n集
		// cross:返回的是交叉的新数据，diff:增加的新数据，obs:老数据废弃部分
		$SOR = ['cross'=>array_intersect_key($arr_n, $arr_o),'diff'=>array_diff_key($arr_n, $arr_o),'obs'=>array_diff_key($arr_o, $arr_n)];
		if(!empty($SOR['cross'])){
			foreach ($SOR['cross'] as &$cross){
				$old = $arr_o[$cross[$key]];
				if(array_key_exists('id', $old)){
					$cross['id'] = $old['id'];
				}
			}
		}

		return $SOR;
	}
}
