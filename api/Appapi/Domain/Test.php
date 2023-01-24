<?php
/****
**方言秀
***/
class Domain_Test {
	public function jssend($uid,$videoid,$type) {
		$rs = array();

		$model = new Model_Test();
		$rs = $model->jssend($uid,$videoid,$type);

		return $rs;
	}

}
