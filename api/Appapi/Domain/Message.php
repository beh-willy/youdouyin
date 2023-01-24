<?php

class Domain_Message {

   
    public function fansLists($uid,$p){
        $rs = array();

        $model = new Model_Message();
        $rs = $model->fansLists($uid,$p);

        return $rs;
    }

    public function praiseLists($uid,$p){
        $rs = array();

        $model = new Model_Message();
        $rs = $model->praiseLists($uid,$p);

        return $rs;
    }

    public function atLists($uid,$p){
        $rs = array();

        $model = new Model_Message();
        $rs = $model->atLists($uid,$p);

        return $rs;
    }

    public function commentLists($uid,$p){
        $rs = array();

        $model = new Model_Message();
        $rs = $model->commentLists($uid,$p);

        return $rs;
    }


    public function officialLists($p){
        $rs = array();

        $model = new Model_Message();
        $rs = $model->officialLists($p);

        return $rs;
    }

    public function systemnotifyLists($uid,$p){
        $rs = array();

        $model = new Model_Message();
        $rs = $model->systemnotifyLists($uid,$p);

        return $rs;
    }

    public function getLastTime($uid){
        $rs = array();

        $model = new Model_Message();
        $rs = $model->getLastTime($uid);

        return $rs;
    }
}
