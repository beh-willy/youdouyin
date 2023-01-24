<?php
/**
 * 关于红域短视频
 */
namespace Appapi\Controller;
use Common\Controller\HomebaseController;
use QCloud\Cos\Api;
use QCloud\Cos\Auth;
class AboutController extends HomebaseController {
	
	public function index(){
		
		$version=I("version");
		$uid=I("uid");
		$token=I("token");

		if(checkToken($uid,$token)==700){
			$this->assign("reason",'您的登陆状态失效，请重新登陆！');
			$this->display(':error');
			exit;
		}

		//获取网站标题
		$sitename=M("config")->where("id=1")->getField("sitename");
		$now=time();

		//获取关于我们分类下的文章列表
		$list=M("term_relationships")->order("listorder")->where("term_id=11")->select();
		$articleList=array();
		foreach ($list as $k => $v) {
			$info=M("posts")->field("id,post_title")->where("id={$v['object_id']}")->find();
			$articleList[]=$info;
		}

		//获取分类里id为13的分类名称
		$name=M("terms")->where("term_id=13")->getField("name");
		
		$this->assign("sitename",$sitename);
		$this->assign("time",$now);
		$this->assign("articleList",$articleList);
		$this->assign("name",$name);
		$this->assign("version",$version);
		$this->assign("uid",$uid);
		$this->assign("token",$token);
		$this->display();
	    
	}


	


		
}