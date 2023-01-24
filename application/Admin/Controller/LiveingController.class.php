<?php

/**
 * 直播记录
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class LiveingController extends AdminbaseController {
    function index(){
			$config=M("config")->where("id='1'")->find();
			$map=array();
			$map['islive']=1;
		   if($_REQUEST['start_time']!=''){
				  $map['starttime']=array("gt",strtotime($_REQUEST['start_time']));
					$_GET['start_time']=$_REQUEST['start_time'];
			 }
			 
			 if($_REQUEST['end_time']!=''){
				 
				   $map['starttime']=array("lt",strtotime($_REQUEST['end_time']));
					 $_GET['end_time']=$_REQUEST['end_time'];
			 }
			 if($_REQUEST['start_time']!='' && $_REQUEST['end_time']!='' ){
				 
				 $map['starttime']=array("between",array(strtotime($_REQUEST['start_time']),strtotime($_REQUEST['end_time'])));
				 $_GET['start_time']=$_REQUEST['start_time'];
				 $_GET['end_time']=$_REQUEST['end_time'];
			 }

			 if($_REQUEST['keyword']!=''){
				 $map['uid']=$_REQUEST['keyword']; 
				 $_GET['keyword']=$_REQUEST['keyword'];
			 }
			
	
			
    	$live=M("users_live");
    	$count=$live->where($map)->count();
    	$page = $this->page($count, 20);
    	$lists = $live
    	->where($map)
    	->order("starttime DESC")
    	->limit($page->firstRow . ',' . $page->listRows)
    	->select();
			
			foreach($lists as $k=>$v){
				 $userinfo=M("users")->field("user_nicename")->where("id='{$v['uid']}'")->find();
				 $lists[$k]['userinfo']=$userinfo;
			}
			
    	$this->assign('config', $config);
    	$this->assign('lists', $lists);
    	$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display();
    }
	
	function del(){
		$uid=intval($_GET['uid']);
		if($uid){
			$result=M("users_live")->where("uid={$uid}")->delete();				
				if($result){
						$this->success('删除成功');
				 }else{
						$this->error('删除失败');
				 }			
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display();				
	}		

    
	function add(){
			$this->display();				
	}	
	function add_post(){
		if(IS_POST){	
			$nowtime=time();
			$uid=(int)$_POST['uid'];
			$pull=$_POST['pull'];
			$type=$_POST['type'];
			$type_val=$_POST['type_val'];
			
			$User=M("users");
			$live=M("users_live");
			
			$userinfo=$User->field("user_nicename,avatar,avatar_thumb")->where("id={$uid}")->find();
			if(!$userinfo){
				$this->error('用户不存在');
			}
			
			$liveinfo=$live->field('uid,islive')->where("uid={$uid}")->find();
			if($liveinfo['islive']==1){
				$this->error('该用户正在直播');
			}
			
			$title='';
			$stream=$uid.'_'.$nowtime;
			$data=array(
				"uid"=>$uid,
				"user_nicename"=>$userinfo['user_nicename'],
				"avatar"=>$userinfo['avatar'],
				"avatar_thumb"=>$userinfo['avatar_thumb'],
				"showid"=>$nowtime,
				"starttime"=>$nowtime,
				"title"=>$title,
				"province"=>'',
				"city"=>'好像在火星',
				"stream"=>$stream,
				"thumb"=>'',
				"pull"=>$pull,
				"lng"=>'0',
				"lat"=>'0',
				"type"=>$type,
				"type_val"=>$type_val,
				"isvideo"=>1,
				"islive"=>1,
			);	
		
			 if($liveinfo){
				$result=$live->where("uid={$uid}")->save($data); 
			 }else{
				$result=$live->add($data); 
			 }
			 
			 if($result){
				  $this->success('添加成功');
			 }else{
				  $this->error('添加失败');
			 }
		}			
	}		
	function edit(){
		$uid=intval($_GET['uid']);
		if($uid){
			$live=M("users_live")->where("uid={$uid}")->find();
			$this->assign('live', $live);						
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display();				
	}
	
	function edit_post(){
		if(IS_POST){			
			 $live=M("users_live");
			 $live->create();
			 $result=$live->save(); 
			 if($result){
				  $this->success('修改成功');
			 }else{
				  $this->error('修改失败');
			 }
		}			
	}
		
}
