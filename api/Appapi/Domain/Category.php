<?php

class Domain_Category {

    public function getTag($num,$type) {
        $rs = array();
        $model = new Model_Category();
        $rs = $model->getTag($num,$type);
        return $rs;
    }
    public function GetCategory($num) {
        $rs = array();
        $model = new Model_Category();
        $rs = $model->GetCategory($num);
        return $rs;
    }
    public function getSubCategory($cid) {
        $rs = array();
        $model = new Model_Category();
        $rs = $model->getSubCategory($cid);
        return $rs;
    }
    public function getCategoryVideo($cid,$p,$type=1,$uid) {
        $rs = array();
        $model = new Model_Category();
        $rs = $model->getCategoryVideo($cid,$p,$type,$uid);
        return $rs;
    }


    public function gatCateIdByName($data,$belongto) {
        $rs = array();
        $model = new Model_Category();
        $rs = $model->gatCateIdByName($data,$belongto);

        return $rs;
    }

	
}
