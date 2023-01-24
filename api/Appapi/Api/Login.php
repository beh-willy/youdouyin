<?php
session_start();
class Api_Login extends Api_Common { 
	public function getRules() {
        return array(
			'userLogin' => array(
                'user_login' => array('name' => 'user_login', 'type' => 'string', 'desc' => '账号'),
				'code' => array('name' => 'code', 'type' => 'string', 'require' => true,   'desc' => '验证码'),
				'uuid' => array('name' => 'uuid', 'type' => 'string',  'require' => false, 'default'=>'',  'desc' => '设备id'),
				'signature' => array('name' => 'signature', 'type' => 'string',  'require'=>'true', 'desc' => '签名'),
            ),
            //游客登录
            'userLogin1' => array(
                'uuid' => array('name' => 'uuid', 'type' => 'string', 'require' => true, 'desc' => '设备id'),
				'code' => array('name' => 'code', 'type' => 'string',  'desc' => '邀请码'),
				'signature' => array('name' => 'signature', 'type' => 'string',  'require'=>true, 'desc' => '签名'),
				'token' => array('name' => 'token', 'type' => 'string', 'desc' => '用户token'),
            ),
			
			'userFindPass' => array(
                'user_login' => array('name' => 'user_login', 'type' => 'string', 'require' => true,  'min' => '6',  'max'=>'30', 'desc' => '账号'),
				'user_pass' => array('name' => 'user_pass', 'type' => 'string',  'require' => true,  'min' => '1',  'max'=>'30', 'desc' => '密码'),
				'user_pass2' => array('name' => 'user_pass2', 'type' => 'string',  'require' => true,  'min' => '1',  'max'=>'30', 'desc' => '确认密码'),
                'code' => array('name' => 'code', 'type' => 'string', 'min' => 1, 'require' => true,   'desc' => '验证码'),
            ),	
			'userLoginByThird' => array(
                'openid' => array('name' => 'openid', 'type' => 'string', 'min' => 1, 'require' => true,   'desc' => '第三方openid'),
                'type' => array('name' => 'type', 'type' => 'string', 'min' => 1, 'require' => true,   'desc' => '第三方标识'),
                'nicename' => array('name' => 'nicename', 'type' => 'string',   'default'=>'',  'desc' => '第三方昵称'),
                'avatar' => array('name' => 'avatar', 'type' => 'string',  'default'=>'', 'desc' => '第三方头像'),
                'signature' => array('name' => 'signature', 'type' => 'string',  'require'=>true, 'desc' => '签名'),
                'device' => array('name' => 'device', 'type' => 'string', 'desc' => '设备号'),

            ),
			
		
			'getForgetCode' => array(
				'mobile' => array('name' => 'mobile', 'type' => 'string', 'min' => 1, 'require' => true,  'desc' => '手机号'),
			),

			'getLoginCode' => array(
				'mobile' => array('name' => 'mobile', 'type' => 'string', 'min' => 1, 'require' => true,  'desc' => '手机号'),
				'signature' => array('name' => 'signature', 'type' => 'string',  'require'=>'true', 'desc' => '签名'),
			),

			'shareRsg' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true,  'desc' => '邀请注册者id'),
				'mobile' => array('name' => 'mobile', 'type' => 'string', 'min' => 1, 'require' => true,  'desc' => '手机号'),
				'code' => array('name' => 'code', 'type' => 'string', 'min' => 1, 'require' => true,   'desc' => '验证码'),
			),
			'test' => array(
			
			),

        );
	}
	

    /**
     * 会员登陆 需要密码
     * @desc 用于用户登陆信息
     * @return int code 操作码，0表示成功
     * @return array info 用户信息
     * @return string info[0].id 用户ID
     * @return string info[0].user_nicename 昵称
     * @return string info[0].avatar 头像
     * @return string info[0].avatar_thumb 头像缩略图
     * @return string info[0].sex 性别
     * @return string info[0].signature 签名
     * @return string info[0].coin 用户余额
     * @return string info[0].login_type 注册类型
     * @return string info[0].level 等级
     * @return string info[0].province 省份
     * @return string info[0].city 城市
     * @return string info[0].birthday 生日
     * @return string info[0].token 用户Token
     * @return string msg 提示信息  $authcode='rCt52pF2cnnKNB3Hkp';
		$user_pass="###".md5(md5($authcode.$pass));
     */
    public function userLogin() {


        $rs = array('code' => 0, 'msg' => '', 'info' => array());

		      
		$user_login=$this->checkNull($this->user_login);
		$code=$this->checkNull($this->code);
		$uuid=$this->checkNull($this->uuid);

		$configpri=$this->getConfigPri();

		$len=strlen($configpri['signature']);
		$signature=$this->checkNull($this->signature);
		$newsign=md5(md5($code).'#d51251e410368a0'.$user_login.'d586e01'.substr($configpri['signature'],1,$len-2).'d5186e');

		file_put_contents("data/userLogin/".date('Y-m-d').".txt", "负载服务器1==> 登录账号：".$user_login.";验证码：".$code.";时间：".date('Y-m-d H:i:s').";请求签名：".$signature.";系统生成签名：".$newsign.";IP:".$_SERVER['REMOTE_ADDR'].PHP_EOL, FILE_APPEND);
      
		if($newsign!=$signature){
			$rs['code'] = 1002;
            $rs['msg'] = '验签失败';
        	return $rs;
		}

		if($code==''){
			$rs['code'] = 1011;
            $rs['msg'] = '请填写验证码';
             return $rs;
		}

      if(empty($this->getcaches('Login_Mobile_'.$user_login))){
        $rs['code'] = 1012;
            $rs['msg'] = '手机号码错误或验证码失效';
            return $rs;
      }else{
        if($code!=$this->getcaches('Login_Mobile_'.$user_login)){
          $rs['code'] = 1013;
            $rs['msg'] = '验证码错误';
            return $rs;
        }
      }
        $domain = new Domain_Login();
        $info = $domain->userLogin($user_login,$uuid);
		if($info==1002){
			$rs['code'] = 1002;
            $rs['msg'] = '该账号已被禁用';
            return $rs;	
		}
	
        $rs['info'][0] = $info;
       $this->delcache('Login_Mobile_'.$user_login);		
		//file_put_contents("data/userLogin/".date('Y-m-d').".txt", "负载服务器1==> 登录账号：".$user_login.";信息：".json_encode($rs).";时间：".date('Y-m-d H:i:s').";请求签名：".$signature.";系统生成签名：".$newsign.";IP:".$_SERVER['REMOTE_ADDR'].PHP_EOL, FILE_APPEND);
        return $rs;
    }

    /**
     * 游客登陆 不需要密码
     * @desc 用于用户登陆信息
     * @return int code 操作码，0表示成功
     * @return array info 用户信息
     * @return string info[0].id 用户ID
     * @return string info[0].user_nicename 昵称
     * @return string info[0].avatar 头像
     * @return string info[0].avatar_thumb 头像缩略图
     * @return string info[0].sex 性别
     * @return string info[0].signature 签名
     * @return string info[0].coin 用户余额
     * @return string info[0].login_type 注册类型
     * @return string info[0].level 等级
     * @return string info[0].province 省份
     * @return string info[0].city 城市
     * @return string info[0].birthday 生日
     * @return string info[0].token 用户Token
     * @return string msg 提示信息  $authcode='rCt52pF2cnnKNB3Hkp';
		$user_pass="###".md5(md5($authcode.$pass));
     */

	public function userLogin1() {

        $rs = array('code' => 0, 'msg' => '', 'info' => array());
	   /* if(!$this->isMobile()){
	    	$rs['code'] = 1000;
            $rs['msg'] = '请在移动端打开';
        	return $rs;
	    };*/
		$uuid=$this->checkNull($this->uuid);
		
		$configpri=$this->getConfigPri();

		$len=strlen($configpri['signature']);
		$signature=$this->checkNull($this->signature);
		$newsign=md5(md5($uuid).'#d51251e410368a0'.''.'d586e01'.substr($configpri['signature'],1,$len-2).'d5186e');

		file_put_contents("data/userLogin/".date('Y-m-d').".txt", "负载服务器1==> 登录设备号：".$uuid.";时间：".date('Y-m-d H:i:s').";请求签名：".$signature.";系统生成签名：".$newsign.";IP:".$_SERVER['REMOTE_ADDR'].PHP_EOL, FILE_APPEND);
     
		if($newsign!=$signature){
			$rs['code'] = 1002;
            $rs['msg'] = '验签失败';
        	return $rs;
		}


        $domain = new Domain_Login();

        $info = $domain->userLogin1($uuid);
  
		if($info==1002){
			$rs['code'] = 1002;
            $rs['msg'] = '该账号已被禁用';
            return $rs;	
		}
	
        $rs['info'][0] = $info;

       $this->delcache('Login_Mobile_'.$info['user_login']);
				
		//file_put_contents("data/userLogin/".date('Y-m-d').".txt", "负载服务器1==> 登录账号：".$user_login.";信息：".json_encode($rs).";时间：".date('Y-m-d H:i:s').";请求签名：".$signature.";系统生成签名：".$newsign.";IP:".$_SERVER['REMOTE_ADDR'].PHP_EOL, FILE_APPEND);
        return $rs;
    }
   	
	/**
     * 会员找回密码
     * @desc 用于会员找回密码
     * @return int code 操作码，0表示成功，1表示验证码错误，2表示用户密码不一致,3短信手机和登录手机不一致 4、用户不存在 801 密码6-12位数字与字母
     * @return array info 
     * @return string msg 提示信息
     */
    public function userFindPass() {
		
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $rs['code']=1002;
        return $rs;
		
		$user_login=$this->checkNull($this->user_login);
		$user_pass=$this->checkNull($this->user_pass);
		$user_pass2=$this->checkNull($this->user_pass2);
		$code=$this->checkNull($this->code);
		
		
	 	if($user_login!=$_SESSION['forget_mobile']){
            $rs['code'] = 1001;
            $rs['msg'] = '手机号码不一致';
            return $rs;					
		}

		if($code!=$_SESSION['forget_mobile_code']){
            $rs['code'] = 1002;
            $rs['msg'] = '验证码错误';
            return $rs;					
		}	
		

		if($user_pass!=$user_pass2){
            $rs['code'] = 1003;
            $rs['msg'] = '两次输入的密码不一致';
            return $rs;					
		}	

		$check = $this->passcheck($user_pass);
		if($check== 0 ){
            $rs['code'] = 1004;
            $rs['msg'] = '密码6-12位数字与字母';
            return $rs;										
        }else if($check== 2){
            $rs['code'] = 1005;
            $rs['msg'] = '密码不能纯数字或纯字母';
            return $rs;										
        }		

		$domain = new Domain_Login();
        $info = $domain->userFindPass($user_login,$user_pass);	
		
		if($info==1006){
			$rs['code'] = 1006;
            $rs['msg'] = '该帐号不存在';
            return $rs;	
		}else if($info===false){
			$rs['code'] = 1007;
            $rs['msg'] = '重置失败，请重试';
            return $rs;	
		}
		
		$_SESSION['forget_mobile'] = '';
		$_SESSION['forget_mobile_code'] = '';
		$_SESSION['forget_mobile_expiretime'] = '';

        return $rs;
    }
	
    /**
     * 第三方登录
     * @desc 用于用户登陆信息
     * @return int code 操作码，0表示成功
     * @return array info 用户信息
     * @return string info[0].id 用户ID
     * @return string info[0].user_nicename 昵称
     * @return string info[0].avatar 头像
     * @return string info[0].avatar_thumb 头像缩略图
     * @return string info[0].sex 性别
     * @return string info[0].signature 签名
     * @return string info[0].coin 用户余额
     * @return string info[0].login_type 注册类型
     * @return string info[0].level 等级
     * @return string info[0].province 省份
     * @return string info[0].city 城市
     * @return string info[0].birthday 生日
     * @return string info[0].token 用户Token
     * @return string msg 提示信息
     */
    public function userLoginByThird() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());


        $configpri=$this->getConfigPri();
		$openid=$this->checkNull($this->openid);
		$type=$this->checkNull($this->type);
		$nicename=$this->checkNull($this->nicename);
		$avatar=$this->checkNull($this->avatar);
		$signature=$this->checkNull($this->signature);
		$device=$this->checkNull($this->device);

		$len=strlen($configpri['signature']);


		$newsign=md5(md5($openid).'#d51251e410368a0'.$type.'d586e01'.substr($configpri['signature'],1,$len-2).'d5186e');

		file_put_contents("data/userLoginByThird/".date('Y-m-d').".txt", "负载服务器1==> openid：".$openid.";三方标识：".$type.";三方昵称：".$nicename.";三方头像：".$avatar.";时间：".date('Y-m-d H:i:s').";请求签名：".$signature.";系统生成签名：".$newsign.";IP:".$_SERVER['REMOTE_ADDR'].PHP_EOL, FILE_APPEND);

		if($signature!=$newsign){
			 $rs['code'] = 1002;
			return $rs;
		}
		
        $domain = new Domain_Login();
        $info = $domain->userLoginByThird($openid,$type,$nicename,$avatar,$device);
		
        if($info==1002){
            $rs['code'] = 1002;
            $rs['msg'] = '该账号已被禁用';
            return $rs;					
		}else if($info==1003){
            $rs['code'] = 1003;
            $rs['msg'] = '该设备已绑定其他QQ账号';
            return $rs;	
        }else if($info==1004){
            $rs['code'] = 1004;
            $rs['msg'] = '该QQ账号已绑定其他设备';
            return $rs;	
        }

        $rs['info'][0] = $info;

        return $rs;
    }
	
			

	/**
	 * 获取找回密码短信验证码
	 * @desc 用于找回密码获取短信验证码
	 * @return int code 操作码，0表示成功,2发送失败
	 * @return array info 
	 * @return string msg 提示信息
	 */
	 
	public function getForgetCode() {

		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$rs['code']=1002;

		return $rs;
		
		$mobile = $this->mobile;
		
		$ismobile=$this->checkMobile($mobile);
		if(!$ismobile){
			$rs['code']=1001;
			$rs['msg']='请输入正确的手机号';
			return $rs;	
		}

		$isExist=$this->checkMoblieIsExist($mobile);

		if($isExist==0){
			$rs['code']=1001;
			$rs['msg']='该手机号不存在';
			return $rs;
		}

		if($_SESSION['forget_mobile']==$mobile && $_SESSION['forget_mobile_expiretime']> time() ){
			$rs['code']=1002;
			$rs['msg']='验证码1分钟有效，请勿多次发送';
			return $rs;
		}

        $limit = $this->ip_limit();	
		if( $limit == 1){
			$rs['code']=1003;
			$rs['msg']='您已当日发送次数过多';
			return $rs;
		}	
		$mobile_code = $this->random(6,1);
		
		/* 发送验证码 */
 		$result=$this->sendCode($mobile,$mobile_code);

		if($result['code']===0){

			$_SESSION['forget_mobile'] = $mobile;
			$_SESSION['forget_mobile_code'] = $mobile_code;
			$_SESSION['forget_mobile_expiretime'] = time() +60*5;


		}else if($result['code']==667){

			$_SESSION['forget_mobile'] = $mobile;
			$_SESSION['forget_mobile_code'] = $result['msg'];
			$_SESSION['forget_mobile_expiretime'] = time() +60*5;	
			
			$rs['code']=0;
			$rs['msg']=$result['msg'];

			return $rs;

		}else{

			$rs['code']=1002;
			$rs['msg']=$result['msg'];

			return $rs;
		}
		
		$rs['msg']="发送成功";
		return $rs;
	}


	/**
	 * 获取登录短信验证码
	 * @desc 用于登录获取短信验证码
	 * @return int code 操作码，0表示成功,2发送失败
	 * @return array info 
	 * @return string msg 提示信息
	 */
	 
	public function getLoginCode() {


		$rs = array('code' => 0, 'msg' => '', 'info' => array());
   		

   
		$mobile = $this->mobile;
		 // echo  $this->setcaches('Login_Mobile_'.$mobile,'191508',360); exit;
		
		$ismobile=$this->checkMobile($mobile);
		if(!$ismobile){
			$rs['code']=1001;
			$rs['msg']='请输入正确的手机号';
			return $rs;	
		}

		

		$signature=$this->checkNull($this->signature);

		$configpri=$this->getConfigPri();
		
		$len=strlen($configpri['signature']);

		$newsign=md5(md5($mobile).'#d51251e410368a0'.$mobile.'d586e01'.substr($configpri['signature'],1,$len-2).'d5186e');


		file_put_contents("data/getLoginCode/".date('Y-m-d').".txt", "负载服务器1==> mobile：".$mobile.";时间：".date('Y-m-d H:i:s').";请求签名：".$signature.";系统生成签名：".$newsign.";IP:".$_SERVER['REMOTE_ADDR'].PHP_EOL, FILE_APPEND);

		if($signature!=$newsign){
			 $rs['code'] = 1004;
             $rs['msg']='验签失败';
			return $rs;
		}





		//验证手机号是否被禁用
		$status=$this->checkMoblieCanCode($mobile);

		if($status==0){
			$rs['code']=1001;
			$rs['msg']='该账号已被禁用';
			return $rs;	
		}
      
           
      //getcaches('')

		if(!empty($this->getcaches('Login_Mobile_'.$mobile))){
			$rs['code']=1002;
			$rs['msg']='验证码5分钟有效，请勿多次发送';
			return $rs;
		}
		
        $limit = $this->ip_limit();	
		if( $limit == 1){
			$rs['code']=1003;
			$rs['msg']='您已当日发送次数过多';
			return $rs;
		}

		


		$mobile_code = $this->random(6,1);
		
		/* 发送验证码 */
 		$result=$this->sendCode($mobile,$mobile_code);

		 if($result['code']==667){
			/*$_SESSION['login_mobile'] = $mobile;
            $_SESSION['login_mobile_code'] = $result['msg'];
            $_SESSION['login_mobile_expiretime'] = time() +60*5;*/
            $rs['code']=667;
			$rs['msg']='验证码为：'.$result['msg'];
			return $rs;
		}else{
			$rs['code']=1002;
			$rs['msg']=$result['msg'];
			return $rs;
		}
		$this->setcaches('Login_Mobile_'.$mobile,$mobile_code,60*5);
		$rs['msg']="发送成功".$this->getcaches('Login_Mobile_'.$mobile);
		return $rs;
	}
	



	/**
     * h5分享页面注册 需要验证码
     * @desc h5分享页面注册 需要验证码
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */

	public function shareRsg() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$rs['code']=1002;
		return $rs;
		
		$uid=$this->checkNull($this->uid);
		$mobile=$this->checkNull($this->mobile);
		$code=$this->checkNull($this->code);

		if($code==''){
			$rs['code'] = 1001;
            $rs['msg'] = '请填写验证码';
             return $rs;
		}

		if($mobile!=$_SESSION['login_mobile']){
			$rs['code'] = 1001;
            $rs['msg'] = '手机号码错误';
            return $rs;
		}
		if($code!=$_SESSION['login_mobile_code']){
			$rs['code'] = 1001;
            $rs['msg'] = '验证码错误';
            return $rs;
		}

        $domain = new Domain_Login();
        $info = $domain->shareRsg($mobile,$uid);
        if($info==1001){
        	$rs['code'] = 1001;
            $rs['msg'] = '邀请者不存在';
            return $rs;
        }
		if($info==1002){
			$rs['code'] = 1002;
            $rs['msg'] = '该账号已存在';
            return $rs;	
		}

		if($info==1003){
        	$rs['code'] = 1003;
            $rs['msg'] = '邀请者账号被禁用';
            return $rs;
        }
		
		$_SESSION['login_mobile']='';
		$_SESSION['login_mobile_code']='';
	
        $rs['info'][0] = $info;
				
		
        return $rs;
	}	
	


}
