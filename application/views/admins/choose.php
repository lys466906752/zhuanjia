<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<LINK rel="Bookmark" href="/favicon.ico" >
<LINK rel="Shortcut Icon" href="/favicon.ico" />
<!--[if lt IE 9]>
<script type="text/javascript" src="/assets/admins/lib/html5.js"></script>
<script type="text/javascript" src="/assets/admins/lib/respond.min.js"></script>
<script type="text/javascript" src="/assets/admins/lib/PIE_IE678.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="/assets/admins/static/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="/assets/admins/static/h-ui.admin/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="/assets/admins/lib/Hui-iconfont/1.0.7/iconfont.css" />
<link rel="stylesheet" type="text/css" href="/assets/admins/lib/icheck/icheck.css" />
<link rel="stylesheet" type="text/css" href="/assets/admins/static/h-ui.admin/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="/assets/admins/static/h-ui.admin/css/style.css" />
<script type="text/javascript" src="/assets/admins/lib/jquery/1.9.1/jquery.min.js"></script> 
<!--[if IE 6]>
<script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<!--/meta 作为公共模版分离出去-->
<script>
	var form_status=0;
	function changeType()
	{
		var webType=$('#webType').val();
		if(webType=='' || webType==0)
		{
			layer.msg('请选择一个分类');	
		}
		else
		{
			if(form_status==0 || form_status==3){
				$.ajax({url:"<?php echo admin_url();?>home/changes/" + webType, 
				type: 'GET', 
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
								layer.msg('切换成功，2秒后刷新');
								setTimeout('parent.location.reload();',1500);
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
	}
</script>
<title>添加管理员</title>

</head>
<body>
<article class="page-container">
	<div class="form form-horizontal" id="form-article-add" action="#">
	<table class="table table-border table-bordered table-hover table-bg table-sort">
		<tbody>
			
			
			<tr class="text-c" id="show_a">
			  <td width="164"><font color='#cc0000'>*</font> 请选择分类：</td>
			  <td width="760" align="left" valign="middle">
              <select class="input-text" id="webType" name="webType">
              	<option value="">--请选择一个--</option>
                <?php foreach($types->result_array() as $type){?><option value="<?php echo $type['id'];?>" <?php echo isset($_COOKIE['webType']) && $_COOKIE['webType']==$type['id']?'selected':'';?>><?php echo $type['name'];?></option><?php }?>
              </select>
              </td>
		  </tr>

			<tr class="text-c" >
			  <td colspan="2" align="center" valign="middle">
              
              <button onClick="changeType();" class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 点 击 选 择   </button>
              </td>
		  </tr>
 
		</tbody>
	</table>   	
	</div>
  
</article>
<script>
	
	var form_loads=1;
	
	function article_save_submit()
	{
		
		if(form_loads==1)
		{
			
			
					
			var username=$("#username").val().replace(/(^\s*)|(\s*$)/g,"");
			var passwd=$("#passwd").val().replace(/(^\s*)|(\s*$)/g,"");
			var passwd_re=$("#passwd_re").val().replace(/(^\s*)|(\s*$)/g,"");
			var usernameTest=/^[a-zA-Z0-9_-]{4,16}$/;

			
			if(username=="")
			{
				layer.msg('请填写管理员用户名');			
			}
			else if(!usernameTest.test(username))
			{
				layer.msg('管理员用户名格式不正确');
			}
			else if(passwd=='' || passwd.length<6 || passwd.length>18)
			{
				layer.msg('请填写管理员密码，6-18位');	
			}
			else if(passwd!=passwd_re)
			{
				layer.msg('确认密码不正确');
			}
			else
			{
				layer.closeAll();
				form_loads=2;	

				$.ajax({url:"<?php echo admin_url();?>admins/inserts", 
				type: 'POST', 
				data:{username:username,passwd:passwd,passwd_re:passwd_re}, 
				dataType: 'html', 
				timeout: 15000, 
					error: function(){
						layer.closeAll();
						form_loads=1;
						layer.alert('抱歉：程序更新过程中出错，请您稍后再试！', {
							icon: 2,
							skin: 'layer-ext-moon'
						})
					},
					beforeSend:function(){
						var index = layer.load(3,{
							shade: [0.2,'#333333'] //0.1透明度的白色背景
						});	
						form_loads=2;								
					},
					success:function(result){
						layer.closeAll();
						form_loads=1;
						result=result.replace(/(^\s*)|(\s*$)/g,"");
						if(result.indexOf("|")>0){
							arr=result.split("|");
							if(arr[0]==100){
								form_state=1;
								layer.alert(arr[1], {
									icon: 1,
									skin: 'layer-ext-moon'
								})		
								setTimeout("location='<?php echo admin_url();?>admins/index.html?keywords=<?php echo $keywords;?>&pageindex=<?php echo $pageindex;?>'",1500);
							}else if(arr[0]==200){
								layer.alert('登录状态已失效，请重新登录！', {
									icon: 2,
									skin: 'layer-ext-moon'
								})			
								setTimeout("location='<?php echo admin_url();?>login/index.html'",1500);			
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
			}
		}
		else
		{
			//第三方扩展皮肤
			layer.alert('尚有其他数据正在处理中，请稍等', {
			  icon: 7,
			  skin: 'layer-ext-moon' //该皮肤由layer.seaning.com友情扩展。关于皮肤的扩展规则，去这里查阅
			})			
		}
	}
</script>

<!--_footer 作为公共模版分离出去-->

<script type="text/javascript" src="/assets/admins/lib/layer/2.1/layer.js"></script> 
<script type="text/javascript" src="/assets/admins/lib/icheck/jquery.icheck.min.js"></script> 
<script type="text/javascript" src="/assets/admins/lib/jquery.validation/1.14.0/jquery.validate.min.js"></script> 
<script type="text/javascript" src="/assets/admins/lib/jquery.validation/1.14.0/validate-methods.js"></script> 
<script type="text/javascript" src="/assets/admins/lib/jquery.validation/1.14.0/messages_zh.min.js"></script> 
<script type="text/javascript" src="/assets/admins/static/h-ui/js/H-ui.js"></script> 
<script type="text/javascript" src="/assets/admins/static/h-ui.admin/js/H-ui.admin.js"></script> 
<!--/_footer /作为公共模版分离出去-->

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="/assets/admins/lib/My97DatePicker/WdatePicker.js"></script>  
<script type="text/javascript" src="/assets/admins/lib/webuploader/0.1.5/webuploader.min.js"></script> 
<script type="text/javascript" src="/assets/admins/lib/ueditor/1.4.3/ueditor.config.js"></script> 
<script type="text/javascript" src="/assets/admins/lib/ueditor/1.4.3/ueditor.all.min.js"> </script> 
<script type="text/javascript" src="/assets/admins/lib/ueditor/1.4.3/lang/zh-cn/zh-cn.js"></script>

<!--/请在上方写此页面业务相关的脚本-->
</body>
</html>