<?php
	
	//发送手机短信
	if(! function_exists('send_msg'))
	{
		function send_msg($tplid,$mobile,$content,$code="utf-8")
		{
			require "require.php";
			$post_data = $svr_param;
			$post_data['method'] = 'sendMsg';//如果有乱码请尝试用 sendUtf8Msg 或  sendGbkMsg
			$post_data['mobile'] = $mobile;
			$post_data['content']=$content;// 或 "@1@=$code";
			$post_data['msgtype']= '2';    // 1-普通短信，2-模板短信
			$post_data['tempid'] =$tplid;  // 模板编号 ， 在客户创建模板后会生成模板编号
			$post_data['code']   = 'utf-8';// utf-8,gbk
			$res =request_post($msn_url, $post_data);  // 如果账号开了免审，或者是做模板短信，将会按照规则正常发出，而不会进人工审核平台
			return $res;				
		}	
	}
	
	//做一个发送数据处理函数
	if(! function_exists('request_post'))
	{
		function request_post($url = '', $post_data = array()) {
			if (empty($url) || empty($post_data)) {
				return false;
			}

			
			$o = "";
			foreach ( $post_data as $k => $v ) 
			{ 

				$o.= "$k=" . urlencode($v). "&" ;
			}
			$post_data = substr($o,0,-1);
		
			$postUrl = $url;
			$curlPost = $post_data;
			$ch = curl_init();//初始化curl
			curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
			curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
			curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
			curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
			$data = curl_exec($ch);//运行curl
			curl_close($ch);
			
			return $data;
		}
	}