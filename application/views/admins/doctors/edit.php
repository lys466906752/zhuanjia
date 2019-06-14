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
<script type="text/javascript" src="/assets/admins/lib/icheck/jquery.icheck.min.js"></script> 
<script type="text/javascript" src="/assets/admins/lib/jquery.validation/1.14.0/jquery.validate.min.js"></script> 
<script type="text/javascript" src="/assets/admins/lib/jquery.validation/1.14.0/validate-methods.js"></script> 
<script type="text/javascript" src="/assets/admins/lib/jquery.validation/1.14.0/messages_zh.min.js"></script> 
<script type="text/javascript" src="/assets/admins/static/h-ui/js/H-ui.js"></script> 
<script type="text/javascript" src="/assets/admins/static/h-ui.admin/js/H-ui.admin.js"></script> 
<script charset="utf-8" src="/assets/kindeditor/kindeditor.js"></script>
<script charset="utf-8" src="/assets/kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="/assets/admins/lib/layer/2.1/layer.js"></script> 
<script type="text/javascript">
	var form_loads=1;
</script>
<!--[if IE 6]>
<script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<!--/meta 作为公共模版分离出去-->

<title>修改医生信息</title>

</head>
<body>
<article class="page-container">
	<div class="form form-horizontal" id="form-article-add" action="#">
	<table class="table table-border table-bordered table-hover table-bg table-sort">
		<tbody>
			
			
		<tr class="text-c" id="show_a">
			  <td width="200"><font color='#cc0000'>*</font> 医生姓名：</td>
			  <td align="left" valign="middle"><input type="text" class="input-text"  placeholder="请填写医生的真实姓名" id="realname" name="realname" value="<?php echo $res['realname'];?>"></td>
		  </tr>
			<tr class="text-c" style="display:none;">
			  <td><font color='#cc0000'>*</font> 所属科室：</td>
			  <td align="left" valign="middle">
			  	<input type="hidden" id="rooms" name="rooms" value="<?php echo $res['type'];?>">

			  </td>
		  </tr>
			<tr class="text-c" id="show_a">
			  <td><font color='#cc0000'>*</font> 是否显示：</td>
			  <td align="left" valign="middle">
			  	<select id="displays" name="displays" class="input-text">
			  		<option value="1" <?php if($res['show']==1){?>selected<?php }?>>------显示------</option>
			  		<option value="2" <?php if($res['show']==2){?>selected<?php }?>>------隐藏------</option>
			  	</select>

			  </td>
		  </tr>
			<!--
			PHP create Input Here
			-->	
         	<?php
         		$jsons=json_decode($res['json'],true);

         		//设置一个读取默认值的函数
         		function getDefaultValue($jsons,$field)
         		{	
         			if(isset($jsons[$field]['value']))
         			{
         				
         				return htmlspecialchars_decode($jsons[$field]['value']);
         			}

         			return '';
         		}

         		$models='';
         		$KindEditor='';
         		$syc='';

         		foreach($dicts->result_array() as $array){
         	?>
         		<?php
         			if($array['model']==1)
         			{
         				//普通文本域
         				$models.=$array['field'].'_'.$array['model']."|";
         		?>
		 			<tr class="text-c" id="show_a">
					  <td> <?php echo $array['desc'];?>：</td>
					  <td align="left" valign="middle">
					  	<input type="text" class="input-text" id="<?php echo $array['field'];?>" name="<?php echo $array['field'];?>" value="<?php echo htmlspecialchars(getDefaultValue($jsons,$array['field']));?>">
					  </td>
				  	</tr> 
		  		<?php
		  			}
		  			elseif($array['model']==2)
		  			{
		  				//上传图片
		  				$models.=$array['field'].'_'.$array['model']."|";
		  				$filer=getDefaultValue($jsons,$array['field']);
		  		?>
		  			<tr>
					  <td style="text-align: center;"> <?php echo $array['desc'];?>：</td>
					  <td align="left" valign="middle" style="color: #999;">
					  	<span  id="imgInner<?php echo $array['field'];?>">
					  		<?php
					  			if($filer!='')
					  			{
					  		?>
					  		<a href="/<?php echo $filer;?>" target="_blank"><Img src="/<?php echo $filer;?>" style="width:120px;height:120px;"></a>
					  		<?php
					  			}
					  			else
					  			{
					  		?>
					  		无图
					  		<?php
					  			}
					  		?>
					    </span>
					  </td>
				  	</tr>
		  			<tr>
					  <td style="text-align: center;"> <?php echo $array['desc'];?>：</td>
					  <td align="left" valign="middle">
						<script type="text/javascript">
						  		
					  		//点击选择图片文件
					  		function clickUploadFile<?php echo $array['field'];?>()
					  		{
					  			$('#file_<?php echo $array['field'];?>').click();
					  		}

					  		//提交对应的表单
					  		function submit_<?php echo $array['field'];?>()
					  		{

					  			if(form_loads==1)
								{
									layer.closeAll();
									var index = layer.load(1, {
									  shade: [0.9,'#333'] //0.1透明度的白色背景
									});
									form_loads=2;	
									document.form_<?php echo $array['field'];?>.submit();	
								}
								else
								{
									layer.msg('抱歉：还有进程数据正在处理中，请稍等...');
								}

					  		}


				  		</script>

					  	<a href="javascript:clickUploadFile<?php echo $array['field'];?>();" style="width: auto;padding-left: 20px; padding-right: 20px; line-height: 30px; border-radius:4px; background: #ccc;color:#666;display: inline-block;">点击上传</a>

					  	<iframe id="tarGet_<?php echo $array['field'];?>" name="tarGet_<?php echo $array['field'];?>" src="#" style="width:0;height:0;border:0px solid #fff;display:none;"></iframe>

					  	<form id="form_<?php echo $array['field'];?>" name="form_<?php echo $array['field'];?>" action="<?php echo admin_url();?>uploads/index?field=<?php echo $array['field'];?>" method="post" enctype="multipart/form-data" class="definewidth m20" target="tarGet_<?php echo $array['field'];?>" style="display:none;">

						  	<span style="display: none;">
						  		<input type="file" id="file_<?php echo $array['field'];?>" name="file_<?php echo $array['field'];?>" value="" onchange="submit_<?php echo $array['field'];?>()">
						  	</span>
						  	<input type="hidden" id="<?php echo $array['field'];?>" name="<?php echo $array['field'];?>" value="<?php echo $filer;?>">
					  	</form>
					  </td>
				  	</tr> 
				  	
		  		<?php
		  			}
		  			elseif($array['model']==3)
		  			{
		  				//富文本编辑器
		  				$models.=$array['field'].'_'.$array['model']."|";
		  				$KindEditor.="window.editor".$array['field']." = K.create('#".$array['field']."');";

		  				$syc.='editor'.$array['field'].'.sync(); ';
		  		?>
		 			<tr class="text-c" id="show_a">
					  <td> <?php echo $array['desc'];?>：</td>
					  <td align="left" valign="middle">
					  	<textarea class="input-text" id="<?php echo $array['field'];?>" name="<?php echo $array['field'];?>" cols="45" rows="5"><?php echo stripslashes(getDefaultValue($jsons,$array['field']));?></textarea>

					  	
					  	
					  </td>
				  	</tr>  
				  	
		  		<?php
		  			}
		  		?>  	
         	<?php
         		}
         	?>


			<tr class="text-c" >
			  <td colspan="2" align="center" valign="middle">
              <input type="hidden" id="allPuts" name="allPuts" value="<?php echo trim($models,'|');?>" >
              <button onClick="article_save_submit();" class="btn btn-primary radius" type="submit"><i class="Hui-iconfont">&#xe632;</i> 点 击 更 新   </button>
              </td>
		  </tr>
 
		</tbody>
	</table>   	
	</div>
  
</article>

<script type="text/javascript">
//上传成功回调
	function stopUpload(res)
	{
		//alert(res);
		layer.closeAll();
		form_loads=1;	
		if(res.indexOf("|")>=0)
		{
			arr=res.split("|");

			if(arr[0]==100)
			{
				//alert(arr[2]);
				$('#' + arr[2]).val(arr[1]);
		
				//alert($('#imgInner' + arr[2]).html());
				$('#imgInner' + arr[2]).html('<a href="/' + arr[1] + '" target="_blank"><Img src="/' + arr[1] + '" style="width:120px;height:120px;"></a>');
			}
			else if(arr[0]==200)
			{
				location='<?php echo admin_url();?>login/index.html';
			}
			else if(arr[0]==300)
			{
				layer.msg(arr[1]);
			}
		}
		else
		{
			layer.msg('操作过程出错，请您稍后再试！');
		}
	}         		
</script>
<script type="text/javascript">
	KindEditor.ready(function(K) {
		<?php echo $KindEditor;?> 
	});

</script>
<script>
	
	var form_loads=1;
	
	function article_save_submit()
	{
		
		if(form_loads==1)
		{
			
			<?php
				echo $syc;
			?>
						
			var realname=$("#realname").val().replace(/(^\s*)|(\s*$)/g,"");
			var displays=$('#displays').val();
			var rooms=$('#rooms').val();

			var jsonStr='';//定义一个json字符串
			var allPuts=$('#allPuts').val().replace(/(^\s*)|(\s*$)/g,"");

			if(allPuts!='')
			{
				if(allPuts.indexOf('|')>=0)
				{
					//存在遍历添加
					arr=allPuts.split('|');
					for(var i=0;i<arr.length;i++)
					{
						var as=arr[i].split('_');
						if(as[1]==1 || as[1]==2)
						{
							var values=$('#' + as[0]).val().replace(/(^\s*)|(\s*$)/g,"") + '{recson100}' + as[1] + '{recson100}' + as[0];
						}
						else
						{

							var values=$('#' + as[0]).val() + '{recson100}' + as[1] + '{recson100}' + as[0];
						}
						if(jsonStr=='')
						{
							jsonStr=values;
						}
						else
						{
							jsonStr=jsonStr + '{json100}' + values;
						}
					}
				}
				else
				{
					//单条添加
					var as=allPuts.split('_');
					if(as[1]==1 || as[1]==2)
					{
						var values=$('#' + as[0]).val().replace(/(^\s*)|(\s*$)/g,"") + '{recson}' + as[1] + '{recson}' + as[0];
					}
					else
					{
						editor.sync(); 
						var values=$('#' + as[0]).val() + '{recson}' + as[1] + '{recson}' + as[0];
					}
					if(jsonStr=='')
					{
						jsonStr=values;
					}
					else
					{
						jsonStr=jsonStr + '{json100}' + values;
					}
				}

				// alert(jsonStr);
			}
			
			
			if(realname=="")
			{
				layer.msg('请填写医生的真实姓名');			
			}
			else
			{
				layer.closeAll();
				form_loads=2;	

				$.ajax({url:"<?php echo admin_url();?>doctors/updates/<?php echo $res['id'];?>", 
				type: 'POST', 
				data:{realname:realname,show:displays,jsonStr:jsonStr,rooms:rooms}, 
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
							shade: [false] //0.1透明度的白色背景
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
								setTimeout("location='<?php echo admin_url();?>doctors/index.html?keywords=<?php echo $keywords;?>&pageindex=<?php echo $pageindex;?>&rooms=<?php echo $rooms;?>'",1500);
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