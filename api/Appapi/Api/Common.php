<?php
header('Access-Control-Allow-Origin: *');
class Api_Common extends PhalApi_Api {
	function __construct(){
		DI()->redis=$this->connectionRedis();
	}
	/* Redis链接 */
	protected function connectionRedis(){
		$REDIS_HOST= DI()->config->get('app.REDIS_HOST');
		$REDIS_AUTH= DI()->config->get('app.REDIS_AUTH');
		$REDIS_PORT= DI()->config->get('app.REDIS_PORT');
		$redis = new Redis();
		$redis -> pconnect($REDIS_HOST,$REDIS_PORT);
		$redis -> auth($REDIS_AUTH);

		return $redis;
	}
	/* 设置缓存 */
	protected function setcache($key,$info){
		$config=$this->getConfigPri();
		if($config['cache_switch']!=1){
			return 1;
		}
		DI()->redis->set($key,json_encode($info));
		DI()->redis->expire($key, $config['cache_time']); 
		return 1;
	}	
	/* 设置缓存 可自定义时间*/
	protected function setcaches($key,$info,$time){

		DI()->redis->set($key,json_encode($info));
		DI()->redis->expire($key, $time); 

		return 1;
	}
	/* 获取缓存 */
	protected function getcache($key){
		$config=$this->getConfigPri();

		if($config['cache_switch']!=1){
			$isexist=false;
		}else{
			$isexist=DI()->redis->get($key);
		}

		return json_decode($isexist,true);
	}		
	/* 获取缓存 不判断后台设置 */
	protected function getcaches($key){
		$isexist=DI()->redis->get($key);
		return json_decode($isexist,true);
	}
	/* 删除缓存 */
	protected function delcache($key){
		$isexist=DI()->redis->del($key);
		return 1;
	}	
	
	/* 公共配置 */
	protected function getConfigPub() {
		$key='getConfigPub';
		$config=$this->getcaches($key);
		$config=false;
		if(!$config){
			$domain = new Domain_Common();
			$config = $domain->getConfigPub();
		}
        
		return 	$config;

	}	
	/* 私密配置 */
	protected function getConfigPri() {
		$key='getConfigPri';
		$config=$this->getcaches($key);
		$config=false;
		if(!$config){
			$domain = new Domain_Common();
			$config = $domain->getConfigPri();
		}
		return 	$config;
	}	

	/* 判断token */
	protected function checkToken($uid,$token) {
		
		$userinfo=$this->getCache("token_".$uid);
		if(!$userinfo){
			$domain = new Domain_Common();
			$rs = $domain->checkToken($uid,$token);
			return $rs;							
		}

		if($userinfo['token']!=$token || $userinfo['expiretime']<time()){
			return 700;				
		}else{
			return 	0;				
		} 	
	}

	/* 敏感词语屏蔽 */
	protected function word_shield($txt) {
		
	
			$domain = new Domain_Common();
			$info = $domain->word_shield($txt);				
	
		
		return $info;
	}

	//判断是否移动端打开
	protected function isMobile() {
		// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
		if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
			return true;
		}
		// 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
		if (isset($_SERVER['HTTP_VIA'])) {
			// 找不到为flase,否则为true
			if(stristr($_SERVER['HTTP_VIA'], "wap") !== false){
				return true;
			}
		}
		// 脑残法，判断手机发送的客户端标志,兼容性有待提高。其中'MicroMessenger'是电脑微信
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
			$clientkeywords = array(
			'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
			'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
			'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
			'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
			'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
			'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
			'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
			'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
			'wapr','webc','winw','winw','xda','xda-'
			);
		// 从HTTP_USER_AGENT中查找手机浏览器的关键字
			if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
				return true;
			}
		}
		// 协议法，因为有可能不准确，放到最后判断
		if (isset ($_SERVER['HTTP_ACCEPT'])) {
		// 如果只支持wml并且不支持html那一定是移动设备
		// 如果支持wml和html但是wml在html之前则是移动设备
		if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
			return true;
		}
		}

		if (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
		|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false) {
		return true;
		}

		return false;
	}
	/* 用户基本信息 */
	protected function getUserInfo($uid) {
		$info=$this->getCache("userinfo_".$uid);
		if(!$info){
			$domain = new Domain_Common();
			$info = $domain->getUserInfo($uid);				
		}
		
		return $info;
	}

//添加金币变动日志
	protected function add_coin_log($uid,$videoid,$coin,$type,$action,$time){
		$insert=array("type"=>$type,"action"=>$action,"uid"=>$uid,"touid"=>$uid,"giftid"=>$videoid,"giftcount"=>1,"totalcoin"=>$coin,"showid"=>'0',"addtime"=>$time);
		$isup=DI()->notorm->users_coinrecord->insert($insert);

	} 
	
	/* 判断是否关注 */
	protected function isAttention($uid,$touid) {

		$domain = new Domain_Common();
		$rs = $domain->isAttention($uid,$touid);
		return $rs;
	}
	/* 是否黑名单 */
	protected function isBlack($uid,$touid) {

		$domain = new Domain_Common();
		$rs = $domain->isBlack($uid,$touid);
		return $rs;
	}


	/* 是否购买过该视频 */
	protected function ifBuy($uid,$videoid) {

		$domain = new Domain_Common();
		$rs = $domain->ifBuy($uid,$videoid);
		return $rs;
	}

	/* 判断权限 */
	protected function isAdmin($uid,$liveuid) {

		$domain = new Domain_Common();
		$rs = $domain->isAdmin($uid,$liveuid);
		return $rs;
	}
	
	/* 会员等级 */
	protected function getLevel($experience) {
		$levelid=1;
		$key='level';
		$level=$this->getCache($key);
		if(!$level){
			$domain = new Domain_Common();
			$levelid = $domain->getLevel($experience);	
			return $levelid;
		}

		foreach($level as $k=>$v){
			if( $v['level_up']>=$experience){
				$levelid=$v['levelid'];
				break;
			}else{
				$level_a = $v['levelid'];
			}
		}
		$levelid = $levelid < $level_a ? $level_a:$levelid;
		return $levelid;
	}	

	/* 主播等级 */
	protected function getLevelAnchor($experience) {
		$levelid=1;
		$key='levelanchor';
		$level=$this->getCache($key);
		if(!$level){
			$domain = new Domain_Common();
			$levelid = $domain->getLevelAnchor($experience);	
			return $levelid;
		}

		foreach($level as $k=>$v){
			if( $v['level_up']>=$experience){
				$levelid=$v['levelid'];
				break;
			}else{
				$level_a = $v['levelid'];
			}
		}
		$levelid = $levelid < $level_a ? $level_a:$levelid;
		return $levelid;
	}	
	/* 数字格式化 */
	protected function NumberFormat($num){
		if($num<10000){

		}else if($num<1000000){
			$num=round($num/10000,2).'万';
		}else if($num<100000000){
			$num=round($num/10000,1).'万';
		}else if($num<10000000000){
			$num=round($num/100000000,2).'亿';
		}else{
			$num=round($num/100000000,1).'亿';
		}
		return $num;
	}

	 /* 直播分类 */
    protected function getLiveClass(){

    	$domain = new Domain_Common();
		$rs = $domain->getLiveClass();
		return $rs;

    }
	/**
	 * 返回带协议的域名
	 */
	protected function get_host(){
		//$host=$_SERVER["HTTP_HOST"];
	//	$protocol=$this->is_ssl()?"https://":"http://";
		//return $protocol.$host;
		$config=$this->getConfigPub();
		return $config['site'];
	}	
	
	/**
	 * 转化数据库保存的文件路径，为可以访问的url
	 */
	protected function get_upload_path($file){
		if(strpos($file,"http")===0){

			return html_entity_decode($file);
			// return $this->setTxUrl(html_entity_decode($file));
		}else if(strpos($file,"/")===0){
			$filepath= $this->get_host().$file;
			return html_entity_decode($filepath);
		}else{
			$space_host= DI()->config->get('app.Qiniu.space_host');
			$filepath=$space_host."/".$file;
			return html_entity_decode($filepath);
		}
	}
	
	/**
	 * 转化url地址 主要是腾讯云或者七牛云原始域名
	 */
	protected function get_path($url){
		$configpri=$this->getConfigPri();

		if($configpri['cloudtype']==2){

			$new_url = str_replace('file.myqcloud','cos.ap-chengdu.myqcloud',$url);
		
			if ($new_url) {
				return $new_url;
			}
			
		}
		return $url;
	}
	
	
	/* 去除NULL 判断空处理 主要针对字符串类型*/
	protected function checkNull($checkstr){
		
		$checkstr=urldecode($checkstr);
		$checkstr=htmlspecialchars($checkstr);
		$checkstr=trim($checkstr);
		$checkstr=$this->filterEmoji($checkstr);
		if( strstr($checkstr,'null') || (!$checkstr && $checkstr!=0 ) ){
			$str='';
		}else{
			$str=$checkstr;
		}
		return $str;	
	}
	
	/* 去除emoji表情 */
	protected function filterEmoji($str){
		$str = preg_replace_callback(
			'/./u',
			function (array $match) {
				return strlen($match[0]) >= 4 ? '' : $match[0];
			},
			$str);
		return $str;
	}
	/* 密码检查 */
	protected function passcheck($user_pass) {
		$num = preg_match("/^[a-zA-Z]+$/",$user_pass);
		$word = preg_match("/^[0-9]+$/",$user_pass);
		$check = preg_match("/^[a-zA-Z0-9]{6,12}$/",$user_pass);
		if($num || $word ){
			return 2;
		}else if(!$check){
			return 0;
		}		
		return 1;
	}
	/* 判断账号是否禁用 */
	protected function isBan($uid){
		$domain = new Domain_Common();
		$rs = $domain->isBan($uid);
		return $rs;
		
	}
	/* 判断账号是否认证 */
	protected function isAuth($uid){
		$domain = new Domain_Common();
		$rs = $domain->isAuth($uid);
		return $rs;
		
	}
	/* 过滤关键词 */
	protected function filterField($field){
		$configpri=$this->getConfigPri();
		
		$sensitive_field=$configpri['sensitive_field'];
		
		$sensitive=explode(",",$sensitive_field);
		$replace=array();
		$preg=array();
		foreach($sensitive as $k=>$v){
			if($v){
				$re='';
				$num=mb_strlen($v);
				for($i=0;$i<$num;$i++){
					$re.='*';
				}
				$replace[$k]=$re;
				$preg[$k]='/'.$v.'/';
			}else{
				unset($sensitive[$k]);
			}
		}
		
		return preg_replace($preg,$replace,$field);
	}
	/* 检验手机号 */
	protected function checkMobile($mobile){

		$ismobile = preg_match("/^1[3|4|5|7|8]\d{9}$/",$mobile);
		if($ismobile){
			return 1;
		}else{
			return 0;
		}
	}
	/* 随机数 */
	protected function random($length = 6 , $numeric = 0) {
		PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
		if($numeric) {
			$hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
		} else {
			$hash = '';
			$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
			$max = strlen($chars) - 1;
			for($i = 0; $i < $length; $i++) {
				$hash .= $chars[mt_rand(0, $max)];
			}
		}
		return $hash;
	}	
	/* 发送验证码 */
	protected function sendCode($mobile,$code){
		$rs=array();
        
        //LV
        if( preg_match('/^177|178|170|171/',$mobile )){
            $rs['code']=1003;
			$rs['msg']='获取失败';
            return $rs;
        }
        
		$config = $this->getConfigPri();

		if(!$config['sendcode_switch']){
            $rs['code']=667;
			$rs['msg']='123456';
            $this->setcaches('Login_Mobile_'.$mobile,'123456',60*5);
			//file_put_contents("data/sendCode/".date('Y-m-d').".txt", T('负载服务器1==>手机号：').$mobile.T(';验证码：').$code.T(';时间：').date('Y-m-d H:i:s').";IP:".$_SERVER['REMOTE_ADDR'].PHP_EOL, FILE_APPEND);
            return $rs;
        }

		/* 互亿无线 */
		//$target = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";
		
		//$post_data = "account=".$config['ihuyi_account']."&password=".$config['ihuyi_ps']."&mobile=".$mobile."&content=".rawurlencode(T('您的验证码是：').$code.T('。请不要把验证码泄露给其他人。'));
		//密码可以使用明文密码或使用32位MD5加密
		//$gets = $this->xml_to_array($this->Post($post_data, $target));


		//短信宝
		$statusStr = array(
		"0" => "短信发送成功",
		"-1" => "参数不全",
		"-2" => "服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！",
		"30" => "密码错误",
		"40" => "账号不存在",
		"41" => "余额不足",
		"42" => "帐户已过期",
		"43" => "IP地址限制",
		"50" => "内容含有敏感词"
		);
		$smsapi = "http://api.smsbao.com/";
		$user = $config['ihuyi_account']; //短信平台帐号
		$pass = md5($config['ihuyi_ps']); //短信平台密码
		$content="【91联盟】您的验证码是：". $code ."，请在5分钟内输入，如非本人操作请忽略。";//要发送的短信内容
		$phone = $mobile;//要发送短信的手机号码
		$sendurl = $smsapi."sms?u=".$user."&p=".$pass."&m=".$phone."&c=".urlencode($content);
		$result =file_get_contents($sendurl) ;
	
		//聚合数据     
		//发送验证码
		// $sendUrl = 'https://api.mysubmail.com/message/send';
		// $smsConf = array(
		// 	'appid'	=> $config['ihuyi_account'],
		// 	'signature'	=> $config['ihuyi_ps'],
		// 	'to'	=> $mobile,
		// 	'content'	=> '【91联盟】您的验证码是：'. $code .'，请在5分钟内输入，如非本人操作请忽略。'
		// );

		/*$sendUrl = "http://v.juhe.cn/sms/send";
		$smsConf=array(
			"key"=>$config['ihuyi_ps'],
			"mobile"=>$mobile,
			"tpl_id"=>$config['ihuyi_account'],
			"tpl_value"=>"#code#=".$code
		);*/
		//$content=$this->juhecurl($sendUrl,$smsConf,1);
		
		//$result=json_decode($content,true);
		// $error_code=$result["error_code"];

	
		if($statusStr[$result]=='短信发送成功'){
			$rs['code']=0;
			$rs['msg']=T('获取成功');
            $this->setcaches('Login_Mobile_'.$mobile,$code,60*5);

			file_put_contents("data/sendCode/".date('Y-m-d').".txt", T('手机号：').$mobile.T(';验证码：').$code.T(';时间：').date('Y-m-d H:i:s').PHP_EOL, FILE_APPEND);
		}else{
			$rs['code']=1002;
			// $rs['msg']=$gets['SubmitResult']['msg'];
			$rs['msg']=$statusStr[$result];
		} 
		return $rs;
	}
	
	private function juhecurl($url,$params=false,$ispost=0){
		$httpInfo=array();
		$ch=curl_init();
		curl_setopt($ch,CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
		curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/5.0(Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22");
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,30);
		curl_setopt($ch,CURLOPT_TIMEOUT,30);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		if($ispost){
			curl_setopt($ch,CURLOPT_POST,true);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$params);
			curl_setopt($ch,CURLOPT_URL,$url);
		}else{
			if($params){
				curl_setopt($ch,CURLOPT_URL,$url."?".$params);
			}else{
				curl_setopt($ch,CURLOPT_URL,$url);
			}
		}
		$response=curl_exec($ch);
		if($response===FALSE){
			//echo"cURLError:".curl_error($ch);
			return false;
		}
		$httpCode=curl_getinfo($ch,CURLINFO_HTTP_CODE);
		$httpInfo=array_merge($httpInfo,curl_getinfo($ch));
		curl_close($ch);
		return $response;
	}
	
	/*protected function sendCode1($mobile,$code){
		$rs=array();
		
		$config = $this->getConfigPri();
		
		if(!$config['sendcode_switch']){
            $rs['code']=667;
			$rs['msg']='123456';
			$rs['conf'] = $config['sendcode_switch'];
            return $rs;
        }

		return $rs;
	}*/
	
	protected function Post($curlPost,$url){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
		$return_str = curl_exec($curl);
		curl_close($curl);
		return $return_str;
	}
	
	protected function xml_to_array($xml){
		$reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
		if(preg_match_all($reg, $xml, $matches)){
			$count = count($matches[0]);
			for($i = 0; $i < $count; $i++){
			$subxml= $matches[2][$i];
			$key = $matches[1][$i];
				if(preg_match( $reg, $subxml )){
					$arr[$key] = $this->xml_to_array( $subxml );
				}else{
					$arr[$key] = $subxml;
				}
			}
		}
		return $arr;
	}
	/* 发送验证码 */
	
	/* 检测文件后缀 */
	protected function checkExt($filename){
		$config=array("jpg","png","jpeg");
		$ext   =   pathinfo(strip_tags($filename), PATHINFO_EXTENSION);
		 
		return empty($config) ? true : in_array(strtolower($ext), $config);
	}	
	
	/* 同系统函数 array_column   php版本低于5.5.0 时用  */
	protected function array_column2($input, $columnKey, $indexKey = NULL){
		$columnKeyIsNumber = (is_numeric($columnKey)) ? TRUE : FALSE;
		$indexKeyIsNull = (is_null($indexKey)) ? TRUE : FALSE;
		$indexKeyIsNumber = (is_numeric($indexKey)) ? TRUE : FALSE;
		$result = array();
 
		foreach ((array)$input AS $key => $row){ 
			if ($columnKeyIsNumber){
				$tmp = array_slice($row, $columnKey, 1);
				$tmp = (is_array($tmp) && !empty($tmp)) ? current($tmp) : NULL;
			}else{
				$tmp = isset($row[$columnKey]) ? $row[$columnKey] : NULL;
			}
			if (!$indexKeyIsNull){
				if ($indexKeyIsNumber){
					$key = array_slice($row, $indexKey, 1);
					$key = (is_array($key) && ! empty($key)) ? current($key) : NULL;
					$key = is_null($key) ? 0 : $key;
				}else{
					$key = isset($row[$indexKey]) ? $row[$indexKey] : 0;
				}
			}
			$result[$key] = $tmp;
		}
		return $result;
	}
	
	/**
	*  @desc 获取推拉流地址
	*  @param string $host 协议，如:http、rtmp
	*  @param string $stream 流名,如有则包含 .flv、.m3u8
	*  @param int $type 类型，0表示播流，1表示推流
	*/
	protected function PrivateKeyA($host,$stream,$type){
		$configpri=$this->getConfigPri();
		$cdn_switch=$configpri['cdn_switch'];
		//$cdn_switch=3;
		switch($cdn_switch){
			case '1':
				$url=$this->PrivateKey_ali($host,$stream,$type);
				break;
			case '2':
				$url=$this->PrivateKey_tx($host,$stream,$type);
				break;
			case '3':
				$url=$this->PrivateKey_qn($host,$stream,$type);
				break;
			case '4':
				$url=$this->PrivateKey_ws($host,$stream,$type);
				break;
			case '5':
				$url=$this->PrivateKey_wy($host,$stream,$type);
				break;
			case '6':
				$url=$this->PrivateKey_ady($host,$stream,$type);
				break;
		}

		
		return $url;
	}
	
	/**
	*  @desc 阿里云直播A类鉴权
	*  @param string $host 协议，如:http、rtmp
	*  @param string $stream 流名,如有则包含 .flv、.m3u8
	*  @param int $type 类型，0表示播流，1表示推流
	*/
	protected function PrivateKey_ali($host,$stream,$type){
		$configpri=$this->getConfigPri();
		$key=$configpri['auth_key'];
		if($type==1){
			$domain=$host.'://'.$configpri['push_url'];
			$time=time() +60*60*10;
		}else{
			$domain=$host.'://'.$configpri['pull_url'];
			$time=time() - 60*30 + $configpri['auth_length'];
		}
		
		$filename="/5showcam/".$stream;
		if($key!=''){
			$sstring = $filename."-".$time."-0-0-".$key;
			$md5=md5($sstring);
			$auth_key="auth_key=".$time."-0-0-".$md5;
		}
		if($type==1){
			if($auth_key){
				$auth_key='&'.$auth_key;
			}
			$url=$domain.$filename.'?vhost='.$configpri['pull_url'].$auth_key;
		}else{
			if($auth_key){
				$auth_key='?'.$auth_key;
			}
			$url=$domain.$filename.$auth_key;
		}
		
		return $url;
	}
	
	/**
	*  @desc 腾讯云推拉流地址
	*  @param string $host 协议，如:http、rtmp
	*  @param string $stream 流名,如有则包含 .flv、.m3u8
	*  @param int $type 类型，0表示播流，1表示推流
	*/
	protected function PrivateKey_tx($host,$stream,$type){
		$configpri=$this->getConfigPri();
		$bizid=$configpri['tx_bizid'];
		$push_url_key=$configpri['tx_push_key'];
		$stream_a=explode('.',$stream);
		$streamKey = $stream_a[0];
		$ext = $stream_a[1];
		$live_code = $bizid . "_" .$streamKey;      	
		$now_time = time() + 3*60*60;
		$txTime = dechex($now_time);

		$txSecret = md5($push_url_key . $live_code . $txTime);
		$safe_url = "&txSecret=" .$txSecret."&txTime=" .$txTime;		

		if($type==1){
			//$push_url = "rtmp://" . $bizid . ".livepush2.myqcloud.com/live/" .  $live_code . "?bizid=" . $bizid . "&record=flv" .$safe_url;	可录像
			$url = "rtmp://" . $bizid .".livepush2.myqcloud.com/live/" . $live_code . "?bizid=" . $bizid . "" .$safe_url;	
		}else{
			$url = 'http://'. $bizid .".liveplay.myqcloud.com/live/" . $live_code . ".flv";
		}
		
		return $url;
	}

	/**
	*  @desc 七牛云直播
	*  @param string $host 协议，如:http、rtmp
	*  @param string $stream 流名,如有则包含 .flv、.m3u8
	*  @param int $type 类型，0表示播流，1表示推流
	*/
	protected function PrivateKey_qn($host,$stream,$type){
		
		$configpri=$this->getConfigPri();
		$ak=$configpri['qn_ak'];
		$sk=$configpri['qn_sk'];
		$hubName=$configpri['qn_hname'];
		$push=$configpri['qn_push'];
		$pull=$configpri['qn_pull'];
		$stream_a=explode('.',$stream);
		$streamKey = $stream_a[0];
		$ext = $stream_a[1];

		if($type==1){
			$time=time() +60*60*10;
			//RTMP 推流地址
			$url = \Qiniu\Pili\RTMPPublishURL($push, $hubName, $streamKey, $time, $ak, $sk);
		}else{
			if($ext=='flv'){
				$pull=str_replace('pili-live-rtmp','pili-live-hdl',$pull);
				//HDL 直播地址
				$url = \Qiniu\Pili\HDLPlayURL($pull, $hubName, $streamKey);
			}else if($ext=='m3u8'){
				$pull=str_replace('pili-live-rtmp','pili-live-hls',$pull);
				//HLS 直播地址
				$url = \Qiniu\Pili\HLSPlayURL($pull, $hubName, $streamKey);
			}else{
				//RTMP 直播放址
				$url = \Qiniu\Pili\RTMPPlayURL($pull, $hubName, $streamKey);
			}
		}
				
		return $url;
	}
	/**
	*  @desc 网宿推拉流
	*  @param string $host 协议，如:http、rtmp
	*  @param string $stream 流名,如有则包含 .flv、.m3u8
	*  @param int $type 类型，0表示播流，1表示推流
	*/
	protected function PrivateKey_ws($host,$stream,$type){
		$configpri=$this->getConfigPri();
		if($type==1){
			$domain=$host.'://'.$configpri['ws_push'];
			//$time=time() +60*60*10;
		}else{
			$domain=$host.'://'.$configpri['ws_pull'];
			//$time=time() - 60*30 + $configpri['auth_length'];
		}
		
		$filename="/".$configpri['ws_apn']."/".$stream;

		$url=$domain.$filename;
		
		return $url;
	}
	//网易cdn 创建房间并获取房间信息
	protected function PrivateKey_wy($host,$stream,$type)
	{
		$configpri=$this->getConfigPri();
		$appkey=$configpri['wy_appkey'];
		$appSecret=$configpri['wy_appsecret'];
		$nonce =rand(1000,9999);
		$curTime=time();
		$var=$appSecret.$nonce.$curTime;
		$checkSum=sha1($appSecret.$nonce.$curTime);
		/* exit; */
		$paramarr = array(
			"name"  =>$stream,
			"type"=> 0,
		);
		$paramarr=json_encode($paramarr);
		$header =array(
			"Content-Type:application/json;charset=utf-8",
			"AppKey:".$appkey,
			"Nonce:" .$nonce,
			"CurTime:".$curTime,
			"CheckSum:".$checkSum,
		);
		$curl=curl_init();
		curl_setopt($curl,CURLOPT_URL, 'https://vcloud.163.com/app/channel/create');
		curl_setopt($curl,CURLOPT_HEADER, 0);
		curl_setopt($curl,CURLOPT_HTTPHEADER, $header); 
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($curl,CURLOPT_POST, 1);
		curl_setopt($curl,CURLOPT_POSTFIELDS, $paramarr);
		$data = curl_exec($curl);
		curl_close($curl);
		$url=json_decode($data,1);
		return $url;
	}
	
	/**
	*  @desc 奥点云推拉流
	*  @param string $host 协议，如:http、rtmp
	*  @param string $stream 流名,如有则包含 .flv、.m3u8
	*  @param int $type 类型，0表示播流，1表示推流
	*/
	protected function PrivateKey_ady($host,$stream,$type){
		$configpri=$this->getConfigPri();
		$stream_a=explode('.',$stream);
		$streamKey = $stream_a[0];
		$ext = $stream_a[1];

		if($type==1){
			$domain=$host.'://'.$configpri['ady_push'];
			//$time=time() +60*60*10;
			$filename="/".$configpri['ady_apn'].'/'.$stream;
			$url=$domain.$filename;
		}else{
			if($ext=='m3u8'){
				$domain=$host.'://'.$configpri['ady_hls_pull'];
				//$time=time() - 60*30 + $configpri['auth_length'];
				$filename="/".$configpri['ady_apn']."/".$stream;
				$url=$domain.$filename;
			}else{
				$domain=$host.'://'.$configpri['ady_pull'];
				//$time=time() - 60*30 + $configpri['auth_length'];
				$filename="/".$configpri['ady_apn']."/".$stream;
				$url=$domain.$filename;
			}
		}
				
		return $url;
	}
	/**
	*  @desc 登录奖励
	*  @param string $host 协议，如:http、rtmp
	*  @param string $stream 流名,如有则包含 .flv、.m3u8
	*  @param int $type 类型，0表示播流，1表示推流
	*/
	protected function LoginBonus($uid,$token){
		
		$domain = new Domain_Common();
		$rs = $domain->LoginBonus($uid,$token);
		return $rs;

	}
	
	/* 获取用户VIP */
	protected function getUserVip($uid){
		$domain = new Domain_Common();
		$rs = $domain->getUserVip($uid);
		return $rs;
		
	}
	
	/* 获取用户坐骑 */
	protected function getUserCar($uid){
		$domain = new Domain_Common();
		$rs = $domain->getUserCar($uid);
		return $rs;
		
	}
	
	/* 获取用户靓号 */
	protected function getUserLiang($uid){
		$domain = new Domain_Common();
		$rs = $domain->getUserLiang($uid);
		return $rs;
		
	}

	/* IP限定 */
	protected function ip_limit(){
		$domain = new Domain_Common();
		$rs = $domain->ip_limit();
		return $rs;
		
	}
	/* 账号是否被禁 */
	protected function isBlackUser($uid) {

		$domain = new Domain_Common();
		$rs = $domain->isBlackUser($uid);
		
		return $rs;
	}

	/*判断手机号是否存在*/
	protected function checkMoblieIsExist($mobile){
		$domain = new Domain_Common();
		$rs = $domain->checkMoblieIsExist($mobile);
		return $rs;
	}

	/*判断手机号是否能发送验证码*/
	protected function checkMoblieCanCode($mobile){
		$domain = new Domain_Common();
		$rs = $domain->checkMoblieCanCode($mobile);
		return $rs;
	}

	/*加密*/
	protected function setEncrypt($code){
		$str = "g-PLGVI5oRwn:SJZbMyODETY3rq08fjaAvCN4Kkctu1sXiBlW6.9dFxzHeQ2hU7pm";
		$strl=strlen($str);
        
	   	$len = strlen($code);

      	$newCode = '';
	   	for($i=0;$i<$len;$i++){
         	for($j=0;$j<$strl;$j++){
            	if($str[$j]==$code[$i]){
               		if(($j+1)==$strl){
                   		$newCode.=$str[0];
	               	}else{
	                   	$newCode.=$str[$j+1];
	               	}
	            }
         	}
      	}
      	return $newCode;
	}
    
    /* 腾讯COS处理 */
    public function setTxUrl($url){
        
        if(!strstr($url,'myqcloud')){
            return $url;
        }//lzmtfht-1300297335.cos.ap-chengdu.myqcloud.com..file.myqcloud.com','cos.ap-chengdu.myqcloud.com
        $url=str_replace('file.myqcloud.com','cos.ap-chengdu.myqcloud.com',$url);
        
        $url_a=parse_url($url);
        
        $file=$url_a['path'];
        $signkey='Shanghai0912';
        $now_time = time();
        $sign=md5($signkey.$file.$now_time);
        
        $and='?';
        if($url_a['query']){
            $and='&';
        }
        
        return $url.$and.'sign='.$sign.'&t='.$now_time;
        
    }

    /*分销分润*/
	public function setAgentProfit1($uid,$total){
		$domain = new Domain_Common();
		$rs = $domain->setAgentProfit1($uid,$total);
		return $rs;
	}
    
} 
