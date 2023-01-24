<?php

class Model_Login extends Model_Common {
	
	protected $fields='id,user_nicename,avatar,avatar_thumb,sex,signature,coin,user_status,login_type,province,city,birthday,last_login_time,code,age,mobile,free_count,inviteurl';

	
	/* h5 分享页面注册 */   	
	public function shareRsg($user_login,$uid) {

		//判断邀请者是否存在
		$isexist=DI()->notorm->users->select("user_login,user_status")->where("id=? and user_type='2'",$uid)->fetchOne();

		if(!$isexist){
			return 1001;
		}

		if($isexist['user_status']!=1){
			return 1003;
		}
		
		$info=DI()->notorm->users
		->select($this->fields)
		->where('user_login=? and user_type="2"',$user_login) 
		->fetchOne();

		if(!$info){
			
			//新注册该用户
			$user_pass='maiyatang';
			$user_pass=$this->setPass($user_pass);
			$user_login=$user_login;

			$nickname='手机用户'.substr($user_login,-4);
			
			$avatar='/default.jpg';
			$avatar_thumb='/default_thumb.jpg';

			$code=$this->createCode();

			//获取后台配置的邀请奖励
			$configpri=$this->getConfigPri();	
			$data=array(
				'user_login' => $user_login,
				'user_nicename' =>$nickname,
				'user_pass' =>$user_pass,
				'signature' =>'这家伙很懒，什么都没留下',
				'avatar' =>$avatar,
				'avatar_thumb' =>$avatar_thumb,
				'last_login_ip' =>$_SERVER['HTTP_X_FORWARDED_FOR'],
				'create_time' => date("Y-m-d H:i:s"),
				'user_status' => 1,
				'last_login_time' => date("Y-m-d H:i:s"),
				'free_count' => $configpri['free_count'],
				'inviteurl' => '/appapi/Sharelogin?code='.$code,//邀请链接
				
				"user_type"=>2,//会员
				"code"=>$code,
				"coin"=>0,
				"age"=>0,
				"mobile"=>$user_login,
				"login_type"=>'phone'
			);
			
			$info =DI()->notorm->users->insert($data);
			$info['isVip']='0';
			$info['praise']='0';
			$info['fans']='0';
			$info['follows']='0';
			$info['workVideos']='0';
			$info['likeVideos']='0';

			//给新注册用户添加金币
			DI()->notorm->users->where("id=?",$info['id'])->update(array('coin'=> new NotORM_Literal("coin + {$configpri['invite_tacket']} ")));
			//给邀请用户添加金币
			DI()->notorm->users->where("id=?",$uid)->update(array('coin'=> new NotORM_Literal("coin + {$configpri['invite_tacket']} ")));
			//$this->addtimes($uid);
			//写入分享注册奖励记录
			
			$data=array(
				"uid"=>$info['id'],//被邀请人
				"touid"=>$uid,
				"coin"=>$configpri['invite_tacket'],
				"addtime"=>time()
			);

			DI()->notorm->users_sharereg->insert($data);
			
		}else{

			return 1002;
			unset($info['user_status']);

		}

		
		return $info;
	}

	/*增加分享用户次数*/
	protected function addtimes($uid){
			$num=DI()->notorm->users_sharereg->where("uid=?",$uid)->count();
 			$infos=DI()->notorm->spread
                ->select("*")
                ->where("spread_num<=?",$num)
             	->limit('1')
                ->order("spread_num desc")
                ->fetchOne(); 
             if(!empty($infos)){
                
    			
                $parentInfo = $this->getUserInfo($uid);
                $update = [];
                $update['spread_count'] = $infos['free_count'];
                if ($infos['free_count'] > $parentInfo['spread_count']) {//如果分享的用户数量达到下一等级，则也需要给当天余额增加
                    $update['free_count'] = $parentInfo['free_count'] + $infos['free_count'];
                }

                $flag =  DI()->notorm->users->update($uid,$update);
               return $flag;
            }    
		
	}

	/* 会员登录 */   	
    public function userLogin($user_login,$uuid='') {
		
		$info=DI()->notorm->users
				->select($this->fields)
				->where('user_login=? and user_type="2"',$user_login) 
				->fetchOne();

		if(!$info){
			
			//新注册该用户
			$user_pass='lingdu';
			$user_pass=$this->setPass($user_pass);
			$user_login=$user_login;

			$nickname='手机用户'.substr($user_login,-4);
			
			$avatar='/default.jpg';
			$avatar_thumb='/default_thumb.jpg';

			$code=$this->createCode();

			//获取后台配置的邀请奖励
			$configpri=$this->getConfigPri();	
			$data=array(
				'user_login' => $user_login,
				'user_nicename' =>$nickname,
				'user_pass' =>$user_pass,
				'signature' =>'这家伙很懒，什么都没留下',
				'avatar' =>$avatar,
				'avatar_thumb' =>$avatar_thumb,
				'last_login_ip' =>$_SERVER['HTTP_X_FORWARDED_FOR'],
				'create_time' => date("Y-m-d H:i:s"),
				'last_login_time' => date("Y-m-d H:i:s"),
				'free_count' => $configpri['free_count'],
				'inviteurl' => '/appapi/Sharelogin?code='.$code,//邀请链接
				'uuid' => $uuid,
				'user_status' => 1,
				"user_type"=>2,//会员
				"code"=>$code,
				'free_count'=>10,
				"coin"=>0,
				"age"=>0,
				"mobile"=>$user_login,
				"login_type"=>'phone'
			);
			
			$rs=DI()->notorm->users->insert($data);	
		
			$info['id']=$rs['id'];
			$info['user_nicename']=$data['user_nicename'];
			$info['avatar']=$this->get_upload_path($data['avatar']);
			$info['avatar_thumb']=$this->get_upload_path($data['avatar_thumb']);
			$info['inviteurl']=$this->get_upload_path($data['inviteurl']);
			$info['uuid']=$uuid;
			$info['sex']='2';
			$info['signature']=$data['signature'];
			$info['coin']='0';
			$info['login_type']=$data['login_type'];
			$info['province']='';
			$info['city']='';
			$info['birthday']='';
			$info['last_login_time']='';
			$info['code']=$code;
			$info['age']="0";
			$info['free_count']=$configpri['free_count'];
			$info['mobile']=$user_login;
			$info['isreg']='1';
			$info['isVip']='0';
			$info['praise']='0';
			$info['fans']='0';
			$info['follows']='0';
			$info['workVideos']='0';
			$info['likeVideos']='0';
			
		}else{

			if($info['user_status']=='0'){
				return 1002;					
			}
			unset($info['user_status']);
			
			$info['avatar']=$this->get_upload_path($info['avatar']);
			$info['avatar_thumb']=$this->get_upload_path($info['avatar_thumb']);
			$info['inviteurl']=$this->get_upload_path($info['inviteurl']);
			$info['praise']=$this->getPraises($info['id']);
			$vip=$this->getUserVip($info['id']);
		$info['isVip']=$vip['type'];
		$info['viptime']=$vip['endtime'];
		
			$info['fans']=$this->getFans($info['id']);
			$info['follows']=$this->getFollows($info['id']);
			$info['workVideos']=$this->getWorks($info['id']);
			$info['likeVideos']=$this->getLikes($info['id']);
			//$this->setCache("userinfo_".$uid,$info);

			//判断用户是否填写了邀请码
			$shareReg=DI()->notorm->users_sharereg->where("uid=?",$info['id'])->fetchOne();
			if(!$shareReg){
				$info['isreg']='1';

			}else{
				$info['isreg']='0';
			}

		}

			$token=md5(md5($info['id'].$user_login.time()));
			$info['token']=$token;
			$this->updateToken($info['id'],$token);

			$cache=array("token_".$info['id'],"userinfo_".$info['id']);
			$this->delcache($cache);

		
        return $info;
    }	
	
	
	/* 游客登录 */   	
    public function userLogin1($uuid) {
		
		$info=DI()->notorm->users
				->select($this->fields)
				->where('uuid=? and user_type="2"',$uuid) 
				->fetchOne();
		$configpri=$this->getConfigPri();
		if(!$info){
			
			//新注册该用户
			
			$avatar='/default.jpg';
			$avatar_thumb='/default_thumb.jpg';

			$code=$this->createCode();

			//获取后台配置的邀请奖励
				
			$data=array(
				'user_nicename' =>'',
				'user_login' =>'',
				'signature' =>'这家伙很懒，什么都没留下',
				'avatar' =>$avatar,
				'uuid' =>$uuid,
				'avatar_thumb' =>$avatar_thumb,
				'last_login_ip' =>$_SERVER["HTTP_X_FORWARDED_FOR"]?$_SERVER["HTTP_X_FORWARDED_FOR"]:$_SERVER["REMOTE_ADDR"],
				'create_time' => date("Y-m-d H:i:s"),
				'last_login_time' => date("Y-m-d H:i:s"),
				'free_count' => $configpri['free_count'],
				'inviteurl' => '/appapi/Sharelogin?code='.$code,//邀请链接
				'user_status' => 1,
				"user_type"=>2,//用户
				"code"=>$code,
				"coin"=>0,
				"age"=>0,
				"mobile"=>$user_login,
				"login_type"=>'phone'
			);
			
			$rs=DI()->notorm->users->insert($data);	
			$nickname='游客账号_'.$rs['id'];
			DI()->notorm->users->where('id=?',$rs['id']) ->update(array('user_nicename'=>$nickname));
			$info['id']=$rs['id'];
			$info['user_nicename']=$nickname;
			$info['avatar']=$this->get_upload_path($data['avatar']);
			$info['avatar_thumb']=$this->get_upload_path($data['avatar_thumb']);
			$info['inviteurl']=$this->get_upload_path($data['inviteurl']);
			$info['sex']='2';
			$info['signature']=$data['signature'];
			$info['coin']='0';
			$info['login_type']=$data['login_type'];
			$info['province']='';
			$info['city']='';
			$info['birthday']='';
			$info['last_login_time']='';
			$info['code']=$code;
			$info['age']="0";
			$info['free_count']=$configpri['free_count'];
			$info['mobile']='';
			$info['isreg']='1';
			$info['isVip']='0';
			$info['praise']='0';
			$info['fans']='0';
			$info['follows']='0';
			$info['workVideos']='0';
			$info['likeVideos']='0';
			
		}else{

			if($info['user_status']=='0'){
				return 1002;					
			}

			DI()->notorm->users->where('id=?',$info['id']) ->update(array('uuid'=>$uuid));
			unset($info['user_status']);
			
			$info['avatar']=$this->get_upload_path($info['avatar']);
			$info['avatar_thumb']=$this->get_upload_path($info['avatar_thumb']);
			$info['inviteurl']=$this->get_upload_path($info['inviteurl']);
			$info['free_count']=$info['free_count'].'/'.$configpri['free_count'];

			//$this->setCache("userinfo_".$uid,$info);
			$info['praise']=$this->getPraises($info['id']);
			$vip=$this->getUserVip($info['id']);
			$info['isVip']=$vip['type'];
			$info['viptime']=$vip['endtime'];
			$info['fans']=$this->getFans($info['id']);
			$info['follows']=$this->getFollows($info['id']);
			$info['workVideos']=$this->getWorks($info['id']);
			$info['likeVideos']=$this->getLikes($info['id']);
		

			//判断用户是否填写了邀请码
			$shareReg=DI()->notorm->users_sharereg->where("uid=?",$info['id'])->fetchOne();
			if(!$shareReg){
				$info['isreg']='1';

			}else{
				$info['isreg']='0';
			}

		}
		$list=DI()->notorm->admin_push->select("id,title,synopsis,type,url,addtime,content")->order("id desc")->fetchOne();
		$ad = [];
		if($list){
			$ad['id'] =  $list['id'];
			$ad['noticeTitle'] =  $list['title'];
			$ad['noticeContent'] =  $list['content'];
			$ad['url'] =  $list['url'];
		}
		
			$info['tongZhi'] = (Object)$ad;
			$token=md5(md5($info['id'].$user_login.time()));
			$info['token']=$token;
			$this->updateToken($info['id'],$token);

			$cache=array("token_".$info['id'],"userinfo_".$info['id']);
			$this->delcache($cache);

		
        return $info;
    }	
	

	/* 找回密码 */
	public function userFindPass($user_login,$user_pass){
		$isexist=DI()->notorm->users
				->select('id')
				->where('user_login=? and user_type="2"',$user_login) 
				->fetchOne();
		if(!$isexist){
			return 1006;
		}		
		$user_pass=$this->setPass($user_pass);

		return DI()->notorm->users
				->where('id=?',$isexist['id']) 
				->update(array('user_pass'=>$user_pass));
		
	}
	/* ip限定 */
	/*public function ip_limit($ip){
		$date = date("Ymd");
		$configpri=$this->getConfigPri();
		if($configpri['iplimit_switch']==0){
			return 0;
		}
		$isexist=DI()->notorm->getcode_limit_ip
				->select('ip,date,times')
				->where(' ip=? ',$ip) 
				->fetchOne();
		if(!$isexist){
			$data=array(
				"ip" => $ip,
				"date" => $date,
				"times" => 1,
			);
			$isexist=DI()->notorm->getcode_limit_ip->insert($data);
			return 0;
		}elseif($date == $isexist['date'] && $isexist['times'] < $configpri['iplimit_times'] ){
			return 1;
		}else{
			if($date == $isexist['date']){
				$isexist=DI()->notorm->getcode_limit_ip
						->where(' ip=? ',$ip) 
						->update(array('times'=> new NotORM_Literal("times + 1 ")));
				return 0;
			}else{
				$isexist=DI()->notorm->getcode_limit_ip
						->where(' ip=? ',$ip) 
						->update(array('date'=> $date ,'times'=>1));
				return 0;
			}
		}	

	}	*/	
		
	/* 第三方会员登录 */
    public function userLoginByThird($openid,$type,$nickname,$avatar,$device) {			

        /* QQ判断设备号 */
        if($type=='qq'){

            $isexist=DI()->notorm->users
                ->select('openid')
                ->where('device=? and login_type=? and user_type="2"',$device,$type)
                ->fetchOne();    
            if($isexist && $isexist['openid'] !=$openid){
                return 1003;
            }
            
        }

          
        $info=DI()->notorm->users
            ->select($this->fields.',device')
            ->where('openid=? and login_type=? and user_type="2"',$openid,$type)
            ->fetchOne();
		$configpri=$this->getConfigPri();
        
        /* QQ判断设备号 */
        if($type=='qq'){
            if($info && $info['device']!='' && $info['device'] !=$device){
                return 1004;
            }
            
        }

		if(!$info){

			/* 注册 */
			$user_pass='yunbaokeji';
			$user_pass=$this->setPass($user_pass);
			$user_login=$type.'_'.time().rand(100,999);

			if(!$nickname){
				$nickname=$type.'用户-'.substr($openid,-4);
			}else{
				$nickname=urldecode($nickname);
			}
			if(!$avatar){
				$avatar='/default.jpg';
				$avatar_thumb='/default_thumb.jpg';
			}else{
				$avatar=urldecode($avatar);
				$avatar_thumb=$avatar;
			}

			$code=$this->createCode();
			
			$data=array(
				'user_login' => $user_login,
				'user_nicename' =>$nickname,
				'user_pass' =>$user_pass,
				'signature' =>'这家伙很懒，什么都没留下',
				'avatar' =>$avatar,
				'avatar_thumb' =>$avatar_thumb,
				'last_login_ip' =>$_SERVER['HTTP_X_FORWARDED_FOR'],
				'create_time' => date("Y-m-d H:i:s"),
				'inviteurl' => '/appapi/Sharelogin?code='.$code,//邀请链接
				'free_count'=>$configpri['free_count'],
				'user_status' => 1,
				'openid' => $openid,
				'login_type' => $type, 
				"user_type"=>2,//会员
				"code"=>$code,
				"device"=>$device,
				"age"=>0,
				"coin"=>$configpri['reg_reward'],
			);
			
			$rs=DI()->notorm->users->insert($data);		
		
			$info['id']=$rs['id'];
			$info['user_nicename']=$data['user_nicename'];
			$info['avatar']=$this->get_upload_path($data['avatar']);
			$info['avatar_thumb']=$this->get_upload_path($data['avatar_thumb']);
			$info['inviteurl']=$this->get_upload_path($data['inviteurl']);
			$info['sex']='2';
			$info['signature']=$data['signature'];
			$info['coin']='0';
			$info['login_type']=$data['login_type'];
			$info['free_count']=$configpri['free_count'];
			$info['province']='';
			$info['city']='';
			$info['birthday']='';
			$info['consumption']='0';
			$info['user_status']=1;
			$info['last_login_time']='';
			$info['isreg']=1;
            $info['device']=$data['device'];

		}else{


			//判断用户是否填写了邀请码
			$shareReg=DI()->notorm->users_sharereg->where("uid=?",$info['id'])->fetchOne();
			if(!$shareReg){
				$info['isreg']=1;

			}else{
				$info['isreg']=0;
			}
			
		}
        if($info['device']==''){
            DI()->notorm->users->where('id=? and device=""',$info['id'])->update(array("device"=>$device ));
        }

		
		if($info['user_status']=='0'){
			return 1002;					
		}
		unset($info['user_status']);

		unset($info['last_login_time']);
		
		$token=md5(md5($info['id'].$openid.time()));
		
		$info['token']=$token;
		$info['avatar']=$this->get_upload_path($info['avatar']);
		$info['avatar_thumb']=$this->get_upload_path($info['avatar_thumb']);
		
		$this->updateToken($info['id'],$token);
		
		$cache=array("token_".$info['id'],"userinfo_".$info['id']);
		$this->delcache($cache);
        return $info;
    }		
	
	/* 更新token 登陆信息 */
    public function updateToken($uid,$token) {
		$expiretime=time()+60*60*24*300;
		
        DI()->notorm->users
			->where('id=?',$uid)
            ->update(array("token"=>$token, "expiretime"=>$expiretime ,'last_login_time' => date("Y-m-d H:i:s"), "last_login_ip"=>$_SERVER['HTTP_X_FORWARDED_FOR']?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER["REMOTE_ADDR"] ));
		return 1;
    }	
	
	/* 生成邀请码 */
	/*public function createCode(){
		$code = 'ABCDEFGHIJKLMNPQRSTUVWXYZ';
		$rand = $code[rand(0,25)]
			.strtoupper(dechex(date('m')))
			.date('d').substr(time(),-5)
			.substr(microtime(),2,5)
			.sprintf('%02d',rand(0,99));
		for(
			$a = md5( $rand, true ),
			$s = '123456789ABCDEFGHIJKLMNPQRSTUV',
			$d = '',
			$f = 0;
			$f < 6;
			$g = ord( $a[ $f ] ),
			$d .= $s[ ( $g ^ ord( $a[ $f + 6 ] ) ) - $g & 0x1F ],
			$f++
		);
		if(mb_strlen($d)==6){
			$oneinfo=DI()->notorm->users
					->select("id")
					->where('code=?',$d)
					->fetchOne();
			if(!$oneinfo){
				return $d;
			}
		}
        $d=$this->createCode();
		return $d;
	}*/


	public function createCode($len=6,$format='ALL2'){
        $is_abc = $is_numer = 0;
        $password = $tmp =''; 
        switch($format){
            case 'ALL':
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                break;
            case 'ALL2':
                $chars='ABCDEFGHJKLMNPQRSTUVWXYZ0123456789';
                break;
            case 'CHAR':
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                break;
            case 'NUMBER':
                $chars='0123456789';
                break;
            default :
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                break;
        }
        
        while(strlen($password)<$len){
            $tmp =substr($chars,(mt_rand()%strlen($chars)),1);
            if(($is_numer <> 1 && is_numeric($tmp) && $tmp > 0 )|| $format == 'CHAR'){
                $is_numer = 1;
            }
            if(($is_abc <> 1 && preg_match('/[a-zA-Z]/',$tmp)) || $format == 'NUMBER'){
                $is_abc = 1;
            }
            $password.= $tmp;
        }
        if($is_numer <> 1 || $is_abc <> 1 || empty($password) ){
            $password = $this->createCode($len,$format);
        }
        if($password!=''){
            
            $oneinfo=DI()->notorm->users
	            ->select("id")
	            ->where("code=?",$password)
	            ->fetchOne();
	        
            if(!$oneinfo){
                return $password;
            }            
        }
        $password = $this->createCode($len,$format);
        return $password;
    }

}
