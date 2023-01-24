<?php

class Domain_Pay {


	public function getPayData($uid,$changeid,$paytype,$money,$productid) {
			$rs = array();

			$model = new Model_Pay();
			$rs = $model->getPayData($uid,$changeid,$paytype,$money,$productid);

			return $rs;
	}

	
	

}
