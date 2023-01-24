<?php

class Domain_Community {
	public function setCommunity($data) {
		$rs = array();

		$model = new Model_Community();
		$rs = $model->setCommunity($data);

		return $rs;
	}
	
    public function setComment($data) {
        $rs = array();

        $model = new Model_Community();
        $rs = $model->setComment($data);

        return $rs;
    }
    public function addView($uid,$communityid) {
        $rs = array();

        $model = new Model_Community();
        $rs = $model->addView($uid,$communityid);

        return $rs;
    }
    public function addLike($uid,$communityid) {
        $rs = array();

        $model = new Model_Community();
        $rs = $model->addLike($uid,$communityid);

        return $rs;
    }

    public function collectCommunity($uid,$communityid){
        $rs = array();

        $model = new Model_Community();
        $rs = $model->collectCommunity($uid,$communityid);

        return $rs;
    }

    public function getCollectLists($uid,$p){
        $rs = array();

        $model = new Model_Community();
        $rs = $model->getCollectLists($uid,$p);

        return $rs;
    }

    public function addStep($uid,$communityid) {
        $rs = array();

        $model = new Model_Community();
        $rs = $model->addStep($uid,$communityid);

        return $rs;
    }
    public function addShare($uid,$communityid) {
        $rs = array();

        $model = new Model_Community();
        $rs = $model->addShare($uid,$communityid);

        return $rs;
    }

    public function setBlack($uid,$communityid) {
        $rs = array();

        $model = new Model_Community();
        $rs = $model->setBlack($uid,$communityid);

        return $rs;
    }

    public function addCommentLike($uid,$commentid) {
        $rs = array();

        $model = new Model_Community();
        $rs = $model->addCommentLike($uid,$commentid);

        return $rs;
    }
	public function getCommunityList($uid,$cid,$p,$order) {
        $rs = array();

        $model = new Model_Community();
        $rs = $model->getCommunityList($uid,$cid,$p,$order);

        return $rs;
    }
	public function getAttentionCommunity($uid,$p) {
        $rs = array();

        $model = new Model_Community();
        $rs = $model->getAttentionCommunity($uid,$p);

        return $rs;
    }
	public function getCommunity($uid,$communityid) {
        $rs = array();

        $model = new Model_Community();
        $rs = $model->getCommunity($uid,$communityid);

        return $rs;
    }
	public function getComments($uid,$communityid,$p) {
        $rs = array();

        $model = new Model_Community();
        $rs = $model->getComments($uid,$communityid,$p);

        return $rs;
    }

	public function getReplys($uid,$commentid,$p) {
        $rs = array();

        $model = new Model_Community();
        $rs = $model->getReplys($uid,$commentid,$p);

        return $rs;
    }

	public function getMyCommunity($uid,$p) {
        $rs = array();

        $model = new Model_Community();
        $rs = $model->getMyCommunity($uid,$p);

        return $rs;
    }
	
	public function del($uid,$communityid) {
        $rs = array();

        $model = new Model_Community();
        $rs = $model->del($uid,$communityid);

        return $rs;
    }
 
	public function getHomeCommunity($uid,$touid,$p) {
        $rs = array();

        $model = new Model_Community();
        $rs = $model->getHomeCommunity($uid,$touid,$p);

        return $rs;
    }
 
    public function report($data) {
        $rs = array();

        $model = new Model_Community();
        $rs = $model->report($data);

        return $rs;
    }

    public function getRecommendCommunitys($uid,$p){
        $rs = array();

        $model = new Model_Community();
        $rs = $model->getRecommendCommunitys($uid,$p);

        return $rs;
    }

    
 
	 public function test() {
        $rs = array();

        $model = new Model_Community();
        $rs = $model->test();

        return $rs;
    }

    public function getNearby($uid,$lng,$lat,$p){
        $rs = array();

        $model = new Model_Community();
        $rs = $model->getNearby($uid,$lng,$lat,$p);
        
        return $rs;
    }

    public function getReportContentlist() {
        $rs = array();

        $model = new Model_Community();
        $rs = $model->getReportContentlist();

        return $rs;
    }


    public function checkOutCommunity($type,$communityid){
        $rs = array();

        $model = new Model_Community();
        $rs = $model->checkOutCommunity($type,$communityid);

        return $rs;
    }

    public function setOutCommunity($data){
        $rs = array();

        $model = new Model_Community();
        $rs = $model->setOutCommunity($data);

        return $rs;
    }
    public function ceshi(){
        $rs = array();

        $model = new Model_Community();
        $rs = $model->ceshi();

        return $rs;
    }
}
