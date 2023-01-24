<?php

class Domain_Common {

	public function getConfigPub() {
		$rs = array();
		$model = new Model_Common();
		$rs = $model->getConfigPub();
		return $rs;
	}
	
	public function getConfigPri() {
		$rs = array();
		$model = new Model_Common();
		$rs = $model->getConfigPri();
		return $rs;
	}
	
	public function checkToken($uid,$token) {
		$rs = array();
		$model = new Model_Common();
		$rs = $model->checkToken($uid,$token);
		return $rs;
	}

	/* 敏感词语屏蔽 */
	public function word_shield($txt) {
		
		$rs = array();
		$model = new Model_Common();
		$info = $model->word_shield($txt);				
	
		
		return $info;
	}

	public function getUserInfo($uid) {
		$rs = array();
		$model = new Model_Common();
		$rs = $model->getUserInfo($uid);
		return $rs;
	}
	
	public function isAttention($uid,$touid) {
		$rs = array();
		$model = new Model_Common();
		$rs = $model->isAttention($uid,$touid);
		return $rs;
	}
	
	public function isBlack($uid,$touid) {
		$rs = array();
		$model = new Model_Common();
		$rs = $model->isBlack($uid,$touid);
		return $rs;
	}

	/* 视频是否购买 */
	public function ifBuy($uid,$videoid){
		$rs = array();
		$model = new Model_Common();
		$rs = $model->ifBuy($uid,$videoid);
		return $rs;
	}

	public function isAdmin($uid,$liveuid) {
		$rs = array();
		$model = new Model_Common();
		$rs = $model->isAdmin($uid,$liveuid);
		return $rs;
	}
	public function getLevel($experience) {
		$rs = array();
		$model = new Model_Common();
		$rs = $model->getLevel($experience);
		return $rs;
	}
	public function getLevelAnchor($experience) {
		$rs = array();
		$model = new Model_Common();
		$rs = $model->getLevelAnchor($experience);
		return $rs;
	}

	 /* 直播分类 */
    public function getLiveClass(){

    	$domain = new Model_Common();
		$rs = $domain->getLiveClass();
		return $rs;
    }
	
	public function isBan($uid) {
		$rs = array();
		$model = new Model_Common();
		$rs = $model->isBan($uid);
		return $rs;
	}
	public function isAuth($uid) {
		$rs = array();
		$model = new Model_Common();
		$rs = $model->isAuth($uid);
		return $rs;
	}
	
	public function LoginBonus($uid,$token){
		$rs = array();
		$model = new Model_Common();
		$rs = $model->LoginBonus($uid,$token);
		return $rs;

	}

	public function getUserVip($uid){
		$rs = array();
		$model = new Model_Common();
		$rs = $model->getUserVip($uid);
		return $rs;

	}

	public function getUserCar($uid){
		$rs = array();
		$model = new Model_Common();
		$rs = $model->getUserCar($uid);
		return $rs;

	}

	public function getUserLiang($uid){
		$rs = array();
		$model = new Model_Common();
		$rs = $model->getUserLiang($uid);
		return $rs;

	}
    public function ip_limit() {
        $rs = array();
        $model = new Model_Common();
        $rs = $model->ip_limit();

        return $rs;
    }
	public function isBlackUser($uid){
		$rs = array();
		$model = new Model_Common();
		$rs = $model->isBlackUser($uid);
		return $rs;

	}

	/*检测手机号是否存在*/
	public function checkMoblieIsExist($mobile){
		$rs = array();
		$model = new Model_Common();
		$rs = $model->checkMoblieIsExist($mobile);
		return $rs;
	}

	/*检测手机号是否存在*/
	public function checkMoblieCanCode($mobile){
		$rs = array();
		$model = new Model_Common();
		$rs = $model->checkMoblieCanCode($mobile);
		return $rs;
	}

	/*分销分润*/
	public function setAgentProfit1($uid,$total){
		$model = new Model_Common();
		$rs = $model->setAgentProfit1($uid,$total);
		return $rs;
	}
}
