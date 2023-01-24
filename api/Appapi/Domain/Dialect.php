<?php
/****
**方言秀
***/
class Domain_Dialect {
	public function getDialect() {
		$rs = array();

		$model = new Model_Dialect();
		$rs = $model->getDialect();

		return $rs;
	}
	public function getDialectmaterial() {
		$rs = array();

		$model = new Model_Dialect();
		$rs = $model->getDialectmaterial();

		return $rs;
	}
	public function setDialectshow($data) {
		$rs = array();

		$model = new Model_Dialect();
		$rs = $model->setDialectshow($data);

		return $rs;
	}
	//方言秀点赞
	 public function addLike($uid,$videoid,$type) {
        $rs = array();

        $model = new Model_Dialect();
        $rs = $model->addLike($uid,$videoid,$type);

        return $rs;
    }
	
    public function setComment($data) {
        $rs = array();

        $model = new Model_Dialect();
        $rs = $model->setComment($data);

        return $rs;
    }
    public function addView($uid,$videoid) {
        $rs = array();

        $model = new Model_Dialect();
        $rs = $model->addView($uid,$videoid);

        return $rs;
    }
   

    public function addStep($uid,$videoid) {
        $rs = array();

        $model = new Model_Dialect();
        $rs = $model->addStep($uid,$videoid);

        return $rs;
    }
    public function addShare($uid,$videoid) {
        $rs = array();

        $model = new Model_Dialect();
        $rs = $model->addShare($uid,$videoid);

        return $rs;
    }

    public function setBlack($uid,$videoid) {
        $rs = array();

        $model = new Model_Dialect();
        $rs = $model->setBlack($uid,$videoid);

        return $rs;
    }

    public function addCommentLike($uid,$commentid) {
        $rs = array();

        $model = new Model_Dialect();
        $rs = $model->addCommentLike($uid,$commentid);

        return $rs;
    }
	public function getDialectList($uid,$dialecttype,$type,$p) {
        $rs = array();

        $model = new Model_Dialect();
        $rs = $model->getDialectList($uid,$dialecttype,$type,$p);

        return $rs;
    }
	public function getAttentionVideo($uid,$p) {
        $rs = array();

        $model = new Model_Dialect();
        $rs = $model->getAttentionVideo($uid,$p);

        return $rs;
    }
	public function getVideo($uid,$videoid) {
        $rs = array();

        $model = new Model_Dialect();
        $rs = $model->getVideo($uid,$videoid);

        return $rs;
    }
	public function getComments($uid,$videoid,$p) {
        $rs = array();

        $model = new Model_Dialect();
        $rs = $model->getComments($uid,$videoid,$p);

        return $rs;
    }

	public function getReplys($uid,$commentid,$p) {
        $rs = array();

        $model = new Model_Dialect();
        $rs = $model->getReplys($uid,$commentid,$p);

        return $rs;
    }

	public function getMyVideo($uid,$p) {
        $rs = array();

        $model = new Model_Dialect();
        $rs = $model->getMyVideo($uid,$p);

        return $rs;
    }
	
	public function del($uid,$videoid) {
        $rs = array();

        $model = new Model_Dialect();
        $rs = $model->del($uid,$videoid);

        return $rs;
    }
 
	public function getHomeVideo($uid,$touid,$p) {
        $rs = array();

        $model = new Model_Dialect();
        $rs = $model->getHomeVideo($uid,$touid,$p);

        return $rs;
    }
	public function getOscarVideo($uid,$p) {
        $rs = array();

        $model = new Model_Dialect();
        $rs = $model->getOscarVideo($uid,$p);

        return $rs;
    }
 
    public function report($data,$type) {
        $rs = array();

        $model = new Model_Dialect();
        $rs = $model->report($data,$type);

        return $rs;
    }
 
   public function getReportContentlist() {
        $rs = array();

        $model = new Model_Dialect();
        $rs = $model->getReportContentlist();

        return $rs;
    }
	public function likeIspay($uid) {
        $rs = array();

        $model = new Model_Dialect();
        $rs = $model->likeIspay($uid);

        return $rs;
    }
	public function urgeVideo($uid,$type,$videoid) {
        $rs = array();

        $model = new Model_Dialect();
        $rs = $model->urgeVideo($uid,$type,$videoid);

        return $rs;
    }
	public function getVideoUrge($uid,$type,$videoid) {
        $rs = array();

        $model = new Model_Dialect();
        $rs = $model->getVideoUrge($uid,$type,$videoid);

        return $rs;
    }
	//我催更的视频列表
	public function getMyurgevideoList($uid,$p) {
        $rs = array();

        $model = new Model_Dialect();
        $rs = $model->getMyurgevideoList($uid,$p);

        return $rs;
    }
	//累计观看时长
	public function addlooktimes($uid,$looktimes) {
        $rs = array();

        $model = new Model_Dialect();
        $rs = $model->addlooktimes($uid,$looktimes);

        return $rs;
    }
	
	public function getUrgenum($uid) {
        $rs = array();

        $model = new Model_Dialect();
        $rs = $model->getUrgenum($uid);

        return $rs;
    }
}
