<?php

/**
 * 兑换管理：映票、钻石
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController; 
class ExchangeController extends AdminbaseController {
	/**映票兑换列表**/
    function index(){

		$map=array();
		$map['type']='0'; 
		
		if($_REQUEST['status']!=''){
			$map['status']=$_REQUEST['status']; 
			$_GET['status']=$_REQUEST['status'];
		}

		if($_REQUEST['keyword']!=''){
			$map['uid']=array("like","%".$_REQUEST['keyword']."%");  
			$_GET['keyword']=$_REQUEST['keyword'];
		}
		
    	$exchange=M("exchange");
    	$Users=M("users");
    	$count=$exchange->where($map)->count();
    	$page = $this->page($count, 20);
    	$lists = $exchange
			->where($map)
			->order("addtime desc")
			->limit($page->firstRow . ',' . $page->listRows)
			->select();
		foreach($lists as $k=>$v){
			$userinfo=$Users->field("user_nicename")->where("id='{$v[uid]}'")->find();
			if(!$userinfo){
				$userinfo=array(
					'user_nicename'=>'已删除'
				);
			}
			$lists[$k]['userinfo']=$userinfo;
		}
		
    	$this->assign('lists', $lists);
    	$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display();
    }
	/**钻石兑换列表**/
    function coinindex(){

		$map=array();
		$map['type']='1'; 
		
		if($_REQUEST['status']!=''){
			$map['status']=$_REQUEST['status']; 
			$_GET['status']=$_REQUEST['status'];
		}

		if($_REQUEST['keyword']!=''){
			$map['uid']=array("like","%".$_REQUEST['keyword']."%");  
			$_GET['keyword']=$_REQUEST['keyword'];
		}
		
    	$dialect=M("exchange");
    	$Users=M("users");
    	$count=$dialect->where($map)->count();
    	$page = $this->page($count, 20);
    	$lists = $dialect
			->where($map)
			->order("addtime desc")
			->limit($page->firstRow . ',' . $page->listRows)
			->select();
		foreach($lists as $k=>$v){
			$userinfo=getUserInfo($v['uid']);
			if(!$userinfo){
				$userinfo=array(
					'user_nicename'=>'已删除'
				);
			}
			$lists[$k]['userinfo']=$userinfo;
		}
    	$this->assign('lists', $lists);
    	$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display();
    }
	/**删除兑换记录*/
	function del(){
		$id=intval($_GET['id']);
		if($id){
			$result=M("exchange")->where("id={$id}")->delete();				
			if($result!==false){
				$this->success('删除成功');
			}else{
				$this->error('删除失败');
			}			
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display();				
	}	
	
	function edit(){
		$id=intval($_GET['id']);
		$type=intval($_GET['type']);//类型：0：金币兑换映票；1：观看时长兑换钻石
		if($id){
			$exchangeinfo=M("exchange")->where("id={$id}")->find();
			$userinfo=getUserInfo($exchangeinfo['uid']);
			$exchangeinfo['userinfo']=$userinfo;
			
			$this->assign('urgeinfo', $exchangeinfo);						
			$this->assign('type', $type);						
		}else{				
			$this->error('数据传入失败！');
		}
		$this->display();				
	}
	///映票兑换
	function edit_post(){
		if(IS_POST){
			$exchange=M("exchange");
		 
			$id=I('id');
			$uid=I('uid');
			$status=I('status');
			$likenums=I('likenums');//点赞数量
			$votenums=I('votenums');//兑换映票数量
		
			if($id){
				if($status==1){//处理成功
					M("users")->where("id='".$uid."'")->setInc("votes",$votenums);
					//添加消费表的兑换记录
					/* 更新直播 映票 累计映票 */
					M("users_coinrecord")->add(array("type"=>'income',"action"=>'votechange',"uid"=>$uid,"touid"=>$uid,"giftid"=>"0","giftcount"=>"0","totalcoin"=>$votenums,"showid"=>"0","addtime"=>time() ));	
				}else if($status==2){//处理失败
				    M("users")->where("id='".$uid."'")->setInc("unexchange_like",$likenums);
				}
				$data['status']=$status;
				$data['uptime']=time();
				$result=$exchange->where("id='{$id}'")->save($data);				
				if($result!==false){
					$this->success('处理成功');
				}else{
					$this->error('处理失败');
				}			
			}else{				
				$this->error('数据传入失败！');
			}		
		}			
	}
	///钻石兑换
	
	function editcoin(){
		$id=intval($_GET['id']);
		$type=intval($_GET['type']);//类型：0：票房兑换映票；1：观看时长兑换钻石
		if($id){
			$exchangeinfo=M("exchange")->where("id={$id}")->find();
			$userinfo=getUserInfo($exchangeinfo['uid']);
			$exchangeinfo['userinfo']=$userinfo;
			
			$this->assign('urgeinfo', $exchangeinfo);						
			$this->assign('type', $type);						
		}else{				
			$this->error('数据传入失败！');
		}
		$this->display();				
	}
	function edit_coinpost(){
		if(IS_POST){
			$exchange=M("exchange");
		 
			$id=I('id');
			$uid=I('uid');
			$status=I('status');
			$likenums=I('likenums');//观看时长
			$votenums=I('votenums');//钻石数量
		
			if($id){
				if($status==1){//处理成功
					M("users")->where("id='".$uid."'")->setInc("coin",$votenums);
				}else if($status==2){//处理失败
				    M("users")->where("id='".$uid."'")->setInc("looktimes",$likenums);
				}
				$data['status']=$status;
				$data['uptime']=time();
				$result=$exchange->where("id='{$id}'")->save($data);				
				if($result!==false){
					$this->success('处理成功');
				}else{
					$this->error('处理失败');
				}			
			}else{				
				$this->error('数据传入失败！');
			}		
		}			
	}
	
}
