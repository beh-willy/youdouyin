<?php
/**
 * 联系客服
 */
namespace Appapi\Controller;
use Common\Controller\HomebaseController;

class ServiceController extends HomebaseController {
	
	public function index(){
		

		//获取网站标题
		$sitename=M("config")->where("id=1")->getField("sitename");
		$now=time();

		//获取配置信息中的客服QQ和客服电话
		$info=M("config")->where("id=1")->field("qq,mobile")->find();
		
		$this->assign("sitename",$sitename);
		$this->assign("time",$now);
		$this->assign("info",$info);

		$this->display();
	    
	}


	


		
}