<?php

class Api_Bonus extends Api_Common {

	public function getRules() {
		return array(
			'setCash' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string',  'require' => true, 'desc' => '用户token'),
				'money' => array('name' => 'money', 'type' => 'string',  'require' => true, 'desc' => '钻石'),
				'type' => array('name' => 'type', 'type' => 'int',  'require' => true, 'desc' => '提现方式，1微信 2支付宝'),
				'account' => array('name' => 'account', 'type' => 'string',  'require' => true, 'desc' => '提现账户'),
				'signature' => array('name' => 'signature', 'type' => 'string',  'require'=>'true', 'desc' => '签名'),
				
			),

			'getCashLists'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string',  'require' => true, 'desc' => '用户token'),
				'p' => array('name' => 'p', 'type' => 'string',  'require' => true,'default'=>1, 'desc' => '提现账户'),
			),

			'setBonus' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string',  'require' => true, 'desc' => '用户token'),
				'money' => array('name' => 'money', 'type' => 'string',  'require' => true, 'desc' => '钻石'),
				'type' => array('name' => 'type', 'type' => 'int',  'require' => true, 'desc' => '提现方式，1微信 2支付宝'),
				'account' => array('name' => 'account', 'type' => 'string',  'require' => true, 'desc' => '提现账户'),
				'signature' => array('name' => 'signature', 'type' => 'string',  'require'=>'true', 'desc' => '签名'),
				
			),

			'getBonusLists'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string',  'require' => true, 'desc' => '用户token'),
				'p' => array('name' => 'p', 'type' => 'string',  'require' => true,'default'=>1, 'desc' => '提现账户'),
			),
			
		);
	}
	
	/**
	 * 金币提现
	 * @desc 金币提现
	 * @return int code 操作码，0表示成功
	 * @return string msg 提示信息
	 * @return array info 返回信息
	 */
	public function setCash(){
		
		$rs = array('code' => 0, 'msg' => '申请提现成功,请等待管理员审核', 'info' => array());
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		$money=$this->money;
		$type=$this->type;
		$account=$this->account;


		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		if($money==""||!is_numeric($money)){
			$rs['code']=1001;
			$rs['msg']="请填写提现金额";
			return $rs;

		}


		if($money<0){
			$rs['code']=1001;
			$rs['msg']="提现金额错误";
			return $rs;
		}

		/*if(floor($money)!=$money){
        	$rs['code']=1001;
			$rs['msg']="提现金额请填写整数";
			return $rs;
  		}*/

  		$money=floor($money*100)/100;


		if($type==""){
			$rs['code']=1001;
			$rs['msg']="请选择提现方式";
			return $rs;
		}

		if($account==""){
			$rs['code']=1001;
			$rs['msg']="请填写提现账户";
			return $rs;
		}


		$configpri=$this->getConfigPri();

		$len=strlen($configpri['signature']);
		$signature=$this->checkNull($this->signature);
		$newsign=md5(md5($account).'#d51251e410368a0'.$uid.$token.'d586e01'.substr($configpri['signature'],1,$len-2).'d5186e');

		if($newsign!=$signature){
			$rs['code'] = 1002;
        	return $rs;
		}


		$domain = new Domain_Bonus();
		$res=$domain->setCash($uid,$money,$type,$account);	

		if($res==1001){
			$rs['code']=1001;
			$rs['msg']="无金额可提现";
			return $rs;
		}

		if($res==1002){
			$rs['code']=1002;
			$rs['msg']="提现金额必须大于最低提现金额";
			return $rs;
		}

		if($res==1003){
			$rs['code']=1003;
			$rs['msg']="余额不足，无法提现";
			return $rs;
		}

		if($res==1004){
			$rs['code']=1004;
			$rs['msg']="提现失败";
			return $rs;
		}

		return $rs;

	}

	/**
	 * 获取金币提现记录
	 * @desc 获取金币提现记录
	 * @return int code 状态码，0表示成功
	 * @return string msg 提示信息
	 * @return array info 返回信息
	 * @return int info[0].id 信息id
	 * @return string info[0].type 提现类型（1微信 2支付宝）
	 * @return string info[0].account 提现账户
	 * @return string info[0].money 提现金额
	 * @return string info[0].addtime 提现时间
	 * @return string info[0].status 处理状态
	 */
	public function getCashLists(){
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		$p=$this->p;

		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		$domain = new Domain_Bonus();
		$res=$domain->getCashLists($uid,$p);
		if($res==1001){
			$rs['code']=1001;
			$rs['msg']="暂无提现记录";
			return $rs;
		}

		$rs['info']=$res;
		return $rs;

	}


	/**
	 * 分红提现
	 * @desc 金币提现
	 * @return int code 操作码，0表示成功
	 * @return string msg 提示信息
	 * @return array info 返回信息
	 */
	public function setBonus(){
		
		$rs = array('code' => 0, 'msg' => '申请提现成功,请等待管理员审核', 'info' => array());
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		$money=$this->money;
		$type=$this->type;
		$account=$this->account;

		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		if($money==""||!is_numeric($money)){
			$rs['code']=1001;
			$rs['msg']="请填写提现金额";
			return $rs;

		}

		if($money<0){
			$rs['code']=1001;
			$rs['msg']="提现金额错误";
			return $rs;
		}

		$money=floor($money*100)/100;

		if($type==""){
			$rs['code']=1001;
			$rs['msg']="请选择提现方式";
			return $rs;
		}

		if($account==""){
			$rs['code']=1001;
			$rs['msg']="请填写提现账户";
			return $rs;
		}

		$configpri=$this->getConfigPri();

		$len=strlen($configpri['signature']);
		$signature=$this->checkNull($this->signature);
		$newsign=md5(md5($account).'#d51251e410368a0'.$uid.$token.'d586e01'.substr($configpri['signature'],1,$len-2).'d5186e');

		if($newsign!=$signature){
			$rs['code'] = 1002;
        	return $rs;
		}
		

		$domain = new Domain_Bonus();
		$res=$domain->setBonus($uid,$money,$type,$account);	

		if($res==1001){
			$rs['code']=1001;
			$rs['msg']="无金额可提现";
			return $rs;
		}

		if($res==1002){
			$rs['code']=1002;
			$rs['msg']="提现金额必须大于最低提现金额";
			return $rs;
		}

		if($res==1003){
			$rs['code']=1003;
			$rs['msg']="余额不足，无法提现";
			return $rs;
		}

		if($res==1004){
			$rs['code']=1004;
			$rs['msg']="提现失败";
			return $rs;
		}

		return $rs;

	}


	/**
	 * 获取分红提现记录
	 * @desc 获取金币提现记录
	 * @return int code 状态码，0表示成功
	 * @return string msg 提示信息
	 * @return array info 返回信息
	 * @return int info[0].id 信息id
	 * @return string info[0].type 提现类型（1微信 2支付宝）
	 * @return string info[0].account 提现账户
	 * @return string info[0].money 提现金额
	 * @return string info[0].addtime 提现时间
	 * @return string info[0].status 处理状态
	 */
	public function getBonusLists(){
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		$p=$this->p;

		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		$domain = new Domain_Bonus();
		$res=$domain->getBonusLists($uid,$p);
		if($res==1001){
			$rs['code']=1001;
			$rs['msg']="暂无提现记录";
			return $rs;
		}

		$rs['info']=$res;
		return $rs;

	}



}
