<?php
/**
 * VIP
 */
namespace Appapi\Controller;
use Common\Controller\HomebaseController;
class VipController extends HomebaseController {
	public $type=array(
		'1'=>'VIP会员',
		'2'=>'至尊VIP会员',
	);
	public $long=array(
		'1'=>'1个月',
		'3'=>'3个月',
		'6'=>'6个月',
		'12'=>'12个月',
	);	
	function index(){       
		$uid=I("uid");
		$token=I("token");
		
		if( !$uid || !$token || checkToken($uid,$token)==700 ){
			$this->assign("reason",'您的登陆状态失效，请重新登陆！');
			$this->display(':error');
			exit;
		} 
		$this->assign("uid",$uid);
		$this->assign("token",$token);
		
		$userinfo=getUserInfo($uid);
		$this->assign("userinfo",$userinfo);
		
		$config=getConfigPub();
		$this->assign("config",$config);
		$Vip=M("vip");
		$lists=$Vip->field("type")->group("type")->order("type asc")->select();
		
		foreach($lists as $k=>$v){
			$list=$Vip->where("type={$v['type']}")->order("orderno asc")->select();
			$lists[$k]['list']=$list;
		}
		
		$this->assign("lists",$lists);
		$this->assign("type",$this->type);
		$this->assign("long",$this->long);
		
		/* 用户VIP */
		$nowtime=time();
		$Users_vip=M("users_vip");
		
		$uservip=$Users_vip->where("uid={$uid} and endtime>{$nowtime}")->find();
		$this->assign("uservip",$uservip);
		
		$this->display();
	    
	}
	
	function buy(){
		$uid=I("uid");
		$token=I("token");
		$vipid=I("vipid");
		
		$rs=array('code'=>0,'info'=>array(),'msg'=>'购买成功');
		
		if( !$uid || !$token || checkToken($uid,$token)==700){
			$rs['code']=700;
			$rs['msg']='您的登陆状态失效，请重新登陆！';
			echo json_encode($rs);
			exit;
		} 

		$vipinfo=M("vip")->where("id={$vipid}")->find();
		if(!$vipinfo){
			$rs['code']=1001;
			$rs['msg']='VIP信息错误';
			echo json_encode($rs);
			exit;
		}
		$User=M('users');
		
		$userinfo=$User->field("coin")->where("id={$uid}")->find();
		
		$total=$vipinfo['coin'];
		$giftid=$vipinfo['id'];
		$addtime=time();
		$showid=0;
		$giftcount=$vipinfo['length'];
		$type=$vipinfo['type'];
		
		if($userinfo['coin'] < $total){
			$rs['code']=1002;
			$rs['msg']='余额不足';
			echo json_encode($rs);
			exit;
		}
		
		$endtime=$addtime+60*60*24*30*$giftcount;
		$Users_vip=M("users_vip");
		
		$uservip=$Users_vip->where("uid={$uid}")->find();
		
		if($uservip){
			if($uservip['endtime'] > $addtime){
				if($uservip['type'] > $type){
					$rs['code']=1003;
					$rs['msg']='已有高等级VIP，不能购买低级VIP';
					echo json_encode($rs);
					exit;
				}
				if($uservip['type'] == $type){
					$endtime=$uservip['endtime']+60*60*24*30*$giftcount;
				}
			}
			$data=array(
				'endtime'=>$endtime,
				'type'=>$type,
			);
			$Users_vip->where("uid={$uid}")->save($data);
		}else{
			
			$data=array(
				'uid'=>$uid,
				'addtime'=>$addtime,
				'endtime'=>$endtime,
				'type'=>$type,
			);
			$Users_vip->add($data);
		}
		
				/* 更新用户余额 消费 */
		M()->execute("update __PREFIX__users set coin=coin-{$total},consumption=consumption+{$total} where id='{$uid}'");

		/* 添加记录 */
		M("users_coinrecord")->add(array("type"=>'expend',"action"=>'buyvip',"uid"=>$uid,"touid"=>$uid,"giftid"=>$giftid,"giftcount"=>$giftcount,"totalcoin"=>$total,"showid"=>$showid,"addtime"=>$addtime ));	
		
		$result=$this->type[$type].' 到期:'.date("Y-m-d",$endtime);
		
		$rs['info']['endtime']=$result;
		echo json_encode($rs);
		exit;
	}

}