<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('foreground');
pc_base::load_sys_class('format', '', 0);
pc_base::load_sys_class('form', '', 0);

class cban_member extends foreground{

	public function init(){
		if(isset($_POST['dosubmit']))
		{
			require_once CACHE_MODEL_PATH.'member_input.class.php';
			require_once CACHE_MODEL_PATH.'member_update.class.php';
			$member_input = new member_input($this->memberinfo['modelid']);
			$modelinfo = $member_input->get($_POST['info']);
				
			if(!$_POST['diqu-2'] || !$_POST['diqu-1']){
				showmessage('地区选择必须详细到市级', HTTP_REFERER);
			}else{
				$modelinfo['diqu'] = $_POST['diqu-2'];
			}
				
			$this->db->set_model($this->memberinfo['modelid']);
			$membermodelinfo = $this->db->get_one(array('userid'=>$this->memberinfo['userid']));
			if(!empty($membermodelinfo))
			{
				$this->db->update($modelinfo, array('userid'=>$this->memberinfo['userid']));
			} else{
				$modelinfo['userid'] = $this->memberinfo['userid'];
				$this->db->insert($modelinfo);
			}
			//  		print_r($_POST);
			// 			print_r($modelinfo);
			showmessage(L('operation_success'), HTTP_REFERER);
		} else {
			$_username = $this->memberinfo['username'];
			$_usertype = get_modelname($this->memberinfo['modelid']);
				
			//获取会员模型表单
			require CACHE_MODEL_PATH.'member_form.class.php';
			$member_form = new member_form($this->memberinfo['modelid']);
			$this->db->set_model($this->memberinfo['modelid']);
			$membermodelinfo = $this->db->get_one(array('userid'=>$this->memberinfo['userid']));
			$forminfos = $forminfos_arr = $member_form->get($membermodelinfo);
				
			//万能字段过滤
			foreach($forminfos as $field=>$info)
			{
				if($info['isomnipotent'])
				{
					unset($forminfos[$field]);
				} else {
					if($info['formtype']=='omnipotent')
					{
						foreach($forminfos_arr as $_fm=>$_fm_value)
						{
							if($_fm_value['isomnipotent'])
							{
								$info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'], $info['form']);
							}
						}
						$forminfos[$field]['form'] = $info['form'];
					}
				}
			}
	
	
			$formValidator = $member_form->formValidator;
				
			//print_r($forminfos);
			include template('member', 'cban_index');
		}
	}
	

	/*供应信息发布*/
	public function supply_infor()
	{
		//删除曾经上传的文件的cookie，则关闭“未处理文件“的功能
		param::set_cookie('att_json','');
	
		if($_POST['dosubmit']){
			if(count($_POST['imglist_url'])<1 || count($_POST['imglist_url'])>5){
				showmessage('商品图片数量在 1 - 5 张');
			}
				
			if( trim($_POST['info']['title']) && trim($_POST['info']['goods']) && strlen($_POST['info']['describe'])<40
			&& $_POST['info']['type'] && trim($_POST['info']['num']) && trim($_POST['L_1-2'])
			&& trim($_POST['info']['lxr']) && trim($_POST['info']['tel']) && strlen($_POST['info']['note'])<100 )
			{
				pc_base::load_app_func('global');
	
				$date['title'] = addslashes(trim($_POST['info']['title']));
				$date['userid'] = param::get_cookie('_userid');
				$date['username'] = param::get_cookie('_username');
				$date['goods'] = addslashes(trim($_POST['info']['goods']));
				$date['describe'] = $_POST['info']['describe'] ? addslashes($_POST['info']['describe']) : '';
				$date['type'] = $_POST['info']['type'];
				$date['num'] = $_POST['info']['num'];
				$date['diqu'] = $_POST['L_1-2'];
				$date['lxr'] = addslashes(trim($_POST['info']['lxr']));
				$date['tel'] = addslashes(trim($_POST['info']['tel']));
				$date['note'] = $_POST['info']['note'] ? addslashes($_POST['info']['note']) : '';
				$date['time'] = SYS_TIME;
				$date['img'] = serialize(merger_array(get_imglist_filepath($_POST['imglist_url']),$_POST['imglist_alt']));
	
				$attachmentdb = pc_base::load_model('attachment_model');
	
				$where = sql_where_or(get_imglist_filepath($_POST['imglist_url']),'filepath');
				$attachmentdb->update('status=1',$where);
	
				$thisdb = get_cbandb('cban_supply');
	
				if($thisdb->insert($date)) showmessage('添加成功，返回继续添加','index.php?m=member&c=index&a=supply_infor');
	
			}else{
				showmessage('参数错误');
			}
	
		}else{
			//上传图片js参数
			$name = "图片上传";
			$args = "5,gif|jpg|jpeg|png|bmp,0";
			$authkey = upload_key($args);
			include template('member', 'cban_supply_infor');
		}
	}
	
	
	public function mendian_infor()
	{
		//删除曾经上传的文件的cookie，则关闭“未处理文件“的功能
		param::set_cookie('att_json','');
		if($_POST['dosubmit'] && !$_POST['id']){
				
			if( trim($_POST['info']['mdname']) && trim($_POST['L_1-2']) && trim($_POST['info']['dizhi']) && trim($_POST['info']['lxr'])
			&& $_POST['info']['tel'] && trim($_POST['info']['dianhua']) && trim($_POST['info']['email']) && strlen(trim($_POST['info']['content']))<3000 )
			{
	
				$date['userid'] = param::get_cookie('_userid');
				$date['username'] = param::get_cookie('_username');
				$date['mdname'] = addslashes(trim($_POST['info']['mdname']));
				$date['diqu'] = $_POST['L_1-2'];
				$date['dizhi'] = addslashes(trim($_POST['info']['dizhi']));
				$date['lxr'] = addslashes(trim($_POST['info']['lxr']));
				$date['tel'] = addslashes(trim($_POST['info']['tel']));
				$date['dianhua'] = addslashes(trim($_POST['info']['dianhua']));
				$date['chuanzhen'] = addslashes(trim($_POST['info']['chuanzhen']));
				$date['email'] = addslashes(trim($_POST['info']['email']));
				$date['weixin'] = addslashes(trim($_POST['info']['weixin']));
				$date['qq'] = addslashes(trim($_POST['info']['qq']));
				$date['content'] = addslashes($_POST['content']);
				$date['time'] = SYS_TIME;
				$date['status'] = '1';
					
				$src_list = preg_img_src(new_stripslashes($_POST[content]));
	
				$where = sql_where_or(get_imglist_filepath($src_list),'filepath');
	
				$attachmentdb = pc_base::load_model('attachment_model');
				$attachmentdb->update('status=1',$where);
					
				$thisdb = get_cbandb('cban_mendian');
				if($thisdb->insert($date)) showmessage('添加成功，返回继续添加','index.php?m=member&c=index&a=mendian_infor');
					
			}else{
				showmessage('参数错误');
			}
				
		}else if($_POST['dosubmit'] && $_POST['id']){
				
			if( trim($_POST['info']['mdname']) && trim($_POST['L_1-2']) && trim($_POST['info']['dizhi']) && trim($_POST['info']['lxr'])
			&& $_POST['info']['tel'] && trim($_POST['info']['dianhua']) && trim($_POST['info']['email']) && strlen(trim($_POST['info']['content']))<3000 )
			{
					
				$where['userid'] = param::get_cookie('_userid');
				$where['id'] = addslashes($_POST['id']);
	
				$date['mdname'] = addslashes(trim($_POST['info']['mdname']));
				$date['diqu'] = $_POST['L_1-2'];
				$date['dizhi'] = addslashes(trim($_POST['info']['dizhi']));
				$date['lxr'] = addslashes(trim($_POST['info']['lxr']));
				$date['tel'] = addslashes(trim($_POST['info']['tel']));
				$date['dianhua'] = addslashes(trim($_POST['info']['dianhua']));
				$date['chuanzhen'] = addslashes(trim($_POST['info']['chuanzhen']));
				$date['email'] = addslashes(trim($_POST['info']['email']));
				$date['weixin'] = addslashes(trim($_POST['info']['weixin']));
				$date['qq'] = addslashes(trim($_POST['info']['qq']));
				$date['content'] = addslashes($_POST['content']);
				$date['time'] = SYS_TIME;
	
				$src_list = preg_img_src(new_stripslashes($_POST[content]));
				$thisdb = get_cbandb('cban_mendian');
				$content = $thisdb -> get_one($where,'content');
				$content = stripslashes($content['content']);
				update_content_attachment($content);
	
				$attachmentdb = pc_base::load_model('attachment_model');
				$attachmentdb->update('status=1', sql_where_or(get_imglist_filepath($src_list),'filepath'));
					
	
				if($thisdb->update($date,$where)) showmessage('修改成功');
					
			}else{
				showmessage('参数错误');
			}
				
		}else{
			$info = '';
			if($_GET[id]){
				$thisdb = get_cbandb('cban_mendian');
				$info = $thisdb -> get_one('id=\''.addslashes($_GET[id]).'\'');
				$info['content'] = stripslashes($info['content']);
			}
			include template('member', 'cban_mendian_infor');
		}
	}
	
	public function mendian_infor_per(){
		if(isset($_POST['dosubmit']))
		{
			require_once CACHE_MODEL_PATH.'member_input.class.php';
			require_once CACHE_MODEL_PATH.'member_update.class.php';
			$member_input = new member_input($this->memberinfo['modelid']);
			$modelinfo = $member_input->get($_POST['info']);
		
			if(!$_POST['diqu-2'] || !$_POST['diqu-1']){
				showmessage('地区选择必须详细到市级', HTTP_REFERER);
			}else{
				$modelinfo['diqu'] = $_POST['diqu-2'];
			}
		
			$this->db->set_model($this->memberinfo['modelid']);
			$membermodelinfo = $this->db->get_one(array('userid'=>$this->memberinfo['userid']));
			if(!empty($membermodelinfo))
			{
				$this->db->update($modelinfo, array('userid'=>$this->memberinfo['userid']));
			} else{
				$modelinfo['userid'] = $this->memberinfo['userid'];
				$this->db->insert($modelinfo);
			}
			//  		print_r($_POST);
			// 			print_r($modelinfo);
			showmessage(L('operation_success'), HTTP_REFERER);
		} else {
			$_username = $this->memberinfo['username'];
			$_usertype = get_modelname($this->memberinfo['modelid']);
		
			//获取会员模型表单
			require CACHE_MODEL_PATH.'member_form.class.php';
			$member_form = new member_form($this->memberinfo['modelid']);
			$this->db->set_model($this->memberinfo['modelid']);
			$membermodelinfo = $this->db->get_one(array('userid'=>$this->memberinfo['userid']));
			$forminfos = $forminfos_arr = $member_form->get($membermodelinfo);
		
			//万能字段过滤
			foreach($forminfos as $field=>$info)
			{
				if($info['isomnipotent'])
				{
					unset($forminfos[$field]);
				} else {
					if($info['formtype']=='omnipotent')
					{
						foreach($forminfos_arr as $_fm=>$_fm_value)
						{
							if($_fm_value['isomnipotent'])
							{
								$info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'], $info['form']);
							}
						}
						$forminfos[$field]['form'] = $info['form'];
					}
				}
			}
		
		
			$formValidator = $member_form->formValidator;
		
			include template('member', 'cban_mendian_infor_per');
		}
	}
	
	public function supply_infor_manage()
	{
		if($_GET['status'] && $_GET['id']){
			$string = $_SERVER['argv'][0];
			$string = preg_replace('/&id=[0-9]*/i','',$string);
			$string = preg_replace('/&status=[0-9]*/i','',$string);
			//  		echo $string."<br>";
			$where['userid'] = addslashes(param::get_cookie('_userid'));
			$where['id'] = addslashes($_GET['id']);
			if ($_GET['status']==1)
			{
				$thisdb = get_cbandb('cban_supply');
				if($thisdb->update('status=3',$where))
					showmessage('撤销成功！','index.php?'.$string);
			}
			if ($_GET['status']==3)
			{
				$thisdb = get_cbandb('cban_supply');
				if($thisdb->update('status=1',$where))
					showmessage('重新发布成功！','index.php?'.$string);
			}
		}else{
			$where = ' userid='.param::get_cookie('_userid');
			if($_GET['dosubmit']){
				if(trim($_GET['keyword'])){
					$keywords = addslashes(trim($_GET['keyword']));
					$where .= $where ? ' and title like \'%'.$keywords.'%\'' : ' title like \'%'.$keywords.'%\'';
					$where .= $where ? ' or goods like \'%'.$keywords.'%\'' : ' goods like \'%'.$keywords.'%\'';
						
				}
	
				if(trim($_GET['L_1-1'])){
	
					if(trim($_GET['L_1-2'])){
						$where .= $where ? ' and diqu =\''.addslashes(trim($_GET['L_1-2'])).'\'' : ' diqu =\''.addslashes(trim($_GET['L_1-2'])).'\'';
					}else{
						$db_linkage = pc_base::load_model('linkage_model');
						$date_linkage = $db_linkage -> select(array('parentid'=>addslashes(trim($_GET['L_1-1']))),'linkageid');
						// 						echo implode(',',$date_linkage);
						$arr = array();
						foreach ($date_linkage as $k=>$v){
							$arr[$k] = $v[linkageid];
						}
						$where .= $where ? ' and diqu in ('.implode(',',$arr).')' : ' diqu in ('.implode(',',$arr).')' ;
					}
				}
	
				if(trim($_GET['type'])){
					$type='';
					if(trim($_GET['type'][1])){
						$type .= $type ? ' or type=1 ' :' type=1 ';
					}
					if(trim($_GET['type'][2])){
						$type .= $type ? ' or type=2 ' :' type=2 ';
					}
					if(trim($_GET['type'][1]) && trim($_GET['type'][2])){
						$type=' type in(1,2) ';
					}
					$where .= $where ? ' and '.$type : $type;
				}
				if(trim($_GET['status_mes'])){
					$status='';
					if(trim($_GET['status_mes'][1])){
						$status .= $status ? ' or status=1 ' :' status=1 ';
					}
					if(trim($_GET['status_mes'][2])){
						$status .= $status ? ' or status<>1 ' :' status<>1 ';
					}
					if(trim($_GET['status_mes'][1]) && trim($_GET['status_mes'][2])){
						$status = ' status in(1,2,3,4) ';
					}
					$where .= $where ? ' and '.$status : $status;
				}
				// 				print_r($_SERVER['argv']);
			}
			$thisdb = get_cbandb('cban_supply');
			$supply_infor_list = $thisdb->cban_listinfo($where,'time desc',$_GET['page'],20);
			$pages = $thisdb->pages;
			include template('member', 'cban_supply_infor_manage');
		}
	}
	
	public function mendian_infor_manage(){
		if($_GET['status'] && $_GET['id']){
			$string = $_SERVER['argv'][0];
			$string = preg_replace('/&id=[0-9]*/i','',$string);
			$string = preg_replace('/&status=[0-9|a-z]*/i','',$string);
			//echo $string."<br>";
			$where['userid'] = addslashes(param::get_cookie('_userid'));
			$where['id'] = addslashes($_GET['id']);
			if ($_GET['status']==1)
			{
				$thisdb = get_cbandb('cban_mendian');
				if($thisdb->update('status=3',$where))
					showmessage('撤销成功！','index.php?'.$string);
			}
			if ($_GET['status']==3)
			{
				$thisdb = get_cbandb('cban_mendian');
				if($thisdb->update('status=1',$where))
					showmessage('重新发布成功！','index.php?'.$string);
			}
			if ($_GET['status']=='del')
			{
				$thisdb = get_cbandb('cban_mendian');
				$content = $thisdb -> get_one($where,'content');
				$content = stripslashes($content['content']);
				update_content_attachment($content);
	
				if($thisdb->delete($where))
					showmessage('删除成功！','index.php?'.$string);
			}
		}else{
			$where = ' userid='.param::get_cookie('_userid');
			if($_GET['dosubmit']){
				if(trim($_GET['keyword'])){
					$keywords = addslashes(trim($_GET['keyword']));
					$where .= $where ? ' and mdname like \'%'.$keywords.'%\'' : ' mdname like \'%'.$keywords.'%\'';
					$where .= $where ? ' or dizhi like \'%'.$keywords.'%\'' : ' dizhi like \'%'.$keywords.'%\'';
					$where .= $where ? ' or lxr like \'%'.$keywords.'%\'' : ' lxr like \'%'.$keywords.'%\'';
				}
	
				if(trim($_GET['L_1-1'])){
	
					if(trim($_GET['L_1-2'])){
						$where .= $where ? ' and diqu =\''.addslashes(trim($_GET['L_1-2'])).'\'' : ' diqu =\''.addslashes(trim($_GET['L_1-2'])).'\'';
					}else{
						$db_linkage = pc_base::load_model('linkage_model');
						$date_linkage = $db_linkage -> select(array('parentid'=>addslashes(trim($_GET['L_1-1']))),'linkageid');
						// 						echo implode(',',$date_linkage);
						$arr = array();
						foreach ($date_linkage as $k=>$v){
							$arr[$k] = $v[linkageid];
						}
						$where .= $where ? ' and diqu in ('.implode(',',$arr).')' : ' diqu in ('.implode(',',$arr).')' ;
					}
				}
	
				if(trim($_GET['status_mes'])){
					$status='';
					if(trim($_GET['status_mes'][1])){
						$status .= $status ? ' or status=1 ' :' status=1 ';
					}
					if(trim($_GET['status_mes'][2])){
						$status .= $status ? ' or status<>1 ' :' status<>1 ';
					}
					if(trim($_GET['status_mes'][1]) && trim($_GET['status_mes'][2])){
						$status = ' status in(1,2,3,4) ';
					}
					$where .= $where ? ' and '.$status : $status;
				}
			}
			$thisdb = get_cbandb('cban_mendian');
			$mendain_infor_list = $thisdb->cban_listinfo($where,'time asc',$_GET['page'],20);
			$pages = $thisdb->pages;
			include template('member', 'cban_mendian_infor_manage');
		}
	}

}

?>