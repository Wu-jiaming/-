<?php
if(!defined('IN_TG'))
{
	exit('ACCESS DEINED!');
}
_close();
?>
<div id="footer">
<p>本程序执行耗时为: <?php echo  round((_runtime() - start_time),4)?>秒</p>
<p>版权所有 翻版不究</p>
<p>本程序由<span>吴家明</span>大帅哥翻版李炎恢所写</p>
</div>