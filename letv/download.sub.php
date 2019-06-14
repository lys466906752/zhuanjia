<?php
	
	header('Content-type:text/html;charset=utf-8');
	
	if(isset($_POST['vu']))
	{
			//$api="video.download";  
			$video=trim($_POST['vu']);
			$vtype="mp4";  
			//$timestamp=time();  
			$ts=getTimestamp('13');  
			$user = "5548b1a62a";  
			//$vu='41f4b8870a';
			
			$secret_key = "d897030a7f43487b997bab54211f193c";     // 用户仪表盘上的私钥  
			
			
			
			$str1="ts".$ts."user".$user."video".$video."vtype".$vtype.$secret_key;  
			$md5sign=md5($str1);  
			$video=$video;  
			$apiParams=array();  
			$apiParams["ts"]=$ts;  
			$apiParams["user"]=$user;  
			$apiParams["video"]=$video;  
			$apiParams["vtype"]=$vtype;  
			$apiParams["sign"]=$md5sign;  
			//$apiParams["vu"]=$vu; 

			$posts='';
			$url='http://api.letvcloud.com/getplayurl.php';
			foreach($apiParams as $k=>$v)
			{
				if($posts=='')
				{
					$posts=''.$k.'='.urlencode($v);
				}
				else
				{
					$posts.='&'.$k.'='.urlencode($v);
				}
			}

			$posts=$url.'?'.$posts;	

			$curl = curl_init();
		    //设置抓取的url
		    curl_setopt($curl, CURLOPT_URL, $posts);
		    //设置头文件的信息作为数据流输出
		    curl_setopt($curl, CURLOPT_HEADER, 0);
		    //设置获取的信息以文件流的形式返回，而不是直接输出。
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		    //执行命令
		    $data = curl_exec($curl);
		    //关闭URL请求
		    curl_close($curl);
		    //显示获得的数据
		    $array=json_decode($data,true);
			//print_r($array);
			if($array['code']==0)
			{

				
				echo '下载地址为:<a href="'.base64_decode($array['data']['video_list']['video_3']['main_url']).'" target="_blank"><strong>'.base64_decode($array['data']['video_list']['video_3']['main_url']).'</strong></a>';
			}
			else
			{
				echo '数据获取异常，具体原因如下：';
				echo '<hr></hr>';
				echo '<pre>';
				print_r($array);
			}
			
	}
	else
	{
		echo '视频ID不能为空';
	}
	
	function getTimestamp($digits = false) {
		$digits = $digits > 10 ? $digits : 10;
		$digits = $digits - 10;
		if ((!$digits) || ($digits == 10))
		{
			return time();
		}
		else
		{
			return number_format(microtime(true),$digits,'','');
		}
	}