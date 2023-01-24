<?php
session_start();
class Api_User extends Api_Common {

	public function getRules() {
		return array(
			'iftoken' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
			),
			
			'getBaseInfo' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'version_ios' => array('name' => 'version_ios', 'type' => 'string', 'desc' => 'IOS版本号'),
			),
			
			
			'updateAvatar' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'file' => array('name' => 'file','type' => 'file', 'min' => 0, 'max' => 1024 * 1024 * 30, 'range' => array('image/jpg', 'image/jpeg', 'image/png'), 'ext' => array('jpg', 'jpeg', 'png')),
			),
			
			'updateFields' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'fields' => array('name' => 'fields', 'type' => 'string', 'require' => true, 'desc' => '修改信息，json字符串'),
			),
			
			'updatePass' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'oldpass' => array('name' => 'oldpass', 'type' => 'string', 'require' => true, 'desc' => '旧密码'),
				'pass' => array('name' => 'pass', 'type' => 'string', 'require' => true, 'desc' => '新密码'),
				'pass2' => array('name' => 'pass2', 'type' => 'string', 'require' => true, 'desc' => '确认密码'),
			),
			
			'getBalance' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
			),
			
			'getProfit' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
			),
			
			'setCash' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'num' => array('name' => 'num', 'type' => 'int', 'min' => 100, 'require' => true, 'desc' => '金币数量'),
				'bankcard' => array('name' => 'bankcard', 'type' => 'string', 'require' => true, 'desc' => '银行卡号'),
				'bankname' => array('name' => 'bankname', 'type' => 'string', 'require' => true, 'desc' => '收款人'),
				'type' => array('name' => 'type', 'type' => 'int','require' => true,'default'=>0, 'min' => 0, 'desc' => '提现类型'),
			),
			
			'setAttent' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'require' => true, 'desc' => '对方ID'),
			),
			
			'isAttent' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'require' => true, 'desc' => '对方ID'),
			),
			
			'isBlacked' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true, 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'require' => true, 'desc' => '对方ID'),
			),
			'checkBlack' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'require' => true, 'desc' => '对方ID'),
			),

			'setBlack' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'require' => true, 'desc' => '对方ID'),
			),
			
			'getBindCode' => array(
				'mobile' => array('name' => 'mobile', 'type' => 'string', 'min' => 1, 'require' => true,  'desc' => '手机号'),
			),
			
			'setMobile' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'mobile' => array('name' => 'mobile', 'type' => 'string', 'min' => 1, 'require' => true,  'desc' => '手机号'),
				'code' => array('name' => 'code', 'type' => 'string', 'min' => 1, 'require' => true,   'desc' => '验证码'),
			),

			'getInviteList' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),
			),
			
			'getFollowsList' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true, 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int',  'require' => true, 'desc' => '对方ID'),
				'key' => array('name' => 'key', 'type' => 'string', 'desc' => '搜索关键词'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),
			),
			
			'getFansList' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true, 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'require' => true, 'desc' => '对方ID'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),
			),
			
			'getBlackList' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'require' => true, 'desc' => '对方ID'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),
			),
			
			'getLiverecord' => array(
				'touid' => array('name' => 'touid', 'type' => 'int', 'require' => true, 'desc' => '对方ID'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),
			),
			
			'getAliCdnRecord' => array(
                'id' => array('name' => 'id', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '直播记录ID'),
            ),
			
			'getUserHome' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'require' => true, 'desc' => '对方ID'),
			),
			
			'getContributeList' => array(
				'touid' => array('name' => 'touid', 'type' => 'int', 'require' => true, 'desc' => '对方ID'),
				'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
			),
			
			'getPmUserInfo' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'require' => true, 'desc' => '对方ID'),
			),
			
			'getMultiInfo' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'uids' => array('name' => 'uids', 'type' => 'string', 'min' => 1,'require' => true, 'desc' => '用户ID，多个以逗号分割'),

			),
			'Bonus' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
			),
			'setDistribut' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'code' => array('name' => 'code', 'type' => 'string', 'require' => true, 'desc' => '邀请码'),
				'signature' => array('name' => 'signature', 'type' => 'string',  'require'=>'true', 'desc' => '签名'),
			),
			'getLikeVideos'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
			),

			'getMyWallet'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
			),

			'exchangeTicket'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'likenum' => array('name' => 'likenum', 'type' => 'int', 'require' => true, 'desc' => '用户总赞数'),

			),

			'orderLists'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'type' => array('name' => 'type', 'type' => 'int','require' => true, 'min' => 0, 'desc' => '订单类型'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数')
			),
			'incomeLists'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'type' => array('name' => 'type', 'type' => 'int','require' => true, 'min' => 0, 'desc' => '订单类型'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数')
			),
			'getVipInfo'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int',  'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string',  'desc' => '用户token'),

			),
			'agentIncome'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token')
			),
			'performance_list'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数')
			),
			'view_list'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数')
			),

			
			
		);
	}
	/**
	 * 判断token
	 * @desc 用于判断token
	 * @return int code 操作码，0表示成功， 1表示用户不存在
	 * @return array info 
	 * @return string msg 提示信息
	 */
	public function iftoken() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$checkToken=$this->checkToken($this->uid,$this->token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		//获取用户信息存入app本地
		$domain=new Domain_User();
		$info=$domain->getBaseInfo($this->uid);
		$rs['info'][0]=$info;
		return $rs;
	}

	//充金币规则
	public function getChargeRules() {
			$rs = array('code' => 0, 'msg' => '', 'info' => array());

			$model = new Model_User();
			$res = $model->getChargeRules();
			$rs['info'] = $res;
			return $rs;
	}

	/**
	 * 获取用户信息
	 * @desc 用于获取单个用户基本信息
	 * @return int code 操作码，0表示成功， 1表示用户不存在
	 * @return array info 
	 * @return array info[0] 用户信息
	 * @return int info[0].id 用户ID
	 * @return string info[0].level 等级
	 * @return string info[0].lives 直播数量
	 * @return string info[0].follows 关注数
	 * @return string info[0].fans 粉丝数
	 * @return string info[0].agent_switch 分销开关
	 * @return string info[0].family_switch 家族开关
	 * @return string msg 提示信息
	 */
	public function getBaseInfo() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$checkToken=$this->checkToken($this->uid,$this->token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		$domain = new Domain_User();
		$info = $domain->getBaseInfo($this->uid);
		
		$configpub=$this->getConfigPub();
		
		$ios_shelves=$configpub['ios_shelves'];
		

		/* 个人中心菜单 */
		$version_ios=$this->version_ios;
		$list=array();
		$shelves=1;
		if($version_ios==$ios_shelves){
			$agent_switch=0;
			$family_switch=0;
			$shelves=0;
		}

		$rs['info'][0] = $info;

		return $rs;
	}

	/**
	 * 获取VIP充值规则
	 * @desc 用于获取单个用户基本信息
	 * @return int code 操作码，0表示成功， 1表示没有数据
	 * @return array info 
	 * @return string msg 提示信息
	 */
	public function getVipInfo() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid = $this->uid;
		$domain = new Domain_User();
		$info = $domain->getVipInfo($this->uid);
		$rs['info'][0] = $info;

		return $rs;
	}

	/**
	 * 头像上传 (七牛)[根据后台的配置信息来决定是走七牛云存储还是走腾讯云存储]
	 * @desc 用于用户修改头像
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string list[0].avatar 用户主头像
	 * @return string list[0].avatar_thumb 用户头像缩略图
	 * @return string msg 提示信息
	 */
	public function updateAvatar() {
		$rs = array('code' => 0 , 'msg' => '', 'info' => array());

		$checkToken=$this->checkToken($this->uid,$this->token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		if (!isset($_FILES['file'])) {
			$rs['code'] = 1001;
			$rs['msg'] = T('miss upload file');
			return $rs;
		}

		if ($_FILES["file"]["error"] > 0) {
			$rs['code'] = 1002;
			$rs['msg'] = T('failed to upload file with error: {error}', array('error' => $_FILES['file']['error']));
			DI()->logger->debug('failed to upload file with error: ' . $_FILES['file']['error']);
			return $rs;
		}

		//$uptype=DI()->config->get('app.uptype');
		
		//获取后台配置的云存储方式
		$configpri=$this->getConfigPri();
		$cloudtype=$configpri['cloudtype'];


		if($cloudtype==1){
			//七牛
			$url = DI()->qiniu->uploadFile($_FILES['file']['tmp_name'],$configpri['qiniu_accesskey'],$configpri['qiniu_secretkey'],$configpri['qiniu_bucket'],$configpri['qiniu_domain_url']);


			if (!empty($url)) {
				$avatar=  $url.'?imageView2/2/w/600/h/600'; //600 X 600
				$avatar_thumb=  $url.'?imageView2/2/w/200/h/200'; // 200 X 200
				$data=array(
					"avatar"=>$avatar,
					"avatar_thumb"=>$avatar_thumb,
				);

				
				/* 统一服务器 格式 */
				/* $space_host= DI()->config->get('app.Qiniu.space_host');
				$avatar2=str_replace($space_host.'/', "", $avatar);
				$avatar_thumb2=str_replace($space_host.'/', "", $avatar_thumb);
				$data2=array(
					"avatar"=>$avatar2,
					"avatar_thumb"=>$avatar_thumb2,
				); */
			}


		}else if($cloudtype==3){
			//本地上传
			//设置上传路径 设置方法参考3.2
			DI()->ucloud->set('save_path','avatar/'.date("Ymd"));

			//新增修改文件名设置上传的文件名称
		   // DI()->ucloud->set('file_name', $this->uid);

			//上传表单名
			$res = DI()->ucloud->upfile($_FILES['file']);
			
			$files='../upload/'.$res['file'];
			$newfiles=str_replace(".png","_thumb.png",$files);
			$newfiles=str_replace(".jpg","_thumb.jpg",$newfiles);
			$newfiles=str_replace(".gif","_thumb.gif",$newfiles); 
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

			// 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg
			
			$PhalApi_Image->thumb(660, 660, IMAGE_THUMB_SCALING);
			$PhalApi_Image->save($files);

			$PhalApi_Image->thumb(200, 200, IMAGE_THUMB_SCALING);
			$PhalApi_Image->save($newfiles);			
			
			$avatar=  $res['url']; //600 X 600
			
			$avatar_thumb=str_replace(".png","_thumb.png",$avatar);
			$avatar_thumb=str_replace(".jpg","_thumb.jpg",$avatar_thumb);
			$avatar_thumb=str_replace(".gif","_thumb.gif",$avatar_thumb); 

			$data=array(
				"avatar"=>$avatar,
				"avatar_thumb"=>$avatar_thumb,
			);
			
		}else if($cloudtype==2){

			//腾讯云存储
			
				require(API_ROOT.'/public/txcloud/include.php');

				$config=array(
					'app_id' => $configpri['txcloud_appid'],
					'secret_id' => $configpri['txcloud_secret_id'],
					'secret_key' => $configpri['txcloud_secret_key'],
					'region' => $configpri['txcloud_region'],   // bucket所属地域：华北 'tj' 华东 'sh' 华南 'gz'
					'timeout' => 60
				);


				$bucket=$configpri['txcloud_bucket'];
				//bucketname
				//uploadlocalpath
				/* $src = $_FILES['file'];//'./hello.txt'; */
				$src = $_FILES["file"]["tmp_name"];//'./hello.txt';
				
				
				//cosfolderpath
				$folder = '/'.$configpri['tximgfolder'];
				//cospath
				$dst = $folder.'/'.$_FILES["file"]["name"];
				//config your information
		 
				
				date_default_timezone_set('PRC');
				
				$cosApi = new 	\QCloud\Cos\Api($config);
				/* $cosApi->delFile($bucket,$dst);  // 上传文件前删除相同的文件 */
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
					$avatar=  $this->get_path($url); //600 X 600
					$avatar_thumb=  $this->get_path($url).'?imageView2/2/w/200/h/200'; // 200 X 200
					$data=array(
						"avatar"=>$avatar,
						"avatar_thumb"=>$avatar_thumb,
					);
				}
		}
		
		@unlink($_FILES['file']['tmp_name']);

		/* 清除缓存 */
		$this->delCache("userinfo_".$this->uid);
		
		$domain = new Domain_User();
		$info = $domain->userUpdate($this->uid,$data);
		$data['avatar']=$this->get_upload_path($data['avatar']);
		$data['avatar_thumb']=$this->get_upload_path($data['avatar_thumb']);
		$rs['info'][0] = $data;

		return $rs;

	}
	
	/**
	 * 转化url地址 主要是腾讯云或者七牛云原始域名
	 */
	protected function get_path($url){
		$configpri=$this->getConfigPri();

		if($configpri['cloudtype']==2){

			$new_url = str_replace('coscd.myqcloud','cos.ap-chengdu.myqcloud',$url);
			
			if ($new_url) {
				return $new_url;
			}
			
		}
		return $url;
	}
	
	
	/**
     * curl上传文件
     * 
     * @param unknown $url
     * @param unknown $filename
     * @param unknown $path
     * @param unknown $type
     */
    function upload_file($url,$filename,$path,$type){
        //php 5.5以上的用法
        if (class_exists('\CURLFile')) {
            $data = array('file' => new \CURLFile(realpath($path),$type,$filename));
        } else {
            $data = array(
                'file'=>'@'.realpath($path).";type=".$type.";filename=".$filename
            );
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true );
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $return_data = curl_exec($ch);
        curl_close($ch);
        return $return_data;
    }
	/**
	 * 修改用户信息
	 * @desc 用于修改用户信息
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string list[0].msg 修改成功提示信息 
	 * @return string msg 提示信息
	 */
	public function updateFields() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$checkToken=$this->checkToken($this->uid,$this->token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		$fields=json_decode($this->fields,true);
		
		$domain = new Domain_User();
		foreach($fields as $k=>$v){
			$fields[$k]=$this->checkNull($v);
		}
		
		if(array_key_exists('user_nicename', $fields)){
			if($fields['user_nicename']==''){
				$rs['code'] = 1002;
				$rs['msg'] = '昵称不能为空';
				return $rs;
			}
			$isexist = $domain->checkName($this->uid,$fields['user_nicename']);
			if(!$isexist){
				$rs['code'] = 1002;
				$rs['msg'] = '昵称重复，请修改';
				return $rs;
			}
			$fields['user_nicename']=$this->filterField($fields['user_nicename']);
		}
		//手机号
		if(array_key_exists('mobile', $fields)){
			if($fields['mobile']==''){
				$rs['code'] = 1002;
				$rs['msg'] = '手机号码不能为空';
				return $rs;
			}
			$isexist = $domain->checkMobile($this->uid,$fields['mobile']);
			if(!$isexist){
				$rs['code'] = 1002;
				$rs['msg'] = '手机号码重复，请修改';
				return $rs;
			}
			$fields['mobile']=$this->filterField($fields['mobile']);
		}

		//根据生日计算年龄
		if(array_key_exists('birthday', $fields)){
			if($fields['birthday']==''){
				$rs['code'] = 1002;
				$rs['msg'] = '请选择生日';
				return $rs;
			}

			$now=time();
			$time1=strtotime($fields['birthday']);

			$nowYear=date("Y",$now);
			$birthdayYear=date("Y",$time1);

			$age=$nowYear-$birthdayYear;

			$fields['age']=$age;


		}
		
		
		$info = $domain->userUpdate($this->uid,$fields);
	 
		if($info===false){
			$rs['code'] = 1001;
			$rs['msg'] = '修改失败';
			return $rs;
		}
		/* 清除缓存 */
		$this->delCache("userinfo_".$this->uid);
		$rs['info'][0]['msg']='修改成功';
		return $rs;
	}

	/**
	 * 修改密码
	 * @desc 用于修改用户信息
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string list[0].msg 修改成功提示信息
	 * @return string msg 提示信息
	 */
	public function updatePass() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$token=$this->token;
		$oldpass=$this->oldpass;
		$pass=$this->pass;
		$pass2=$this->pass2;
		
		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
		if($pass != $pass2){
			$rs['code'] = 1002;
			$rs['msg'] = '两次新密码不一致';
			return $rs;
		}
		
		$check = $this->passcheck($pass);
		if($check== 0 ){
			$rs['code'] = 1004;
			$rs['msg'] = '密码为6-12位数字与字母组合';
			return $rs;										
		}else if($check== 2){
			$rs['code'] = 1005;
			$rs['msg'] = '密码不能纯数字或纯字母';
			return $rs;										
		}
		
		$domain = new Domain_User();
		$info = $domain->updatePass($uid,$oldpass,$pass);
	 
		if($info==1003){
			$rs['code'] = 1003;
			$rs['msg'] = '旧密码错误';
			return $rs;
		}else if($info===false){
			$rs['code'] = 1001;
			$rs['msg'] = '修改失败';
			return $rs;
		}

		$rs['info'][0]['msg']='修改成功';
		return $rs;
	}	
	
	/**
	 * 我的钻石
	 * @desc 用于获取用户余额,充值规则 支付方式信息
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].coin 用户余额
	 * @return array info[0].rules 充值规则
	 * @return string info[0].rules[].id 充值规则
	 * @return string info[0].rules[].coin 钻石
	 * @return string info[0].rules[].money 价格
	 * @return string info[0].rules[].money_ios 苹果充值价格
	 * @return string info[0].rules[].product_id 苹果项目ID
	 * @return string info[0].rules[].give 赠送钻石，为0时不显示赠送
	 * @return string info[0].aliapp_switch 支付宝开关，0表示关闭，1表示开启
	 * @return string info[0].aliapp_partner 支付宝合作者身份ID
	 * @return string info[0].aliapp_seller_id 支付宝帐号	
	 * @return string info[0].aliapp_key_android 支付宝安卓密钥
	 * @return string info[0].aliapp_key_ios 支付宝苹果密钥
	 * @return string info[0].wx_switch 微信支付开关，0表示关闭，1表示开启
	 * @return string info[0].wx_appid 开放平台账号AppID
	 * @return string info[0].wx_appsecret 微信应用appsecret
	 * @return string info[0].wx_mchid 微信商户号mchid
	 * @return string info[0].wx_key 微信密钥key
	 * @return string msg 提示信息
	 */
	public function getBalance() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$checkToken=$this->checkToken($this->uid,$this->token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
		$domain = new Domain_User();
		$info = $domain->getBalance($this->uid);
		
		$key='getChargeRules';
		$rules=$this->getcaches($key);
		$rules=false;
		if(!$rules){
			$rules= $domain->getChargeRules();
			$this->setcaches($key,$rules,60);
		}
		$info['rules'] =$rules;
		
		$configpri=$this->getConfigPri();
		
		$info['aliapp_switch']=$configpri['aliapp_switch'];
		$info['aliapp_partner']=$configpri['aliapp_switch']==1?$configpri['aliapp_partner']:'';
		$info['aliapp_seller_id']=$configpri['aliapp_switch']==1?$configpri['aliapp_seller_id']:'';
		$info['aliapp_key_android']=$configpri['aliapp_switch']==1?$configpri['aliapp_key_android']:'';
		$info['aliapp_key_ios']=$configpri['aliapp_switch']==1?$configpri['aliapp_key_ios']:'';

		$info['wx_switch']=$configpri['wx_switch'];
		$info['wx_appid']=$configpri['wx_switch']==1?$configpri['wx_appid']:'';
		$info['wx_appsecret']=$configpri['wx_switch']==1?$configpri['wx_appsecret']:'';
		$info['wx_mchid']=$configpri['wx_switch']==1?$configpri['wx_mchid']:'';
		$info['wx_key']=$configpri['wx_switch']==1?$configpri['wx_key']:'';
		
	 
		$rs['info'][0]=$info;
		return $rs;
	}
	
	/**
	 * 我的收益
	 * @desc 用于获取用户收益，包括可体现金额，今日可提现金额
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].votes 总映票
	 * @return string info[0].todaycash 今日可体现金额
	 * @return string info[0].total 可体现金额
	 * @return string msg 提示信息
	 */
	public function getProfit() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$checkToken=$this->checkToken($this->uid,$this->token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		} else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
		$domain = new Domain_User();
		$info = $domain->getProfit($this->uid);
	 
		$rs['info'][0]=$info;
		return $rs;
	}	
	
	/**
	 * 用户提现
	 * @desc 用于进行用户提现
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].msg 提现成功信息
	 * @return string msg 提示信息
	 */
	public function setCash() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		

		$checkToken=$this->checkToken($this->uid,$this->token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		$data['coin_num'] = $this->num;
		$data['bankcard'] = $this->bankcard;
		$data['bankname'] = $this->bankname;
		$data['uid'] = $this->uid;
		$data['type'] = $this->type;
		
		$domain = new Domain_User();
		$info = $domain->setCash($data);
		if($info==1001){
			$rs['code'] = 1001;
			$rs['msg'] = '今日提现已达上限';
			return $rs;
		}else if($info==1003){
			$rs['code'] = 1003;
			$rs['msg'] = '请先进行身份认证';
			return $rs;
		}else if($info==1004){
			$config=$this->getConfigPri();
			$rs['code'] = 1004;
			$rs['msg'] = '提现最低额度为'.$config['cash_min'].'元';
			return $rs;
		}else if($info==1005){
			$rs['code'] = 1005;
			$rs['msg'] = $this->type ==1 ? '现金不足':'金币不足';
			return $rs;
		}else if(!$info){
			$rs['code'] = 1002;
			$rs['msg'] = '提现失败，请重试';
			return $rs;
		}
	 
		$rs['info'][0]['msg']='提现成功,等待审核';
		return $rs;
	}		
	/**
	 * 判断是否关注
	 * @desc 用于判断是否关注
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].isattent 关注信息，0表示未关注，1表示已关注
	 * @return string msg 提示信息
	 */
	public function isAttent() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$isBlackUser=$this->isBlackUser($this->uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
		$info = $this->isAttention($this->uid,$this->touid);
	 
		$rs['info'][0]['isattent']=(string)$info;
		return $rs;
	}			
	
	/**
	 * 关注/取消关注
	 * @desc 用于关注/取消关注
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].isattent 关注信息，0表示未关注，1表示已关注
	 * @return string msg 提示信息
	 */
	public function setAttent() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());


		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		$touid=$this->touid;
		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		$isBlackUser=$this->isBlackUser($uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
		if($uid==$touid){
			$rs['code']=1001;
			$rs['msg']='不能关注自己';
			return $rs;	
		}
		$domain = new Domain_User();
		$info = $domain->setAttent($uid,$touid);
	 
		$rs['info'][0]['isattent']=(string)$info;
		return $rs;
	}			
	
	/**
	 * 判断是否拉黑
	 * @desc 用于判断是否拉黑
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].isattent  拉黑信息,0表示未拉黑，1表示已拉黑
	 * @return string msg 提示信息
	 */
	public function isBlacked() {
			$rs = array('code' => 0, 'msg' => '', 'info' => array());
			$isBlackUser=$this->isBlackUser($this->uid);
			 if($isBlackUser=='0'){
				$rs['code'] = 700;
				$rs['msg'] = '该账号已被禁用';
				return $rs;
			}
			
			$info = $this->isBlack($this->uid,$this->touid);
		 
			$rs['info'][0]['isblack']=(string)$info;
			return $rs;
	}	

	/**
	 * 检测拉黑状态
	 * @desc 用于私信聊天时判断私聊双方的拉黑状态
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].u2t  是否拉黑对方,0表示未拉黑，1表示已拉黑
	 * @return string info[0].t2u  是否被对方拉黑,0表示未拉黑，1表示已拉黑
	 * @return string msg 提示信息
	 */
	public function checkBlack() {
			$rs = array('code' => 0, 'msg' => '', 'info' => array());

			$checkToken=$this->checkToken($this->uid,$this->token);
			if($checkToken==700){
				$rs['code'] = $checkToken;
				$rs['msg'] = '您的登陆状态失效，请重新登陆！';
				return $rs;
			}else if($checkToken==10020){
				$rs['code'] = 700;
				$rs['msg'] = '该账号已被禁用';
				return $rs;
			}

			/*$isBlackUser=$this->isBlackUser($this->uid);
			 if($isBlackUser=='0'){
				$rs['code'] = 700;
				$rs['msg'] = '该账号已被禁用';
				return $rs;
			}*/
			
			$u2t = $this->isBlack($this->uid,$this->touid);
			$t2u = $this->isBlack($this->touid,$this->uid);
			$isattent=$this->isAttention($this->touid,$this->uid);

		 
			$rs['info'][0]['u2t']=(string)$u2t;
			$rs['info'][0]['t2u']=(string)$t2u;
			$rs['info'][0]['isattent']=(string)$isattent;
			return $rs;
	}			
		
	/**
	 * 拉黑/取消拉黑
	 * @desc 用于拉黑/取消拉黑
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].isblack 拉黑信息,0表示未拉黑，1表示已拉黑
	 * @return string msg 提示信息
	 */
	public function setBlack() {
			$rs = array('code' => 0, 'msg' => '', 'info' => array());

			$uid=$this->uid;
			$token=$this->checkNull($this->token);

			$checkToken=$this->checkToken($uid,$token);
			if($checkToken==700){
				$rs['code'] = $checkToken;
				$rs['msg'] = '您的登陆状态失效，请重新登陆！';
				return $rs;
			}else if($checkToken==10020){
				$rs['code'] = 700;
				$rs['msg'] = '该账号已被禁用';
				return $rs;
			}

			$isBlackUser=$this->isBlackUser($this->uid);
			 if($isBlackUser=='0'){
				$rs['code'] = 700;
				$rs['msg'] = '该账号已被禁用';
				return $rs;
			}
			
			$domain = new Domain_User();
			$info = $domain->setBlack($this->uid,$this->touid);
		 
			$rs['info'][0]['isblack']=(string)$info;
			return $rs;
	}		
	
	/**
	 * 获取找回密码短信验证码
	 * @desc 用于找回密码获取短信验证码
	 * @return int code 操作码，0表示成功,2发送失败
	 * @return array info 
	 * @return array info[0]  
	 * @return string msg 提示信息
	 */
	 
	public function getBindCode() {
		$rs = array('code' => 0, 'msg' => '发送成功', 'info' => array());
		
		$mobile = $this->mobile;
		
		$ismobile=$this->checkMobile($mobile);
		if(!$ismobile){
			$rs['code']=1001;
			$rs['msg']='请输入正确的手机号';
			return $rs;	
		}

		if($_SESSION['set_mobile']==$mobile && $_SESSION['set_mobile_expiretime']> time() ){
			$rs['code']=1002;
			$rs['msg']='验证码5分钟有效，请勿多次发送';
			return $rs;
		}

		$mobile_code = $this->random(6,1);
		
		/* 发送验证码 */
	 	$result=$this->sendCode($mobile,$mobile_code);
		if($result['code']===0){
			$this->setcaches('set_mobile'.$mobile,$mobile,300);

			$_SESSION['set_mobile'] = $mobile;
			$_SESSION['set_mobile_code'] = $mobile_code;
			$_SESSION['set_mobile_expiretime'] = time() +60*5;	
		}else if($result['code'] ===667){
			$this->setcaches('set_mobile'.$mobile,$mobile,300);
			$rs['code']=1002;
			$rs['msg']=$result['msg'];
			$_SESSION['set_mobile'] = $mobile;

			$_SESSION['set_mobile_code'] = '123456';
			$_SESSION['set_mobile_expiretime'] = time() +60*5;
		}else{

			$rs['code']=1002;
			$rs['msg']=$result['msg'];
		} 
		
		
		
		return $rs;
	}		

	/**
	 * 绑定手机号
	 * @desc 用于用户绑定手机号
	 * @return int code 操作码，0表示成功，非0表示有错误
	 * @return array info 
	 * @return object info[0].msg 绑定成功提示
	 * @return string msg 提示信息
	 */
	public function setMobile() {

		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$isBlackUser=$this->isBlackUser($this->uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 800;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		$mobile = $this->mobile;
		$mobile2= $this->getcaches('set_mobile'.$this->mobile);
		
		if($mobile != $_SESSION['set_mobile']){
			$rs['code'] = 1001;
			$rs['msg'] = '手机号码不一致';
			return $rs;					
		}

		if($this->code!=$_SESSION['set_mobile_code']){
			$rs['code'] = 1002;
			$rs['msg'] = '验证码错误';
			return $rs;					
		}

		$checkToken=$this->checkToken($this->uid,$this->token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 800;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
			
		$domain = new Domain_User();

		//更新用户信息

		$data=array("mobile"=>$this->mobile,'user_login'=>$this->mobile,'user_nicename'=>'手机用户'.substr($this->mobile,-4));
		$result = $domain->userUpdate($this->uid,$data);
		if($result==0){
			$rs['code'] = $result;
			$rs['msg'] = '绑定失败';
			return $rs;
		}
	
		$rs['msg'] = '绑定成功';
		$rs['info'][0] = $result;

		return $rs;
	}	


	/**
	 * 推荐列表
	 * @desc 用于获取用户的推荐列表
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string msg 提示信息
	 */
	public function getInviteList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$checkToken=$this->checkToken($this->uid,$this->token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 800;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		$domain = new Domain_User();
		$info = $domain->getInviteList($this->uid,$this->p);
	 
		$rs['info']=$info;
		return $rs;
	}	


	
	/**
	 * 关注列表
	 * @desc 用于获取用户的关注列表
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[].isattent 是否关注,0表示未关注，1表示已关注
	 * @return string msg 提示信息
	 */
	public function getFollowsList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$isBlackUser=$this->isBlackUser($this->uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		$key=$this->checkNull($this->key);
		
		$domain = new Domain_User();
		$info = $domain->getFollowsList($this->uid,$this->touid,$this->p,$key);
	 
		$rs['info']=$info;
		
		return $rs;
	}		
	
	/**
	 * 粉丝列表
	 * @desc 用于获取用户的关注列表
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[].isattent 是否关注,0表示未关注，1表示已关注
	 * @return string msg 提示信息
	 */
	public function getFansList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$isBlackUser=$this->isBlackUser($this->uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
		$domain = new Domain_User();
		$info = $domain->getFansList($this->uid,$this->touid,$this->p);
	 
		$rs['info']=$info;
		return $rs;
	}	

	/**
	 * 黑名单列表
	 * @desc 用于获取用户的名单列表
	 * @return int code 操作码，0表示成功
	 * @return array info 用户基本信息
	 * @return string msg 提示信息
	 */
	public function getBlackList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$isBlackUser=$this->isBlackUser($this->uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
		$domain = new Domain_User();
		$info = $domain->getBlackList($this->uid,$this->touid,$this->p);
	 
		$rs['info']=$info;
		return $rs;
	}		
	
	/**
	 * 直播记录
	 * @desc 用于获取用户的直播记录
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[].nums 观看人数
	 * @return string info[].datestarttime 格式化的开播时间
	 * @return string info[].dateendtime 格式化的结束时间
	 * @return string info[].video_url 回放地址
	 * @return string info[].file_id 回放标示
	 * @return string msg 提示信息
	 */
	public function getLiverecord() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$domain = new Domain_User();
		$info = $domain->getLiverecord($this->touid,$this->p);
	 
		$rs['info']=$info;
		return $rs;
	}	

    /**
     *获取阿里云cdn录播地址
     *@desc 如果使用的阿里云cdn，则使用该接口获取录播地址
     *@return int code 操作码，0表示成功
     *@return string info[0].url 录播视频地址
	 * @return string msg 提示信息
    */		
    public function getAliCdnRecord(){

        $rs = array('code' => 0,'msg' => '', 'info' => array());
        $domain = new Domain_User();
        $info = $domain->getAliCdnRecord($this->id);
        if($info==1001){
            $rs['code']=1001;
            $rs['msg']='Access key Id or access key secret is invalid';
            return $rs;
        }else if( $info==1002){
            $rs['code']=1002;
            $rs['msg']='直播回放不存在';
            return $rs;
        }

        $rs['info'][0]['url']=$info;

        return $rs;
    }	


	/**
	 * 个人主页 
	 * @desc 用于获取个人主页数据
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].follows 关注数
	 * @return string info[0].fans 粉丝数
	 * @return string info[0].isattention 是否关注，0表示未关注，1表示已关注
	 * @return string info[0].isblack 我是否拉黑对方，0表示未拉黑，1表示已拉黑
	 * @return string info[0].isblack2 对方是否拉黑我，0表示未拉黑，1表示已拉黑
	 * @return array info[0].contribute 贡献榜前三
	 * @return array info[0].contribute[].avatar 头像
	 * @return string info[0].islive 是否正在直播，0表示未直播，1表示直播
	 * @return array info[0].liveinfo 直播信息
	 * @return array info[0].liverecord 直播记录
	 * @return array info[0].video 视频列表
	 * @return string msg 提示信息
	 */
	public function getUserHome() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$isBlackUser=$this->isBlackUser($this->uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
		$domain = new Domain_User();
		$info=$domain->getUserHome($this->uid,$this->touid);
		
		$rs['info'][0]=$info;
		return $rs;
	}		

	/**
	 * 贡献榜 
	 * @desc 用于获取个人主页数据
	 * @return int code 操作码，0表示成功
	 * @return array info 排行榜列表
	 * @return string info[].total 贡献总数
	 * @return string info[].userinfo 用户信息
	 * @return string msg 提示信息
	 */
	public function getContributeList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$domain = new Domain_User();
		$info=$domain->getContributeList($this->touid,$this->p);
		
		$rs['info']=$info;
		return $rs;
	}	
	
	/**
     * 私信用户信息
     * @desc 用于获取其他用户基本信息
     * @return int code 操作码，0表示成功，1表示用户不存在
     * @return array info   
     * @return string info[0].id 用户ID
     * @return string info[0].isattention 我是否关注对方，0未关注，1已关注
     * @return string info[0].isattention2 对方是否关注我，0未关注，1已关注
     * @return string msg 提示信息
     */
    public function getPmUserInfo() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		$isBlackUser=$this->isBlackUser($this->uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

        $info = $this->getUserInfo($this->touid);
		 if (empty($info)) {
            $rs['code'] = 1001;
            $rs['msg'] = T('user not exists');
            return $rs;
        }
        $info['isattention2']= (string)$this->isAttention($this->touid,$this->uid);
        $info['isattention']= (string)$this->isAttention($this->uid,$this->touid);
       
        $rs['info'][0] = $info;

        return $rs;
    }		

	/**
	 * 获取多用户信息 
	 * @desc 用于获取获取多用户信息
	 * @return int code 操作码，0表示成功
	 * @return array info 排行榜列表
	 * @return string info[].utot 是否关注，0未关注，1已关注
	 * @return string info[].ttou 对方是否关注我，0未关注，1已关注
	 * @return string msg 提示信息
	 */
	public function getMultiInfo() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$isBlackUser=$this->isBlackUser($this->uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
		$uids=explode(",",$this->uids);

		foreach ($uids as $k=>$userId) {
			if($userId){
				$userinfo= $this->getUserInfo($userId);
				if($userinfo){
					/*$userinfo['utot']= $this->isAttention($this->uid,$userId);
					
					$userinfo['ttou']= $this->isAttention($userId,$this->uid);*/
					$userinfo['isattent']= $this->isAttention($this->uid,$userId);
											
					$rs['info'][]=$userinfo;
																
				}					
			}
		}

		return $rs;
	}	

	/**
	 * 登录奖励 
	 * @desc 用于用户登录奖励
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].bonus_switch 登录开关，0表示未开启
	 * @return string info[0].bonus_day 登录天数,0表示已奖励
	 * @return string info[0].bonus_list 登录奖励列表
	 * @return string info[0].bonus_list[].day 登录天数
	 * @return string info[0].bonus_list[].coin 登录奖励
	 * @return string msg 提示信息
	 */
	public function Bonus() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		
		$info=$this->LoginBonus($uid,$token);
		
		if($info==700){
			$rs['code']=700;
			$rs['msg']='您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($info==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
		$rs['info'][0]=$info;

		return $rs;
	}		
	
	/**
	 * 设置分销上级 
	 * @desc 用于用户首次登录设置分销关系
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].msg 提示信息
	 * @return string msg 提示信息
	 */
	public function setDistribut() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		$code=$this->checkNull($this->code);
		$signature=$this->checkNull($this->signature);
		
		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
		if($code==''){
			$rs['code']=1001;
			$rs['msg']='请输入邀请码';
			return $rs;
		}

		$configpri=$this->getConfigPri();
		
		$len=strlen($configpri['signature']);

		$newsign=md5(md5($code).'#d51251e410368a0'.$uid.$token.'d586e01'.substr($configpri['signature'],1,$len-2).'d5186e');

		if($signature!=$newsign){
			 $rs['code'] = 1002;
			 $rs['msg']='签名错误';
			return $rs;
		}
		
		$domain = new Domain_User();
		$info=$domain->setDistribut($uid,$code);

		if($info==1000){
			$rs['code']=1000;
			$rs['msg']='邀请异常';
			return $rs;
		}
		if($info==1004){
			$rs['code']=1000;
			$rs['msg']='自己不能邀请自己哦';
			return $rs;
		}
		
		if($info==1002){
			$rs['code']=1002;
			$rs['msg']='邀请码错误';
			return $rs;
		}

		if($info==1003){
			$rs['code']=1003;
			$rs['msg']='已经邀请了';
			return $rs;
		}
		
		$rs['info'][0]['msg']='设置成功';

		return $rs;
	}

	/**
	 * 获取喜欢的视频
	 * @desc 用户获取用户喜欢的视频
	 * @return int code 状态码，0表示成功
	 * @return string msg 提示信息
	 * @return array info
	 * @return string info[0].uid 视频发布者id
	 * @return string info[0].title 视频标题
	 * @return string info[0].thumb 视频封面图
	 * @return string info[0].thumb_s 视频封面小图
	 * @return string info[0].href 视频地址
	 * @return string info[0].likes 视频被喜欢总数
	 * @return string info[0].views 视频被浏览数
	 * @return string info[0].comments 视频评论数
	 * @return string info[0].shares 视频被分享数
	 * @return string info[0].addtime 视频发布时间
	 * @return string info[0].city 视频发布城市
	 * @return string info[0].datetime 视频发布时间（格式化为汉字形式）
	 * @return string info[0].userinfo 视频发布者信息
	 */
	public function getLikeVideos(){
		$uid=$this->uid;
		$p=$this->p;

		

		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$domain = new Domain_User();
		$res=$domain->getLikeVideos($uid,$p);
		if($res==1001){
			$rs['code']=0;
			$rs['msg']="暂无视频列表";
			return $rs;
		}

		$rs['info']=$res;

		return $rs;
	}

	/**
	 * 获取我的钱包信息
	 * @desc 用于获取我的钱包信息
	 * @return int code 状态码，0表示成功
	 * @return string msg 提示信息
	 * @return string msg 提示信息
	 * @return int info[0].coin 用户的金币数
	 * @return int info[0].bonus 用户的分红金额
	 * @return int info[0].ticket 用户的可提现金额
	 */
	public function getMyWallet(){
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$uid=$this->uid;
		$token=$this->checkNull($this->token);

		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		$domain = new Domain_User();
		$res=$domain->getMyWallet($uid);



		$rs['info'][0]=$res;

		return $rs;

	}


	public function exchangeTicket(){

		$rs = array('code' => 0, 'msg' => '兑换成功', 'info' => array());
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		$likenum=$this->checkNull($this->likenum);

		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		if($likenum==""||!is_numeric($likenum)||$likenum<1){
			$rs['code'] = 1001;
			$rs['msg'] = '请填写赞数';
			return $rs;
		}

		$domain = new Domain_User();
		$res=$domain->exchangeTicket($uid,$likenum);

		if($res==1001){
			$rs['code']=1001;
			$rs['msg']="点赞数不足";
			return $rs;
		}

		return $rs;

	}

//充值明细
	public function orderLists(){
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		$type=$this->checkNull($this->type);


		$checkToken=$this->checkToken($this->uid,$this->token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 800;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
			
		$domain = new Domain_User();
		$res= $domain->orderLists($uid,$type,$this->p);
		
		$rs['info'] = $res;

		return $rs;
		


	}
//收支列表
	public function incomeLists(){
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		$type=$this->checkNull($this->type);


		$checkToken=$this->checkToken($this->uid,$this->token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 800;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
			
		$domain = new Domain_User();
		$res= $domain->incomeLists($uid,$type,$this->p);
		
		$rs['info'] = $res;

		return $rs;
		


	}


	//代理赚钱页面明细
	public function agentIncome(){
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		


		$checkToken=$this->checkToken($this->uid,$this->token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 800;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
			
		$domain = new Domain_User();
		$res= $domain->agentIncome($uid);
		
		$rs['info'] = $res;

		return $rs;
		


	}

	//代理业绩明细
	public function performance_list(){
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		


		$checkToken=$this->checkToken($this->uid,$this->token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 800;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
			
		$domain = new Domain_User();
		$res= $domain->performance_list($uid,$this->p);
		
		$rs['info'] = $res;

		return $rs;
		


	}

	//视频浏览历史记录
	public function view_list(){
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		


		$checkToken=$this->checkToken($this->uid,$this->token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 800;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
			
		$domain = new Domain_User();
		$res= $domain->view_list($uid,$this->p);
		
		$rs['info'] = $res;

		return $rs;
		


	}

	public function test(){
		$id='dsp_admin_1';
		$this->getUserInfo($id);


	}
}
