<?php

class Model_Test extends Model_Common {
	
	public function jssend($uid,$videoid,$type){
		
		$result=$this->jgsend($uid,$videoid,$type);
		
		return $result;
	}

}
