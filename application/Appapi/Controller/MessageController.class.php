<?php
/**
 * 推送消息
 */
namespace Appapi\Controller;
use Common\Controller\HomebaseController;
class MessageController extends HomebaseController {
	
	/*官方推送信息详情*/
	public function msginfo(){
		$id=I("id");
		
		//判断该信息是否存在
		$info=M("admin_push")->where("id={$id}")->find();

		if(!$info){
			$this->assign("reason",'信息不存在');
			$this->display(':error');
			exit;
		}

		$this->assign("info",$info);
		
		$this->display();
	    
	}

}