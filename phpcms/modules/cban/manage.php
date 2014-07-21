<?php
pc_base::load_app_class('admin','admin',0);
pc_base::load_sys_class('form', '', 0);
class manage extends admin {

	function __construct(){

	}
	
	/*供应信息后台管理*/
	function supply_list(){
		$where = '';
		if($_GET['dosubmit']){
			if(trim($_GET['keywords'])){
				$keywords = addslashes(trim($_GET['keywords']));
				$where .= $where ? ' and title like \'%'.$keywords.'%\'' : ' title like \'%'.$keywords.'%\'';
				$where .= $where ? ' or goods like \'%'.$keywords.'%\'' : ' goods like \'%'.$keywords.'%\'';
				$where .= $where ? ' or lxr like \'%'.$keywords.'%\'' : ' lxr like \'%'.$keywords.'%\'';
				$where .= $where ? ' or note like \'%'.$keywords.'%\'' : ' note like \'%'.$keywords.'%\'';
			}
			if(trim($_GET['status'])){
				$status = addslashes(trim($_GET['status']));
				$where .= $where ? ' and status = \''.$status.'\'' : ' status = \''.$status.'\'';
			}
		}
		
		$thisdb = get_cbandb('cban_supply');
		$page = $_GET['page'] ? $_GET['page'] : '1';
		$infos = $thisdb->listinfo($where,'time desc',$page,20);
		$pages = $thisdb->pages;
		include $this->admin_tpl('supply_list');
	}
	
	function supply_manage(){
		
		if($_POST['dosubmit'] && $_GET['op']&& $_POST['id']){
			
			$id =  new_addslashes($_POST[id]);
			$num = count($id);
			$where = 'id in ('.implode(',',$id).')';
			$thisdb = get_cbandb('cban_supply');
			/*批量删除*/
			if($_GET['op']==1){
				
				$date = $thisdb->select($where,'img');
				$attachmentdb = get_cbandb('cban_attachment');
				foreach ($date as $k){
					$img_arr = unserialize($k['img']);
					foreach ($img_arr as $kk){
						$attachmentdb->update('status=0','filepath=\''.$kk['0'].'\'');
					}
				}
 				$thisdb = get_cbandb('cban_supply');
				$thisdb->delete($where);
				showmessage('操作成功！',HTTP_REFERER);
			}
			/*批量审核通过*/
			if($_GET['op']==2){
				$thisdb->update('status=2',$where);
				showmessage('操作成功！',HTTP_REFERER);
			}
			/*批量审核失败*/
			if($_GET['op']==3){
				$thisdb->update('status=4',$where);
				showmessage('操作成功！',HTTP_REFERER);
			}
		}else{
			showmessage('你至少选中一条记录！');
		}
		
	}
	
	/*门店信息后台管理*/
	function mendian_list(){
		
		$where = '';
		
		if($_GET['dosubmit']){
			if(trim($_GET['keywords'])){
				$keywords = addslashes(trim($_GET['keywords']));
				$where .= $where ? ' and mdname like \'%'.$keywords.'%\'' : ' mdname like \'%'.$keywords.'%\'';
				$where .= $where ? ' or dizhi like \'%'.$keywords.'%\'' : ' dizhi like \'%'.$keywords.'%\'';
				$where .= $where ? ' or lxr like \'%'.$keywords.'%\'' : ' lxr like \'%'.$keywords.'%\'';
				$where .= $where ? ' or content like \'%'.$keywords.'%\'' : ' content like \'%'.$keywords.'%\'';
			}
			if(trim($_GET['status'])){
				$status = addslashes(trim($_GET['status']));
				$where .= $where ? ' and status = \''.$status.'\'' : ' status = \''.$status.'\'';
			}
		}
		
		$thisdb = get_cbandb('cban_mendian');
		$page = $_GET['page'] ? $_GET['page'] : '1';
		$infos = $thisdb->listinfo($where,'time desc',$page,20);
		$pages = $thisdb->pages;
		include $this->admin_tpl('mendian_list');
	}
	
	/*门店信息编辑*/
	function mendian_edit(){
		//删除曾经上传的文件的cookie，则关闭“未处理文件“的功能
		param::set_cookie('att_json','');
		if($_POST['dosubmit'] && intval($_POST['id'])){
			
			if(intval($_POST['L_1-2'])==0){
				showmessage('地区必须相信的市级');
			}
			$info = $_POST['info'];
			$info['diqu'] = $_POST['L_1-2'];
			
			unset($info['L_1']);
			$where = 'id='.intval($_POST['id']);
 			$src_list = preg_img_src(new_stripslashes($info[content]));
 			$thisdb = get_cbandb('cban_mendian');
 			$content = $thisdb -> get_one($where,'content');
			$content = stripslashes($content['content']);
			update_content_attachment($content);
			
			$attachmentdb = pc_base::load_model('attachment_model');
			$attachmentdb->update('status=1', sql_where_or(get_imglist_filepath($src_list),'filepath'));
			
			$info = new_addslashes($info);
			if($thisdb->update($info,$where)) showmessage('修改成功',HTTP_REFERER);
			
			
		}else{
			
			$thisdb = get_cbandb('cban_mendian');
			$id = intval($_GET['id']);
			$info = $thisdb->get_one('id='.$id);
			$info['content'] = stripslashes($info['content']);
			include $this->admin_tpl('mendian_edit');
		}
	}
	
	
	function mendian_manage(){
	
		if($_POST['dosubmit'] && $_GET['op']&& $_POST['id']){
				
			$id =  new_addslashes($_POST[id]);
			$num = count($id);
			$where = 'id in ('.implode(',',$id).')';
			$thisdb = get_cbandb('cban_mendian');
			/*批量删除*/
			if($_GET['op']==1){

				$date = $thisdb->select($where,'content');
				$attachmentdb = get_cbandb('cban_attachment');
				foreach ($date as $k){
					$content = stripslashes($k['content']);
					update_content_attachment($content);
				}
				$thisdb = get_cbandb('cban_mendian');
				$thisdb->delete($where);
				showmessage('操作成功！',HTTP_REFERER);
			}
			/*批量审核通过*/
			if($_GET['op']==2){
				$thisdb->update('status=2',$where);
				showmessage('操作成功！',HTTP_REFERER);
			}
			/*批量审核失败*/
			if($_GET['op']==3){
				$thisdb->update('status=4',$where);
				showmessage('操作成功！',HTTP_REFERER);
			}
		}else{
			showmessage('你至少选中一条记录！');
		}
	
	}
}
?>