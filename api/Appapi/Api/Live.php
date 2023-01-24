<?php

class Api_Live extends Api_Common {

	public function getRules() {
		return array(
			'createRoom' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'user_nicename' => array('name' => 'user_nicename', 'type' => 'string', 'require' => true, 'desc' => '用户昵称 url编码'),
				'avatar' => array('name' => 'avatar', 'type' => 'string',  'require' => true, 'desc' => '用户头像 url编码'),
				'avatar_thumb' => array('name' => 'avatar_thumb', 'type' => 'string',  'require' => true, 'desc' => '用户小头像 url编码'),
				'title' => array('name' => 'title', 'type' => 'string','default'=>'', 'desc' => '直播标题 url编码'),
				'province' => array('name' => 'province', 'type' => 'string', 'default'=>'', 'desc' => '省份'),
				'city' => array('name' => 'city', 'type' => 'string', 'default'=>'', 'desc' => '城市'),
				'lng' => array('name' => 'lng', 'type' => 'string', 'default'=>'0', 'desc' => '经度值'),
				'lat' => array('name' => 'lat', 'type' => 'string', 'default'=>'0', 'desc' => '纬度值'),
				'type' => array('name' => 'type', 'type' => 'int', 'default'=>'0', 'desc' => '直播类型，0是一般直播，1是私密直播，2是收费直播，3是计时直播，5是游戏直播'),
				'type_val' => array('name' => 'type_val', 'type' => 'string', 'default'=>'', 'desc' => '类型值'),
			),
			'changeLive' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
				'status' => array('name' => 'status', 'type' => 'int', 'require' => true, 'desc' => '直播状态 0关闭 1直播'),
			),
			'changeLiveType' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
				'type' => array('name' => 'type', 'type' => 'int', 'default'=>'0', 'desc' => '直播类型，0是一般直播，1是私密直播，2是收费直播，3是计时直播，5是游戏直播'),
				'type_val' => array('name' => 'type_val', 'type' => 'string', 'default'=>'', 'desc' => '类型值'),
			),
			'stopRoom' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
			),
			
			'stopInfo' => array(
				'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
			),
			
			'checkLive' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
				'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
			),
			
			'roomCharge' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
				'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
			),
			'timeCharge' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
				'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
			),
			
			'enterRoom' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
				'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
				'city' => array('name' => 'city', 'type' => 'string','default'=>'', 'desc' => '城市'),
			),
			
			'showVideo' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
                'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '上麦会员ID'),
            ),
			
			'getZombie' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'stream' => array('name' => 'stream', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => '流名'),
            ),

			'getUserLists' => array(
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
				'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),
			),
			
			'getPop' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
			),
			
			'getGiftList' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
			),
			
			'sendGift' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
				'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
				'giftid' => array('name' => 'giftid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '礼物ID'),
				'giftcount' => array('name' => 'giftcount', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '礼物数量'),
			),
			
			'sendBarrage' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
				'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
				'giftid' => array('name' => 'giftid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '礼物ID 弹幕为1'),
				'giftcount' => array('name' => 'giftcount', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '礼物数量'),
				'content' => array('name' => 'content', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => '弹幕内容'),
			),
			
			'setAdmin' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
			),
			
			'getAdminList' => array(
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
			),
			
			'setReport' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
				'content' => array('name' => 'content', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => '举报内容'),
			),
			
			'getVotes' => array(
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
			),
			
			'setShutUp' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => '用户token'),
                'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '禁言用户ID'),
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
            ),
			
			'kicking' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
			),
			
			'superStopRoom' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '会员ID'),
                'token' => array('name' => 'token', 'require' => true, 'min' => 1, 'desc' => '会员token'),
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
				'type' => array('name' => 'type', 'type' => 'int','default'=>0, 'desc' => '关播类型 0表示关闭当前直播 1表示关闭当前直播并禁用账号'),
            ),
			/*'searchMusic' => array(
				'key' => array('name' => 'key', 'type' => 'string','require' => true,'desc' => '关键词'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),
            ),
			
			'getDownurl' => array(
				'audio_id' => array('name' => 'audio_id', 'type' => 'int','require' => true,'desc' => '歌曲ID'),
            ),*/
			
			'setLiveThumb' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '会员ID'),
                'token' => array('name' => 'token', 'require' => true, 'min' => 1, 'desc' => '会员token'),
            ),

		);
	}

	/**
	 * 创建开播
	 * @desc 用于用户开播生成记录
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string info[0].userlist_time 用户列表请求间隔
	 * @return string info[0].barrage_fee 弹幕价格
	 * @return string info[0].votestotal 主播映票
	 * @return string info[0].stream 流名
	 * @return string info[0].push 推流地址
	 * @return string info[0].chatserver socket地址
	 * @return string info[0].pull_wheat 连麦播流地址
	 * @return string info[0].shut_time 禁言时间 
	 * @return string info[0].kick_time 踢人时间 
	 * @return object info[0].liang 用户靓号信息
	 * @return string info[0].liang.name 号码，0表示无靓号
	 * @return object info[0].vip 用户VIP信息
	 * @return string info[0].vip.type VIP类型，0表示无VIP，1表示普通VIP，2表示至尊VIP
	 * @return string msg 提示信息
	 */
	public function createRoom() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$uid = $this->uid;
		$token=$this->checkNull($this->token);
		$configpub=$this->getConfigPub();
		if($configpub['maintain_switch']==1){
			$rs['code']=1002;
			$rs['msg']=$configpub['maintain_tips'];
			return $rs;

		}
		$isban = $this->isBan($uid);
		if(!$isban){
			$rs['code']=1001;
			$rs['msg']='该账号已被禁用';
			return $rs;
		}
		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}
		$configpri=$this->getConfigPri();
		if($configpri['auth_islimit']==1){
			$isauth=$this->isAuth($uid);
			if(!$isauth){
				$rs['code']=1002;
				$rs['msg']='请先进行身份认证或等待审核';
				return $rs;
			}
		}
		$userinfo=$this->getUserInfo($uid);
		
		if($configpri['level_islimit']==1){
			if( $userinfo['level'] < $configpri['level_limit'] ){
				$rs['code']=1003;
				$rs['msg']='等级小于'.$configpri['level_limit'].'级，不能直播';
				return $rs;
			}
		}
				
		$nowtime=time();
		
		$user_nicename=$this->checkNull($this->user_nicename);
		$avatar=$this->checkNull($this->avatar);
		$avatar_thumb=$this->checkNull($this->avatar_thumb);
		$showid=$nowtime;
		$starttime=$nowtime;
		$title=$this->checkNull($this->title);
		$province=$this->checkNull($this->province);
		$city=$this->checkNull($this->city);
		$lng=$this->checkNull($this->lng);
		$lat=$this->checkNull($this->lat);
		$type=$this->checkNull($this->type);
		$type_val=$this->checkNull($this->type_val);
		
		if( $type==1 && $type_val=='' ){
			$rs['code']=1002;
			$rs['msg']='密码不能为空';
			return $rs;
		}else if(($type == 2 || $type==3) && $type_val<=0){
			$rs['code']=1002;
			$rs['msg']='价格不能小于等于0';
			return $rs;
		}
		
		
		
		$stream=$uid.'_'.$nowtime;
		//$pull='rtmp://'.$configpri['pull_url'].'/5showcam/'.$stream;
		//$push='rtmp://'.$configpri['push_url'].'/5showcam/'.$stream.'?vhost='.$configpri['pull_url'];
		if($configpri['cdn_switch']==5)
		{
			$push=$this->PrivateKeyA('rtmp',$stream,1);
			$pull=$push['ret']["rtmpPullUrl"];
			$wy_cid=$push['ret']["cid"];
			$push=$push['ret']["pushUrl"];
		}else{
			$push=$this->PrivateKeyA('rtmp',$stream,1);
			$pull=$this->PrivateKeyA('rtmp',$stream,0);
		}
	


		if(!$city){
			$city='好像在火星';
		}
		if(!$lng && $lng!=0){
			$lng='';
		}
		if(!$lat && $lat!=0){
			$lat='';
		}

		$thumb='';
		if($_FILES){
			if ($_FILES["file"]["error"] > 0) {
				$rs['code'] = 1003;
				$rs['msg'] = T('failed to upload file with error: {error}', array('error' => $_FILES['file']['error']));
				DI()->logger->debug('failed to upload file with error: ' . $_FILES['file']['error']);
				return $rs;
			}
			
			if(!$this->checkExt($_FILES["file"]['name'])){
				$rs['code']=1004;
				$rs['msg']='图片仅能上传 jpg,png,jpeg';
				return $rs;
			}

			$uptype=DI()->config->get('app.uptype');
			if($uptype==1){
				//七牛
				$url = DI()->qiniu->uploadFile($_FILES['file']['tmp_name']);

				if (!empty($url)) {
					$thumb=  $url.'?imageView2/2/w/600/h/600'; //600 X 600
				}
			}else if($uptype==2){
				//本地上传
				//设置上传路径 设置方法参考3.2
				DI()->ucloud->set('save_path','thumb/'.date("Ymd"));

				//新增修改文件名设置上传的文件名称
			   // DI()->ucloud->set('file_name', $this->uid);

				//上传表单名
				$res = DI()->ucloud->upfile($_FILES['file']);
				
				$files='../upload/'.$res['file'];
				$PhalApi_Image = new Image_Lite();
				//打开图片
				$PhalApi_Image->open($files);
				/**
				 * 可以支持其他类型的缩略图生成，设置包括下列常量或者对应的数字：
				 * IMAGE_THUMB_SCALING      //常量，标识缩略图等比例缩放类型
				 * IMAGE_THUMB_FILLED       //常量，标识缩略图缩放后填充类型
				 * IMAGE_THUMB_CENTER       //常量，标识缩略图居中裁剪类型
				 * IMAGE_THUMB_NORTHWEST    //常量，标识缩略图左上角裁剪类型
				 * IMAGE_THUMB_SOUTHEAST    //常量，标识缩略图右下角裁剪类型
				 * IMAGE_THUMB_FIXED        //常量，标识缩略图固定尺寸缩放类型
				 */
				$PhalApi_Image->thumb(660, 660, IMAGE_THUMB_SCALING);
				$PhalApi_Image->save($files);							
				
				$thumb=$res['url'];
			}else if($uptype==3){//腾讯云上传
				require(API_ROOT.'/public/txcloud/include.php');
				$config=DI()->config->get('app.TxCloud.config');
				$bucket=DI()->config->get('app.TxCloud.bucketname');
				//bucketname
				$bucket = 'aosika';
				//uploadlocalpath
				/* $src = $_FILES['file'];//'./hello.txt'; */
				$src = $_FILES["file"]["tmp_name"];//'./hello.txt';
				
				$configpri=$this->getConfigPri();
				//cosfolderpath
				$folder = '/'.$configpri['txuserimgfolder'];
				//cospath
				$dst = $folder.'/'.$_FILES["file"]["name"];
				//config your information
				
				date_default_timezone_set('PRC');
				
				$cosApi = new 	\QCloud\Cos\Api($config);
				// Upload file into bucket.
				$ret = $cosApi->upload($bucket, $src, $dst);
				
				if($ret['code']==0){
					$rs['code']=0;
				}else{
					$rs['code']=1002;
					$rs['msg']=$ret['message'];
					return $rs;
				} 
				$url = $ret['data']['source_url'];
				if (!empty($url)) {
					$avatar=  $url.'?imageView2/2/w/600/h/600'; //600 X 600
					$avatar_thumb=  $url.'?imageView2/2/w/200/h/200'; // 200 X 200
					$data=array(
						"avatar"=>$avatar,
						"avatar_thumb"=>$avatar_thumb,
					);
				}
		}
			
			@unlink($_FILES['file']['tmp_name']);			
		}
		
		
		/* 主播靓号 */
		$liang=$this->getUserLiang($uid);
		$goodnum=0;
		if($liang['name']!=0){
			$goodnum=$liang['name'];
		}
		$info['liang']=$liang;

		
		/* 主播VIP */
		$vip=$this->getUserVip($uid);
		$info['vip']=$vip;

		
		$dataroom=array(
			"uid"=>$uid,
			"user_nicename"=>$user_nicename,
			"avatar"=>$avatar,
			"avatar_thumb"=>$avatar_thumb,
			"showid"=>$showid,
			"starttime"=>$starttime,
			"title"=>$title,
			"province"=>$province,
			"city"=>$city,
			"stream"=>$stream,
			"thumb"=>$thumb,
			"pull"=>$pull,
			"lng"=>$lng,
			"lat"=>$lat,
			"type"=>$type,
			"type_val"=>$type_val,
			"goodnum"=>$goodnum,
			"isvideo"=>0,
			"islive"=>0,
		);	

		$domain = new Domain_Live();
		$result = $domain->createRoom($uid,$dataroom);
		if($result===false){
			$rs['code'] = 1011;
			$rs['msg'] = '开播失败，请重试';
			return $rs;
		}
		$data=array('city'=>$city);
		$domain2 = new Domain_User();
		$info2 = $domain2->userUpdate($uid,$data);
		
		$userinfo['city']=$city;
		$userinfo['sign'] = md5($uid.'_'.$uid);
		$userinfo['userType']=50;

		DI()->redis  -> set($token,json_encode($userinfo));

		$votestotal=$domain->getVotes($uid);
		
		$info['userlist_time']=$configpri['userlist_time'];
		$info['barrage_fee']=$configpri['barrage_fee'];
		$info['chatserver']=$configpri['chatserver'];
		$info['agorakitid']=$configpri['agorakitid'];
		$info['shut_time']=$configpri['shut_time'].'秒';
		$info['kick_time']=$configpri['kick_time'].'秒';
		$info['votestotal']=$votestotal;
		$info['stream']=$stream;
		$info['push']=$push;
		$info['pull_wheat']='rtmp://'.$configpri['pull_url'].'/5showcam/';

		$rs['info'][0] = $info;
		/* 极光推送 */
		$app_key = $configpri['jpush_key'];
		$master_secret = $configpri['jpush_secret'];

		if($app_key && $master_secret && $type==0){
			require './JPush/autoload.php';

			// 初始化
			$client = new \JPush\Client($app_key, $master_secret,null);
			
			$anthorinfo=array(
				"uid"=>$dataroom['uid'],
				"avatar"=>$dataroom['avatar'],
				"avatar_thumb"=>$dataroom['avatar_thumb'],
				"user_nicename"=>$dataroom['user_nicename'],
				"title"=>$dataroom['title'],
				"city"=>$dataroom['city'],
				"stream"=>$dataroom['stream'],
				"pull"=>$dataroom['pull'],
				"thumb"=>$dataroom['thumb'],
			);
			$fansids = $domain->getFansIds($uid); 
			$uids=$this->array_column2($fansids,'uid');
			$nums=count($uids);	
			$apns_production=false;
			if($configpri['jpush_sandbox']){
				$apns_production=true;
			}

			for($i=0;$i<$nums;){
				$alias=array();
				for($n=0;$n<1000;$n++,$i++){
					if($uids[$i]){
						$alias[]=$uids[$i].'PUSH';								 
					}else{
						break;
					}
				}	 
				try{	

					$result = $client->push()
							->setPlatform('all')
							->addAlias($alias)
							->setNotificationAlert('你的好友：'.$anthorinfo['user_nicename'].'正在直播，邀请你一起')
							->iosNotification('你的好友：'.$anthorinfo['user_nicename'].'正在直播，邀请你一起', array(
								'sound' => 'sound.caf',
								'category' => 'jiguang',
								'extras' => array(
									'userinfo' => $anthorinfo
								),
							))
							->androidNotification('你的好友：'.$anthorinfo['user_nicename'].'正在直播，邀请你一起', array(
								'extras' => array(
									'userinfo' => $anthorinfo
								),
							))
							->options(array(
								'sendno' => 100,
								'time_to_live' => 0,
								'apns_production' =>  $apns_production,
							))
							->send();
				} catch (Exception $e) {   
					//file_put_contents('./jpush.txt',date('y-m-d h:i:s').'提交参数信息 设备名:'.json_encode($alias)."\r\n",FILE_APPEND);
					//file_put_contents('./jpush.txt',date('y-m-d h:i:s').'提交参数信息:'.$e."\r\n",FILE_APPEND);
				}					
			}			
		}
		/* 极光推送 */

		return $rs;
	}
	

	/**
	 * 修改直播状态
	 * @desc 用于主播修改直播状态
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].msg 成功提示信息
	 * @return string msg 提示信息
	 */
	public function changeLive() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid = $this->uid;
		$token=$this->checkNull($this->token);
		$stream=$this->checkNull($this->stream);
		
		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}
		
		$domain = new Domain_Live();
		$info=$domain->changeLive($uid,$stream,$this->status);

		$rs['info'][0]['msg']='成功';
		return $rs;
	}	

	/**
	 * 修改直播类型
	 * @desc 用于主播修改直播类型
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].msg 成功提示信息
	 * @return string msg 提示信息
	 */
	public function changeLiveType() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid = $this->uid;
		$token=$this->checkNull($this->token);
		$stream=$this->checkNull($this->stream);
		
		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}
		
		$type=$this->checkNull($this->type);
		$type_val=$this->checkNull($this->type_val);
		
		if( $type==1 && $type_val=='' ){
			$rs['code']=1002;
			$rs['msg']='密码不能为空';
			return $rs;
		}else if(($type == 2 || $type==3) && $type_val<=0){
			$rs['code']=1002;
			$rs['msg']='价格不能小于等于0';
			return $rs;
		}
		
		
		$data=array(
			"type"=>$type,
			"type_val"=>$type_val,
		);
		
		$domain = new Domain_Live();
		$info=$domain->changeLiveType($uid,$stream,$data);

		$rs['info'][0]['msg']='成功';
		return $rs;
	}	
	
	/**
	 * 关闭直播
	 * @desc 用于用户结束直播
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].msg 成功提示信息
	 * @return string msg 提示信息
	 */
	public function stopRoom() { 
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid = $this->uid;
		$token=$this->checkNull($this->token);
		$stream=$this->checkNull($this->stream);
		
		$key='stopRoom_'.$stream;
		$isexist=$this->getcaches($key);
		if(!$isexist){
			$checkToken=$this->checkToken($uid,$token);
			if($checkToken==700){
				$rs['code'] = $checkToken;
				$rs['msg'] = '您的登陆状态失效，请重新登陆！';
				return $rs;
			}
			
			$domain = new Domain_Live();
			$info=$domain->stopRoom($uid,$stream);
			
			$this->setcaches($key,'1',60);
		}
		
		$rs['info'][0]['msg']='关播成功';
		return $rs;
	}	
	
	/**
	 * 直播结束信息
	 * @desc 用于直播结束页面信息展示
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].nums 人数
	 * @return string info[0].length 时长
	 * @return string info[0].votes 映票数
	 * @return string msg 提示信息
	 */
	public function stopInfo() { 
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$stream=$this->checkNull($this->stream);
		
		$domain = new Domain_Live();
		$info=$domain->stopInfo($stream);

		$rs['info'][0]=$info;
		return $rs;
	}		
	
	/**
	 * 检查直播
	 * @desc 用于用户进房间时检查直播
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].type 房间类型	
	 * @return string info[0].type_val 收费房间价格，默认0
	 * @return string info[0].type_msg 提示信息
	 * @return string msg 提示信息
	 */
	public function checkLive() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		$liveuid=$this->liveuid;
		$stream=$this->checkNull($this->stream);
		
		$configpub=$this->getConfigPub();
		if($configpub['maintain_switch']==1){
			$rs['code']=1002;
			$rs['msg']=$configpub['maintain_tips'];
			return $rs;

		}
		$isban = $this->isBan($uid);
		if(!$isban){
			$rs['code']=1001;
			$rs['msg']='该账号已被禁用';
			return $rs;
		}

		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}

		$iskick=DI()->redis  -> hGet($liveuid.'kick',$uid);
		$nowtime=time();
		if($iskick>$nowtime){
			$surplus=$iskick-$nowtime;
			$rs['code']=1004;
			$rs['msg']='您已被踢出房间，剩余'.$surplus.'秒';
		}else{
			DI()->redis  -> hdel($liveuid.'kick',$uid);
		}
		
		
		$domain = new Domain_Live();
		$info=$domain->checkLive($uid,$liveuid,$stream);
		
		if($info==1005){
			$rs['code'] = 1005;
			$rs['msg'] = '直播已结束';
			return $rs;
		}
		$rs['info'][0]=$info;
		
		
		return $rs;
	}
	
	/**
	 * 房间扣费
	 * @desc 用于房间扣费
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].coin 用户余额
	 * @return string msg 提示信息
	 */
	public function roomCharge() { 
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		$liveuid=$this->liveuid;
		$stream=$this->checkNull($this->stream);
		

		
		$domain = new Domain_Live();
		$info=$domain->roomCharge($uid,$token,$liveuid,$stream);
		
		if($info==700){
			$rs['code'] = 700;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($info==1005){
			$rs['code'] = 1005;
			$rs['msg'] = '直播已结束';
			return $rs;
		}else if($info==1006){
			$rs['code'] = 1006;
			$rs['msg'] = '该房间非扣费房间';
			return $rs;
		}else if($info==1007){
			$rs['code'] = 1007;
			$rs['msg'] = '房间费用有误';
			return $rs;
		}else if($info==1008){
			$rs['code'] = 1008;
			$rs['msg'] = '余额不足';
			return $rs;
		}
		$rs['info'][0]['coin']=$info['coin'];
	
		return $rs;
	}	

	/**
	 * 房间计时扣费
	 * @desc 用于房间计时扣费
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].coin 用户余额
	 * @return string msg 提示信息
	 */
	public function timeCharge() { 
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		$liveuid=$this->liveuid;
		$stream=$this->checkNull($this->stream);
		$domain = new Domain_Live();
		
		$key='timeCharge_'.$stream.'_'.$uid;
		$cache=$this->getcaches($key);
		if($cache){
			$coin=$domain->getUserCoin($uid);
			$rs['info'][0]['coin']=$coin['coin'];
			return $rs;
		}
		
		$info=$domain->roomCharge($uid,$token,$liveuid,$stream);
		
		if($info==700){
			$rs['code'] = 700;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($info==1005){
			$rs['code'] = 1005;
			$rs['msg'] = '直播已结束';
			return $rs;
		}else if($info==1006){
			$rs['code'] = 1006;
			$rs['msg'] = '该房间非扣费房间';
			return $rs;
		}else if($info==1007){
			$rs['code'] = 1007;
			$rs['msg'] = '房间费用有误';
			return $rs;
		}else if($info==1008){
			$rs['code'] = 1008;
			$rs['msg'] = '余额不足';
			return $rs;
		}
		$rs['info'][0]['coin']=$info['coin'];
		
		$this->setCaches($key,1,50); 
	
		return $rs;
	}		
	

	/**
	 * 进入直播间
	 * @desc 用于用户进入直播
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].votestotal 直播映票
	 * @return string info[0].barrage_fee 弹幕价格
	 * @return string info[0].userlist_time 用户列表获取间隔
	 * @return string info[0].chatserver socket地址
	 * @return string info[0].isattention 是否关注主播，0表示未关注，1表示已关注
	 * @return string info[0].nums 房间人数
	 * @return string info[0].push_url 推流地址
	 * @return string info[0].pull_url 播流地址
	 * @return string info[0].showvideo 连麦用户ID，0表示未连麦
	 * @return string info[0].showvideo_url 连麦播流地址
	 * @return array info[0].userlists 用户列表
	 * @return object info[0].vip 用户VIP信息
	 * @return string info[0].vip.type VIP类型，0表示无VIP，1表示普通VIP，2表示至尊VIP
	 * @return object info[0].liang 用户靓号信息
	 * @return string info[0].liang.name 号码，0表示无靓号
	 * @return string info[0].shut_time 禁言时间 
	 * @return string info[0].kick_time 踢人时间 
	 * @return string info[0].linkmic_limit 连麦等级限制 
	 * @return string msg 提示信息
	 */
	public function enterRoom() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		$liveuid=$this->liveuid;
		$city=$this->checkNull($this->city);
		$stream=$this->checkNull($this->stream);
		$isban = $this->isBan($uid);
		if(!$isban){
			$rs['code']=1001;
			$rs['msg']='该账号已被禁用';
			return $rs;
		}

		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}
		
		$userinfo=$this->getUserInfo($uid);
		
		$carinfo=$this->getUserCar($uid);
		$userinfo['car']=$carinfo;

		if($userinfo['issuper']==1){
			DI()->redis  -> hset('super',$userinfo['id'],'1');
		}else{
			DI()->redis  -> hDel('super',$userinfo['id']);
		}
		if(!$city){
			$city='好像在火星';
		}
		
		$data=array('city'=>$city);
		$domain2 = new Domain_User();
		$info = $domain2->userUpdate($uid,$data);
		$userinfo['city']=$city;
		$userinfo['sign'] = md5($liveuid.'_'.$userinfo['id']);
		$userinfo['userType'] = 30;
		
		unset($userinfo['issuper']);
		
		DI()->redis  -> set($token,json_encode($userinfo));
		
		
		$lists=array();
		$times=20;
		$pnum=20;
		$n=0;
		$key="getUserLists_".$liveuid.'_'.$times;
		$isexist=$this->getcache($key);
		if(!$isexist){ 
			$list=DI()->redis -> hVals('userlist_'.$stream);
			foreach($list as $v){
				$lists[]=json_decode($v,true);
				if($n==$times){
					break;
				}
				$n++;
			}
			if($n>=$pnum){
				$this->setcache($key,$lists);
			}
		}else{
			$lists=$isexist;
		}
		
		$nums=DI()->redis->hlen('userlist_'.$stream);
		$showvideo='0';
		$ishowVideo=DI()->redis  -> hGet('ShowVideo',$liveuid);
		
		if($ishowVideo){
			$showvideo=$ishowVideo;
		}

		$domain = new Domain_Live();
		$configpri=$this->getConfigPri();
		
		$push_url='rtmp://'.$configpri['push_url'].'/5showcam/';
		$pull_url='?vhost='.$configpri['pull_url'];
		
	    $info=array(
			'votestotal'=>$domain->getVotes($liveuid),
			'barrage_fee'=>$configpri['barrage_fee'],
			'userlist_time'=>$configpri['userlist_time'],
			'chatserver'=>$configpri['chatserver'],
			'push_url'=>$push_url,
			'pull_url'=>$pull_url,
			'showvideo'=>$showvideo,
			'showvideo_url'=>'rtmp://'.$configpri['pull_url'].'/5showcam/',
			'nums'=>$nums,
			'shut_time'=>$configpri['shut_time'].'秒',
			'kick_time'=>$configpri['kick_time'].'秒',
			'linkmic_limit'=>$configpri['linkmic_limit'],
			'coin'=>$userinfo['coin'],
			'vip'=>$userinfo['vip'],
			'liang'=>$userinfo['liang'],
		);
		$info['isattention']=(string)$this->isAttention($uid,$liveuid);
		$info['userlists']=$lists;	
		
		$rs['info'][0]=$info;
		return $rs;
	}	
	
    /**
     * 连麦信息
     * @desc 用于主播同意连麦 写入redis
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string msg 提示信息
     */
		 
    public function showVideo() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		$liveuid=$this->liveuid;
		$touid=$this->touid;

        if($uid!=$liveuid){
			$rs['code']=1001;
			$rs['info']='您不是主播';
			$rs['msg']='您不是主播';
			return $rs;
		}
		
		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}
		
		DI()->redis  -> hset('ShowVideo',$liveuid,$touid);
					
        return $rs;
    }		

	
    /**
     * 获取僵尸粉
     * @desc 用于获取僵尸粉
     * @return int code 操作码，0表示成功
     * @return array info 僵尸粉信息
     * @return string msg 提示信息
     */
		 
    public function getZombie() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$stream=$this->checkNull($this->stream);
		
		$stream2=explode('_',$stream);
		$liveuid=$stream2[0];
			
	
		$domain = new Domain_Live();
		
		$iszombie=$domain->isZombie($liveuid);
		
		if($iszombie==0){
			$rs['code']=1001;
			$rs['info']='未开启僵尸粉';
			$rs['msg']='未开启僵尸粉';
			return $rs;
			
		}

		/* 判断用户是否进入过 */
		$isvisit=DI()->redis ->sIsMember($liveuid.'_zombie_uid',$uid);

		if($isvisit){
			$rs['code']=1003;
			$rs['info']='用户已访问';
			$rs['msg']='用户已访问';
			return $rs;
			
		}
	
		$times=DI()->redis  -> get($liveuid.'_zombie');
		
		if($times && $times>10){
			$rs['code']=1002;
			$rs['info']='次数已满';
			$rs['msg']='次数已满';
			return $rs;
		}else if($times){
			$times=$times+1;
			
		}else{
			$times=0;
		}
	
		DI()->redis  -> set($liveuid.'_zombie',$times);
		DI()->redis  -> sAdd($liveuid.'_zombie_uid',$uid);
		
		/* 用户列表 */ 

		$uidlist=array();
		$list=DI()->redis -> hVals('userlist_'.$stream);
		foreach($list as $v){
			$uidlist[]=json_decode($v,true);
		}
	
		$uids=$this->array_column2($uidlist,'id');		
		$uid=implode(",",$uids);

		$where='0';
		if($uid){
			$where.=','.$uid;
		} 
		$where=str_replace(",,",',',$where);
		$where=trim($where, ",");
		$rs['info'][0]['list'] = $domain->getZombie($stream,$where);

		$nums=DI()->redis->hlen('userlist_'.$stream);

		$rs['info'][0]['nums']=(string)$nums;
		
        return $rs;
    }	
	/**
	 * 用户列表 
	 * @desc 用于直播间获取用户列表
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].userlist 用户列表
	 * @return string info[0].nums 房间人数
	 * @return string info[0].votestotal 主播映票
	 * @return string msg 提示信息
	 */
	public function getUserLists() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

		$liveuid=$this->liveuid;
		$stream=$this->checkNull($this->stream);
		$p=$this->p;

		/* 用户列表 */ 
		$n=1;
		$pnum=20;
		$start=($p-1)*$pnum;
		$times=$p*$pnum;
		$lists=array();
		
		$key="getUserLists_".$liveuid.'_'.$times;
		$isexist=$this->getcache($key);
		if(!$isexist){ 
			$list=DI()->redis -> hVals('userlist_'.$stream);
			foreach($list as $v){
				$lists[]=json_decode($v,true);
				if($n==$times){
					break;
				}
				$n++;
			}
			if($n>=$pnum){
				$this->setcache($key,$lists);
			}
		}else{
			$lists=$isexist;
		}
		$nums=DI()->redis->hlen('userlist_'.$stream);

		$rs['info'][0]['userlist']=$lists;
		$rs['info'][0]['nums']=(string)$nums;

		/* 主播信息 */
		$domain = new Domain_Live();
		$rs['info'][0]['votestotal']=$domain->getVotes($liveuid);
		

        return $rs;
	}				
		

		
	/**
	 * 弹窗 
	 * @desc 用于直播间弹窗信息
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].consumption 消费总数
	 * @return string info[0].votestotal 票总数
	 * @return string info[0].follows 关注数
	 * @return string info[0].fans 粉丝数
	 * @return string info[0].isattention 是否关注，0未关注，1已关注
	 * @return string info[0].action 操作显示，0表示自己，30表示普通用户，40表示管理员，501表示主播设置管理员，502表示主播取消管理员，60表示超管管理主播 
	 * @return object info[0].vip 用户VIP信息
	 * @return string info[0].vip.type VIP类型，0表示无VIP，1表示普通VIP，2表示至尊VIP
	 * @return object info[0].liang 用户靓号信息
	 * @return string info[0].liang.name 号码，0表示无靓号
	 * @return string msg 提示信息
	 */
	public function getPop() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$liveuid=$this->liveuid;
		$touid=$this->touid;
		
		$domain = new Domain_Live();
		$info=$domain->getPop($touid);
		if(!$info){
			$rs['code']=1002;
			$rs['msg']='用户信息不存在';
			return $rs;
		}
		$info['isattention']=(string)$this->isAttention($uid,$touid);
		if($uid==$touid){
			$info['action']='0';
		}else{
			$uid_admin=$this->isAdmin($uid,$liveuid);
			$touid_admin=$this->isAdmin($touid,$liveuid);

			if($uid_admin==40 && $touid_admin==30){
				$info['action']='40';
			}else if($uid_admin==50 && $touid_admin==30){
				$info['action']='501';
			}else if($uid_admin==50 && $touid_admin==40){
				$info['action']='502';
			}else if($uid_admin==60 && $touid_admin<50){
				$info['action']='40';
			}else if($uid_admin==60 && $touid_admin==50){
				$info['action']='60';
			}else{
				$info['action']='30';
			}
			
		}
		
		$rs['info'][0]=$info;
		return $rs;
	}				

	/**
	 * 礼物列表 
	 * @desc 用于获取礼物列表
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].coin 余额
	 * @return array info[0].giftlist 礼物列表
	 * @return string info[0].giftlist[].id 礼物ID
	 * @return string info[0].giftlist[].type 礼物类型
	 * @return string info[0].giftlist[].giftname 礼物名称
	 * @return string info[0].giftlist[].needcoin 礼物价格
	 * @return string info[0].giftlist[].gifticon 礼物图片
	 * @return string msg 提示信息
	 */
	public function getGiftList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		
		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}
		
		$key='getGiftList';
		$giftlist=$this->getcache($key);
		if(!$giftlist){
			$domain = new Domain_Live();
			$giftlist=$domain->getGiftList();
			$this->setcache($key,$giftlist);
		}
		
		$domain2 = new Domain_User();
		$coin=$domain2->getBalance($uid);
		
		$rs['info'][0]['giftlist']=$giftlist;
		$rs['info'][0]['coin']=$coin['coin'];
		return $rs;
	}		

	/**
	 * 赠送礼物 
	 * @desc 用于赠送礼物
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].gifttoken 礼物token
	 * @return string info[0].level 用户等级
	 * @return string info[0].coin 用户余额
	 * @return string msg 提示信息
	 */
	public function sendGift() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$uid=$this->uid;
		$token=$this->token;
		$liveuid=$this->liveuid;
		$stream=$this->checkNull($this->stream);
		$giftid=$this->giftid;
		$giftcount=$this->giftcount;
		
		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		} 
		
		$domain = new Domain_Live();
		$result=$domain->sendGift($uid,$liveuid,$stream,$giftid,$giftcount);
		
		if($result==1001){
			$rs['code']=1001;
			$rs['msg']='余额不足';
			return $rs;
		}else if($result==1002){
			$rs['code']=1002;
			$rs['msg']='礼物信息不存在';
			return $rs;
		}
		
		$rs['info'][0]['gifttoken']=$result['gifttoken'];
        $rs['info'][0]['level']=$result['level'];
        $rs['info'][0]['coin']=$result['coin'];
		
		unset($result['gifttoken']);

		DI()->redis  -> set($rs['info'][0]['gifttoken'],json_encode($result));
		
		
		return $rs;
	}		
	
	/**
	 * 发送弹幕 
	 * @desc 用于发送弹幕
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].barragetoken 礼物token
	 * @return string info[0].level 用户等级
	 * @return string info[0].coin 用户余额
	 * @return string msg 提示信息
	 */
	public function sendBarrage() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$uid=$this->uid;
		$token=$this->token;
		$liveuid=$this->liveuid;
		$stream=$this->checkNull($this->stream);
		$giftid=$this->giftid;
		$giftcount=$this->giftcount;
		
		$content=$this->checkNull($this->content);
		if($content==''){
			$rs['code'] = 1003;
			$rs['msg'] = '弹幕内容不能为空';
			return $rs;
		}
		
		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		} 
		
		$domain = new Domain_Live();
		$result=$domain->sendBarrage($uid,$liveuid,$stream,$giftid,$giftcount,$content);
		
		if($result==1001){
			$rs['code']=1001;
			$rs['msg']='余额不足';
			return $rs;
		}else if($result==1002){
			$rs['code']=1002;
			$rs['msg']='礼物信息不存在';
			return $rs;
		}
		
		$rs['info'][0]['barragetoken']=$result['barragetoken'];
        $rs['info'][0]['level']=$result['level'];
        $rs['info'][0]['coin']=$result['coin'];
		
		unset($result['barragetoken']);

		DI()->redis -> set($rs['info'][0]['barragetoken'],json_encode($result));

		return $rs;
	}			

	/**
	 * 设置/取消管理员 
	 * @desc 用于获取礼物列表
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].isadmin 是否是管理员，0表示不是管理员，1表示是管理员
	 * @return string msg 提示信息
	 */
	public function setAdmin() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$token=$this->token;
		$liveuid=$this->liveuid;
		$touid=$this->touid;
		
		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		} 
		
		if($uid!=$liveuid){
			$rs['code'] = 1001;
			$rs['msg'] = '你不是该房间主播，无权操作';
			return $rs;
		}
		
		$domain = new Domain_Live();
		$info=$domain->setAdmin($liveuid,$touid);
		
		if($info==1004){
			$rs['code'] = 1004;
			$rs['msg'] = '最多设置5个管理员';
			return $rs;
		}else if($info==1003){
			$rs['code'] = 1003;
			$rs['msg'] = '操作失败，请重试';
			return $rs;
		}
		
		$rs['info'][0]['isadmin']=$info;
		return $rs;
	}		
	
	/**
	 * 管理员列表 
	 * @desc 用于获取管理员列表
	 * @return int code 操作码，0表示成功
	 * @return array info 管理员列表
	 * @return array info[].userinfo 用户信息
	 * @return string msg 提示信息
	 */
	public function getAdminList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$domain = new Domain_Live();
		$info=$domain->getAdminList($this->liveuid);
		
		$rs['info']=$info;
		return $rs;
	}			
	
	/**
	 * 用户举报 
	 * @desc 用于用户举报
	 * @return int code 操作码，0表示成功
	 * @return array info 礼物列表
	 * @return string info[0].msg 举报成功
	 * @return string msg 提示信息
	 */
	public function setReport() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		$touid=$this->touid;
		$content=$this->checkNull($this->content);
		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		} 
		
		if(!$content){
			$rs['code'] = 1001;
			$rs['msg'] = '举报内容不能为空';
			return $rs;
		}
		
		$domain = new Domain_Live();
		$info=$domain->setReport($uid,$touid,$content);
		if($info===false){
			$rs['code'] = 1002;
			$rs['msg'] = '举报失败，请重试';
			return $rs;
		}
		
		$rs['info'][0]['msg']="举报成功";
		return $rs;
	}			
	
	/**
	 * 主播映票 
	 * @desc 用于获取主播映票
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].votestotal 用户总数
	 * @return string msg 提示信息
	 */
	public function getVotes() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$domain = new Domain_Live();
		$info=$domain->getVotes($this->liveuid);
		
		$rs['info'][0]=$info;
		return $rs;
	}		
	
    /**
     * 禁言
     * @desc 用于 禁言操作
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string msg 提示信息
     */
		 
    public function setShutUp() { 
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$token=$this->token;
		$liveuid=$this->liveuid;
		$touid=$this->touid;

		$checkToken = $this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code']=700;
			$rs['msg']='token已过期，请重新登陆';
			return $rs;
		}
						
        $uidtype = $this->isAdmin($uid,$liveuid);

		if($uidtype==30 ){
			$rs["code"]=1001;
			$rs["msg"]='无权操作';
			return $rs;									
		}				
				
        $touidtype = $this->isAdmin($touid,$liveuid);
		
		if($touidtype==60){
			$rs["code"]=1001;
			$rs["msg"]='对方是超管，不能禁言';
			return $rs;	
		}

		if($uidtype==40){
			if( $touidtype==50){
				$rs["code"]=1002;
				$rs["msg"]='对方是主播，不能禁言';
				return $rs;		
			}	
			if($touidtype==40 ){
				$rs["code"]=1002;
				$rs["msg"]='对方是管理员，不能禁言';
				return $rs;		
			}	
			
		}
		$nowtime=time();	
        $result=DI()->redis -> hGet($liveuid . 'shutup',$touid);
		if($result){
			if($nowtime<=$result){
				$rs["code"]=1003;
				$rs["msg"]='对方已被禁言';
				return $rs;	
			}
		}		
		
		$configpri=$this->getConfigPri();
		$shut_time=$configpri['shut_time'];
		$time=$nowtime + $shut_time;
        DI()->redis -> hSet($liveuid . 'shutup',$touid,$time);	  
				
        return $rs;
    }	
	
	/**
	 * 踢人 
	 * @desc 用于直播间踢人
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].msg 踢出成功
	 * @return string msg 提示信息
	 */
	public function kicking() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$token=$this->token;
		$liveuid=$this->liveuid;
		$touid=$this->touid;
		
		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		} 
		$admin_uid=$this->isAdmin($uid,$liveuid);
		if($admin_uid==30){
			$rs['code']=1001;
			$rs['msg']='无权操作';
			return $rs;
		}
		$admin_touid=$this->isAdmin($touid,$liveuid);
		
		if($admin_touid==60){
			$rs["code"]=1002;
			$rs["msg"]='对方是超管，不能被踢出';
			return $rs;
		}
		
		if($admin_uid!=60){
			if($admin_touid==50 ){
				$rs['code']=1001;
				$rs['msg']='对方是主播，不能被踢出';
				return $rs;
			}else if($admin_touid==40 ){
				$rs['code']=1002;
				$rs['msg']='对方是管理员，不能被踢出';
				return $rs;
			}				
		}		
			
		$nowtime=time();
		
		$configpri=$this->getConfigPri();
		$kick_time=$configpri['kick_time'];
		
		$time=$nowtime + $kick_time;
		
		$result=DI()->redis->hset($liveuid.'kick',$touid,$time);


		$rs['info'][0]['msg']='踢出成功';
		return $rs;
	}		
	
	/**
     * 超管关播
     * @desc 用于超管关播
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].msg 提示信息 
     * @return string msg 提示信息
     */
		
	public function superStopRoom(){

		$rs = array('code' => 0, 'msg' => '', 'info' =>array());
		
		$domain = new Domain_Live();
		
		$result = $domain->superStopRoom($this->uid,$this->token,$this->liveuid,$this->type);
		if($result==700){
			$rs['code'] = 700;
            $rs['msg'] = 'token已过期，请重新登陆';
            return $rs;
		}else if($result==1001){
			$rs['code'] = 1001;
            $rs['msg'] = '你不是超管，无权操作';
            return $rs;
		}
		$rs['info'][0]['msg']='关闭成功';
 
    	return $rs;
	}	

	/**
     * 歌曲查询
     * @desc 用于搜索歌曲（百度音乐）
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[].audio_id 歌曲ID 
     * @return string info[].audio_name 歌曲名称 
     * @return string info[].artist_name 歌手 
     * @return string msg 提示信息
     */
		
	/*public function searchMusic(){

		$rs = array('code' => 0, 'msg' => '', 'info' =>array());
		
		$key=$this->checkNull($this->key);
		if($key!=''){
			$url='http://musicmini.baidu.com/app/search/searchList.php?qword='.$key.'&ie=utf-8&page='.$p;
			
			$ch = curl_init(); 
			$timeout = 10; 
			curl_setopt ($ch, CURLOPT_URL, $url); 
			curl_setopt ($ch, CURLOPT_HEADER, 0); 
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout); 

			$contents = curl_exec($ch);
			curl_close($ch);

			$p="/<table[^>]+>(.+)<\/table>/sU"; //表格部分代码
			preg_match_all($p,$contents,$img); 

			$p2='/<tr.*>.*<td class="sName.*key="(.+)".*>.*<a href="javascript:;" onclick="playSong\((.+),.*\)".*>.*<\/a>.*<\/td>.*<td class="uName.*key="(.+)".*>.*<\/td>.*<\/tr>/sU';
			preg_match_all($p2,$img[0][0],$m,PREG_SET_ORDER);
			foreach($m as $k=>$v){
				$info[$k]['audio_id']=str_replace("&#039;",'',$v[2]);
				$info[$k]['audio_name']=$v[1];
				$info[$k]['artist_name']=$v[3];
			}
			if($info){
				$rs['info']=$info;
			}
			
		}
		
 
    	return $rs;
	}*/	

	/**
     * 歌曲信息
     * @desc 用于获取歌曲详细信息（百度音乐）
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].audio_link 歌曲下载链接 
     * @return string info[0].audio_ext 文件后缀 
     * @return string info[0].time_len 时长 
     * @return string info[0].audio_size 大小 
     * @return string info[0].lrcLink 歌词链接 
     * @return string msg 提示信息
     */
		
	/*public function getDownurl(){

		$rs = array('code' => 0, 'msg' => '', 'info' =>array());
		$info=array();

		$url='http://music.baidu.com/data/music/links?songIds='.$this->audio_id;
		
		$ch = curl_init(); 
		$timeout = 10; 
		curl_setopt ($ch, CURLOPT_URL, $url); 
		curl_setopt ($ch, CURLOPT_HEADER, 0); 
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout); 

		$contents = curl_exec($ch);
		curl_close($ch);
		
		$body=json_decode($contents,true);
		$errorCode=$body['errorCode'];
		$songList=$body['data']['songList'];

		if($errorCode!=22000 || !$songList){
			$rs['code'] = 1001;
            $rs['msg'] = '歌曲不存在';
            return $rs;
		}


		$info['audio_link']=$songList[0]['songLink'];
		$info['audio_ext']=$songList[0]['format'];
		$info['time_len']=$songList[0]['time'];
		$info['audio_size']=$songList[0]['size'];
		$info['lrcLink']=$songList[0]['lrcLink'];

			

		$rs['info'][0]=$info;
 
    	return $rs;
	}*/	
	
	/**
	 * 用户余额 
	 * @desc 用于获取用户余额
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].coin 余额
	 * @return string msg 提示信息
	 */
	public function getCoin() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		
		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}
		
		
		$domain2 = new Domain_User();
		$coin=$domain2->getBalance($uid);

		$rs['info'][0]['coin']=$coin['coin'];
		return $rs;
	}		
	
	/**
     * 开播配置
     * @desc 用于开播前获取相关配置信息
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0]['gamelist'] 提示信息 
     * @return string info[0].msg 提示信息 
     * @return string msg 提示信息
     */
		
	public function getLiveCon(){

		$rs = array('code' => 0, 'msg' => '', 'info' =>array());
		
		/* $domain = new Domain_Live();
		
		$result = $domain->superStopRoom($this->uid,$this->token,$this->liveuid,$this->type);
		if($result==700){
			$rs['code'] = 700;
            $rs['msg'] = 'token已过期，请重新登陆';
            return $rs;
		}else if($result==1001){
			$rs['code'] = 1001;
            $rs['msg'] = '你不是超管，无权操作';
            return $rs;
		} */
		
		$gamelist=array(
			array('id'=>'1','game_name'=>'王者荣耀',"package_name"=>"com.tencent.tmgp.sgame",'game_key'=>'7A3EA0ECD91632C8','scheme'=>'QQ41D4D384://','video_switch'=>'1','thumb'=>$this->get_upload_path('/default.jpg')),
			array('id'=>'2','game_name'=>'天天酷跑',"package_name"=>"com.tencent.tmgp.sgame",'game_key'=>'C454F6D7C02E0C65','scheme'=>'qiuqiudazuozhan://://','video_switch'=>'0','thumb'=>$this->get_upload_path('/default.jpg')),
		);
		$rs['info'][0]['gamelist']=$gamelist;
 
    	return $rs;
	}	
	
	/**
	 * 游戏截图
	 * @desc 用于定时更新游戏房间直播封面
	 * @return int code 操作码，0表示成功
	 * @return array info
	 * @return string msg 提示信息
	 */
	public function setLiveThumb() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$uid = $this->uid;
		$token=$this->checkNull($this->token);


		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			/* $rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！'; */
			return $rs;
		}

		$thumb='';
		//file_put_contents('./zhifu.txt',date('y-m-d H:i:s').' 提交参数信息 request:'.json_encode($_REQUEST)."\r\n",FILE_APPEND);
		//file_put_contents('./zhifu.txt',date('y-m-d H:i:s').' 提交参数信息 _FILES:'.json_encode($_FILES)."\r\n",FILE_APPEND);
		if($_FILES){
			if ($_FILES["file"]["error"] > 0) {
				/* $rs['code'] = 1003;
				$rs['msg'] = T('failed to upload file with error: {error}', array('error' => $_FILES['file']['error']));
				DI()->logger->debug('failed to upload file with error: ' . $_FILES['file']['error']); */
				return $rs;
			}
			
			if(!$this->checkExt($_FILES["file"]['name'])){
				/* $rs['code']=1004;
				$rs['msg']='图片仅能上传 jpg,png,jpeg'; */
				return $rs;
			}

			$uptype=DI()->config->get('app.uptype');
			if($uptype==1){
				//七牛
				$url = DI()->qiniu->uploadFile($_FILES['file']['tmp_name']);

				if (!empty($url)) {
					$thumb=  $url.'?imageView2/2/w/600/h/600'; //600 X 600
				}
			}else if($uptype==2){
				//本地上传
				//设置上传路径 设置方法参考3.2
				DI()->ucloud->set('save_path','thumb/'.date("Ymd"));

				//新增修改文件名设置上传的文件名称
			   // DI()->ucloud->set('file_name', $this->uid);

				//上传表单名
				$res = DI()->ucloud->upfile($_FILES['file']);
				
				$files='../upload/'.$res['file'];
				$PhalApi_Image = new Image_Lite();
				//打开图片
				$PhalApi_Image->open($files);
				/**
				 * 可以支持其他类型的缩略图生成，设置包括下列常量或者对应的数字：
				 * IMAGE_THUMB_SCALING      //常量，标识缩略图等比例缩放类型
				 * IMAGE_THUMB_FILLED       //常量，标识缩略图缩放后填充类型
				 * IMAGE_THUMB_CENTER       //常量，标识缩略图居中裁剪类型
				 * IMAGE_THUMB_NORTHWEST    //常量，标识缩略图左上角裁剪类型
				 * IMAGE_THUMB_SOUTHEAST    //常量，标识缩略图右下角裁剪类型
				 * IMAGE_THUMB_FIXED        //常量，标识缩略图固定尺寸缩放类型
				 */
				$PhalApi_Image->thumb(660, 660, IMAGE_THUMB_SCALING);
				$PhalApi_Image->save($files);							
				
				$thumb=$res['url'];
			}
			
			@unlink($_FILES['file']['tmp_name']);	

			$dataroom=array(
				"thumb"=>$thumb,
			);	

			$domain = new Domain_Live();
			$result = $domain->setLiveThumb($uid,$dataroom);		
			
			if($result===false){
				/* $rs['code'] = 1011;
				$rs['msg'] = '开播失败，请重试'; */
				return $rs;
			}
			
		}
		//$rs['info'][0] = $info;

		return $rs;
	}
	
}
