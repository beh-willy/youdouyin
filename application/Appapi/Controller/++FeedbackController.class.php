<?php
/**
 * 用户反馈
 */
namespace Appapi\Controller;
use Common\Controller\HomebaseController;

class FeedbackController extends HomebaseController{
	
	function index(){
		 $uid=I("uid");
		 $version=I("version");
		 $model=I("model");
		 $this->assign("uid",$uid);
		 $this->assign("version",$version);
		 $this->assign("model",$model);
		 $this->display();
	}
	
	function feedbackSave(){
		$data['uid']=I('uid');
		$data['version']=checkNull(I('version'));
		$data['model']=checkNull(I('model'));
		$data['content']=checkNull(I('content'));
		$data['addtime']=time();

		$result=M("users_feedback")->add($data);
		if($result){
				echo json_encode(array("status"=>0,'msg'=>''));
		}else{
			 	echo json_encode(array("status"=>400,'errormsg'=>'提交失败'));
		}
	
	}	
}