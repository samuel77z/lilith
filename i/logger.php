<?php
$_define_log2web=true;
if(file_exists('d/olog')){

	if(file_exists('d/log'))
	{
		if(filectime('d/log')>filectime('d/olog')+3600)
		{
			unlink('d/log');
			unlink('d/olog');
			$_define_log2web=false;
		}
	}
}
if($_define_log2web){
	function log2web($str){
		$lf=fopen('d/log','a+');

		if(is_string($str)) {
			fwrite($lf,'<li>');
			$str=htmlspecialchars($str);
			fwrite($lf,$str);
			fwrite($lf,'</li>');
		}else {
			fwrite($lf, '<li><pre>');
			$str=print_r($str,true);
			$str=htmlspecialchars($str);
			fwrite($lf,$str);
			fwrite($lf,'</pre></li>');

		}
		fclose($lf);
	}
} else {
	function log2web($str){
	}
}
