<?php

class Domain_User {

	public function getBaseInfo($userId) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getBaseInfo($userId);

			return $rs;
	}
	
	public function checkName($uid,$name) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->checkName($uid,$name);

			return $rs;
	}
	
	public function userUpdate($uid,$fields) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->userUpdate($uid,$fields);

			return $rs;
	}
	
	public function updatePass($uid,$oldpass,$pass) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->updatePass($uid,$oldpass,$pass);

			return $rs;
	}

	public function getBalance($uid) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getBalance($uid);

			return $rs;
	}
	
	public function getChargeRules() {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getChargeRules();

			return $rs;
	}
	
	public function getProfit($uid) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getProfit($uid);

			return $rs;
	}

	public function setCash($data) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->setCash($data);

			return $rs;
	}
	
	public function setAttent($uid,$touid) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->setAttent($uid,$touid);

			return $rs;
	}
	
	public function setBlack($uid,$touid) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->setBlack($uid,$touid);

			return $rs;
	}

	public function getInviteList($uid,$p) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getInviteList($uid,$p);

			return $rs;
	}
	
	
	public function getFollowsList($uid,$touid,$p,$key) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getFollowsList($uid,$touid,$p,$key);

			return $rs;
	}
	
	public function getFansList($uid,$touid,$p) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getFansList($uid,$touid,$p);

			return $rs;
	}

	public function getBlackList($uid,$touid,$p) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getBlackList($uid,$touid,$p);

			return $rs;
	}

	public function getLiverecord($touid,$p) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->getLiverecord($touid,$p);

			return $rs;
	}
	
	public function getUserHome($uid,$touid) {
		$rs = array();

		$model = new Model_User();
		$rs = $model->getUserHome($uid,$touid);
		return $rs;
	}	
	
	public function getContributeList($touid,$p) {
		$rs = array();

		$model = new Model_User();
		$rs = $model->getContributeList($touid,$p);
		return $rs;
	}	
	
	public function setDistribut($uid,$code) {
		$rs = array();

		$model = new Model_User();
		$rs = $model->setDistribut($uid,$code);
		return $rs;
	}	
	
	public function getAliCdnRecord($id) {
        $rs = array();
                
        $model = new Model_Alicdnrecord();
        $rs = $model->getAliCdnRecord($id);

        return $rs;
    }	
	public function checkMobile($uid,$mobile) {
        $rs = array();
                
        $model = new Model_User();
        $rs = $model->checkMobile($uid,$mobile);

        return $rs;
    }

    public function getLikeVideos($uid,$p){
    	$rs = array();
                
        $model = new Model_User();
        $rs = $model->getLikeVideos($uid,$p);

        return $rs;
    }

    
 	public function getVipInfo($uid){
    	$rs = array();
                
        $model = new Model_User();
        $rs = $model->getVipInfo($uid);

        return $rs;
    }
    public function getMyWallet($uid){
    	$rs = array();
                
        $model = new Model_User();
        $rs = $model->getMyWallet($uid);

        return $rs;
    }

    public function exchangeTicket($uid,$likenum){
    	$rs = array();
                
        $model = new Model_User();
        $rs = $model->exchangeTicket($uid,$likenum);

        return $rs;
    }

    public function orderLists($uid,$type,$p) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->orderLists($uid,$type,$p);

			return $rs;
	}
	public function incomeLists($uid,$type,$p) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->incomeLists($uid,$type,$p);

			return $rs;
	}

	public function agentIncome($uid) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->agentIncome($uid);

			return $rs;
	}
	public function performance_list($uid,$p) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->performance_list($uid,$p);

			return $rs;
	}
	public function view_list($uid,$p) {
			$rs = array();

			$model = new Model_User();
			$rs = $model->view_list($uid,$p);

			return $rs;
	}
}
