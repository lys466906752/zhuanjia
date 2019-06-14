<?php

	//常规的帮助函数

	//组合对应的数据信息，返回给api
	if(! function_exists('getSelectItem'))
	{	

		function getSelectItem($json,$items)
		{
			$returnArray=[];

			$array=json_decode($json,true);

			foreach($items as $k=>$v)
			{
				$returnArray[$k]=selectItemRes($array,$k,$v);
			}

			return $returnArray;
		}

	}	

	//从数组中套数据出啦
	if(! function_exists('selectItemRes'))
	{
		function selectItemRes($array,$k,$v)
		{
			if(isset($array[$k]['value']))
			{
				if($array[$k]['model']==3)
				{
					return $v>0 && strlen($array[$k]['value'])>$v*4?mb_substr(changeUrl(stripslashes($array[$k]['value'])),0,$v):changeUrl(stripslashes($array[$k]['value']));
				}
				return $v>0 && strlen($array[$k]['value'])>$v*4?mb_substr($array[$k]['value'],0,$v):$array[$k]['value'];
			}

			return '';
		}
	}

	//更改kind里面的图片地址
	if(! function_exists('changeUrl'))
	{
		function changeUrl($contents)
		{

			return str_replace('src="/assets/kindeditor/attached','src="'.base_url().'assets/kindeditor/attached',$contents);

		}
	}
	
	//jsonp输出处理
	if(! function_exists('jsonp_show'))
	{
		function jsonp_show($_array)
		{
			$callback=!empty($_GET['jsonpcallback'])?trim($_GET['jsonpcallback']):'';
			$_array=json_encode($_array);
			echo "{$callback}({$_array});";
			exit();				
		}	
	}
	
	//检测两者时间
	if(! function_exists('checkDotime'))
	{
		function checkDotime($time_a,$int)
		{
			if(time()-$time_a>$int)
			{
				return true;	
			}	
			
			return false;
		}	
	}
	
	//查询配置文件
	if(! function_exists('select_config'))
	{
		function select_config($file,$key='')
		{
			$file=FCPATH.'assets/config/'.$file.'.config.php';
							
			if(is_file($file))
			{
				require ($file);
	
				if($key=='')
				{
					return $_Conf;
				}
				
				if(isset($_Conf[$key]))
				{
					return $_Conf[$key];
				}
				
				return false;
			}
			
			return false;
		}	
	}
	
	//获取配置文件里面的某个参数的随机值
	if(! function_exists('select_config_rands'))
	{
		function select_config_rands($file,$key)
		{
			$res=select_config($file,$key);
			
			if(!$res)
			{
				return false;	
			}
			
			$array=explode('@',$res);
			$k=mt_rand(0,count($array)-1);
			return $array[$k];	
		}	
	}
	
	//获取IP地址信息
	if (! function_exists('get_ip'))
	{
		function get_ip()
		{
			
			if (getenv('HTTP_CLIENT_IP')) {
				$ip = getenv('HTTP_CLIENT_IP');
			}
			elseif (getenv('HTTP_X_FORWARDED_FOR')) {
				$ip = getenv('HTTP_X_FORWARDED_FOR');
			}
			elseif (getenv('HTTP_X_FORWARDED')) {
				$ip = getenv('HTTP_X_FORWARDED');
			}
			elseif (getenv('HTTP_FORWARDED_FOR')) {
				$ip = getenv('HTTP_FORWARDED_FOR');
			
			}
			elseif (getenv('HTTP_FORWARDED')) {
				$ip = getenv('HTTP_FORWARDED');
			}
			else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}
			
			return $ip;	
		}
	}
	
	//获取发布者的浏览器信息
	if(! function_exists('user_agent'))
	{
		function user_agent()
		{
			return isset($_SERVER["HTTP_USER_AGENT"]) && $_SERVER["HTTP_USER_AGENT"]!="" ?$_SERVER["HTTP_USER_AGENT"]:"未知";
		}
	}	
	
	//获取Token信息
	if(! function_exists('get_token'))
	{
		function get_token()
		{
			return sha1(microtime().mt_rand(1000,9999)).sha1(uniqid());	
		}	
	}
	