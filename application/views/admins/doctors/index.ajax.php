<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="javascript:del_all();" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 删除所选医生信息</a> &nbsp;&nbsp; <a href="<?php echo admin_url();?>doctors/add?keywords=<?php echo $keywords;?>&pageindex=<?php echo $pageindex;?>" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> 添加医生</a></span> <span class="r">共有数据：<strong><?php echo $pageall;?></strong> 条</span> 
</div>

	<div class="mt-20">
    
	<table class="table table-border table-bordered table-hover table-bg table-sort">
		<thead>
			<tr class="text-c">
			  <th width="25"><input type="checkbox" name="sx" id="sx" value="100" onClick="choose_all();" ></th>
			  <th width="52">医生ID</th>
              <th width="52">是否显示</th>
			  <th width="">医生姓名</th>
			  <th width="99">所在分类</th>
			  <th width="99">操作</th>
          </tr>
		</thead>
		<tbody>
        	<?php
            	foreach($query->result_array() as $array)
				{
			?>
			<tr id="zo_<?php echo $array["id"];?>" class="text-c" >
                <td><input type="checkbox" name="cid" id="cid" value="<?php echo $array["id"];?>"></td>
                <td><?php echo $array["id"];?></td>
                <td id="loadeds_<?php echo $array['id'];?>"><?php echo $array['show']==1?'<a href="javascript:changes_do(2,'.$array['id'].');" style="color:green;font-weight:blod;">√</a>':'<a href="javascript:changes_do(1,'.$array['id'].');" style="color:red;font-weight:blod;">×</a>';?></td>
                <td class="f-14 td-manage"><strong><?php echo $array['realname'];?></strong></td>
                <td class="f-14 td-manage"><?php echo $this->typeInfo['name'];?></td>
                <td class="f-14 td-manage"><a style="text-decoration:none" class="ml-5" onClick="members_edit('<?php echo $array["id"];?>')" href="javascript:;" title="完善并编辑信息"><i class="Hui-iconfont">&#xe6df;</i></a> </td>
            </tr>
            <?php
            	}
			?>
		</tbody>
	</table>
    
    
    
</div>
    
    <?php
    	 if($pagecount>1){
	?>
    <div style="width:100%;padding-top:15px;">
    	<div style="width:40%; float:left; height:30px; line-height:30px;">显示 <?php echo $pageindex;?> / <?php echo $pagecount;?> 页 &nbsp;&nbsp;&nbsp;  <input type="text" id="pagenum" name="pagenum" value="<?php echo $pageindex;?>" style="text-align:center; width:40px; height:18px; line-height:18px; border:#999999 1px solid;" > &nbsp; 页 &nbsp; <input type="button" value="跳转" style="text-align:center; width:40px; height:20px; line-height:20px; border:#999999 1px solid;" onClick="page_gos();"> &nbsp;&nbsp;&nbsp; 共 <strong><?php echo $pageall;?></strong> 条数据</div>
        
        <div style="width:60%; float:right; text-align:right;" class="pages">
		<?php
           if($pageindex>1){
        ?>
        <a href="javascript:show_pages(1)">首页</a> <a href="javascript:show_pages('<?php echo $pageindex-1;?>')">上一页</a> 
        <?php
            }
        ?>
        <?php
            for($i=$arrs[0];$i<=$arrs[1];$i++){
        ?>
        <?php
            if($i!=$pageindex){
        ?>
        <a href="javascript:show_pages('<?php echo $i;?>')"><?php echo $i;?></a>
        <?php
            }else{
        ?>
        <span><?php echo $i;?></span>
        <?php
            }
        ?>
        <?php
            }
        ?>      
        <?php
            if($pageindex<$pagecount){
        ?>                                         
        <a href="javascript:show_pages('<?php echo $pageindex+1;?>')">下一页</a> <a href="javascript:show_pages('<?php echo $pagecount;?>')">末页</a>
        <?php
            }
        ?>
        </div>
    </div>
    <?php
    	}
	?>

