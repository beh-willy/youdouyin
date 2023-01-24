<?php
/****
**兑换：金币、观看时长
***/
class Domain_Exchange {
	//获取映票信息
	public function getVotesAndLike($uid) {
		$rs = array();

		$model = new Model_Exchange();
		$rs = $model->getVotesAndLike($uid);

		return $rs;
	}
	//获取观看时间信息
	public function getLooktimesAndCoin($uid) {
		$rs = array();

		$model = new Model_Exchange();
		$rs = $model->getLooktimesAndCoin($uid);

		return $rs;
	}
	//兑换界面输入兑换的映票返回的信息集合
	public function showExchangevotes($votestotal,$unexchange_like,$changeset) {
		$rs = array();

		$model = new Model_Exchange();
		$rs = $model->showExchangevotes($votestotal,$unexchange_like,$changeset);

		return $rs;
	}
	//金币兑换映票
	public function changelikeTovotes($uid,$votes,$needlike) {
		$rs = array();

		$model = new Model_Exchange();
		$rs = $model->changelikeTovotes($uid,$votes,$needlike);

		return $rs;
	}
	//兑换记录
	public function getExchangeRecord($uid,$type) {
		$rs = array();

		$model = new Model_Exchange();
		$rs = $model->getExchangeRecord($uid,$type);

		return $rs;
	}
}
