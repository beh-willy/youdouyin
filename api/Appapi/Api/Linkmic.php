<?php

class Api_Linkmic extends Api_Common {

	public function getRules() {
		return array(
			'RequestLVBAddrForLinkMic' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
			),
			'RequestPlayUrlWithSignForLinkMic' => array( 
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'originStreamUrl' => array('name' => 'originStreamUrl', 'type' => 'string',  'require' => true, 'desc' => '流地址'),
			),
			'MergeVideoStream' => array( 
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'mergeparams' => array('name' => 'mergeparams', 'type' => 'string',  'require' => true, 'desc' => '混流参数'),
			),
		);
	}
	
	/**
	 * 获取连麦推拉流地址
	 * @desc 用于 获取连麦推拉流地址
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[].pushurl 推流地址
	 * @return string info[].timestamp 当前时间
	 * @return string info[].playurl 播流地址
	 * @return string msg 提示信息
	 */
	public function RequestLVBAddrForLinkMic() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=$this->uid;
		$configpri = $this->getConfigPri(); 

		$bizid = $configpri['tx_bizid'];
		$push_url_key = $configpri['tx_push_key'];
		
        $tmp_id = str_replace(array("@","#","-"),"_",$uid);
        $now_time = time();
		$now_time2 = $now_time + 3*60*60;
		$txTime = dechex($now_time2);
		
        $live_code = $bizid . "_" . $tmp_id . "_" . $txTime;

		$txSecret = md5($push_url_key . $live_code . $txTime);
		
        //$play_url = "http://" . $bizid . ".liveplay.myqcloud.com/live/" .  $live_code . ".flv";
        $safe_url = "&txSecret=" . $txSecret."&txTime=" .$txTime;
        //$push_url = "rtmp://" . $bizid . ".livepush2.myqcloud.com/live/" .  $live_code . "?bizid=" . $bizid . "&record_interval=10800&record=flv|hls" .$safe_url;
        $push_url = "rtmp://" . $bizid . ".livepush2.myqcloud.com/live/" .  $live_code . "?bizid=" . $bizid .$safe_url;
        $play_url = "rtmp://" . $bizid . ".liveplay.myqcloud.com/live/".$live_code. "?bizid=" . $bizid .$safe_url;
		
        $info=array(
			"pushurl" => $push_url,
			"timestamp" => $now_time, 
			"playurl" => $play_url
		);

		$rs['info'][0]=$info;
		return $rs;			
	}		

	/**
	 * 获取鉴权流地址
	 * @desc 用于鉴权流地址
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[].streamUrlWithSignature 鉴权地址
	 * @return string info[].timestamp 当前时间
	 * @return string msg 提示信息
	 */
	public function RequestPlayUrlWithSignForLinkMic() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$originalUrl=$this->checkNull($this->originStreamUrl);
		
		$configpri = $this->getConfigPri(); 

		$bizid = $configpri['tx_bizid'];
		$push_url_key = $configpri['tx_push_key'];
		
		
        $list = preg_split ('/\//', $originalUrl);
        $length = count($list);
        $url = preg_split ('/\./', $list[$length - 1]);
		
        $now_time = time();
		$now_time = $now_time + 3*60*60;
		$txTime = dechex($now_time);
		
		$txSecret = md5($push_url_key . $url[0] . $txTime);
		
        $safe_url = $originalUrl."?txSecret=" . $txSecret ."&txTime=" .$txTime ."&bizid=".$bizid;


        $info=array(
			"streamUrlWithSignature" => $safe_url,
			"timestamp" => $now_time, 
		);


		$rs['info'][0]=$info;
		return $rs;			
	}		
	
	/**
	 * 连麦混流
	 * @desc 用于连麦混流
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string msg 提示信息
	 */
	public function MergeVideoStream() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$mergeparams=html_entity_decode($this->checkNull($this->mergeparams));
		
		$configpri = $this->getConfigPri(); 

		$appid = $configpri['tx_appid'];
		$bizid = $configpri['tx_bizid'];
		$push_url_key = $configpri['tx_push_key'];
		$call_back_key = $configpri['tx_api_key'];
		

		$t=time()+60;
		$sign=$md5_val = md5($call_back_key . strval($t));;

		$param=$mergeparams;

		$url='http://fcgi.video.qcloud.com/common_access?appid='.$appid.'&interface=Mix_StreamV2&t='.$t.'&sign='.$sign;

		$ch = curl_init ();
		@curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查  
		@curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在  
		@curl_setopt($ch, CURLOPT_URL, $url);
		@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		@curl_setopt($ch, CURLOPT_POST, 1);
		@curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		@curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json; charset=utf-8',
				'Content-Length: ' . strlen($param)
			)
		);

		@$result = curl_exec($ch);
		if(curl_errno($ch)){
			//print curl_error($ch);
			file_put_contents('./MergeVideoStream.txt',date('y-m-d H:i:s').' 提交参数信息 ch:'.json_encode(curl_error($ch))."\r\n",FILE_APPEND);
		}
		curl_close($ch);
        //file_put_contents('./MergeVideoStream.txt',date('y-m-d H:i:s').' 提交参数信息 result:'.json_encode($result)."\r\n",FILE_APPEND);

		if(!$result || $result['code']!=0){
			$rs['code']=1002;
			$rs['msg']=$result['message'];
			return $rs;
		}

		return $rs;			
	}	

}
