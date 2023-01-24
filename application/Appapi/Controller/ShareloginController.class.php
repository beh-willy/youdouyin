<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------

namespace Appapi\Controller;
use Common\Controller\HomebaseController; 


class ShareloginController extends HomebaseController {
	

	function index() {
		$code = I("code");

		//获取用户的邀请码
		$user =M("users")->where("code='$code'")->field('id,code')->find();
		
		if($user){
			$code = $user['code'];
		}else{
			$code='';
		}
		
		$configpub=getConfigPub();
		$configpri=getConfigPri();


		//$this->assign("uid",$uid);
		$this->assign("code",$code);
		$this->assign("name_coin",$configpub['name_coin']);
		$this->assign("invite_tacket",$configpri['invite_tacket']);

    	$this->display();
    }

	
}