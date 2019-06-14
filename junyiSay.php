<?php

	header("Content-type:text/html;charset=utf-8");
	
	isset($_GET["func"]) && trim($_GET["func"])!=""?$func=htmlspecialchars($_GET["func"]):exit("您访问的页面不存在");
	isset($_GET["code"]) && trim($_GET["code"])!=""?$code=htmlspecialchars($_GET["code"]):exit("您访问的页面不存在");
	isset($_GET["str"]) && trim($_GET["str"])!=""?$str=htmlspecialchars($_GET["str"]):exit("您访问的页面不存在");
	isset($_GET["urlback"]) && trim($_GET["urlback"])!=""?$urlback=htmlspecialchars($_GET["urlback"]):$urlback="";

?>
<script src="http://pinglun.999junyi.com/assets/js/jquery.js"></script>
<script>
	$(document).ready(function() {
		<?php
			if($urlback=="")
			{
		?>
		window.parent.<?php echo $func;?>('<?php echo $code;?>','<?php echo $str;?>');
		<?php
			}
			else
			{
		?>
		location="<?php echo $urlback;?>";
		<?php
			}
		?>
	});
</script>
	
	
	
	
	
	
	
