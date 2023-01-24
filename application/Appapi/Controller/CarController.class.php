<?php
/**
 * 坐骑
 */
namespace Appapi\Controller;
use Common\Controller\HomebaseController;
class CarController extends HomebaseController {
	
	function index(){       
		$uid=I("uid");
		$token=I("token");
		
		if(checkToken($uid,$token)==700){
			$this->assign("reason",'您的登陆状态失效，请重新登陆！');
			$this->display(':error');
			exit;
		} 
		
		$this->assign("uid",$uid);
		$this->assign("token",$token);
		
		/* 坐骑信息 */
		$Car=M("car");
		$carlist=$Car->order("orderno asc")->select();
		$this->assign("carlist",$carlist);
		
		/* 用户坐骑 */
		/* $Car_u=M("users_car");
		$user_carlist= */

		
		
		$this->display();
	    
	}
	
	function buy(){
		$uid=I("uid");
		$token=I("token");
		$carid=I("carid");
		
		$rs=array('code'=>0,'info'=>array(),'msg'=>'购买成功');
		
		if( !$uid || !$token || checkToken($uid,$token)==700){
			$rs['code']=700;
			$rs['msg']='您的登陆状态失效，请重新登陆！';
			echo json_encode($rs);
			exit;
		} 

		$carinfo=M("car")->where("id={$carid}")->find();
		if(!$carinfo){
			$rs['code']=1001;
			$rs['msg']='坐骑信息错误';
			echo json_encode($rs);
			exit;
		}
		
		$User=M('users');
		
		$userinfo=$User->field("coin")->where("id={$uid}")->find();
		
		$total=$carinfo['needcoin'];
		$giftid=$carinfo['id'];
		$addtime=time();
		$showid=0;
		$giftcount=1;

		
		if($userinfo['coin'] < $total){
			$rs['code']=1002;
			$rs['msg']='余额不足';
			echo json_encode($rs);
			exit;
		}
		
		$endtime=$addtime+60*60*24*30*$giftcount;
		$Users_car=M("users_car");
		
		$usercar=$Users_car->where("uid={$uid} and carid={$carid}")->find();
		
		if($usercar){
			if($usercar['endtime'] > $addtime){
				$endtime=$usercar['endtime']+60*60*24*30*$giftcount;
			}
			$data=array(
				'endtime'=>$endtime,
			);
			$Users_car->where("id={$usercar['id']}")->save($data);
		}else{
			$data=array(
				'uid'=>$uid,
				'addtime'=>$addtime,
				'endtime'=>$endtime,
				'carid'=>$carid,
			);
			$Users_car->add($data);
		}
		
		/* 更新用户余额 消费 */
		M()->execute("update __PREFIX__users set coin=coin-{$total},consumption=consumption+{$total} where id='{$uid}'");

		/* 添加记录 */
		M("users_coinrecord")->add(array("type"=>'expend',"action"=>'buycar',"uid"=>$uid,"touid"=>$uid,"giftid"=>$giftid,"giftcount"=>$giftcount,"totalcoin"=>$total,"showid"=>$showid,"addtime"=>$addtime ));	
		$rs['msg']='您已成功购买'.$carinfo['name'].'坐骑，请前往“装备中心”进行查看';
		echo json_encode($rs);
		exit;
	}
	/* 我的坐骑 */
	function mycar(){       
		$uid=I("uid");
		$token=I("token");
		
		if(checkToken($uid,$token)==700){
			$this->assign("reason",'您的登陆状态失效，请重新登陆！');
			$this->display(':error');
			exit;
		} 
		
		$this->assign("uid",$uid);
		$this->assign("token",$token);
		
		/* 坐骑信息 */
		$Car=M("car");
		$carlist2=array();
		$carlist=$Car->order("orderno asc")->select();
		foreach($carlist as $k=>$v){
			$carlist2[$v['id']]=$v;
		}

		/* 用户坐骑 */
		$nowtime=time();
		$Car_u=M("users_car");
		$user_carlist=$Car_u->where("uid={$uid} and endtime>{$nowtime}")->select();
		
		foreach($user_carlist as $k=>$v){
			if($carlist2[$v['carid']]){
				$user_carlist[$k]['carinfo']=$carlist2[$v['carid']];
			}else{
				unset($user_carlist[$k]);
			}
		}

		$this->assign("user_carlist",$user_carlist);
		
		$this->display();
	    
	}
	
	/* 装备坐骑 */
	function set(){
		$uid=I("uid");
		$token=I("token");
		$carid=I("carid");
		$status=I("status");
		
		$rs=array('code'=>0,'info'=>array(),'msg'=>'更换成功');
		
		if( !$uid || !$token || checkToken($uid,$token)==700){
			$rs['code']=700;
			$rs['msg']='您的登陆状态失效，请重新登陆！';
			echo json_encode($rs);
			exit;
		} 
		$setstatus=$status?0:1;
		
		$Car_u=M("users_car");

		$data1=array(
			'status'=>0,
		);
		$Car_u->where("uid={$uid}")->save($data1);

		
		$data=array(
			'status'=>$setstatus,
		);
		$result=$Car_u->where("uid={$uid} and carid={$carid}")->save($data);

		echo json_encode($rs);
		exit;
		
	}
}