<?php
namespace Home\Controller;
use Think\Controller;
class ShareController extends Controller {
    public function index(){
		$config=getConfigPub();
		$this->assign('config',$config);
		$Model = new \Think\Model();
		
		$list=$Model->query("select l.uid,l.avatar,l.avatar_thumb,l.user_nicename,l.title,l.city,l.stream,l.pull,l.thumb from __PREFIX__users_live l left join __PREFIX__users u on l.uid=u.id where l.islive= '1'  order by u.isrecommend desc,l.starttime desc limit 0,20");
		
		foreach($list as $k=>$v){
			if(!$v['thumb']){
				$list[$k]['thumb']=$v['avatar'];
			}
		}
		
		$this->assign('list',$list);
		
		/* session('uid',null);
		session('token',null);
		session('openid',null);
		session('unionid',null);
		session('userinfo',null); */


		$this->display();
		
		
    }
	
	public function show(){
		$roomnum=(int)I('roomnum');
		
		$User=M('users');
		$Live=M('users_live');
		$liveinfo=array();
		$configpri=getConfigPri();
		$this->assign('configpri',$configpri);
		
		$config=getConfigPub();
		$this->assign('config',$config);

		$liveinfo=$Live->field("uid,user_nicename,avatar,avatar_thumb,islive,stream,pull,isvideo,type")->where("uid='{$roomnum}' and islive='1'")->find();
		if(!$liveinfo){
			$anchor=$User->field("id,user_nicename,avatar,avatar_thumb")->where("id='{$roomnum}'")->find();
			$liveinfo['uid']=$anchor['id'];
			$liveinfo['user_nicename']=$anchor['user_nicename'];
			$liveinfo['avatar']=$anchor['avatar'];
			$liveinfo['avatar_thumb']=$anchor['avatar_thumb'];
			$liveinfo['islive']='0';
		}
		
		if($liveinfo['isvideo']==1){
			$hls=$liveinfo['pull'] ;
		}else{
			$hls=$liveinfo['islive'] && $liveinfo['type']==0 ? PrivateKeyA('http',$liveinfo['stream'].'.m3u8',0):'';
			
		}
		$this->assign('livetype',$liveinfo['type']);
		$this->assign('hls',$hls);
		$this->assign('liveinfo',$liveinfo);
		
		$isattention=0;
		$uid=session("uid");
		//$uid=12;
		if($uid){
			$userinfo=getUserPrivateInfo($uid);
			
			$isexist=M("users_attention")->where("uid='{$uid}' and touid='{$liveinfo['uid']}'")->find();
			if($isexist){
				$isattention=1;
			}
		}
		$this->assign('isattention',$isattention);
		$this->assign('userinfo',$userinfo);
		$this->assign('userinfoj',json_encode($userinfo));

		$this->display();
	}
	
	public function wxLogin(){
		$roomnum=I('roomnum');
		$configpri=getConfigPri();
		
		$AppID = $configpri['login_wx_appid'];
		$callback  = 'http://'.$_SERVER['HTTP_HOST'].'/wxshare/index.php/Share/wxLoginCallback?roomnum='.$roomnum; //????????????
		//????????????
		session_start();
		//-------????????????????????????CSRF??????
		$state  = md5(uniqid(rand(), TRUE));
		$_SESSION["wx_state"]    = $state; //??????SESSION
		$callback = urlencode($callback);
		//snsapi_base ??????  snsapi_userinfo ??????
		$wxurl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$AppID}&redirect_uri={$callback}&response_type=code&scope=snsapi_userinfo&state={$state}#wechat_redirect ";
		
		header("Location: $wxurl");
	}
	
	public function wxLoginCallback(){
		$code=I('code');
		$roomnum=I('roomnum');
		if($code){
			$configpri=getConfigPri();
		
			$AppID = $configpri['login_wx_appid'];
			$AppSecret = $configpri['login_wx_appsecret'];
			/* ??????token */
			$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid={$AppID}&secret={$AppSecret}&code={$code}&grant_type=authorization_code";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_URL, $url);
			$json =  curl_exec($ch);
			curl_close($ch);
			$arr=json_decode($json,1);
			/* ??????token ????????????30??? */
			$url="https://api.weixin.qq.com/sns/oauth2/refresh_token?appid={$AppID}&grant_type=refresh_token&refresh_token={$arr['refresh_token']}";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_URL, $url);
			$json =  curl_exec($ch);
			curl_close($ch);
			
			$url="https://api.weixin.qq.com/sns/userinfo?access_token={$arr['access_token']}&openid={$arr['openid']}&lang=zh_CN";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_URL, $url);
			$json =  curl_exec($ch);
			curl_close($ch);
			$wxuser=json_decode($json,1);

			/* ?????????????????? ???????????? ?????? unionid  ?????? ??? openid  */
			$openid=$wxuser['unionid'];
			if(!$openid){
				echo '?????????????????????????????????';
				exit;
			}
			$User=M('users');
		
			$userinfo=$User->field("id,user_login,user_nicename,avatar,avatar_thumb,sex,signature,consumption,votestotal,province,city,coin,votes,birthday,issuper")->where("openid!='' and openid='{$openid}'")->find();

			if(empty($userinfo)){	
				if($openid!=""){
					$authcode='rCt52pF2cnnKNB3Hkp';
					$user_pass="###".md5(md5($authcode.'123456'));
					
					$data=array(
						'openid' 	=>$openid,
						'user_login'	=> "wx_".time().substr($openid,-4), 
						'user_pass'		=>$user_pass,
						'user_nicename'	=> filterEmoji($wxuser['nickname']),
						'sex'=> $wxuser['sex'],
						'avatar'=> $wxuser['headimgurl'],
						'avatar_thumb'	=> $wxuser['headimgurl'],
						'login_type'=> "wx",
						'last_login_ip' =>$_SERVER['REMOTE_ADDR'],
						'create_time' => date("Y-m-d H:i:s"),
						'last_login_time' => date("Y-m-d H:i:s"),
						'user_status' => 1,
						"user_type"=>2,//??????
						'signature' =>'????????????????????????????????????',
					);	
					$userid=$User->add($data);
					
					$userinfo=$User->field("id,user_login,user_nicename,avatar,avatar_thumb,sex,signature,consumption,votestotal,province,city,coin,votes,birthday,issuper")->where("id='{$userid}'")->find();
				}
			} 
			$userinfo['level']=getLevel($userinfo['consumption']);

			$token=md5(md5($userinfo['id'].$userinfo['user_login'].time()));
			$expiretime=time()+60*60*24*300;

			$User->where("id='{$userinfo['id']}'")->save(array('token'=>$token,'expiretime'=>$expiretime));
			$userinfo['token']=$token; 

			session('uid',$userinfo['id']);
			session('token',$userinfo['token']);
			session('openid',$wxuser['openid']);
			session('unionid',$wxuser['unionid']);
			session('userinfo',$userinfo);
			
			$href='http://'.$_SERVER['HTTP_HOST'].'/wxshare/index.php/Share/show?roomnum='.$roomnum;
			
		 	header("Location: $href");
			
		}else{
			
			
			
		}
		
	}
	
	
	/* ??????????????? */
	public function getCode(){
		
		$config=getConfigPri();
	
		$target = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";

		$mobile = I("mobile");

		$mobile_code = random(6,1);

		$post_data = "account=".$config['ihuyi_account']."&password=".$config['ihuyi_ps']."&mobile=".$mobile."&content=".rawurlencode("?????????????????????".$mobile_code."?????????????????????????????????????????????");
		//???????????????????????????????????????32???MD5??????
		$gets = $this->xml_to_array($this->Post($post_data, $target)); 
		if($gets['SubmitResult']['code']==2){
			$_SESSION['mobile'] = $mobile;
			$_SESSION['mobile_code'] = $mobile_code;
			$_SESSION['reg_mobile_expiretime'] = time() +60*1;
			//$rs['info']['code']=$mobile_code;
		}else{
			 $rs['code']=2;
			 $rs['msg']=$gets['SubmitResult']['msg'];
			 
		}

		$rs=array(
			'errno'=>0,
			'data'=>array(),
			'errmsg'=>'???????????????',
		);
		
		echo json_encode($rs);
		exit;
	}
	public function Post($curlPost,$url){
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
	public function xml_to_array($xml){
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
			
	
	/* ?????? */
/* 	$user_login!=$_SESSION['mobile'] */
	public function userLogin(){
		$user_login=I("mobile");
		$code=I("code");
		$rs=array('errno'=>0,'data'=>array(),'errmsg'=>'');
		if($user_login!=session('mobile')){	
			$rs['errno']=3;
			$rs['errmsg']='?????????????????????';
			echo json_encode($rs);
			exit;						
		}

		if($code!=session('mobile_code')){
			$rs['errno']=1;
			$rs['errmsg']='???????????????';
			echo json_encode($rs);
			exit;	
			
		}	
	
		$User=M("users");
		
		$userinfo=$User->field("id,user_login,user_nicename,avatar,avatar_thumb,sex,signature,consumption,votestotal,province,city,coin,votes,birthday,issuper,user_status")->where("user_login='{$user_login}' and user_type='2'")->find();
		
		if(!$userinfo){
			$pass='yunbaokj';
			$user_pass=setPass($pass);
			
			/* ????????? ???????????? */
			$data=array(
				'user_login' => $user_login,
				'user_email' => '',
				'mobile' =>$user_login,
				'user_nicename' =>'???????????????',
				'user_pass' =>$user_pass,
				'signature' =>'????????????????????????????????????',
				'avatar' =>'/default.jpg',
				'avatar_thumb' =>'/default_thumb.jpg',
				'last_login_ip' =>get_client_ip(),
				'create_time' => date("Y-m-d H:i:s"),
				'last_login_time' => date("Y-m-d H:i:s"),
				'user_status' => 1,
				"user_type"=>2,//??????
			);	
			$userid=$User->add($data);	
			$userinfo=array(
				'id' => $userid,
				'user_login' => $data['user_login'],
				'user_nicename' => $data['user_nicename'],
				'avatar' => $data['avatar'],
				'avatar_thumb' => $data['avatar_thumb'],
				'sex' => '2',
				'signature' => $data['signature'],
				'consumption' => 0,
				'votestotal' => 0,
				'province' => '',
				'city' => '',
				'coin' => 0,
				'votes' => 0,
				'birthday' => '',
				'issuper' => 0,
				'user_status' => 1,
			);
		} 
		
		if($userinfo['user_status']==0){
			$rs['errno']=1002;
			$rs['errmsg']='??????????????????';
			echo json_encode($rs);
			exit;	
		}
		$userinfo['level']=getLevel($userinfo['consumption']);
		if(!$userinfo['token'] || !$userinfo['expiretime']){
			$token=md5(md5($userinfo['id'].$userinfo['user_login'].time()));
			$expiretime=time()+60*60*24*300;
			$User->where("id='{$userinfo['id']}'")->save(array('token'=>$token,'expiretime'=>$expiretime));
			$userinfo['token']=$token;
		}

		session('uid',$userinfo['id']);
		session('token',$userinfo['token']);
		session('user',$userinfo);
		
		echo json_encode($rs);
		exit;	
		exit;	
	} 	
	

	/* ???????????? ????????? */
	public function setNodeInfo() {

		/* ?????????????????? */
		$uid=session("uid");
		$liveuid=I('liveuid');
		$token=session("token");
		if($uid>0){ 
			$info=getUserInfo($uid);				
			$info['sign'] = md5($liveuid.'_'.$info['id']);
			$info['token']=$token;
			
			$carinfo=getUserCar($uid);
			$info['car']=$carinfo;
		}else{
			/* ?????? */
			$sign= mt_rand(1000,9999);
			$info['id'] = '-'.$sign;
			$info['user_nicename'] = '??????'.$sign;
			$info['avatar'] = '';
			$info['avatar_thumb'] = '';
			$info['sex'] = '0';
			$info['signature'] = '0';
			$info['consumption'] = '0';
			$info['votestotal'] = '0';
			$info['province'] = '';
			$info['city'] = '';
			$info['level'] = '0';
			$info['sign'] = md5($liveuid.'_'.$sign);
			$info['token']=$info['sign'];
			$info['vip']=array('type'=>'0');
			$info['car']=array(
							'id'=>'0',
							'swf'=>'',
							'swftime'=>'0',
							'words'=>'',
						);
			$token =$info['sign'] ;
		}			

		$redis = connectionRedis();
		$redis  -> set($token,json_encode($info));
		$redis -> close();	
		$data=array(
			'error'=>0,
			'userinfo'=>$info,
		 );
		echo  json_encode($data);				
		
	}
	
	
	public function getGift(){
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$gift=M("gift")->field("id,type,giftname,needcoin,gifticon")->order("orderno asc")->select();
		$rs['info']=$gift;
		echo json_encode($rs);
		exit;
	}
	
	/* ?????? */
	public function follow(){
		$uid=session("uid");
		$touid=(int)I('touid');
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$data=array(
			"uid"=>$uid,
			"touid"=>$touid,
		);
		$result=M("users_attention")->add($data);
		if(!$result){
			$rs = array(
				'code' => 1001, 
				'msg' => '????????????', 
				'info' => array()
			);
		}
		echo json_encode($rs);
		exit;
	}
	
	/* ????????? */
	public function sendGift(){

		$User=M("users");
		$uid=session("uid");
		$token=I("token");
		$touid=I('touid');
		$stream=I('stream');
		$giftid=I('giftid');
		$giftcount=1;
		$userinfo= $User->field('coin,token,expiretime,user_nicename,avatar')->where("id='{$uid}'")->find();
	
		/* ???????????? */
		$giftinfo=M("gift")->field("giftname,gifticon,needcoin,type")->where("id='{$giftid}'")->find();		
		if(!$giftinfo){
			echo '{"errno":"1001","data":"","msg":"??????????????????"}';
			exit;				
		}
		$total= $giftinfo['needcoin']*$giftcount;
		$addtime=time();
		if($userinfo['coin'] < $total){
			/* ???????????? */
			echo '{"errno":"1001","data":"","msg":"????????????"}';
			exit;	
		}		
		/* ?????????????????? ?????? */
		M()->execute("update __PREFIX__users set coin=coin-{$total},consumption=consumption+{$total} where id='{$uid}'");
		/* ???????????? ?????? ???????????? */						 
		M()->execute("update __PREFIX__users set votes=votes+{$total},votestotal=votestotal+{$total} where id='{$touid}'");
		/* ???????????? ?????? ???????????? */
		$stream2=explode('_',$stream);
		$showid=$stream2[1];
		
		M("users_coinrecord")->add(array("type"=>'expend',"action"=>'sendgift',"uid"=>$uid,"touid"=>$touid,"giftid"=>$giftid,"giftcount"=>$giftcount,"totalcoin"=>$total,"showid"=>$showid,"addtime"=>$addtime ));	
		
		$userinfo2=$User->field("consumption,coin,votestotal")->where("id='{$uid}'")->find();
		$level=getLevel($userinfo2['consumption']);				 
		$gifttoken=md5(md5('sendGift'.$uid.$touid.$giftid.$giftcount.$total.$showid.$addtime));

		$result=array("uid"=>(int)$uid,"giftid"=>(int)$giftid,"giftcount"=>(int)$giftcount,"totalcoin"=>$total,"giftname"=>$giftinfo['giftname'],"gifticon"=>$giftinfo['gifticon'],"level"=>$level,"coin"=>$userinfo2['coin'],"votestotal"=>$userinfo2['votestotal']);
		$redis = connectionRedis();
		$redis  -> set($gifttoken,json_encode($result));
		$redis -> close();	
		$evensend="n";
		if($giftinfo['type']==1)
		{
			$evensend="y";
		}
		echo '{"errno":"0","uid":"'.$uid.'","level":"'.$level.'","evensend":"'.$evensend.'","coin":"'.$userinfo2['coin'].'","gifttoken":"'.$gifttoken.'","msg":"????????????"}';
		exit;	
			
	}

	/* ????????????  */
	public function pay(){
		$uid=session('uid');
		$userinfo=M("users")->field("id,user_nicename,avatar_thumb,coin")->where("id='{$uid}'")->find();
		$this->assign('userinfo',$userinfo);
		
		$chargelist=M('charge_rules')->field('id,coin,money,money_ios,product_id,give')->order('orderno asc')->select();
		
		$this->assign('chargelist',$chargelist);
		
		$this->display();
	}
	/* ??????????????? */
	public function getOrderId(){
		$uid=session('uid');
		$chargeid=I('chargeid');
		$rs=array(
			'code'=>0,
			'data'=>array(),
			'msg'=>'',
		);
		$charge=M("charge_rules")->where("id={$chargeid}")->find();
		if($charge){
			$orderid=$uid.'_'.date('YmdHis').rand(100,999);
			$orderinfo=array(
				"uid"=>$uid,
				"touid"=>$uid,
				"money"=>$charge['money'],
				"coin"=>$charge['coin'],
				"coin_give"=>$charge['give'],
				"orderno"=>$orderid,
				"type"=>'2',
				"status"=>0,
				"addtime"=>time()
			);
			$result=M("users_charge")->add($orderinfo);
			if($result){
				$rs['data']['uid']=$uid;
				$rs['data']['money']=$charge['money'];
				$rs['data']['orderid']=$orderid;
			}else{
				$rs['code']=1001;
				$rs['msg']='??????????????????';
			}
			
		}else{
			$rs['code']=1002;
			$rs['msg']='??????????????????';
			
		}
		
		
		echo json_encode($rs);
		exit;
		
	}
	/* ?????? */
	public function charge(){
		

		ini_set('date.timezone','Asia/Shanghai');
		//error_reporting(E_ERROR);
		require_once "../wxpay/lib/WxPay.Api.php";
		require_once "../wxpay/pay/WxPay.JsApiPay.php";

		//????????????????????????
		/* function printf_info($data)
		{
			foreach($data as $key=>$value){
				echo "<font color='#00ff55;'>$key</font> : $value <br/>";
			}
		} */

		/* $uid=$_REQUEST['uid'];
		$money=$_REQUEST['money'];
		$orderid=$_REQUEST['orderid']; */
		
		$uid=12;
		$money=0.01;
		$orderid='12_123456';

		$fee=$money*100;

		$money=number_format($money,2,'.','');
		
		//??????????????????openid
		$tools = new \JsApiPay();

		//$openId = $tools->GetOpenid();
		$openId = session('openid');
		
		//??????????????????
		$input = new \WxPayUnifiedOrder();
		$input->SetBody("????????????");
		$input->SetAttach("test");
		$input->SetOut_trade_no(\WxPayConfig::MCHID.date("YmdHis"));
		//$input->SetOut_trade_no($orderid);
		$input->SetTotal_fee($fee);
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag($uid);
		$input->SetNotify_url('http://'.$_SERVER['HTTP_HOST'].'/wxpay/pay/notify_jsapi.php');
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($openId);
		$WxPayApi=new \WxPayApi();
		//$order = WxPayApi::unifiedOrder($input);
		$order = $WxPayApi->unifiedOrder($input);
		//echo '<font color="#f00"><b>???????????????????????????</b></font><br/>';
		//printf_info($order);
		$jsApiParameters = $tools->GetJsApiParameters($order);		
		$rs=array(
			'code'=>0,
			'data'=>$jsApiParameters,
			'msg'=>0,
		);
		echo json_encode($rs);
		exit;
	}
	
	
	
	
	
	
}