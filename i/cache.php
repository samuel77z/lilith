<?php

function cacheExtract($str){
	global $_UserCacheVars;
	$ar=unserialize($str);
	if(is_array($ar)){
		log2web($ar);
		foreach($ar as $k => $v){
			$GLOBALS[$k]=$v;
			$_UserCacheVars[]=$k;
		}
	}
}

function getUserCache($key){
  global $_UserCacheKey,$_UserCacheVars;
	$_UserCacheKey=$key;
	$fn='c/'.$key;
	if(file_exists($fn)){
		$str=file_get_contents($fn);
		cacheExtract($str);
	}
}

function lockCache($key) {
	global $_UserCacheFile;
	$fn='c/'.$key;
	$_UserCacheFile=fopen($fn,'c+');
	flock($_UserCacheFile,LOCK_EX);
	$str=fgets($_UserCacheFile);
	cacheExtract($str);
	rewind($_UserCacheFile);

  function storeUserCache(){
	 global $_UserCacheVars,$_UserCacheFile;
		$au=array_unique($_UserCacheVars);
		$ar=array();
		foreach($au as $k){
		if(array_key_exists($k,$GLOBALS)){
		 $ar[$k]=$GLOBALS[$k];
		 }
		}
		// print_r($ar);
		$str=serialize($ar);
		fwrite($_UserCacheFile,$str);
		flock($_UserCacheFile,LOCK_UN);
		fclose($_UserCacheFile);
	}
	
	register_shutdown_function('storeUserCache');

}

function keepInCache(... $vars){
	global $_UserCacheVars;
	foreach($vars as $var){
		$_UserCacheVars[]=$var;
	}
}

