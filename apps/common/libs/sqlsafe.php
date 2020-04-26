<?php
class sqlsafe {
	private $getfilter = "'|(and|or)\\b.+?(>|<|=|in|like)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
	private $postfilter = "\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
	private $cookiefilter = "\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
	/**
	 * 构造函数
	 */
	public function __construct() {
		
		foreach($_GET as $key=>$value){
			$this->stopattack($key,$value,$this->getfilter);
			
			$value = trim($value); //去除空格
			$value = str_replace("\t","",$value); //去除制表符号
			$value = str_replace("\n", "", $value);//去除空格和换行
			$value = str_replace("'","",$value); //去除单引号
			$value = strip_tags($value,""); //去除HTML代码
			$value = str_replace("\r\n","",$value); //去除回车换行符号
			$value = str_replace("\r","",$value); //去除回车
			$value = trim($value); //去除空格			
			
			$_GET[$key] = $value;
		}
		
		foreach($_POST as $key=>$value){$this->stopattack($key,$value,$this->postfilter);}
		foreach($_COOKIE as $key=>$value){$this->stopattack($key,$value,$this->cookiefilter);}
		
		$_GET = $this->htmlspecialchars($_GET);
		$_POST = $this->htmlspecialchars($_POST);
	}
	/**
	 * 参数检查并写日志
	 */
	public function stopattack($StrFiltKey, $StrFiltValue, $ArrFiltReq){
		if(is_array($StrFiltValue))$StrFiltValue = implode($StrFiltValue);
		if (preg_match("/".$ArrFiltReq."/is",$StrFiltValue) == 1){
			//$this->writeslog($_SERVER["REMOTE_ADDR"]."    ".strftime("%Y-%m-%d %H:%M:%S")."    ".$_SERVER["PHP_SELF"]."    ".$_SERVER["REQUEST_METHOD"]."    ".$StrFiltKey."    ".$StrFiltValue);
			exit('您提交的参数非法,系统已记录您的本次操作！');
		}
	}
	
	public function htmlspecialchars($array)
	{
		foreach($array as $key => $value)
		{
			if (is_array($value)) {
				$array[$key] = $this->htmlspecialchars($value);
			} else {
				$array[$key] = htmlspecialchars($value, ENT_QUOTES);
			}
		}
		
		return $array;
	}
	
	
	
	/**
	 * SQL注入日志
	 */
	public function writeslog($log){
		$log_path = CACHE_PATH.'logs'.DIRECTORY_SEPARATOR.'sql_log.txt';
		$ts = fopen($log_path,"a+");
		fputs($ts,$log."\r\n");
		fclose($ts);
	}
}

$sqlsafe = new sqlsafe();