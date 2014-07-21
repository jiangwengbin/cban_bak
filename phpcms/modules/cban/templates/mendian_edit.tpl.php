<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header', 'admin');
?>
<div class="pad-10">
<form method="post" action="?m=cban&c=manage&a=mendian_edit" name="myform" id="myform">
<table class="table_form" width="100%">
<tbody>
	<tr>
		<th width="80">用户名:</th>
		<td><?php echo $info['username']?></td>
	</tr>
	<tr>
		<th>门店名称：</th>
		<td><input type="text" name="info[mdname]" style="width:400px;" value="<?php echo $info[mdname]?>" /></td>
	</tr>
    <tr>
		<th>文章内容：</th>
		<td><textarea name="info[content]" id="content"><?php echo $info[content]?></textarea><?php echo form::editor('content','full','mendian_info','','','1','0','gif|jpg|jpeg|png|bmp','200')?></td>
	</tr>
	<tr>
		<th>地区：</th>
		<td><?php echo menu_linkage(1,'L_1',$info[diqu])?></td>
	</tr>
    <tr>
		<th>地址：</th>
		<td><input type="text" name="info[dizhi]" style="width:400px;" value="<?php echo $info[dizhi]?>" /></td>
	</tr>
    <tr>
		<th>联系人：</th>
		<td><input type="text" name="info[lxr]" style="width:400px;" value="<?php echo $info[lxr]?>" /></td>
	</tr>
    <tr>
		<th>tel：</th>
		<td><input type="text" name="info[tel]" style="width:400px;" value="<?php echo $info[tel]?>" /></td>
	</tr>
    <tr>
		<th>固话：</th>
		<td><input type="text" name="info[dianhua]" style="width:400px;" value="<?php echo $info[dianhua]?>" /></td>
	</tr>
    <tr>
		<th>传真：</th>
		<td><input type="text" name="info[chuanzhen]" style="width:400px;" value="<?php echo $info[chuanzhen]?>" /></td>
	</tr>
    <tr>
		<th>email：</th>
		<td><input type="text" name="info[email]" style="width:400px;" value="<?php echo $info[email]?>" /></td>
	</tr>
    <tr>
		<th>微信：</th>
		<td><input type="text" name="info[weixin]" style="width:400px;" value="<?php echo $info[weixin]?>" /></td>
	</tr>
    <tr>
		<th>QQ：</th>
		<td><input type="text" name="info[qq]" style="width:400px;" value="<?php echo $info[qq]?>" /></td>
	</tr>
	<tr>
		<th>当前状态:</th>
		<td>
			<input name="info[status]" type="radio" value="1" <?php if($info['status']==1) {?>checked<?php }?>></input>&nbsp;未审核&nbsp;&nbsp;
			<input name="info[status]" type="radio" value="2" <?php if($info['status']==2) {?>checked<?php }?>></input>&nbsp;审核通过&nbsp;&nbsp;
            <input name="info[status]" type="radio" value="3" <?php if($info['status']==3) {?>checked<?php }?>></input>&nbsp;用户撤销&nbsp;&nbsp;
            <input name="info[status]" type="radio" value="4" <?php if($info['status']==4) {?>checked<?php }?>></input>&nbsp;审核失败&nbsp;&nbsp;
         </td>
	</tr>
    </tbody>
</table>
<input type="hidden" name="id" value="<?php echo $info[id]?>" />
<input type="submit" name="dosubmit" id="dosubmit" value="OK" class="dialog">&nbsp;<input type="reset" class="dialog" value="clear">
</form>
</div>
</body>
</html>