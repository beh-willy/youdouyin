<?php

class Domain_Video {
	public function setVideo($data,$music_id,$ispass) {
		$rs = array();

		$model = new Model_Video();
		$rs = $model->setVideo($data,$music_id,$ispass);

		return $rs;
	}
	
    public function setComment($data) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->setComment($data);

        return $rs;
    }
    public function addView($uid,$videoid) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->addView($uid,$videoid);

        return $rs;
    }
    public function buyVideo($uid,$videoinfo) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->buyVideo($uid,$videoinfo);

        return $rs;
    }
    public function addLike($uid,$videoid) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->addLike($uid,$videoid);

        return $rs;
    }

    public function addStep($uid,$videoid) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->addStep($uid,$videoid);

        return $rs;
    }
    public function addShare($uid,$videoid) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->addShare($uid,$videoid);

        return $rs;
    }

    public function setBlack($uid,$videoid) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->setBlack($uid,$videoid);

        return $rs;
    }

    public function addCommentLike($uid,$commentid) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->addCommentLike($uid,$commentid);

        return $rs;
    }
	public function getVideoList($uid,$p,$type=0) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getVideoList($uid,$p,$type);

        return $rs;
    }
	public function getAttentionVideo($uid,$p) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getAttentionVideo($uid,$p);

        return $rs;
    }
	public function getVideo($uid,$videoid) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getVideo($uid,$videoid);

        return $rs;
    }
	public function getComments($uid,$videoid,$p) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getComments($uid,$videoid,$p);

        return $rs;
    }

	public function getReplys($uid,$commentid,$p) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getReplys($uid,$commentid,$p);

        return $rs;
    }

	public function getMyVideo($uid,$p) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getMyVideo($uid,$p);

        return $rs;
    }

    public function getMyBuyVideo($uid,$p) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getMyBuyVideo($uid,$p);

        return $rs;
    }
	
	public function del($uid,$videoid) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->del($uid,$videoid);

        return $rs;
    }
 
	public function getHomeVideo($uid,$touid,$p) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getHomeVideo($uid,$touid,$p);

        return $rs;
    }
 
    public function report($data) {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->report($data);

        return $rs;
    }

    public function getRecommendVideos($uid,$p){
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getRecommendVideos($uid,$p);

        return $rs;
    }

    
 
	 public function test() {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->test();

        return $rs;
    }

    public function getNearby($uid,$lng,$lat,$p){
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getNearby($uid,$lng,$lat,$p);
        
        return $rs;
    }

    public function getReportContentlist() {
        $rs = array();

        $model = new Model_Video();
        $rs = $model->getReportContentlist();

        return $rs;
    }

    public function setConversion($uid,$videoid){
        $rs = array();

        $model = new Model_Video();
        $rs = $model->setConversion($uid,$videoid);

        return $rs;
    }

    public function checkOutVideo($type,$videoid){
        $rs = array();

        $model = new Model_Video();
        $rs = $model->checkOutVideo($type,$videoid);

        return $rs;
    }

    public function setOutVideo($data){
        $rs = array();

        $model = new Model_Video();
        $rs = $model->setOutVideo($data);

        return $rs;
    }
    public function ceshi(){
        $rs = array();

        $model = new Model_Video();
        $rs = $model->ceshi();

        return $rs;
    }
}
