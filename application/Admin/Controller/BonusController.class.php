<?php

/**
 * 分红管理
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;


class BonusController extends AdminbaseController {

	/*分类添加*/
	function add(){

		$this->display();
	}

	/*添加提交*/
	function add_post(){

		$res=array("code"=>0,"msg"=>"","info"=>array());

		$revenue=I("revenue");
		if($revenue==""||!is_numeric($revenue)){
			$res['code']=1001;
			$res['msg']='广告金额有误';
			echo json_encode($res);
			exit;
		}

		if($revenue<=0){
			$res['code']=1001;
			$res['msg']='广告金额有误';
			echo json_encode($res);
			exit;
		}

		$id=$_SESSION['ADMIN_ID'];
		$user=M("Users")->where("id='{$id}'")->find();

		//获取可分红用户总数
		$count=M("users")->where("user_type=2 and user_status=1 and coin>0")->count();


		if($count>0){

			//向分红发放记录表中写入数据
			$data=array(
				"revenue"=>$revenue,
				"addtime"=>time(),
				"admin"=>$user['user_login']
			);

			$result=M("users_revenue")->add($data);
			if($result==false){
				$res['code']=1001;
				$res['msg']="分红记录写入失败";
				echo json_encode($res);
				exit;
			}else{

				$data=array();
				$data['id']=$result;
				
				$total=M("users")->where("user_type=2 and user_status=1 and coin>0")->sum("coin");
				
				$data['count']=$count;
				$data['total']=$total;
				$res['info']=$data;


				echo json_encode($res);
				exit;
			}

		}else{

			$res['code']=1002;
			$res['msg']='无可分红用户';
			echo json_encode($res);
			exit;
		}



	}


	function sendBonus(){
		$lastid=I("lastid");
		$num=I("num");
		$msgid=I("msgid");
		$total=I("total");

		$rs=array("code"=>0,"msg"=>"","info"=>array(),"zong"=>0);

		//获取分红发放记录信息
		$bonusInfo=M("users_revenue")->where("id={$msgid}")->find();

		//file_put_contents("a.txt", "lastid".$lastid.PHP_EOL,FILE_APPEND);//换行追加

		
		//查询用户
		$userLists=M("users")->field("id,coin")->where("user_type=2 and user_status=1 and coin>0 and id>{$lastid} ")->order("id asc")->limit($num)->select();
		foreach ($userLists as $k => $v) {
			//计算用户可获得分红
			$bonus=floor($bonusInfo['revenue']/$total*$v['coin']*100)/100;

			//file_put_contents("c.txt", "用户id：".$v['id'].",分成：".$bonus.PHP_EOL,FILE_APPEND);//换行追加

			if($bonus>0.01){
				//向分红记录表中写入数据
				$data=array(
					"uid"=>$v['id'],
					"revenue_id"=>$msgid,
					"coin"=>$v['coin'],
					"bonus"=>$bonus,
					"addtime"=>time(),
				);

				$result=M("users_bonus_income")->add($data);

				if($result!==false){
					//扣除用户的钻石数
					M("users")->where("id={$v['id']}")->setDec('coin',$v['coin']);
					//增加用户的钱数
					M("users")->where("id={$v['id']}")->setInc('money',$bonus);
				}

				$lastid=$v['id'];
			}

		}

		//file_put_contents("b.txt", "lastid：".$lastid.PHP_EOL,FILE_APPEND);//换行追加

		$rs['info']=$lastid;


		echo json_encode($rs);
		exit;

	}




	/*分红记录*/
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

		
		
    	$revenue=M("users_revenue");
    	$count=$revenue->where($map)->count();
    	$page = $this->page($count, 20);
    	$lists = $revenue
			->where($map)
			->order("addtime desc")
			->limit($page->firstRow . ',' . $page->listRows)
			->select();

		//var_dump($music->getLastSql());



    	$this->assign('lists', $lists);
    	$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display();
    }

    function userrevenue(){

    	$id=I("id");

    	$map=array();
    	$map['revenue_id']=$id;

    	if($_REQUEST['keyword']!=''){
			$map['uid']=$_REQUEST['keyword']; 
			$_GET['keyword']=$_REQUEST['keyword'];
		 }

		 $revenue=M("users_bonus_income");
		 $count=$revenue->where($map)->count();
		 $page = $this->page($count, 20);
		 $lists = $revenue
			->where($map)
			->order("addtime desc")
			->limit($page->firstRow . ',' . $page->listRows)
			->select();

			//var_dump($revenue->getLastsql());

		foreach ($lists as $k => $v) {
    		$user=M("users")->where("id={$v['uid']}")->field("user_nicename,id")->find();
    		if(!$user){
    			$lists[$k]['userinfo']=array("user_nicename"=>"该用户已删除");
    		}
    		$lists[$k]['userinfo']=$user;
    	}


		$configPub=getConfigPub();
    	$this->assign("ticketName", $configPub['name_coin']);
		$this->assign('lists', $lists);
    	$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	$this->assign("revenue_id", $id);

    	$this->display();

    }

    /*分红提现记录*/
    function bonusforward(){

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

    	$cashlist=M("users_bonus_cashlist");
    	$count=$cashlist->where($map)->count();
    	$page = $this->page($count, 20);
    	$lists = $cashlist
			->where($map)
			->order("addtime desc")
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
    	$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display();
    }


     public function access(){

    	$id=intval($_GET['id']);

		if($id){
			$result=M("users_bonus_cashlist")->where("id={$id}")->save(array("status"=>2,"updatetime"=>time()));				
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
			$result=M("users_bonus_cashlist")->where("id={$id}")->save(array("status"=>3,"updatetime"=>time()));				
			if($result!==false){

				$ticketinfo=M("users_bonus_cashlist")->field("uid,money")->where("id={$id}")->find();
				//将用户的钱退给用户
				M("users")->where("id={$ticketinfo['uid']}")->setInc("money",$ticketinfo['money']);

				$this->success('成功拒绝');
			}else{

				$this->error('拒绝失败');
			}			
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display();
    	
    }



}
