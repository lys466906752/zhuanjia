<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://www.w3.org/TR/REC-html40">
<style type="text/css">
body,td,th {
	font-family: "微软雅黑";
	font-size: 12px;
}
</style>
<table width="650" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td height="40" align="center" valign="middle">字典名称</td>
    <td height="40" align="center" valign="middle">字段名称</td>
    <td height="40" align="center" valign="middle">所属模型</td>
  </tr>
<?php
	foreach($query->result_array() as $array)
	{
?>
  <tr>
    <td height="40" align="center" valign="middle"><?php echo $array["desc"];?></td>
    <td height="40" align="center" valign="middle"><strong><?php echo $array["field"];?></strong></td>
    <td height="40" align="center" valign="middle">
		<?php
            if($array["model"]==1)
            {
                echo '文本域类型';
            }
            elseif($array["model"]==2)
            {
                echo '图片类型';
            }
            elseif($array["model"]==3)
            {
                echo '编辑器类型';
            }
        
        ?>    
    </td>
  </tr>
<?php
	}
?>
</table>
</html>