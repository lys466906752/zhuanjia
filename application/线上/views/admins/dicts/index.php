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
<script src="<?php echo base_url();?>assets/js/jquery-1.11.1.min.js"></script>
<script src="<?php echo base_url();?>assets/layer_pc/layer.js"></script>
<script>
	var keywords="<?php echo $keywords;?>";
	var pageindex=<?php echo $pageindex;?>;


	var form_status=0;
	
	function page_gos()
	{
		pageindex=$("#pagenum").val(); 
		reads();
	}
	
	function show_pages(pages)
	{
		pageindex=pages; 
		reads();
	}
	
	function reads()
	{
		$.ajax({url:"<?php echo admin_url();?>dicts/indexAjax/" + pageindex + "?keywords=" + keywords, 
			type: 'GET', 
			dataType: 'html', 
			timeout: 15000, 
			error: function(){
				layer.closeAll();
				reads();
			},
			beforeSend:function(){
				var index = layer.load(1, {
				  shade: [0.7,'#000'] //0.1透明度的白色背景
				});									
			},
			success:function(result){
				layer.closeAll();
				result=result.replace(/(^\s*)|(\s*$)/g,"");
				//alert(result);
				document.getElementById("inners").innerHTML=result;
			}
		});			
	}
	
	function members_edit(id)
	{
		location="<?php echo admin_url();?>dicts/edit/" + id + ".html?pageindex=" + pageindex + "&keywords=" + keywords;	
	}
	
	$(document).ready(function(){
		
		reads();

	});
	


	
	function soso()
	{
		keywords=$("#keys").val();
		pageindex=1;
		reads();
	}
	
	function choose_all()
	{
		//选择信息
		var s_group = document.getElementsByName("sx");
		var s_group_value="";
		for(var i = 0; i< s_group.length; i++){
			if(s_group[i].checked==true){
				s_group_value=s_group[i].value;
			}
		}
		var s_group = document.getElementsByName("cid");
		for(var i = 0; i< s_group.length; i++){
			if(s_group_value!=""){
				s_group[i].checked=true;
			}else{
				s_group[i].checked=false;	
			}
		}	
	}	
	
	function del_all(){
		var s_group = document.getElementsByName("cid");
		var s_group_value="";
		for(var i = 0; i< s_group.length; i++){
			if(s_group[i].checked==true){
				//alert(group[i].value);
				if(s_group_value==""){
					s_group_value=s_group[i].value;	
				}else{
					s_group_value=s_group_value + "," + s_group[i].value;
				}
			}
		}	
		if(s_group_value=="")
		{
			layer.msg('请选择要删除的数据');
		}
		else
		{
			del_it(s_group_value);	
		}			
	}	

	

	function del_it(id){
		layer.confirm('删除字典数据后，数据不可以恢复，您确定要这么做吗', {
			btn: ['确定','取消'] //按钮
		}, function(){
			del_rs(id);
		}, function(){
			layer.closeAll();
		});				
	}	
	
	function del_rs(id){
		if(form_status==0 || form_status==3){
			$.ajax({url:"<?php echo admin_url();?>dicts/del", 
			type: 'POST', 
			data:{id:id},
			dataType: 'html', 
			timeout: 15000, 
				error: function(){
					layer.closeAll();
					form_status=3;	
					layer.alert('处理失败，请您稍后再试！', {
						icon: 7,
						skin: 'layer-ext-moon'
					})			
				},
				beforeSend:function(){
					layer.closeAll();
					var index = layer.load(3,{
						shade: [0.2,'#333333'] //0.1透明度的白色背景
					});
					form_status=1;	
				},
				success:function(result){
					form_status=3;
					layer.closeAll();
					result=result.replace(/(^\s*)|(\s*$)/g,"");
					if(result.indexOf("|")>0){
						arr=result.split("|");
						if(arr[0]==100){
							layer.alert(arr[1], {
								icon: 1,
								skin: 'layer-ext-moon'
							})		
							setTimeout("reads()",1000);
						}else if(arr[0]==200){
							layer.alert('登录状态已失效，请重新登录！', {
								icon: 2,
								skin: 'layer-ext-moon'
							})			
							setTimeout("location='<?php echo admin_url();?>login/index'",1500);			
						}else if(arr[0]==300){
							layer.alert(arr[1], {
								icon: 2,
								skin: 'layer-ext-moon'
							})						
						}
					}else{
						layer.alert('操作过程出错，请您稍后再试！', {
							icon: 2,
							skin: 'layer-ext-moon'
						})					
					}
				} 
			});
		}else{
			layer.alert('还有其他进程正在进行中，请您稍后再试！', {
				icon: 7,
				skin: 'layer-ext-moon'
			})					
		}				
	}

	function move_up(id)
	{
		if(form_status==0 || form_status==3){
			$.ajax({url:"<?php echo admin_url();?>dicts/ups/" + id, 
			type: 'POST', 
			data:{id:id},
			dataType: 'html', 
			timeout: 15000, 
				error: function(){
					layer.closeAll();
					form_status=3;	
					layer.alert('处理失败，请您稍后再试！', {
						icon: 7,
						skin: 'layer-ext-moon'
					})			
				},
				beforeSend:function(){
					layer.closeAll();
					var index = layer.load(3,{
						shade: [0.2,'#333333'] //0.1透明度的白色背景
					});
					form_status=1;	
				},
				success:function(result){
					form_status=3;
					layer.closeAll();
					result=result.replace(/(^\s*)|(\s*$)/g,"");
					if(result.indexOf("|")>0){
						arr=result.split("|");
						if(arr[0]==100){
							reads();
						}else if(arr[0]==200){
							layer.alert('登录状态已失效，请重新登录！', {
								icon: 2,
								skin: 'layer-ext-moon'
							})			
							setTimeout("location='<?php echo admin_url();?>login/index'",1500);			
						}else if(arr[0]==300){
							layer.alert(arr[1], {
								icon: 2,
								skin: 'layer-ext-moon'
							})						
						}
					}else{
						layer.alert('操作过程出错，请您稍后再试！', {
							icon: 2,
							skin: 'layer-ext-moon'
						})					
					}
				} 
			});
		}else{
			layer.alert('还有其他进程正在进行中，请您稍后再试！', {
				icon: 7,
				skin: 'layer-ext-moon'
			})					
		}						
	}
	
	function move_down(id)
	{
		if(form_status==0 || form_status==3){
			$.ajax({url:"<?php echo admin_url();?>dicts/downs/" + id, 
			type: 'POST', 
			data:{id:id},
			dataType: 'html', 
			timeout: 15000, 
				error: function(){
					layer.closeAll();
					form_status=3;	
					layer.alert('处理失败，请您稍后再试！', {
						icon: 7,
						skin: 'layer-ext-moon'
					})			
				},
				beforeSend:function(){
					layer.closeAll();
					var index = layer.load(3,{
						shade: [0.2,'#333333'] //0.1透明度的白色背景
					});
					form_status=1;	
				},
				success:function(result){
					form_status=3;
					layer.closeAll();
					result=result.replace(/(^\s*)|(\s*$)/g,"");
					if(result.indexOf("|")>0){
						arr=result.split("|");
						if(arr[0]==100){
							reads();
						}else if(arr[0]==200){
							layer.alert('登录状态已失效，请重新登录！', {
								icon: 2,
								skin: 'layer-ext-moon'
							})			
							setTimeout("location='<?php echo admin_url();?>login/index'",1500);			
						}else if(arr[0]==300){
							layer.alert(arr[1], {
								icon: 2,
								skin: 'layer-ext-moon'
							})						
						}
					}else{
						layer.alert('操作过程出错，请您稍后再试！', {
							icon: 2,
							skin: 'layer-ext-moon'
						})					
					}
				} 
			});
		}else{
			layer.alert('还有其他进程正在进行中，请您稍后再试！', {
				icon: 7,
				skin: 'layer-ext-moon'
			})					
		}						
	}
	
</script>
<!--[if IE 6]>
<script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>用户管理</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 字典模型 <span class="c-gray en">&gt;</span> 模型列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="<?php echo admin_url();?>dicts/downloads" target="_blank" title="刷新" ><i class="Hui-iconfont">downLoad</i></a></nav>
<div class="page-container">
	<div class="text-c"> 
    <input type="text" class="input-text" style="width:250px" placeholder="模型描述或者字段名" id="keys" name="keys" value="<?php echo $keywords;?>">&nbsp;&nbsp;
		<button type="submit" class="btn btn-success radius" id="" name="" onClick="soso();"><i class="Hui-iconfont">&#xe665;</i> 搜内容</button>
	</div>	
    <span id="inners">
	
    </span>
</div>

<script type="text/javascript" src="<?php echo base_url();?>assets/admins/lib/layer/2.1/layer.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/admins/lib/laypage/1.2/laypage.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/admins/lib/My97DatePicker/WdatePicker.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/admins/lib/datatables/1.10.0/jquery.dataTables.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/admins/static/h-ui/js/H-ui.js"></script> 
<script type="text/javascript" src="<?php echo base_url();?>assets/admins/static/h-ui.admin/js/H-ui.admin.js"></script> 
<style>
.pages a{background-color: #FFFFFF;border: 1px solid #c53357;color: #c53357;display:inline-block;font-size: 12px;height: 22px;line-height:22px;margin: 0px 3px 0px 0px;padding-left: 5px;padding-right:5px; margin-right:5px; width:auto;}
.pages a:hover{background-color: #c53357;border: 1px solid #c53357;color: #fff;display:inline-block;font-size: 12px;height: 22px;line-height:22px;margin: 0px 3px 0px 0px;padding-left: 5px;padding-right:5px; margin-right:5px; width:auto;}
.pages a:hover{margin-right:5px; text-decoration:underline;}
.pages span{background-color: #f4f4f4;border: 1px solid #ccc;color: #999;display:inline-block;font-size: 12px;height: 22px;line-height: 24px;margin: 0px 3px 0px 0px;padding-left: 5px;padding-right: 5px;margin-right:5px;font-weight:bold;}
</style>
</body>
</html>