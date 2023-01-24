<?php
class Model_User extends Model_Common {
	/* 用户全部信息 */
	public function getBaseInfo($uid) {

		$config=$this->getConfigPri();
		$info=DI()->notorm->users
				->select("id,user_login,user_nicename,uuid,mobile,avatar,avatar_thumb,sex,signature,coin,province,city,area,birthday,age,praise,free_count,free_endtime,inviteurl,code,ye_ji,votes,votestotal")
				->where('id=?  and user_type="2"',$uid)
				->fetchOne();

		if($info['age']==-1){
			$info['age']="年龄未填写";
		}else{
			$info['age'].="岁";
		}

		if($info['city']==""){
			$info['city']="城市未填写";
			$info['hometown']="";
		}else{
			$info['hometown']=$info['province'].$info['city'].$info['area'];
		}	

		$info['avatar']=$this->get_upload_path($info['avatar']);
		$info['avatar_thumb']=$this->get_upload_path($info['avatar_thumb']);						
		$info['follows']=$this->getFollows($uid);
		$info['fans']=$this->getFans($uid);
		$info['praiseTotal']=$info['praise'];  //可减少的赞数
		$info['praise']=$this->getPraises($uid);
		$vip=$this->getUserVip($uid);
		$info['buys']=(string)$this->buys($uid);
		$info['isVip']=$vip['type'];
		$info['viptime']=date('Y-m-d',(int)$vip['endtime']);
		$info['free_count']=$info['free_count'].'/'.$config['free_count'];
		$info['workVideos']=$this->getWorks($uid);
		$info['likeVideos']=$this->getLikes($uid);
		$info['inviteurl']=$this->get_upload_path($info['inviteurl']);
		$list=DI()->notorm->admin_push->select("id,title,synopsis,type,url,addtime,content")->order("id desc")->fetchOne();
		$ad = [];
		if($list){
			$ad['id'] =  $list['id'];
			$ad['noticeTitle'] =  $list['title'];
			$ad['noticeContent'] =  $list['content'];
			$ad['url'] =  $list['url'];
		}
		$info['tongZhi'] = (Object)$ad;
					
		return $info;
	}

	/* VIP规则信息 */
	public function getVipInfo($uid) {

		
		$vip=DI()->notorm->vip
				->select("*")
				->order('orderno asc')
				->fetchAll();
		foreach ($vip as $key => $value) {
			$vip[$key]['thumb'] = $this->get_upload_path($value['thumb']);
			$vip[$key]['name'] = explode('|', $value['name'])[0];
			$vip[$key]['thumb_name'] = explode('|', $value['name'])[1];
		}
		$info['userinfo']=$uid?$this->getUserInfo($uid):(object)array();
		$info['vipinfo']=$vip;				
		
		
					
		return $info;
	}
	
			
	/* 判断昵称是否重复 */
	public function checkName($uid,$name){
		$isexist=DI()->notorm->users
					->select('id')
					->where('id!=? and user_nicename=?',$uid,$name)
					->fetchOne();
		if($isexist){
			return 0;
		}else{
			return 1;
		}
	}
	/* 判断手机号码是否重复 */
	public function checkMobile($uid,$mobile){
		$isexist=DI()->notorm->users
					->select('id')
					->where('id!=? and mobile=?',$uid,$mobile)
					->fetchOne();
		if($isexist){
			return 0;
		}else{
			return 1;
		}
	}
	/* 修改信息 */
	public function userUpdate($uid,$fields){
		/* 清除缓存 */
		$is_user = DI()->notorm->users->where('user_login=?',$fields['user_login'])->fetchOne();
		$user = $this->getUserInfo($uid);
		
		if($is_user){
			$old_id = $is_user['id'];
			$new_id =$uid;

			try {

				DI()->notorm->users->where('id=?',$old_id)->update(['user_login'=>'','mobile'=>'','expiretime'=>time()]);
				DI()->notorm->users->where('id=?',$new_id)->update($fields);
				

				return $this->getBaseInfo($uid);
			} catch (Exception $e) {
				
				return 0;
			}
		
			
		}
		// $this->delCache("userinfo_".$uid);
		$res = DI()->notorm->users
					->where('id=?',$uid)
					->update($fields);

		if($res){
			$this->setCache("userinfo_".$uid,$this->getBaseInfo($uid));
			return $this->getCache("userinfo_".$uid);
		}
		return 0;
	}

	/* 修改密码 */
	public function updatePass($uid,$oldpass,$pass){
		$userinfo=DI()->notorm->users
					->select("user_pass")
					->where('id=?',$uid)
					->fetchOne();
		$oldpass=$this->setPass($oldpass);							
		if($userinfo['user_pass']!=$oldpass){
			return 1003;
		}							
		$newpass=$this->setPass($pass);
		return DI()->notorm->users
					->where('id=?',$uid)
					->update( array( "user_pass"=>$newpass ) );
	}
	
	/* 我的金币 */
	public function getBalance($uid){
		return DI()->notorm->users
				->select("coin")
				->where('id=?',$uid)
				->fetchOne();
	}
	
	/* 充值规则 */
	public function getChargeRules(){

		$rules= DI()->notorm->charge_rules
				->select('id,coin,money,money_ios,product_id,give,link')
				->order('orderno asc')
				->fetchAll();

		return 	$rules;
	}

	
	/* 我的金币收益 */
	public function getProfit($uid){
		$info= DI()->notorm->users
				->select("coin,votes,consumption")
				->where('id=?',$uid)
				->fetchOne();
		$level=$this->getLevel($info['consumption']);		
		//等级限制金额
		$limitcash=$this->getLevelSection($level);	
		
		$config=$this->getConfigPri();
		
		//提现比例
		$cash_rate=$config['cash_rate'];
		//剩余金币
		$votes=$info['coin'];
		//总可提现数
		$total=floor($votes/$cash_rate);
		
		$nowtime=time();
		//当天0点
		$today=date("Ymd",$nowtime);
		$today_start=strtotime($today)-1;
		//当天 23:59:59
		$today_end=strtotime("{$today} + 1 day");
		
		//已提现
		$hascash=DI()->notorm->users_cashrecord
					->where('uid=? and addtime>? and addtime<? and status!=2',$uid,$today_start,$today_end)
					->sum("money");
		if(!$hascash){
			$hascash=0;
		}		
		//今天可提现
		$todaycancash=$limitcash - $hascash;
		
		//今天能提
		if($todaycancash<$total){
			$todaycash=$todaycancash;
		}else{
			$todaycash=$total;
		}
		
		$rs=array(
			"votes"=>$votes,
			"todaycash"=>$todaycash,
			"total"=>$total,
		);
		return $rs;
	}	
	/* 提现  */
	public function setCash($data){



		// $isrz=DI()->notorm->users_auth
		// 		->select("status")
		// 		->where('uid=?',$uid)
		// 		->fetchOne();
		// if(!$isrz || $isrz['status']!=1){
		// 	return 1003;
		// }	
		$cate  =0;//默认金币提现类型
			
		$info= DI()->notorm->users
				->select("id,coin,votes,consumption")
				->where('id=?',$data['uid'])
				->fetchOne();
		$level=$this->getLevel($info['consumption']);		
		//等级限制金额
		$limitcash=$this->getLevelSection($level);	
		
		$config=$this->getConfigPri();

	


		//金币提现手续费 百分比
		$service_charge=$config['service_charge'];
		//金币提现比例1：10
		$cash_rate=$config['ticket_percent'];
		/* 最低额度 */
		$cash_min=$config['draw_min_cash']*$cash_rate;
		
		//总可提现数
		$totalMoney=floor($data['coin_num']/$cash_rate);

		//具体扣除金币数
		$all_coin = floor($data['coin_num'] *$service_charge/100) +$data['coin_num'];
		
		if($all_coin > $info['coin']){
			return 1005;
		}

	
		//已提现
		$nowtime=time();
		//当天0点
		//$today=date("Ymd",$nowtime);
		//$today_start=strtotime($today)-1;
		//当天 23:59:59
		//$today_end=strtotime("{$today} + 1 day");
		
		/*$hascash =DI()->notorm->users_coin_cashlist
					->where('uid=? and addtime>? and addtime<? and status!=2',$uid,$today_start,$today_end)
					->sum("money");
		if(!$hascash){
			$hascash=0;
		}	*/	
		//今天可提现
		//$todaycancash=$limitcash - $hascash;
		
		//今天能提
		/*if($todaycancash<$total){
			$todaycash=$todaycancash;
		}else{
			$todaycash=$total;
		}
		
		if($todaycash==0){
			return 1001;
		}*/
		
		if($data['coin_num'] < $cash_min){
			return 1004;
		}
		
		// $cashvotes=$todaycash*$cash_rate;
		if ($data['type'] ==1) {//现金提现
			//现金提现手续费 百分比
			$service_charge = $config['cash_withdraw'];
			//具体扣除现金账户额度
			$all_coin = floor($data['coin_num'] *$service_charge/100) +$data['coin_num'];
			if($info['votes'] < $cash_min){
				return 1005;
			}
			if($info['votes'] < $config['draw_min_cash']){
				return 1004;
			}
			$totalMoney = $data['coin_num'];
			
			$cash_rate = 1;
			$cate =1;
		}

		$nowtime=time();
		
		$data=array(
			"uid"=>$data['uid'],
			"money"=>$totalMoney,
			"coin"=>$all_coin,
			"account"=>$data['bankname'].'|'.$data['bankcard'],
			"percent"=>$cash_rate,
			//"orderno"=>$uid.'_'.$nowtime.rand(100,999),
			"status"=>0,
			"cate"=>$cate,
			"addtime"=>$nowtime
		);
		
		$user = DI()->notorm->users;
		$coin_cashlist = DI()->notorm->users_coin_cashlist;
		$res1 = $coin_cashlist->insert($data);
		if ($data['type'] ==1) {
			$res2 =	$user->where('id = ?', $data['uid'])->update(array('votes' => new NotORM_Literal("votes - {$totalMoney}")) );
		}else{
			$res2 =	$user->where('id = ?', $data['uid'])->update(array('coin' => new NotORM_Literal("coin - {$all_coin}")) );
		}
		
		if($res1 && $res2){
			if ($data['type'] !=1) $this->add_coin_log($data['uid'],0,$all_coin,$type='-',$action='withdraw',$nowtime);
			$rs = 1;
			
		}else{
			return 0;
		}				
		
		return $rs;
	}
	
	/* 关注 */
	public function setAttent($uid,$touid){


		//判断关注列表情况

		$is_attent = DI()->redis->sismember('users_attent'.$uid,$touid);

		if($is_attent){

			
			DI()->notorm->users_attention
				->where('uid=? and touid=?',$uid,$touid)
				->delete();
			DI()->redis->srem('users_attent'.$uid,$touid);
			return 0;
		}else{

			DI()->redis->sadd('users_attent'.$uid,$touid);
			DI()->notorm->users_black
				->where('uid=? and touid=?',$uid,$touid)
				->delete();
			DI()->notorm->users_attention
				->insert(array("uid"=>$uid,"touid"=>$touid,"addtime"=>time()));


			$isexist1=DI()->notorm->users_attention_messages
					->select("*")
					->where('uid=? and touid=?',$uid,$touid)
					->fetchOne();

			if($isexist1){
				DI()->notorm->users_attention_messages->where('uid=? and touid=?',$uid,$touid)->update(array("addtime"=>time()));
			}else{

				DI()->notorm->users_attention_messages
					->insert(array("uid"=>$uid,"touid"=>$touid,"addtime"=>time()));
			}


			return 1;
		}

		
		/*$isexist=DI()->notorm->users_attention
					->select("*")
					->where('uid=? and touid=?',$uid,$touid)
					->fetchOne();
		if($isexist){
			DI()->notorm->users_attention
				->where('uid=? and touid=?',$uid,$touid)
				->delete();
			return 0;

		}else{
			
			DI()->notorm->users_black
				->where('uid=? and touid=?',$uid,$touid)
				->delete();
			DI()->notorm->users_attention
				->insert(array("uid"=>$uid,"touid"=>$touid,"addtime"=>time()));


			$isexist1=DI()->notorm->users_attention_messages
					->select("*")
					->where('uid=? and touid=?',$uid,$touid)
					->fetchOne();

			if($isexist1){
				DI()->notorm->users_attention_messages->where('uid=? and touid=?',$uid,$touid)->update(array("addtime"=>time()));
			}else{

				DI()->notorm->users_attention_messages
					->insert(array("uid"=>$uid,"touid"=>$touid,"addtime"=>time()));
			}


			return 1;
		}*/			 
	}	
	
	/* 拉黑 */
	public function setBlack($uid,$touid){
		$isexist=DI()->notorm->users_black
					->select("*")
					->where('uid=? and touid=?',$uid,$touid)
					->fetchOne();
		if($isexist){
			DI()->notorm->users_black
				->where('uid=? and touid=?',$uid,$touid)
				->delete();
			return 0;
		}else{
			DI()->notorm->users_attention
				->where('uid=? and touid=?',$uid,$touid)
				->delete();
			DI()->notorm->users_black
				->insert(array("uid"=>$uid,"touid"=>$touid,"addtime"=>time()));

			return 1;
		}			 
	}

	/* 推荐列表 */
	public function getInviteList($uid,$p){
		$pnum=50;
		$start=($p-1)*$pnum;

		$where.="a.uid='{$uid}'";
	
		$prefix= DI()->config->get('dbs.tables.__default__.prefix');

		$record=DI()->notorm->users_sharereg->queryAll("select a.touid,u.code,u.mobile from {$prefix}users_sharereg as a left join {$prefix}users as u on a.touid=u.id where ".$where." limit {$start},{$pnum}");
	

		$lists = array();
		foreach($record as $k=>$v){
			$lists[$k]['touid'] = $v['touid'];
			$lists[$k]['code'] = $v['code'];
			if (!$v['mobile']) {
				$lists[$k]['mobile'] = '暂无';
				$lists[$k]['status'] = '未注册';
			}
		}		
		//$touids=array_values($touids);
		return $lists;
	}


	
	/* 关注列表 */
	public function getFollowsList($uid,$touid,$p,$key){
		$pnum=50;
		$start=($p-1)*$pnum;


		if(!$key){
			$touids=DI()->notorm->users_attention
					->select("touid")
					->where('uid=?',$touid)
					->limit($start,$pnum)
					->fetchAll();
		}else{


			$where.="a.uid='{$touid}' and u.user_nicename like '%".$key."%'";
		

			$prefix= DI()->config->get('dbs.tables.__default__.prefix');

			$touids=DI()->notorm->users_attention->queryAll("select a.touid,u.user_nicename from {$prefix}users_attention as a left join {$prefix}users as u on a.touid=u.id where ".$where." limit {$start},{$pnum}");
		}


		foreach($touids as $k=>$v){
			$userinfo=(array)$this->getUserInfo($v['touid']);

			if($userinfo){
				if($uid==$touid){
					$isattent=1;
				}else{
					$isattent=$this->isAttention($uid,$v['touid']);
				}
				$userinfo['isattention']=$isattent;
				$touids[$k]=$userinfo;
			}else{
				DI()->notorm->users_attention->where('uid=? or touid=?',$v['touid'],$v['touid'])->delete();
				unset($touids[$k]);
			}
		}		
		$touids=array_values($touids);
	
		return $touids;
	}
	
	/* 粉丝列表 */
	public function getFansList($uid,$touid,$p){

		$pnum=50;
		$start=($p-1)*$pnum;
		$touids=DI()->notorm->users_attention
					->select("uid")
					->where('touid=?',$touid)
					->limit($start,$pnum)
					->fetchAll();

		
		foreach($touids as $k=>$v){
			$userinfo=(array)$this->getUserInfo($v['uid']);
			if($userinfo){
				$userinfo['isattention']=$this->isAttention($uid,$v['uid']);
				$touids[$k]=$userinfo;
				$touids[$k]['attentiontime']=$this->datetime($v['addtime']);
			}else{
				DI()->notorm->users_attention->where('uid=? or touid=?',$v['uid'],$v['uid'])->delete();
				unset($touids[$k]);
			}
			
		}		
		$touids=array_values($touids);
		return $touids;
	}	

	/* 黑名单列表 */
	public function getBlackList($uid,$touid,$p){
		$pnum=50;
		$start=($p-1)*$pnum;
		$touids=DI()->notorm->users_black
					->select("touid,addtime")
					->where('uid=?',$touid)
					->limit($start,$pnum)
					->fetchAll();

		foreach($touids as $k=>$v){
			$userinfo=(array)$this->getUserInfo($v['touid']);
			if($userinfo){
				$userinfo['addtime']=$this->datetime($v['addtime']); //拉黑时间
				$touids[$k]=$userinfo;
			}else{
				DI()->notorm->users_black->where('uid=? or touid=?',$v['touid'],$v['touid'])->delete();
				unset($touids[$k]);
			}
		}
		$touids=array_values($touids);
		return $touids;
	}
	
	/* 直播记录 */
	public function getLiverecord($touid,$p){
		$pnum=50;
		$start=($p-1)*$pnum;
		$record=DI()->notorm->users_liverecord
					->select("id,uid,nums,starttime,endtime,title,city")
					->where('uid=?',$touid)
					->order("id desc")
					->limit($start,$pnum)
					->fetchAll();
		foreach($record as $k=>$v){
			$record[$k]['datestarttime']=date("Y年m月d日 H:i",$v['starttime']);
			$record[$k]['dateendtime']=date("Y年m月d日 H:i",$v['endtime']);
		}						
		return $record;						
	}	
	
		/* 个人主页 */
	public function getUserHome($uid,$touid){
		$info=$this->getUserInfo($touid);				

		$info['follows']=$this->NumberFormat($this->getFollows($touid));
		$info['fans']=$this->NumberFormat($this->getFans($touid));
		$info['isattention']=(string)$this->isAttention($uid,$touid);
		$info['buys']=(string)$this->buys($uid);
		$info['superCode'] = '';
		if($uid == $touid){
			$shareReg=DI()->notorm->users_sharereg->where("touid=?",$uid)->fetchOne();
			if($shareReg){
				$inviter = $this->getUserInfo($shareReg['uid']);
				$info['superCode']=(string)$inviter['code'];
			}
		}
		
		
		/*$info['isblack']=(string)$this->isBlack($uid,$touid);
		$info['isblack2']=(string)$this->isBlack($touid,$uid);*/

		
		
		
		
		return $info;
	}
	
	/* 贡献榜 */
	public function getContributeList($touid,$p){
		
		$pnum=50;
		$start=($p-1)*$pnum;

		$rs=array();
		$rs=DI()->notorm->users_coinrecord
				->select("uid,sum(totalcoin) as total")
				->where('touid=?',$touid)
				->group("uid")
				->order("total desc")
				->limit($start,$pnum)
				->fetchAll();
				
		foreach($rs as $k=>$v){
			$rs[$k]['userinfo']=$this->getUserInfo($v['uid']);
		}		
		
		return $rs;
	}
	
	/* 设置分销 */
	public function setDistribut($uid,$code){

		$oneinfo=DI()->notorm->users
				->select("id")
				->where('code=?',$code)
				->fetchOne();

		if(!$oneinfo){
			return 1002;
		}

		if($oneinfo['id'] == $uid){
            return 1004;
        }

		//判断是否已经添加了邀请关系
		
		$shareReg=DI()->notorm->users_sharereg->where("uid=? and touid=?",$oneinfo['id'],$uid)->fetchOne();

		if($shareReg){
			return 1003;
		}

		$configpri=$this->getConfigPri();
		

		//写入分享注册奖励记录
			
			$data=array(
				"uid"=>$oneinfo['id'],//邀请人
				"touid"=>$uid,
				"coin"=>$configpri['invite_tacket'],
				"addtime"=>time()
			);

		DI()->notorm->beginTransaction('db_appapi');		
		$res1=DI()->notorm->users_sharereg->insert($data);

		
        $userinfo = (array)$this->getUserInfo($oneinfo['id']);
        //给新注册用户添加金币
        DI()->notorm->users->where("id=?",$uid)->update(array('coin'=> new NotORM_Literal("coin + {$configpri['invite_tacket']} ")));
        //给邀请用户添加金币
        DI()->notorm->users->where("id=?",$oneinfo['id'])->update(array('coin'=> new NotORM_Literal("coin + {$configpri['invite_tacket']} ")));
        $addTime = $configpri['invite_add_day']*86400;
        $newTime = time() + $configpri['invite_add_day']*86400;
        $vipinfo = $this->getUserVip($oneinfo['id']);
        if($vipinfo['type']){//如果是VIP 把赠送天数加到VIP时间里
            $res2 = DI()->notorm->users_vip->where("uid=?",$oneinfo['id'])->update(array('endtime'=> new NotORM_Literal("endtime + {$addTime} ")));
        }elseif($vipinfo['endtime']){
        	$res2 = DI()->notorm->users_vip->where("uid=?",$oneinfo['id'])->update(array('endtime'=> $newTime));
        }else{
            $insert['uid'] =$oneinfo['id'];
            $insert['addtime'] =time();
            $insert['endtime'] =$newTime;
            $res2 = DI()->notorm->users_vip->insert($insert);
//                //给邀请用户增加观看时间 invite_add_day
//                if($userinfo['free_endtime']>time()){
//                    $free_endtime =$userinfo['free_endtime']+$newTime;
//                }else{
//                    $free_endtime =time()+$newTime;
//                }
//
//                $res2 = DI()->notorm->users->where("id=?",$oneinfo['id'])->update(array('free_endtime'=> $free_endtime));
        }

			// $obj = new Model_Login;
			// $obj->addtimes($oneinfo['id']);

        $one_agent=DI()->notorm->users_agent->where("uid={$oneinfo['id']}")->fetchOne();
        if(!$one_agent){
            $one_agent=array(
                'uid'=>$oneinfo['id'],
                'one_uid'=>0,
                'two_uid'=>0,
            );
        }
        //插入3级层级关系表1
        $data1=array(
            'uid'=>$uid,
            'one_uid'=>$one_agent['uid'],
            'two_uid'=>$one_agent['one_uid'],
            'three_uid'=>$one_agent['two_uid'],
            'addtime'=>time(),
        );
		$res3=DI()->notorm->users_agent->insert($data1);
		if($res1 && $res2 && $res3){
			DI()->notorm->commit('db_appapi');
		}else{
			DI()->notorm->rollback('db_appapi');
			file_put_contents("data/shareLog/".date('Y-m-d').".txt", '邀请关系绑定失败：'.$oneinfo.' to '.$uid.'/'.date('Y-m-d H:i:s').PHP_EOL, FILE_APPEND);
            		echo "fail";exit;
			return 1000;
		}
	
		return 0;
	}

	/*获取用户喜欢的视频列表*/
	public function getLikeVideos($uid,$p){


		$pnum=18;

		$start=($p-1)*$pnum;

		//获取用户喜欢的视频列表
		$list=DI()->notorm->users_video_like->where("uid=? and status=1",$uid)->limit($start,$pnum)->fetchAll();

		if(!$list){
			return 1001;
		}

		foreach ($list as $k => $v) {
			
			$videoinfo=DI()->notorm->users_video->where("id=? and status=1 and isdel=0",$v['videoid'])->fetchOne();

			if(!$videoinfo){
				//DI()->notorm->users_video_like->where("videoid=?",$v['videoid'])->delete();
				unset($list[$k]);
				continue;
			}
			$list[$k]['id']=$videoinfo['id'];
			$list[$k]['addtime']=date('Y-m-d H:i:s', $v['addtime']);
			$list[$k]['uid']=$videoinfo['uid'];
			$list[$k]['cate']=$this->getCateNameById(explode(',', $videoinfo['cate']));
			$list[$k]['title']=$videoinfo['title'];
			$list[$k]['thumb']=$this->get_upload_path($videoinfo['thumb']);
			$list[$k]['thumb_s']=$this->get_upload_path($videoinfo['thumb_s']);
			$list[$k]['href']=$videoinfo['href'];
			$list[$k]['likes']=$videoinfo['likes'];
			$list[$k]['views']=$videoinfo['views'];
			$list[$k]['comments']=$videoinfo['comments'];
			$list[$k]['shares']=$videoinfo['shares'];
			$list[$k]['city']=$videoinfo['city']?$videoinfo['city']:'';
			$list[$k]['lat']=$videoinfo['lat'];
			$list[$k]['lng']=$videoinfo['lng'];
			$list[$k]['datetime']=$this->datetime($v['addtime']);
			$list[$k]['islike']='1';
			$list[$k]['userinfo']=$this->getUserInfo($videoinfo['uid']);
			$list[$k]['isattent']=(string)$this->isAttention($uid,$videoinfo['uid']);

			$list[$k]['isdel']='0';  //暂时跟getAttentionVideo统一(包含下面的)
			$list[$k]['steps']='0';
			$list[$k]['isstep']='0';
			$list[$k]['isdialect']='0';
			$list[$k]['musicinfo']=$this->getMusicInfo($list[$k]['userinfo']['user_nicename'],$v['music_id']);
			
			$list[$k]['is_ad']=$videoinfo['is_ad'];
			$list[$k]['ad_url']=$videoinfo['ad_url'];
			$list[$k]['price']=$videoinfo['price'];
			$list[$k]['watch_ok']=$videoinfo['watch_ok'];

			unset($list[$k]['videoid']);

			$lista[]=$list[$k];  //因为unset掉某个数组后，k值连不起来了，所以重新赋值给新数组

		}

		if(empty($lista)){
			$lista=array();
		}

		return $lista;
	}


	public function getMyWallet($uid){

		//获取用户的金币和总分红数
		$userInfo=DI()->notorm->users->select("coin,money")->where("id=?",$uid)->fetchOne();
		$res=array();
		$res['coin']=$userInfo['coin'];

		$configPri=$this->getConfigPri();
		/*
		//获取所有用户总的coin值
		$sum=DI()->notorm->users->where("user_status=1 and user_type=2")->sum("coin");
		$bonus=0;
		if($sum>0){
			$bonus= floor($configPri['ad_revenue']/$sum*$userInfo['coin']*100)/100;
		}

		$res['bonus']=$bonus;
		*/
	
		//计算用户金币可提现金额
		$ticket=0;
		if($userInfo['coin']>0){
			$ticket=floor($userInfo['coin']/$configPri['ticket_percent']*100)/100;
		}

		$res['ticket']=$ticket;

		$res['bonus']=$userInfo['money'];

		return $res;
	}

	/*点赞兑换金币*/
	public function exchangeTicket($uid,$likenum){

		//获取用户的信息
		$userinfo=DI()->notorm->users->select("praise")->where("id=?",$uid)->fetchOne();

		if($userinfo['praise']<$likenum){
			return 1001;
		}

		

		$configPri=$this->getConfigPri();

		$ticket=floor($likenum/$configPri['praise_percent']);  //向下取整，算得金币数

		$zanNum=$ticket*$configPri['praise_percent'];

		//扣除用户的赞
		DI()->notorm->users->where("id=?",$uid)->update(array('praise' => new NotORM_Literal("praise - {$zanNum}")) );
		//给用户添加金币
		DI()->notorm->users->where("id=?",$uid)->update(array('coin' => new NotORM_Literal("coin + {$ticket}")) );
		//向兑换记录里写入数据
		
		$data=array(
			'uid'=>$uid,
			'praise'=>$zanNum,
			'coin'=>$ticket,
			'addtime'=>time()

		);

		DI()->notorm->users_praise_changecoin->insert($data);
		return 1;
	}



	/* 充值列表 */
	public function orderLists($uid,$type,$p){

		$pnum=20;
		$start=($p-1)*$pnum;
		$w = "uid={$uid} and coin>0";
		if($type==1){//购买vip订单
			$w = "uid={$uid} and coin=0";
		}
		$lists= array();
		$orderLists=DI()->notorm->users_charge
					->select("*")
					->where($w)
					->limit($start,$pnum)
					->fetchAll();

		$status = array('0' =>'未支付', '1' =>'已支付');
		$paytype = array('1' =>'在线支付:微信', '2' =>'在线支付:支付宝');
		foreach($orderLists as $k=>$v){
				$lists[$k]['id'] = $v['id'];
				$lists[$k]['orderno'] = $v['orderno'];
				$lists[$k]['money'] = $v['money'];
				$lists[$k]['memo'] = $paytype[$v['type']];
				$lists[$k]['status'] = $status[$v['status']];
				$lists[$k]['addtime']= date('Y-m-d H:i:s',$v['addtime']);	
		}		
		
		return $lists;
	}	

	/* 收支列表 */
	public function incomeLists($uid,$type,$p){

		$pnum=20;
		$start=($p-1)*$pnum;
		$w = "uid={$uid}";
		if($type==1){//购买vip订单
			$w = array('uid'=>$uid,'type'=>'+');
		}elseif ($type==2) {
			$w = array('uid'=>$uid,'type'=>'-');
		}
		$lists= array();
		if($this->getcaches($uid.$type.$p)){
			$lists =$this->getcaches($uid.$type.$p);
		}else{
			$orderLists=DI()->notorm->users_coinrecord
					->select("*")
					->where($w)
					->limit($start,$pnum)
					->fetchAll();

			$action = array('buyVideo' =>'购买视频', 'withdraw' =>'提现','chongzhi'=>'金币充值',''=>'作品收入');
			
			foreach($orderLists as $k=>$v){
				$lists[$k]['id'] = $v['id'];
				$lists[$k]['totalcoin'] = $v['totalcoin'];
				$lists[$k]['memo'] = $action[$v['action']];
				
				$lists[$k]['addtime']= date('Y-m-d H:i:s',$v['addtime']);	
			}		
			$this->setcaches($uid.$type.$p,$lists,30);
		}
		
		return $lists;
	}	

	/* 代理赚钱 */
	public function agentIncome($uid){

		$data =[];
		$start_time = strtotime(date("Y-m"),time());
		$end_time = time();
		$prefix = DI()->config->get('dbs.tables.__default__.prefix');
		
		//当月收益
		$shouyi = DI()->notorm->users_agent_profit_recode->queryAll("select sum(one_profit + two_profit + three_profit) as shouyi from {$prefix}users_agent_profit_recode where addtime between {$start_time} and {$end_time} and one_uid ={$uid} or two_uid={$uid} or three_uid={$uid}");
		$data['shouyi'] = $shouyi['shouyi'] ? $shouyi['shouyi'] :'0.00';
		
		

		//当月业绩
		
		$shouyi = DI()->notorm->agent_performance_list->queryAll("select sum(money) as yeji from {$prefix}agent_performance_list where addtime between {$start_time} and {$end_time} and uid ={$uid}");
		$data['yeji'] = $shouyi['yeji'] ? $shouyi['yeji'] :'0.00';
 
		//当月推广人数
		$month_tui = DI()->notorm->users_sharereg->queryAll("select count(*) as num from {$prefix}users_sharereg where addtime between {$start_time} and {$end_time} and uid ={$uid}");
		$data['month_tui'] = $month_tui['num'] ? $month_tui['num'] :'0';

		//一级推广人数  二级推广人数   三级推广人数
		$one = DI()->notorm->users_agent->queryAll("select count(one_uid) as one from {$prefix}users_agent where one_uid ={$uid}");
	
		$two = DI()->notorm->users_agent->queryAll("select count(two_uid) as two from {$prefix}users_agent where two_uid={$uid}");
		$three = DI()->notorm->users_agent->queryAll("select count(three_uid) as three from {$prefix}users_agent where three_uid={$uid}");
		$data['tui']['one'] = $one[0]['one'] ? $one[0]['one']:'0';
	
		$data['tui']['two'] = $two[0]['two'] ? $two[0]['two']:'0';
		$data['tui']['three'] = $three[0]['three'] ? $three[0]['three']:'0';
		$data['all_yeji'] = $this->getUserInfo($uid)['ye_ji'];
		$data['all_shouyi'] = $this->getUserInfo($uid)['votestotal'];
		$data['rule_url'] = $this->get_host().'/index.php?g=portal&m=page&a=index&id=7';

		
		return $data;
	}	

	/* 业绩明细 */
	public function performance_list($uid,$p){

		$pnum=20;
		$start=($p-1)*$pnum;
		$w = "uid={$uid}";
		
		$Lists= array();
		if($this->getcaches($uid.$p)){
			$Lists =$this->getcaches($uid.$p);
		}else{
			$Lists=DI()->notorm->agent_performance_list
					->select("*")
					->where($w)
					->limit($start,$pnum)
					->fetchAll();

			foreach($Lists as $k=>$v){
				
				$Lists[$k]['addtime']= date('Y-m-d',$v['addtime']);	
			}		
			$this->setcaches($uid.$p,$Lists,30);
		}
		
		return $Lists;
	}


	/* 视频浏览历史记录 */
	public function view_list($uid,$p){

		$pnum=20;
		$start=($p-1)*$pnum;
		$w = "uid={$uid}";
		
		$videosId= array();
		$view_list= array();
		$start_time = strtotime(date('Y-m-d',strtotime('-10 days')));
		$end_time =time();
		$prefix = DI()->config->get('dbs.tables.__default__.prefix');
		$today_videosId = DI()->redis->smembers('readvideo_'.$uid.date('Y-m-d',time()));
		$this->delcache('view_list'.$uid);
		if ($this->getcaches('view_list'.$uid)) {
			$Lists=json_decode($this->getcaches('view_list'.$uid));
		}else{
			$Lists=DI()->notorm->users_video_view->queryAll("select videoid  from {$prefix}users_video_view where addtime between {$start_time} and {$end_time} and uid ={$uid}");
			if ($Lists) {
			$this->setcaches('view_list'.$uid,json_encode($Lists),strtotime('23:59:59')-time());
				foreach ($Lists as $key => $value) {
					$videosId[$key] = $value['videoid'];
				}
			}
		}
		
		
		$view_list = array_unique(array_merge($today_videosId,$videosId));

		$video=DI()->notorm->users_video
				->select("*")
				->where('id', $view_list)
				->order("RAND()")
				->limit($start,$pnum)
				->fetchAll();

		foreach($video as $k=>$v){
			
			$userinfo=(array)$this->getUserInfo($v['uid']);
			if(!$userinfo){
				$userinfo['user_nicename']="已删除";
			}


			//是否购买过 购买过则免费	jjj
			$isBuy=$this->ifBuy($uid,$v['id']);

			if($isBuy){
				$video[$k]['price']=0;
			}

			//jjj
			$video[$k]['cate']=$this->getCateNameById(explode(',', $v['cate']));	
			$video[$k]['userinfo']=$userinfo;
			$video[$k]['datetime']=$this->datetime($v['addtime']);	
			$video[$k]['comments']=$this->NumberFormat($v['comments']);	
			$video[$k]['likes']=$this->NumberFormat($v['likes']);	
			$video[$k]['steps']=$this->NumberFormat($v['steps']);	
			if($uid){
				$video[$k]['islike']=(string)$this->ifLike($uid,$v['id']);	
				$video[$k]['isstep']=(string)$this->ifStep($uid,$v['id']);	
				$video[$k]['isattent']=(string)$this->isAttention($uid,$v['uid']);	
			}else{
				$video[$k]['islike']=0;	
				$video[$k]['isstep']=0;	
				$video[$k]['isattent']=0;	
			}

			$video[$k]['musicinfo']=$this->getMusicInfo($video[$k]['userinfo']['user_nicename'],$v['music_id']);

			$video[$k]['thumb']=$this->get_upload_path($v['thumb']);	
			$video[$k]['thumb_s']=$this->get_upload_path($v['thumb_s']);	
			$video[$k]['href']=$this->get_upload_path($v['href']);	
			

		}
		
		return $video;
	}	


		 // private function preMember($pid){
   //      if(!$pid){

   //          return self::$preMember;	
   //      }
        
   //      $pid = dao('users')->where(['uid' => $pid])->field('user_id,order_all_num,parent_id')->find();
      
   //      if($pid){									//判断上级是否存在
   //          $p_id =$pid['parent_id'];
            
			// self::$preMember[] =[$pid['user_id']]; 
          
   //          $this->preMember($p_id);		//继续执行本函数
   //      }else{
   //          return self::$preMember;
   //      }
   //  }

}
