<?php 
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<div class="pad-lr-10">
<form name="myform" action="?m=mes&c=mes&a=meslistorder" method="post">
<div class="table-list">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
            <th width="50"><input type="checkbox" value="" id="check_box" onclick="selectall('mesid[]');"></th>
            <th width="180" align="left">标题</th>
			<th width='340' align="left">内容</th>
			<th width='8%' align="left">姓名</th>
			<th width="9%" align="left">联系方式</th>
			<th width='14%' align="left">邮箱</th>
			<th width="12%" align="left">留言时间</th>
			<th width="6%" align="center">管理操作</th>
            </tr>
        </thead>
    	<tbody>
 <?php
if (is_array($mes)){
	foreach ($mes as $m){
?>   
			<tr>
			<td width="50" align="center"><input type="checkbox" value="<?php echo $m['mesid']?>" name="mesid[]"></td>
			<td align="left" width="180"><?php if(1==$m['show_mes'])echo '<font color="#993300"><strong>'.$m['title'].'</strong></font>'; else echo $m['title']?></td>
			<td width="340" align="left"><?php echo $m['content']?></td>
			<td align="left" title="♂：男，♀：女"><?php if(1==$m['sex']) echo "♂".$m['name'];else echo "♀".$m['name']; ?></td>
			<td align="left"><?php echo $m['tel']?></td>
			<td align="left"><?php echo $m['mail']?></td>
			<td align="left"><?php echo  date('Y-m-d H:i:s', $m['time'])?></td>
			<td align="center">
				<a href="javascript:edit('<?php echo $m['mesid']?>', '<?php echo safe_replace($m['title'])?>');void(0);">
				<?php if(!empty($m['re_content'])){?><strong><font color="red">回复</font></strong><?php }else {?>回复<?php }?>
				</a>
			</td>
			</tr>
<?php  
	}
}
?>
		</tbody>
	</table>
</div>
<div class="btn">
	<label for="check_box">全选/取消</label>
	<input type="submit" class="button" name="dosubmit" value="删除" onclick="document.myform.action='?m=mes&c=mes&a=delete';return confirm('此操作不可还原\r\n确定删除选中记录吗？')"/>
	<input type="submit" class="button" name="dosubmit" value="前台显示" onclick="document.myform.action='?m=mes&c=mes&a=show_mes';return confirm('确定要显示选中记录吗？')"/>
</div>
<div id="pages"><?php echo $pages?></div>
</form>
</div>
</body>
</html>
<script type="text/javascript">
function edit(id, title) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'回复:'+title, id:'edit', iframe:'?m=mes&c=mes&a=mes_re&mesid='+id ,width:'600px',height:'300px'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}
</script>