<?php

/**
 * Ticket
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class TicketController extends AdminbaseController {

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

		 if($_REQUEST['type']){
			 $map['type']=$_REQUEST['type']; 
			 $_GET['type']=$_REQUEST['type'];
		 }

		 if($_REQUEST['status']){
			 $map['status']=$_REQUEST['status']; 
			 $_GET['status']=$_REQUEST['status'];
		 }

		 if($_REQUEST['uid']!=''){
			 $map['uid']=$_REQUEST['uid']; 
			 $_GET['uid']=$_REQUEST['uid'];
		 }

		 if($_REQUEST['keyword']!=''){
			 $map['account']=array("like","%".$_REQUEST['keyword']."%");
			 $_GET['keyword']=$_REQUEST['keyword'];
		 }
	
    	$ticket_model=M("users_coin_cashlist");
    	$count=$ticket_model->where($map)->count();
    	$page = $this->page($count, 20);
    	$lists = $ticket_model
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

    public function access(){

    	$id=intval($_GET['id']);

		if($id){

			$result=M("users_coin_cashlist")->where("id={$id}")->save(array("status"=>2,"updatetime"=>time()));				
			if($result!==false){
				$this->success('通过成功');
			}else{

				$this->error('通过失败');
			}			
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display();

    }

    public function refuse(){

    	$id=intval($_GET['id']);
		if($id){
			M()->startTrans();
			$result1 = M("users_coin_cashlist")->where("id={$id}")->save(array("status"=>3,"updatetime"=>time()));				
			if($result1!==false){

				$ticketinfo=M("users_coin_cashlist")->field("uid,coin,cate,money,addtime")->where("id={$id}")->find();
				//将用户的钱退给用户
				if ($ticketinfo['cate']==1) {
					$result2 = M("users")->where("id={$ticketinfo['uid']}")->setInc("votes",$ticketinfo['coin']);
					$msg ='现金提现';
				}else{
					$result2 = M("users")->where("id={$ticketinfo['uid']}")->setInc("coin",$ticketinfo['coin']);
					$msg ='金币提现';
				}
				if ($result2) {
					M()->commit();
					//向系统通知表中写入数据
				    		$sysInfo=array(
				    			'title'=>'提现失败提醒',
				    			'addtime'=>time(),
				    			'admin'=>'',
				    			'ip'=>$_SERVER['REMOTE_ADDR'],
				    			'uid'=>$ticketinfo['uid']

				    		);
				    		$baseMsg='您于'.date("Y-m-d H:i:s",$ticketinfo['addtime']).$msg.$ticketinfo['money'].'元的请求于'.date("Y-m-d H:i:s",time()).'审核为不通过,金额已退回您的账户';
				    		$sysInfo['content']=$baseMsg;
				    		M("system_push")->add($sysInfo);
					$this->success('成功拒绝');
				}
				M()->rollback();
				
				$this->error('拒绝失败');
			}else{
				M()->rollback();
				
				$this->error('拒绝失败');
			}			
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display();
    	
    }
    
		
				
    
    

}
