<?php
/**
 * 靓号
 */
namespace Appapi\Controller;
use Common\Controller\HomebaseController;
class LiangController extends HomebaseController {
	
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
		
		$config=getConfigPub();
		$this->assign("config",$config);
		/* 靓号信息 */
		$Liang=M("liang");
		$lianglist=$Liang->field("length")->group("length")->order("length asc")->select();
		foreach($lianglist as $k=>$v){
			$list=$Liang->where("status=0 and length={$v['length']}")->order("rand()")->limit(0,5)->select();
			$lianglist[$k]['list']=$list;
		}
		$this->assign("lianglist",$lianglist);
		
		
		$this->display();
	    
	}
	
	function replace(){
		$rs=array('code'=>0,'info'=>array(),'msg'=>'');
		$length=I("length");
		$Liang=M("liang");
		$list=$Liang->where("status=0 and length={$length}")->order("rand()")->limit(0,5)->select();
		$rs['info']=$list;
		
		echo json_encode($rs);
		exit;
	}
	
	function buy(){
		$uid=I("uid");
		$token=I("token");
		$liangid=I("liangid");
		
		$rs=array('code'=>0,'info'=>array(),'msg'=>'购买成功');
		
		if( !$uid || !$token || checkToken($uid,$token)==700){
			$rs['code']=700;
			$rs['msg']='您的登陆状态失效，请重新登陆！';
			echo json_encode($rs);
			exit;
		} 
		$Liang=M("liang");
		
		$lianginfo=$Liang->where("id={$liangid}")->find();
		if(!$lianginfo){
			$rs['code']=1001;
			$rs['msg']='靓号信息错误';
			echo json_encode($rs);
			exit;
		}
		
		if($lianginfo['status']==1){
			$rs['code']=1003;
			$rs['msg']='该靓号已出售';
			echo json_encode($rs);
			exit;
		}
		if($lianginfo['status']==2){
			$rs['code']=1003;
			$rs['msg']='该靓号已下架';
			echo json_encode($rs);
			exit;
		}
		
		$User=M('users');
		
		$userinfo=$User->field("coin")->where("id={$uid}")->find();
		
		$total=$lianginfo['coin'];
		$giftid=$lianginfo['id'];
		$addtime=time();
		$showid=0;
		$giftcount=1;

		
		if($userinfo['coin'] < $total){
			$rs['code']=1002;
			$rs['msg']='余额不足';
			echo json_encode($rs);
			exit;
		}

		/* 更新用户余额 消费 */
		M()->execute("update __PREFIX__users set coin=coin-{$total},consumption=consumption+{$total} where id='{$uid}'");

		/* 添加记录 */
		M("users_coinrecord")->add(array("type"=>'expend',"action"=>'buyliang',"uid"=>$uid,"touid"=>$uid,"giftid"=>$giftid,"giftcount"=>$giftcount,"totalcoin"=>$total,"showid"=>$showid,"addtime"=>$addtime ));
		
		$data=array(
			'uid'=>$uid,
			'status'=>1,
			'buytime'=>$addtime,
		);
		$lianginfo=$Liang->where("id={$liangid}")->save($data);
		
		//$rs['msg']='您已成功购买'.$carinfo['name'].'坐骑，请前往“装备中心”进行查看';
		echo json_encode($rs);
		exit;
	}
	
	function myliang(){       
		$uid=I("uid");
		$token=I("token");
		
		if(checkToken($uid,$token)==700){
			$this->assign("reason",'您的登陆状态失效，请重新登陆！');
			$this->display(':error');
			exit;
		} 
		
		$this->assign("uid",$uid);
		$this->assign("token",$token);
		
		$config=getConfigPub();
		$this->assign("config",$config);
		/* 靓号信息 */
		$Liang=M("liang");
		$lianglist=$Liang->field("length")->group("length")->order("length asc")->select();
		foreach($lianglist as $k=>$v){
			$list=$Liang->where("length={$v['length']} and uid={$uid}")->select();
			$lianglist[$k]['list']=$list;
		}
		$this->assign("lianglist",$lianglist);

		$this->display();
	    
	}
	
	function set(){
		$rs=array('code'=>0,'info'=>array(),'msg'=>'更换成功');
		$uid=I("uid");
		$token=I("token");
		$liangid=I("liangid");
		$state=I("state");
		$goodnum=I("goodnum");
		
		if( !$uid || !$token || checkToken($uid,$token)==700){
			$rs['code']=700;
			$rs['msg']='您的登陆状态失效，请重新登陆！';
			echo json_encode($rs);
			exit;
		} 
		$Liang=M("liang");
		$Liang->where("uid={$uid}")->save(array('state'=>0) );
		
		$setstatus=$state?0:1;
		$data=array(
			'state'=>$setstatus,
		);
		$list=$Liang->where("uid={$uid} and id={$liangid}")->save( $data );
		$Users=M("users");
		if($setstatus==1){
			$Users->where("id={$uid}")->setField("goodnum",$goodnum);
		}else{
			$Users->where("id={$uid}")->setField("goodnum",0);
		}
		
		echo json_encode($rs);
		exit;
	}
}