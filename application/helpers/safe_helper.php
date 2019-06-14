<?php

	//安全防注入组件
	define('csrfKeys','(**(&Zwe7d98wehdsjkhbczdbcbz5642615RDDVGSHDVHj++???asjdhgasg');//定义一个对应的csrf的密钥
	
	//js跨域安全回掉
	if(! function_exists('errorDomainSay'))
	{
		function errorDomainSay($code,$url,$func,$str)
		{
			header("location:".$url."/junyiSay.php?func=".$func."&code=".$code."&str=".$str);
			exit();	
		}	
	}
	
	//过滤对应的参数
	if(! function_exists('get_check'))
	{
		function get_check($sql_str)
		{
			if(preg_match('~select|insert|update|delete|script|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file
		|outfile~',$sql_str))
			{
				return false;
			}
			return $sql_str;
		
		}
	}
	
	//获取csrf的值
	if(! function_exists('get_csrfToken'))
	{
		function get_csrfToken($obj)
		{
			$time=time();
			$id=uniqid();
			$res=$obj->encode($time."_".md5($time)."_".$id."_".md5($id)."_".md5($time.$id).'_'.csrfKeys);

			if(isset($_SESSION['csrfCI']) && count($_SESSION['csrfCI'])>100)
			{
				array_shift($_SESSION['csrfCI']);
			}

			$_SESSION['csrfCI'][]=$res;

			return $res;
		}	
	}
	
	//校验csrf的值
	if(! function_exists('check_csrfToken'))
	{
		function check_csrfToken($obj,$value)
		{
			
			if(isset($_SESSION['csrfCI']) && in_array($value,$_SESSION['csrfCI']))
			{

				$decodes=$obj->decode($value);	
				$arr=explode('_',$decodes);

				if(count($arr==6))
				{
					$time=$arr[0];
					$id=$arr[2];
					if(trim(md5($time))==trim($arr[1]) && trim(md5($id))==trim($arr[3]) && trim(md5($time.$id))==trim($arr[4]) && time()-$time<3600 && trim($arr[5])==trim(csrfKeys))
					{
						return true;
					}
					
					return false;
				}
				
				return false;	

			}
			
			return false;

						
		}
	}
	
	//过滤json无法识别的数据信息
	if(! function_exists('clearBom'))
	{
		function clearBom($str)
		{
			$str=str_replace('　','',$str);
			$str=str_replace('
			','',$str);	
			
			return $str;
		}	
	}
