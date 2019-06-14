<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo base_url();?>assets/admins/lib/html5.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admins/lib/respond.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admins/lib/PIE_IE678.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admins/static/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admins/static/h-ui.admin/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admins/lib/Hui-iconfont/1.0.7/iconfont.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admins/lib/icheck/icheck.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admins/static/h-ui.admin/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/admins/static/h-ui.admin/css/style.css" />
<!--[if IE 6]>
<script type="text/javascript" src="<?php echo base_url();?>assets/admins/lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<script>
	function noDownload()
	{
		layer.msg('请选择分类！');	
	}
</script>
<title>我的桌面</title>
</head>
<body>
<div class="page-container">
	<p class="f-20 text-success">欢迎使用军颐中医院医生信息管理系统 <span class="f-14">v1.0</span>！</p>
	<p>本地登录IP：<?php echo ($this->adminInfos["login_ip"]);?> &nbsp;&nbsp;&nbsp;&nbsp; 本次登录时间： <?php echo date("Y-m-d H:i:s",$this->adminInfos['login_time']);?>  &nbsp;&nbsp;&nbsp;&nbsp; 登录次数： <?php echo $this->adminInfos['counts'];?> </p>
  
    
    <?php
    	$today_s=strtotime(date("Y-m-d")." 00:00:00");
		$today_e=strtotime(date("Y-m-d")." 23:59:59");
		
    	$yestday_s=strtotime(date("Y-m-d")." 00:00:00")-24*3600;
		$yestday_e=strtotime(date("Y-m-d")." 23:59:59")-24*3600;
		
		//echo strtotime('last monday');
		
		//这个星期的星期一
		// @$timestamp ，某个星期的某一个时间戳，默认为当前时间
		// @is_return_timestamp ,是否返回时间戳，否则返回时间格式
		function this_monday($timestamp=0,$is_return_timestamp=true){
			static $cache ;
			$id = $timestamp.$is_return_timestamp;
			if(!isset($cache[$id])){
				if(!$timestamp) $timestamp = time();
				$monday_date = date('Y-m-d', $timestamp-86400*date('w',$timestamp)+(date('w',$timestamp)>0?86400:-/*6*86400*/518400));
				if($is_return_timestamp){
					$cache[$id] = strtotime($monday_date);
				}else{
					$cache[$id] = $monday_date;
				}
			}
			return $cache[$id];
		
		}
		
		//这个星期的星期天
		// @$timestamp ，某个星期的某一个时间戳，默认为当前时间
		// @is_return_timestamp ,是否返回时间戳，否则返回时间格式
		function this_sunday($timestamp=0,$is_return_timestamp=true){
			static $cache ;
			$id = $timestamp.$is_return_timestamp;
			if(!isset($cache[$id])){
				if(!$timestamp) $timestamp = time();
				$sunday = this_monday($timestamp) + /*6*86400*/518400;
				if($is_return_timestamp){
					$cache[$id] = $sunday;
				}else{
					$cache[$id] = date('Y-m-d',$sunday);
				}
			}
			return $cache[$id];
		}
		
		$w_s=this_monday();
		$w_e=this_sunday();
		
		$m_s=strtotime(date("Y-m-"."01")." 00:00:00");
		$m_e=strtotime(date("Y-m-".date("t"))." 23:59:59");
		
	?>
  <table class="table table-border table-bordered table-bg">
		<thead>
			<tr>
				<th colspan="4" scope="col">信息统计</th>
			</tr>
			<tr class="text-c">
				<th>医生</th>
				<th>字典数量</th>
				<th>接口文档</th>
			</tr>
		</thead>
		<tbody>
			<tr class="text-c">
				<td><?php echo $doctor->num_rows();?></td>
				<td><?php echo $dict->num_rows();?></td>
				<td>

                <a href="<?php echo admin_url();?>home/api" target="_blank" style="display: inline-block;width:auto;padding-right: 10px; padding-left: 10px; border-radius: 5px; background: #1859a5;color:#fff; line-height: 30px;">点击下载</a>

                </td>
			</tr>
		</tbody>
	</table>
	<table class="table table-border table-bordered table-bg mt-20">
		<thead>
			<tr>
				<th colspan="2" scope="col">服务器信息</th>
			</tr>
	  </thead>
		<tbody>
			<tr>
				<th width="30%">服务器计算机名</th>
				<td><span id="lbServerName"><?php echo $_SERVER['SERVER_NAME'];?></span></td>
			</tr>
			<tr>
				<td>服务器站点目录</td>
				<td><?php echo $_SERVER['DOCUMENT_ROOT'];?></td>
			</tr>
			<tr>
				<td>服务器站点端口</td>
				<td><?php echo $_SERVER['SERVER_PORT'];?></td>
			</tr>
			
			<tr>
				<td>服务器版本 </td>
				<td><?php echo $_SERVER['SERVER_SOFTWARE'];?></td>
			</tr>
			<tr>
				<td>允许上传最大文件 </td>
				<td><?php echo ini_get("post_max_size");?></td>
			</tr>
			<tr>
				<td>服务器操作系统 </td>
				<td><?php echo PHP_OS; ?></td>
			</tr>
			<tr>
				<td>最大执行时间 </td>
				<td><?php echo get_cfg_var("max_execution_time")."秒 "; ?></td>
			</tr>
			<tr>
				<td>脚本运行占用最大内存 </td>
				<td><?php echo get_cfg_var ("memory_limit")?get_cfg_var("memory_limit"):"无" ?></td>
			</tr>
		</tbody>
  </table>
</div>
<footer class="footer mt-20">
	<div class="container">
		<p>感谢Recson、Harry等技术倾力设计<br>
			Copyright &copy;2012 军颐技术团队 v1.0 All Rights Reserved.<br>
			本后台系统由<a href="javascript:void(0);" target="_blank" title="">军颐技术团队</a>提供前端技术支持</p>
	</div>
</footer>
<script type="text/javascript" src="<?php echo base_url();?>assets/admins/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/admins/static/h-ui/js/H-ui.js"></script> 

</body>
</html>