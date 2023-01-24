<?php
/**
 * 会员认证
 */
namespace Appapi\Controller;
use Common\Controller\HomebaseController;
use QCloud\Cos\Api;
use QCloud\Cos\Auth;
class AuthController extends HomebaseController {
	
	public function index(){
		$uid=I('uid');
		$token=I("token");       
		if(checkToken($uid,$token)==700){
			$this->assign("reason",'您的登陆状态失效，请重新登陆！');
			$this->display(':error');
			exit;
		}       
		      
		$this->assign("uid",$uid);
			 
		$auth=M("users_auth")->where("uid='{$uid}'")->find();

		if($auth){

			if($auth['status']==1){
				$this->assign("auth",$auth);
				$this->redirect('Auth/success', array('uid' => $uid));
				exit;
			}

		}

		$time=time();
		$this->assign("time",$time);
		$this->display();
	    
	}


	function success(){
		$uid=I("uid");
		$auth=M("users_auth")->where("uid={$uid}")->find();
		$this->assign("auth",$auth);
		$time=time();
		$this->assign("time",$time);
		$this->display();
	}


	/*认证保存*/
	public function auth_save(){

		$rs=array("code"=>0,"msg"=>"","info"=>array());
		$uid=I("uid");
		$realname=I("realname");
		$phone=I("phone");
		$cardno=I("cardno");

		if($realname==""){
			$rs['code']=1001;
			$rs['msg']="请填写真实姓名";
			return json_encode($rs);
		}

		if(is_numeric($realname)){
			$rs['code']=1001;
			$rs['msg']="请填写正确格式的姓名";
			return json_encode($rs);
		}

		if(mb_strlen($realname)>4){
			$rs['code']=1001;
			$rs['msg']="请填写正确格式的姓名";
			return json_encode($rs);
		}


		if($phone==""||!is_numeric($phone)){
			$rs['code']=1002;
			$rs['msg']="请填写正确的手机号码";
			return json_encode($rs);
		}

		if($cardno==""){
			$rs['code']=1002;
			$rs['msg']="请填写正确的身份证号";
			return json_encode($rs);
		}


		$data['uid']=$uid;
		$data['real_name']=$realname;
		$data['mobile']=$phone;
		$data['cer_no']=$cardno;
		$data['status']=1;
		$data['addtime']=time();

		$authid=M("users_auth")->where("uid='{$uid}'")->getField("uid");

		if($authid){
			$result=M("users_auth")->where("uid='{$authid}'")->save($data);
		}else{
			$result=M("users_auth")->add($data);
		}

		if($result!==false){
			$rs['msg']="认证成功";
			
		}else{
			
			$rs['code']=1003;
			$rs['msg']="认证失败，请重新提交";
			
		}

		echo json_encode($rs);

	}	
}