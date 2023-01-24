<?php

/**
 * 点赞兑换金币
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class PraiseController extends AdminbaseController {

    function index(){
		
		$map=array();

		if($_REQUEST['start_time']!=''){
			  $map['addtime']=array("gt",strtotime($_REQUEST['start_time']));
				$_GET['start_time']=$_REQUEST['start_time'];
		 }
		 
		 if($_REQUEST['end_time']!=''){
			 
			   $map['addtime']=array("lt",strtotime($_REQUEST['end_time']));
				 $_GET['end_time']=$_REQUEST['end_time'];
		 }
		 if($_REQUEST['start_time']!='' && $_REQUEST['end_time']!='' ){
			 
			 $map['addtime']=array("between",array(strtotime($_REQUEST['start_time']),strtotime($_REQUEST['end_time'])));
			 $_GET['start_time']=$_REQUEST['start_time'];
			 $_GET['end_time']=$_REQUEST['end_time'];
		 }

		 if($_REQUEST['keyword']!=''){
			 $map['uid']=array("like","%".$_REQUEST['keyword']."%");
			 $_GET['keyword']=$_REQUEST['keyword'];
		 }

    	$model=M("users_praise_changecoin");
    	$count=$model->where($map)->count();
    	$page = $this->page($count, 20);
    	$lists = $model
    	->where($map)

    	->order("addtime DESC")
    	->limit($page->firstRow . ',' . $page->listRows)
    	->select();
    	foreach ($lists as $k => $v) {
    		$user=M("users")->where("id={$v['uid']}")->field("user_nicename,id")->find();
    		if(!$user){
    			$lists[$k]['userinfo']=array("user_nicename"=>"该用户已删除");
    		}
    		$lists[$k]['userinfo']=$user;
    	}

    	$this->assign('lists', $lists);
    	$this->assign("page", $page->show('Admin'));
    	$this->assign("formget", $_GET);

    	$configPub=getConfigPub();
    	$this->assign("ticketName", $configPub['name_coin']);
    	
    	$this->display();
    }
		
		
}
