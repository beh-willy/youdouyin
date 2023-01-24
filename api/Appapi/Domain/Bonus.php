<?php

class Domain_Bonus {
	public function setCash($uid,$money,$type,$account) {
		$rs = array();

		$model = new Model_Bonus();
		$rs = $model->setCash($uid,$money,$type,$account);

		return $rs;
	}

	public function getCashLists($uid,$p){
		$rs = array();

		$model = new Model_Bonus();
		$rs = $model->getCashLists($uid,$p);

		return $rs;
	}

	public function setBonus($uid,$money,$type,$account) {
		$rs = array();

		$model = new Model_Bonus();
		$rs = $model->setBonus($uid,$money,$type,$account);

		return $rs;
	}

	public function getBonusLists($uid,$p){
		$rs = array();

		$model = new Model_Bonus();
		$rs = $model->getBonusLists($uid,$p);

		return $rs;
	}

	
	
}
