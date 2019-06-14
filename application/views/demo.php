<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Jsonp远程调取医生数据测试页面demo</title>



<script src="/assets/js/jquery-1.11.1.min.js"></script>
<script>
	function getDoctors()
	{
		$.ajax({  
			url : "<?php echo http_url();?>api?selectItem=avatarDefault,officeShow|15,avatarWhite,itemContentsA&doctorId=5,1,2,3,4",  
			dataType:"jsonp",
			type:"GET",  
			jsonp:"jsonpcallback",  
			timeout: 6000,  
			success:function(data){
			
				
				if(data.code==100)
				{
					result=data.result;
					
					var showStr='';
					
					for(var i=0;i<result.length;i++)
					{
						showStr=showStr + '<li>医生姓名:<strong>' + result[i].realname + '</strong>&nbsp;&nbsp;&nbsp;医生ID：<strong>' + result[i].id + '</strong>&nbsp;&nbsp;&nbsp;医生默认图片地址:<strong>' + result[i].avatarDefault + '</strong>医生的职责，已经被截取处理过：<strong>' + result[i].officeShow + '</strong>&nbsp;&nbsp;&nbsp;文章内容:' + result[i].itemContentsA + '</li>';
					}	
					
					$('.doctorInner').html(showStr);	
				}
				else
				{
					alert('网络数据获取失败，请稍后再试！');
				}
			},  
			error:function(){ 
				alert('网络数据获取失败，请稍后再试！');
			}	
		});					
	}
</script>
<style type="text/css">
body,td,th {
	font-family: "微软雅黑";
	font-size: 12px;
}
</style>
</head>

<body>
<div style="width:900px; margin:0 auto; height:auto;">
	<ul class="doctorInner">
    	
  </ul>
</div>
<p align="center"><a href="javascript:getDoctors();" style="display:inline-block;width:auto; padding-left:10px; padding-right:10px; line-height:30px; border-radius:5px; background:#F00;color:#FFF; text-decoration:none;">
  
点击调取医生信息并显示</a></p>
</body>
</html>