<?php
/****
**兑换：金币、观看时长
***/
class Api_Exchange extends Api_Common {

	public function getRules() {
		return array(
            'getVotesAndLike' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => 'token'),
            ),
			'getLooktimesAndCoin' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => 'token'),
            ),
			'showExchangevotes' => array(
				'unexchange_like' => array('name' => 'unexchange_like', 'type' => 'int', 'desc' => '剩余金币'),
				'changeset' => array('name' => 'changeset', 'type' => 'int', 'desc' => '兑换一金币需要点赞数量'),
            	'votes' => array('name' => 'votes', 'type' => 'int', 'desc' => '总金币'),
            ),
			'changelikeTovotes' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => 'token'),
				'type' => array('name' => 'type', 'type' => 'int', 'desc' => '兑换类型：0：金币兑换；1：观看时长兑换'),
            	'votes' => array('name' => 'votes', 'type' => 'int', 'desc' => '要兑换的金币数量'),
            ),
			'getExchangeRecord' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => 'token'),
				'type' => array('name' => 'type', 'type' => 'int', 'desc' => '兑换类型：0：金币兑换；1：观看时长兑换'),
            ),
		);
	}
	/**
     * 获取映票信息
     * @desc 用于用户兑换映票显示映票信息
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function getVotesAndLike() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
	
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		
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

        $domain = new Domain_Exchange();
        $result = $domain->getVotesAndLike($uid);
		$rs['msg'] = '兑换一个金币需要点赞数量为：'.$result['changeset'];
		$rs['info']=$result;
        return $rs;
    }	
	/**
     * 获取观看时间信息
     * @desc 用于用户兑换映票显示映票信息
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function getLooktimesAndCoin() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
	
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		
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

        $domain = new Domain_Exchange();
        $result = $domain->getLooktimesAndCoin($uid);
		$rs['msg'] = '兑换一个钻石需要观看时长为：'.$result['changeset']."分钟";
		$rs['info']=$result;
        return $rs;
    }	
	/**
     * 兑换界面输入兑换的映票返回的信息集合
     * @desc 用于用户兑换映票显示映票信息
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function showExchangevotes() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		$isblackuser=$this->isBlackUser($this->uid);
		
		if($isblackuser==0){
			$rs['code']=10020;
			$rs['msg']='账号已被禁用';
			return $rs;
		}
		$unexchange_like=$this->unexchange_like;
		 
		$changeset=$this->changeset;
		$votes=$this->checkNull($this->votes);
	 

        $domain = new Domain_Exchange();
        $result = $domain->showExchangevotes($votes,$unexchange_like,$changeset);
		if($result==1001){
			$rs['code'] = 1001;
			$rs['msg'] = '剩余点赞数量不足兑换输入的金币数量';
			return $rs;
		}
		$rs['info'][0]=$result;
        return $rs;
    }
		/**
     * 金币兑换映票
     * @desc 用于用户兑换映票显示映票信息
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function changelikeTovotes() {
        $rs = array('code' => 0, 'msg' => '兑换成功', 'info' => array());
		
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		
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
		$votes=$this->votes;
		$type=$this->type;
	
	 

        $domain = new Domain_Exchange();
        $result = $domain->changelikeTovotes($uid,$votes,$type);
		if($result==1001){
			$rs['code'] = 1001;
			$rs['msg'] = '用户不存在';
			return $rs;
		}else if($result==1002){
			$rs['code'] = 1002;
			if($type==1){
				$rs['msg'] = '剩余观看时长不足兑换输入的钻石数量';	
			}else{
				$rs['msg'] = '剩余点赞数量不足兑换输入的金币数量';
			}
			
			return $rs;
		}else if($result===false){
			$rs['code'] = 1003;
			$rs['msg'] = '兑换失败';
			return $rs;
		}
		$rs['info'][0]=$result;

        return $rs;
    }
	/**
     * 兑换记录
     * @desc 用于用户兑换映票显示映票信息
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function getExchangeRecord() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$type=$this->type;
		$token=$this->checkNull($this->token);
		
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
	
        $domain = new Domain_Exchange();
        $result = $domain->getExchangeRecord($uid,$type);
		
		$rs['info']=$result;
        return $rs;
    }

}
