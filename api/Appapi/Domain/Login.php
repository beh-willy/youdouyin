<?php

class Domain_Login {

    public function shareRsg($mobile,$uid) {
        $rs = array();

        $model = new Model_Login();
        $rs = $model->shareRsg($mobile,$uid);

        return $rs;
    }

    public function userLogin($user_login,$uuid) {
        $rs = array();

        $model = new Model_Login();
        $rs = $model->userLogin($user_login,$uuid);

        return $rs;
    }

     public function userLogin1($uuid) {
        $rs = array();

        $model = new Model_Login();
        $rs = $model->userLogin1($uuid);

        return $rs;
    }

   
	
    public function userFindPass($user_login,$user_pass) {
        $rs = array();
        $model = new Model_Login();
        $rs = $model->userFindPass($user_login,$user_pass);

        return $rs;
    }	

    public function userLoginByThird($openid,$type,$nickname,$avatar,$device) {
        $rs = array();

        $model = new Model_Login();
        $rs = $model->userLoginByThird($openid,$type,$nickname,$avatar,$device);

        return $rs;
    }			

}
