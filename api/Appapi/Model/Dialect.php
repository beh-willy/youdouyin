<?php
/****
**方言秀
***/
class Model_Dialect extends Model_Common {
	/* 获取方言名称列表 */
	public function getDialect(){
		$dialectlist=DI()->notorm->dialect
						->fetchAll();
		if(!$dialectlist){
			return 0;
		}
		return $dialectlist;
	}
	/* 获取方言秀素材列表 */
	public function getDialectmaterial(){
		$dialectlist=DI()->notorm->dialectshow_material
						->fetchAll();
		if(!$dialectlist){
			return 0;
		}
		return $dialectlist;
	}
	/* 发布方言秀视频 */
	public function setDialectshow($data) {
		$uid=$data['uid'];
	/* 	$info=DI()->notorm->users
					->select('live_level,unexchange_like,all_like,urge_moneys')
					->where('id=? and user_type="2"',$uid)
					->fetchOne();	
		//主播等级
		$level_anchor=$this->getLiveLevelAnchor($info['all_like'],$info['urge_moneys'],$info['live_level'],$info['unexchange_like'],$uid);
		//主播等级信息:读取对应主播等级设置的催更次数限制
		$levelinfo=$this->getLevelAnchorinfo($level_anchor); */
		//催更金额
		$configpri=$this->getConfigPri();
	/* 	$data['urge_nums']=$levelinfo['urge_nums'];//剩余催更次数
		$data['big_urgenums']=$levelinfo['urge_nums'];//最大催更次数 */
		$data['urge_money']=$configpri['urge_money'];//催更单价
		$data['is_urge']='1';//是否催更：0：否；1：是
		//当前催更视频信息
		$urgevideoinfo=DI()->notorm->users_dialect
					->select('*')
					->where("is_urge = '1' and uid=".$uid)
					->fetchOne();
		if(!$urgevideoinfo){
			$urgevideoinfo=DI()->notorm->users_video
						->select('*')
						->where("is_urge = '1' and uid=".$uid)
						->fetchOne();
			if($urgevideoinfo){
			//查询该视频最后一次催更时间
			$userurgeinfo=DI()->notorm->users_urge
					->select('*')
					->where("videoid = '{$urgevideoinfo['id']}' and video_type=0")
					->order("addtime desc")
					->limit("1")
					->fetchOne();
			}
		}else{
			//查询该视频最后一次催更时间
			$userurgeinfo=DI()->notorm->users_urge
					->select('*')
					->where("videoid = '{$urgevideoinfo['id']}' and video_type=1")
					->order("addtime desc")
					->limit("1")
					->fetchOne();
		}
		if($urgevideoinfo && $userurgeinfo){
			$todayover=$userurgeinfo['addtime']+60*60*$configpri['urge_money_timeset'];//催更后24小时的时间戳
			//催更款
			$urgemoney=($urgevideoinfo['big_urgenums']-$urgevideoinfo['urge_nums'])*$urgevideoinfo['urge_money'];
			if($urgevideoinfo['urge_nums']==0 && $todayover>time()){//催更人数达到设置的人数：催更后更新视频未超过24小时，用户添加催更款，映票，总映票
				DI()->notorm->users
					->where("id = '{$uid}'")
					->update( array('urge_moneys' => new NotORM_Literal("urge_moneys + ".$urgemoney.""),'votes' => new NotORM_Literal("votes + ".$urgemoney.""),'votestotal' => new NotORM_Literal("votestotal + ".$urgemoney."") ) );
				/* 记录 */
				$insert=array("type"=>'income',"action"=>'urgemoneys',"uid"=>$uid,"touid"=>$uid,"giftid"=>'0',"giftcount"=>'0',"totalcoin"=>$urgemoney,"showid"=>'0',"addtime"=>time() );
				$isup=DI()->notorm->users_coinrecord->insert($insert);	
			}else if($urgevideoinfo['urge_nums']>=1 && $urgevideoinfo['big_urgenums'] > $urgevideoinfo['urge_nums']){//未达到崔更设置人数：发布新视频则催更款全部转给视频发布者
				DI()->notorm->users
					->where("id = '{$uid}'")
					->update( array('urge_moneys' => new NotORM_Literal("urge_moneys + ".$urgemoney.""),'votes' => new NotORM_Literal("votes + ".$urgemoney.""),'votestotal' => new NotORM_Literal("votestotal + ".$urgemoney."") ) );
				/* 记录 */
				$insert=array("type"=>'income',"action"=>'urgemoneys',"uid"=>$uid,"touid"=>$uid,"giftid"=>'0',"giftcount"=>'0',"totalcoin"=>$urgemoney,"showid"=>'0',"addtime"=>time() );
				$isup=DI()->notorm->users_coinrecord->insert($insert);	
			}
		}
		//取消其他视频的催更状态
		DI()->notorm->users_dialect
				->where("is_urge = '1' and uid=".$uid)
				->update( array('is_urge' => '0' ) );
		DI()->notorm->users_video
			->where("is_urge = '1' and uid=".$uid)
			->update( array('is_urge' => '0' ) );
		$result= DI()->notorm->users_dialect->insert($data);
		//给催更过上次视频的人、关注的人发送推送通知
		//参数：用户ID，视频ID，视频类型：0：短视频；1：方言秀；
		$this->jgsend($uid,$urgevideoinfo['id'],'1');
		
		return $result;
	}	

	/* 评论/回复 */
    public function setComment($data) {
    	$videoid=$data['dialectid'];

		/* 更新 视频 */
		DI()->notorm->users_dialect
            ->where("id = '{$videoid}'")
		 	->update( array('comments' => new NotORM_Literal("comments + 1") ) );
		
        DI()->notorm->users_dialect_comments
            ->insert($data);
			
		$videoinfo=DI()->notorm->users_dialect
					->select("comments")
					->where('id=?',$videoid)
					->fetchOne();
				
		$count=DI()->notorm->users_dialect_comments
					->where("commentid='{$data['commentid']}'")
					->count();
		$rs=array(
			'comments'=>$videoinfo['comments'],//评论数量
			'replys'=>$count,//回复评论数量
		);
		//更新评论用户评论过的视频总数
		DI()->notorm->users
				->where("id = '{$data['uid']}'")
				->update( array('send_comments' => new NotORM_Literal("send_comments + 1") ) );
			
		return $rs;	
    }			

	/* 阅读 */
	public function addView($uid,$videoid){
		$view=DI()->notorm->users_video_view
						->select("id")
						->where("uid='{$uid}' and videoid='{$videoid}'")
						->fetchOne();
		if(!$view){
			DI()->notorm->users_video_view
						->insert(array("uid"=>$uid,"videoid"=>$videoid,"addtime"=>time() ));
						
			DI()->notorm->users_video
				->where("id = '{$videoid}'")
				->update( array('view' => new NotORM_Literal("view + 1") ) );
		}
		return 0;
	}
	/* 点赞 */
	public function addLike($uid,$videoid,$type){
		
		$rs=array(
			'islike'=>'0',
			'likes'=>'0',
		);
		$dialect=DI()->notorm->users_dialect
				->select("likes,uid")
				->where("id = '{$videoid}'")
				->fetchOne();
			
		if(!$dialect){
			return 1001;
		}
		if($dialect['uid']==$uid){
			return 1002;//不能给自己点赞
		}
		$like=DI()->notorm->users_dialect_like
						->select("id")
						->where("uid='{$uid}' and dialectid='{$videoid}'")
						->fetchOne();
		if($like){
			DI()->notorm->users_dialect_like
						->where("uid='{$uid}' and dialectid='{$videoid}'")
						->delete();
			
			DI()->notorm->users_dialect
				->where("id = '{$videoid}' and likes>0")
				->update( array('likes' => new NotORM_Literal("likes - 1") ) );
			$rs['islike']='0';
		}else{
			DI()->notorm->users_dialect_like
						->insert(array("uid"=>$uid,"dialectid"=>$videoid,"addtime"=>time() ));
			
			DI()->notorm->users_dialect
				->where("id = '{$videoid}'")
				->update( array('likes' => new NotORM_Literal("likes + 1") ) );
			$rs['islike']='1';
		}	
		
		$dialect=DI()->notorm->users_dialect
				->select("likes,uid")
				->where("id = '{$videoid}'")
				->fetchOne();
		$rs['likes']=$dialect['likes'];
		
		if($type==1){//扣费点赞
			//判断免费点赞次数$today+60*60*24;
			$configpri=$this->getConfigPri();
			/* 更新用户余额 消费 */
			DI()->notorm->users
					->where('id = ?', $uid)
					->update(array('coin' => new NotORM_Literal("coin - {$configpri['likemoney']}"),'consumption' => new NotORM_Literal("consumption + {$configpri['likemoney']}") ) );
			/* 记录 */
			$insert=array("type"=>'expend',"action"=>'addlike',"uid"=>$uid,"touid"=>$dialect['uid'],"giftid"=>'0',"giftcount"=>'0',"totalcoin"=>$configpri['likemoney'],"showid"=>'0',"addtime"=>time() );
			$isup=DI()->notorm->users_coinrecord->insert($insert);
		}
		//更新点赞用户点赞总数
		DI()->notorm->users
				->where("id = '{$uid}'")
				->update( array('send_likes' => new NotORM_Literal("send_likes + 1") ) );
		//更新视频发布者的剩余点赞数量及总点赞数量
		DI()->notorm->users
				->where("id = '{$dialect['uid']}'")
				->update( array('unexchange_like' => new NotORM_Literal("unexchange_like + 1"),'all_like' => new NotORM_Literal("all_like + 1") ) );
		
		return $rs; 		
	}
	public  function likeIspay($uid){
		//判断免费点赞次数$today+60*60*24;
		$configpri=$this->getConfigPri();
		
		$todayzero=strtotime(date("Y-m-d"),time());
		$todayover=$todayzero+60*60*24;
	 
		$likenumsdialect=DI()->notorm->users_dialect_like
						->where("addtime between  ".$todayzero." and ".$todayover." and uid=".$uid)
						->count();
						
		$likenumsvideo=DI()->notorm->users_video_like
						->where(" addtime between  ".$todayzero." and ".$todayover." and uid=".$uid)
						->count();
		$likesnums=$likenumsdialect+$likenumsvideo;
			
		if($likesnums>$configpri['freelikenums']){
			return 1;//付费
		}
		return 0;//免费
	}

	/* 踩 */
	public function addStep($uid,$videoid){
		$rs=array(
			'isstep'=>'0',
			'steps'=>'0',
		);
		$like=DI()->notorm->users_dialect_step
						->select("id")
						->where("uid='{$uid}' and dialectid='{$videoid}'")
						->fetchOne();
		if($like){
			DI()->notorm->users_dialect_step
						->where("uid='{$uid}' and dialectid='{$videoid}'")
						->delete();
			
			DI()->notorm->users_dialect
				->where("id = '{$videoid}' and steps>0")
				->update( array('steps' => new NotORM_Literal("steps - 1") ) );
			$rs['isstep']='0';
		}else{
			DI()->notorm->users_dialect_step
						->insert(array("uid"=>$uid,"dialectid"=>$videoid,"addtime"=>time() ));
			
			DI()->notorm->users_dialect
				->where("id = '{$videoid}'")
				->update( array('steps' => new NotORM_Literal("steps + 1") ) );
			$rs['isstep']='1';
		}	
		
		$video=DI()->notorm->users_dialect
				->select("steps")
				->where("id = '{$videoid}'")
				->fetchOne();
		$rs['steps']=$video['steps'];
		return $rs; 		
	}

		/* 评论/回复 点赞 */
	public function addCommentLike($uid,$commentid){
		$rs=array(
			'islike'=>'0',
			'likes'=>'0',
		);
		$like=DI()->notorm->users_dialect_comments_like
						->select("id")
						->where("uid='{$uid}' and commentid='{$commentid}'")
						->fetchOne();
		if($like){
			DI()->notorm->users_dialect_comments_like
						->where("uid='{$uid}' and commentid='{$commentid}'")
						->delete();
			
			DI()->notorm->users_dialect_comments
				->where("id = '{$commentid}' and likes>0")
				->update( array('likes' => new NotORM_Literal("likes - 1") ) );
			$rs['islike']='0';
		}else{
			DI()->notorm->users_dialect_comments_like
						->insert(array("uid"=>$uid,"commentid"=>$commentid,"addtime"=>time() ));
			
			DI()->notorm->users_dialect_comments
				->where("id = '{$commentid}'")
				->update( array('likes' => new NotORM_Literal("likes + 1") ) );
			$rs['islike']='1';
		}	
		
		$video=DI()->notorm->users_dialect_comments
				->select("likes")
				->where("id = '{$commentid}'")
				->fetchOne();
		$rs['likes']=$video['likes'];
		return $rs; 		
	}
	
	
	/* 分享 */
	public function addShare($uid,$videoid){
		$rs=array(
			'isshare'=>'0',
			'shares'=>'0',
		);
		DI()->notorm->users_dialect
			->where("id = '{$videoid}'")
			->update( array('shares' => new NotORM_Literal("shares + 1") ) );
		$rs['isshare']='1';

		
		$video=DI()->notorm->users_dialect
				->select("shares")
				->where("id = '{$videoid}'")
				->fetchOne();
		$rs['shares']=$video['shares'];
		//更新分享用户分享过的视频总数
		if($uid){
			DI()->notorm->users
				->where("id = '{$uid}'")
				->update( array('send_shares' => new NotORM_Literal("send_shares + 1") ) );
		}
		return $rs; 		
	}

	/* 拉黑视频 */
	public function setBlack($uid,$videoid){
		$rs=array(
			'isblack'=>'0',
		);
		$like=DI()->notorm->users_dialect_black
						->select("id")
						->where("uid='{$uid}' and dialectid='{$videoid}'")
						->fetchOne();
		if($like){
			DI()->notorm->users_dialect_black
						->where("uid='{$uid}' and dialectid='{$videoid}'")
						->delete();
			$rs['isblack']='0';
		}else{
			DI()->notorm->users_dialect_black
						->insert(array("uid"=>$uid,"dialectid"=>$videoid,"addtime"=>time() ));
			$rs['isblack']='1';
		}	
		return $rs; 		
	}
	
	/* 方言秀视频 */
	public function getDialectList($uid,$dialecttype,$type,$p){
		$nums=20;
		$start=($p-1)*$nums;
		
		if($uid){
			$videoids_s=$this->getDialectBlack($uid);
		}else{
			$videoids_s='';
		}
		
		
		$where=' isdel=0 ';
		if($type==1){//我的催更视频ID
			$urgevideoids= $this->getUrgeIds($uid,1);
			if(!$urgevideoids){
				return 10010;
			}
			$where .="and id in(".$urgevideoids.")  ";
		}else{//全部方言秀列表
			if($dialecttype!==0){
				$where .="and id not in ({$videoids_s}) and dialect_type={$dialecttype}";
			}else{
				$where .="and id not in ({$videoids_s}) ";
			}
		}
	
		/* $video=DI()->notorm->users_dialect
				->select("*")
				->where($where)
				->order("addtime desc")
				->limit($start,$nums)
				->fetchAll(); */
		$numsp=DI()->redis -> hVals('userdialectp_'.$uid);//上次浏览页数缓存
		if($p==1){
			for($i=1;$i<=$numsp[0];$i++){
				DI()->redis -> hDel('userdialects_'.$uid,$i);
			}
		}else{
			$list=DI()->redis -> hVals('userdialects_'.$uid);
			/* $keylist=DI()->redis -> hKeys('uservideos_'.$uid); */
			$numsr=DI()->redis -> hLen('userdialects_'.$uid); 
			$exitids="";
			foreach($list as $v){
				$lists[]=json_decode($v,true);
				$n++;
			}
			for($ii=0;$ii<=$numsr;$ii++){
				foreach($lists[$ii] as $kk=>$vv){
						if($exitids==""){
							$exitids=$vv['id'];
						}else{
							$exitids=$exitids.",".$vv['id'];
						}
				}
			}
		}
		if($exitids){
			$where .=" and id not in(".$exitids.") ";
		}
		$video=DI()->notorm->users_dialect
				->select("*")
				->where($where)
				/* ->order("addtime desc") */
			    ->order("RAND()")
				->limit(20)
				->fetchAll();
				
		DI()->redis -> hSet('userdialectp_'.$uid,"p",$p);//分页最后页数
		DI()->redis -> hSet('userdialects_'.$uid,$p,json_encode($video));//所有浏览过数据记录
	
			
				
				
				
				
				
				
				
				
				
		foreach($video as $k=>$v){
			$video[$k]['isdialect']='1';
			$video[$k]['datetime']=$this->datetime($v['addtime']);	
			$video[$k]['comments']=$this->NumberFormat($v['comments']);	
			$video[$k]['likes']=$this->NumberFormat($v['likes']);	
			$video[$k]['steps']=$this->NumberFormat($v['steps']);	
			if($uid){
				$video[$k]['islike']=(string)$this->ifLike($uid,$v['id'],'1');	
				$video[$k]['isstep']=(string)$this->ifStep($uid,$v['id'],'1');	
				$video[$k]['isattent']=(string)$this->isAttention($uid,$v['uid']);	
			}else{
				$video[$k]['islike']=0;	
				$video[$k]['isstep']=0;	
				$video[$k]['isattent']=0;	
			}
			
			
			//方言名称
			$dialectname=DI()->notorm->dialect->select("name")->where("type={$v['dialect_type']}")->fetchOne();
			$video[$k]['dialectname']=$dialectname['name'];
			$userinfo=(array)$this->getUserInfo($v['uid']);
			if(!$userinfo){
				$userinfo['user_nicename']="已删除";
			}
			$video[$k]['userinfo']=$userinfo;
			//神评
			/* $commentsinfo=DI()->notorm->users_dialect_comments
					->select("*")
					->order("likes desc")
					->limit(2)
					->fetchAll();
			$isexitlike=0;
			foreach($commentsinfo as $kc=>$vc){
				$commentuserinfo=$this->getUserInfo($vc['uid']);
				$commentsinfo[$kc]['user_nicename']=$commentuserinfo['user_nicename'];
				$commentsinfo[$kc]['avatar']=$commentuserinfo['avatar'];
				$commentsinfo[$kc]['avatar_thumb']=$commentuserinfo['avatar_thumb'];
				if($vc['likes']==0){
					$isexitlike++;
				}
			}
			if($isexitlike==2){//若两个以上神评都不存在点赞，则获取评论时间最新的评论
				$commentsinfo=DI()->notorm->users_dialect_comments
					->select("*")
					->order("addtime desc")
					->limit(2)
					->fetchAll();
					foreach($commentsinfo as $kc=>$vc){
						$commentuserinfo=$this->getUserInfo($vc['uid']);
						$commentsinfo[$kc]['user_nicename']=$commentuserinfo['user_nicename'];
						$commentsinfo[$kc]['avatar']=$commentuserinfo['avatar'];
						$commentsinfo[$kc]['avatar_thumb']=$commentuserinfo['avatar_thumb'];
					}
			} */
			$commentsinfo=$this->getShenping(1,$v['id']);
			if(!$commentsinfo){
				$video[$k]['commentsinfo']=array();
			}else{
				$video[$k]['commentsinfo']=$commentsinfo;
			}
			
		}		
		
		return $video;
	} 	
	
	/* 关注人视频 */
	public function getAttentionVideo($uid,$p){
		$nums=20;
		$start=($p-1)*$nums;
		
		$video=array();
		$attention=DI()->notorm->users_attention
				->select("touid")
				->where("uid='{$uid}'")
				->fetchAll();
		
		if($attention){
			
			$uids=$this->array_column2($attention,'touid');
			$touids=implode(",",$uids);
			
			$videoids_s=$this->getVideoBlack($uid);
			$where="uid in ({$touids}) and id not in ({$videoids_s})  and isdel=0";
			
			$video=DI()->notorm->users_video
					->select("*")
					->where($where)
					->order("addtime desc")
					->limit($start,$nums)
					->fetchAll();
			foreach($video as $k=>$v){
				$video[$k]['userinfo']=$this->getUserInfo($v['uid']);;
				$video[$k]['datetime']=$this->datetime($v['addtime']);	
				$video[$k]['comments']=$this->NumberFormat($v['comments']);	
				$video[$k]['likes']=$this->NumberFormat($v['likes']);	
				$video[$k]['steps']=$this->NumberFormat($v['steps']);	
				$video[$k]['islike']=(string)$this->ifLike($uid,$v['id'],'1');	
				$video[$k]['isstep']=(string)$this->ifStep($uid,$v['id'],'1');	
			}					
			
		}
		
		
		return $video;		
	} 			
	
	/* 视频详情 */
	public function getVideo($uid,$videoid){
		$video=DI()->notorm->users_dialect
					->select("*")
					->where("id = {$videoid}")
					->fetchOne();
		if(!$video){
			return 1000;
		}		
		if($uid){
			$userinfo=DI()->notorm->users
					->select("all_looktimes")
					->where("id = {$uid}")
					->fetchOne();
			$video['all_looktimes']=$userinfo['all_looktimes'];
		}else{
			$video['all_looktimes']=0;
		}		
		$video['userinfo']=$this->getUserInfo($video['uid']);	
		$video['isattent']=(string)$this->isAttention($uid,$video['uid']);	
		$video['datetime']=$this->datetime($video['addtime']);	
		$video['comments']=$this->NumberFormat($video['comments']);	
		$video['likes']=$this->NumberFormat($video['likes']);	
		$video['steps']=$this->NumberFormat($video['steps']);	
		$video['shares']=$this->NumberFormat($video['shares']);	
		$video['islike']=(string)$this->ifLike($uid,$videoid,'1');			
		$video['isstep']=(string)$this->ifStep($uid,$videoid,'1');			
		$video['isdialect']="1";			
		
		return 	$video;
	}
	
	/* 评论列表 */
	public function getComments($uid,$videoid,$p){
		$nums=20;
		$start=($p-1)*$nums;
		$comments=DI()->notorm->users_dialect_comments
					->select("*")
					->where("dialectid='{$videoid}' and parentid='0'")
					->order("addtime desc")
					->limit($start,$nums)
					->fetchAll();
		foreach($comments as $k=>$v){
			$userinfo=(array)$this->getUserInfo($v['uid']);		
			if(!$userinfo){
				$userinfo['user_nicename']="用户不存在";
			}
			$comments[$k]['userinfo']	=$userinfo;	
			$comments[$k]['datetime']=$this->datetime($v['addtime']);	
			$comments[$k]['likes']=$this->NumberFormat($v['likes']);	
			if($uid){
				$comments[$k]['islike']=(string)$this->ifCommentLike($uid,$v['id']);	
			}else{
				$comments[$k]['islike']='0';	
			}
			
			if($v['touid']>0){
				$touserinfo=(array)$this->getUserInfo($v['touid']);
			}
			if(!$touserinfo){
				$touserinfo=(object)array();
				$comments[$k]['touid']='0';
			}
			$comments[$k]['touserinfo']=$touserinfo;

			$count=DI()->notorm->users_dialect_comments
					->where("commentid='{$v['id']}'")
					->count();
			$comments[$k]['replys']=$count;
		}
		
		$commentnum=DI()->notorm->users_dialect_comments
					->where("dialectid='{$videoid}'")
					->count();
		
		$rs=array(
			"comments"=>$commentnum,
			"commentlist"=>$comments,
		);
		
		return $rs;
	}

	/* 回复列表 */
	public function getReplys($uid,$commentid,$p){
		$nums=20;
		$start=($p-1)*$nums;
		$comments=DI()->notorm->users_dialect_comments
					->select("*")
					->where("commentid='{$commentid}'")
					->order("addtime desc")
					->limit($start,$nums)
					->fetchAll();
		foreach($comments as $k=>$v){
			$comments[$k]['userinfo']=$this->getUserInfo($v['uid']);				
			$comments[$k]['datetime']=$this->datetime($v['addtime']);	
			$comments[$k]['likes']=$this->NumberFormat($v['likes']);	
			$comments[$k]['islike']=(string)$this->ifCommentLike($uid,$v['id']);
			if($v['touid']>0){
				$touserinfo=(array)$this->getUserInfo($v['touid']);
			}
			if(!$touserinfo){
				$touserinfo=(object)array();
				$comments[$k]['touid']='0';
			}
			

			if($v['parentid']>0 && $v['parentid']!=$commentid){
				$tocommentinfo=DI()->notorm->users_dialect_comments
						->select("content")
						->where("id='{$v['parentid']}'")
						->fetchOne();
			}else{
				$tocommentinfo=(object)array();
				$touserinfo=(object)array();
				$comments[$k]['touid']='0';
			}
			$comments[$k]['touserinfo']=$touserinfo;
			$comments[$k]['tocommentinfo']=$tocommentinfo;
		}
		
		return $comments;
	}
	
	/* 是否点赞 */
	public function ifLike($uid,$videoid,$type){
		if($type==1){
			$like=DI()->notorm->users_dialect_like
				->select("id")
				->where("uid='{$uid}' and dialectid='{$videoid}'")
				->fetchOne();
		}else{
			$like=DI()->notorm->users_video_like
				->select("id")
				->where("uid='{$uid}' and videoid='{$videoid}'")
				->fetchOne();
		}
		
		if($like){
			return 1;
		}else{
			return 0;
		}	
	}

	/* 是否踩 */
	public function ifStep($uid,$videoid,$type){
		if($type==1){
			$like=DI()->notorm->users_dialect_step
				->select("id")
				->where("uid='{$uid}' and dialectid='{$videoid}'")
				->fetchOne();
		}else{
			$like=DI()->notorm->users_video_step
					->select("id")
					->where("uid='{$uid}' and videoid='{$videoid}'")
					->fetchOne();
		}
		if($like){
			return 1;
		}else{
			return 0;
		}	
	}
	
	/* 评论/回复 是否点赞 */
	public function ifCommentLike($uid,$commentid){
		$like=DI()->notorm->users_video_comments_like
				->select("id")
				->where("uid='{$uid}' and commentid='{$commentid}'")
				->fetchOne();
		if($like){
			return 1;
		}else{
			return 0;
		}	
	}
	
	/* 我的视频 */
	public function getMyVideo($uid,$p){
		$nums=20;
		$start=($p-1)*$nums;
		
		$video=DI()->notorm->users_video
				->select("*")
				->where('uid=?  and isdel=0',$uid)
				->order("addtime desc")
				->limit($start,$nums)
				->fetchAll();
		foreach($video as $k=>$v){
			$video[$k]['userinfo']=$this->getUserInfo($v['uid']);
			$video[$k]['datetime']=$this->datetime($v['addtime']);
			$video[$k]['comments']=$this->NumberFormat($v['comments']);	
			$video[$k]['likes']=$this->NumberFormat($v['likes']);	
			$video[$k]['steps']=$this->NumberFormat($v['steps']);
			$video[$k]['islike']='0';	
			$video[$k]['isattent']='0';	
		}		
		
		return $video;
	} 	
	/* 删除方言秀视频 */
	public function del($uid,$videoid){
		
		$result=DI()->notorm->users_dialect
					->select("*")
					->where("id='{$videoid}' and uid='{$uid}'")
					->fetchOne();	
		if($result){
			$comments=DI()->notorm->users_dialect_comments
					->select("*")
					->where("dialectid='{$videoid}'")
					->fetchAll();	
			foreach($comments as $k=>$v){
				/* 删除 评论的点赞记录 */
				DI()->notorm->users_dialect_comments_like
							->where("commentid='{$v['id']}'")
							->delete();
			}
					
			/* 删除 评论记录 */
			DI()->notorm->users_dialect_comments
						->where("dialectid='{$videoid}'")
						->delete();
			/* 删除  点赞*/
			DI()->notorm->users_dialect_like
						->where("dialectid='{$videoid}'")
						->delete();
			/* 删除视频 */
			DI()->notorm->users_dialect
						->where("id='{$videoid}'")
						->delete();
			/* DI()->notorm->users_dialect
						->where("id='{$videoid}'")
						->update( array( 'isdel'=>1 ) );	 */			
		}				
		return 0;
	}	
	
	/* 举报 */
	public function report($data,$type) {
		if($type==1){
			$dialect=DI()->notorm->users_dialect
					->select("uid")
					->where("id='{$data['dialectid']}'")
					->fetchOne();
			if(!$dialect){
				return 1000;
			}
			
			$data['touid']=$dialect['uid'];
			$result= DI()->notorm->users_dialectreport->insert($data);
		}else{
			$video=DI()->notorm->users_video
					->select("uid")
					->where("id='{$data['videoid']}'")
					->fetchOne();
			if(!$video){
				return 1000;
			}
			
			$data['touid']=$video['uid'];
			$result= DI()->notorm->users_video_report->insert($data);
		}
		if($result!==false){
			return 1;
		}else{
			return 1001;
		}
		
	}



	/* 举报内容显示列表 */
	public function getReportContentlist() {
		
		$reportlist=DI()->notorm->dialect_report
					->select("*")
					->fetchAll();
		if(!$reportlist){
			return 0;
		}
		
		return $reportlist;
		
	}	
	/* 个人主页视频 */
	public function getHomeVideo($uid,$touid,$p){
		$nums=20;
		$start=($p-1)*$nums;
		
		$videoids_s=$this->getVideoBlack($uid);
		$where="id not in ({$videoids_s}) and uid={$touid} and isdel='0'";
		
		$video=DI()->notorm->users_video
				->select("*")
				->where($where)
				->order("addtime desc")
				->limit($start,$nums)
				->fetchAll();
		foreach($video as $k=>$v){
			$video[$k]['userinfo']=$this->getUserInfo($v['uid']);
			$video[$k]['datetime']=$this->datetime($v['addtime']);	
			$video[$k]['comments']=$this->NumberFormat($v['comments']);	
			$video[$k]['likes']=$this->NumberFormat($v['likes']);	
			$video[$k]['steps']=$this->NumberFormat($v['steps']);	
			$video[$k]['islike']=(string)$this->ifLike($uid,$v['id'],'1');	
			$video[$k]['isstep']=(string)$this->ifStep($uid,$v['id'],'1');	
			$video[$k]['isattent']=(string)$this->isAttention($uid,$v['uid']);	
		}		
		
		return $video;
		
	}

	/* 获取入围奥思卡奖项的视频 */
	public function getOscarVideo($uid,$p){
		$nums=20;
		$start=($p-1)*$nums;
		if($uid){
			$videoids_s=$this->getDialectBlack($uid);
		}else{
			$videoids_s=array();
		}
		
		$where="id not in ({$videoids_s}) and isdel='0'";
		
		$oscarvideo=DI()->notorm->oscar_video
				->select("*")
				/* ->where($where) */
				/* ->limit($start,$nums) */
				->fetchAll();
		
		foreach($oscarvideo as $k=>$v){
			
			//奖项信息
			$oscarinfo=DI()->notorm->oscar->where("id=".$v['oscarid'])->fetchOne();
			
			$oscarvideo[$k]['oscarname']=$oscarinfo['name'];
			$oscarvideo[$k]['orderno']=$oscarinfo['orderno'];//奖项排序
			
			//视频信息
			if($v['type']==1){//方言秀视频
				$videoinfo=DI()->notorm->users_dialect->where("id=".$v['videoid'])->where($where)->fetchOne();
				
			}else{
				$videoinfo=DI()->notorm->users_video->where("id=".$v['videoid'])->where($where)->fetchOne();
				
			}
			
			if($videoinfo){
				$oscarvideo[$k]['videoinfo']=$videoinfo;
				//用户信息
				$userinfo=(array)$this->getUserInfo($videoinfo['uid']);
				if($userinfo){
					$oscarvideo[$k]['userinfo']=$userinfo;
				}else{
					$oscarvideo[$k]['userinfo']="0";
				}
				$oscarvideo[$k]['datetime']=$this->datetime($videoinfo['addtime']);	
				$oscarvideo[$k]['comments']=$this->NumberFormat($videoinfo['comments']);	
				$oscarvideo[$k]['likes']=$this->NumberFormat($videoinfo['likes']);	
				$oscarvideo[$k]['steps']=$this->NumberFormat($videoinfo['steps']);	
				$oscarvideo[$k]['islike']=(string)$this->ifLike($uid,$videoinfo['id'],'1');	
				$oscarvideo[$k]['isstep']=(string)$this->ifStep($uid,$videoinfo['id'],'1');	
				$oscarvideo[$k]['isattent']=(string)$this->isAttention($uid,$videoinfo['uid']);
				$commentinfo=$this->getShenping($v['type'],$videoinfo['id']);
				
				if(!$commentinfo){
					$oscarvideo[$k]['commentinfo']=array();
				}else{
					$oscarvideo[$k]['commentinfo']=$commentinfo;
				}
				
			}else{
				unset($oscarvideo[$k]);
			}
		}		
		$oscarvideo=array_values($oscarvideo);
			
		$arr1 = array_map(create_function('$n', 'return $n["orderno"];'), $oscarvideo);
	
		array_multisort($arr1,SORT_ASC,$oscarvideo);//多维数组的排序
		return $oscarvideo;
		
	}
	public function getShenping($type,$videoid){
		
		//神评
		if($type==1){//方言秀神评
			$commentsinfo=DI()->notorm->users_dialect_comments
				->select("*")
				->where("dialectid=".$videoid)
				->order("likes desc")
				->limit(2)
				->fetchAll();
			$isexitlike=0;
			foreach($commentsinfo as $kc=>$vc){
				$commentsinfo[$kc]['videoid']=$vc['dialectid'];
				$commentuserinfo=$this->getUserInfo($vc['uid']);
				$commentsinfo[$kc]['user_nicename']=$commentuserinfo['user_nicename'];
				$commentsinfo[$kc]['avatar']=$commentuserinfo['avatar'];
				$commentsinfo[$kc]['avatar_thumb']=$commentuserinfo['avatar_thumb'];
				
				if($vc['likes']==0){
					$isexitlike++;
				}
			}
			if($isexitlike==2){//若两个以上神评都不存在点赞，则获取评论时间最新的评论
				$commentsinfo=DI()->notorm->users_dialect_comments
					->select("*")
					->where("dialectid=".$videoid)
					->order("addtime desc")
					->limit(2)
					->fetchAll();
					foreach($commentsinfo as $kc=>$vc){
						$commentsinfo[$kc]['videoid']=$vc['dialectid'];
						$commentuserinfo=$this->getUserInfo($vc['uid']);
						$commentsinfo[$kc]['user_nicename']=$commentuserinfo['user_nicename'];
						$commentsinfo[$kc]['avatar']=$commentuserinfo['avatar'];
						$commentsinfo[$kc]['avatar_thumb']=$commentuserinfo['avatar_thumb'];
					}
			}
		}else{//短视频神评
			$commentsinfo=DI()->notorm->users_video_comments
				->select("*")
				->where("videoid=".$videoid)
				->order("likes desc")
				->limit(2)
				->fetchAll();
			$isexitlike=0;
			foreach($commentsinfo as $kc=>$vc){
				$commentuserinfo=$this->getUserInfo($vc['uid']);
				$commentsinfo[$kc]['user_nicename']=$commentuserinfo['user_nicename'];
				$commentsinfo[$kc]['avatar']=$commentuserinfo['avatar'];
				$commentsinfo[$kc]['avatar_thumb']=$commentuserinfo['avatar_thumb'];
				if($vc['likes']==0){
					$isexitlike++;
				}
			}
			if($isexitlike==2){//若两个以上神评都不存在点赞，则获取评论时间最新的评论
				$commentsinfo=DI()->notorm->users_video_comments
					->select("*")
					->where("videoid=".$videoid)
					->order("addtime desc")
					->limit(2)
					->fetchAll();
					foreach($commentsinfo as $kc=>$vc){
						$commentuserinfo=$this->getUserInfo($vc['uid']);
						$commentsinfo[$kc]['user_nicename']=$commentuserinfo['user_nicename'];
						$commentsinfo[$kc]['avatar']=$commentuserinfo['avatar'];
						$commentsinfo[$kc]['avatar_thumb']=$commentuserinfo['avatar_thumb'];
					}
			}
		}
		
		return $commentsinfo;
			
	}
	
	/* 拉黑视频名单 */
	public function getDialectBlack($uid){
		$videoids=array('0');
		$list=DI()->notorm->users_dialect_black
						->select("dialectid")
						->where("uid='{$uid}'")
						->fetchAll();
		if($list){
			$videoids=$this->array_column2($list,'dialectid');
		}
		
		$videoids_s=implode(",",$videoids);
		
		return $videoids_s;
	}
		
	/* 催更视频 */
	public function urgeVideo($uid,$type,$videoid){
		//视频信息
		if($type==1){//方言秀视频
			$videoinfo=DI()->notorm->users_dialect->where("id=".$videoid)->fetchOne();
		}else{//短视频
			$videoinfo=DI()->notorm->users_video->where("id=".$videoid)->fetchOne();
		}
		
		if($videoinfo['urge_nums']<=0){
			return 1002;//催更次数已达上限
		}
		$userinfo=(array)$this->getUserInfo($uid);
		//判断用户余额
		if($userinfo['coin']<$videoinfo['urge_money']){
			return 1001;//余额不足
		}
		//更新用户钻石余额
		DI()->notorm->users
				->where("id = '{$uid}'")
				->update( array('coin' => new NotORM_Literal("coin - ".$videoinfo['urge_money'].""),'consumption' => new NotORM_Literal("consumption + {$videoinfo['urge_money']}") ) );
		/* 记录 */
		$insert=array("type"=>'expend',"action"=>'urgevideo',"uid"=>$uid,"touid"=>$videoinfo['uid'],"giftid"=>'0',"giftcount"=>'0',"totalcoin"=>$videoinfo['urge_money'],"showid"=>'0',"addtime"=>time() );
		$isup=DI()->notorm->users_coinrecord->insert($insert);		
		//添加催更记录
		$data=array(
			"uid"=>$uid,
			"videoid"=>$videoid,
			"video_type"=>$type,
			"addtime"=>time(),
		);
		$result= DI()->notorm->users_urge->insert($data);
		if($result){
			//更新视频催更次数
			if($type==1){//方言秀视频
				$up=DI()->notorm->users_dialect
						->where("id = '{$videoid}'")
						->update( array('urge_nums' => new NotORM_Literal("urge_nums - 1")) );
			}else{//短视频
				$up=DI()->notorm->users_video
						->where("id = '{$videoid}'")
						->update( array('urge_nums' => new NotORM_Literal("urge_nums - 1")) );
			}
			if($up){
				$data=array(
					"shengyunums"=>$videoinfo['urge_nums']-1,
					"big_urgenums"=>$videoinfo['big_urgenums'],
					"urge_money"=>$videoinfo['urge_money']
				);
				//剩余催更次数为0时给视频发布者推送催更通知：
				if($data['shengyunums']==0){
					/* 极光推送 */
					$configpri=$this->getConfigPri();
					$app_key = $configpri['jpush_key'];
					$master_secret = $configpri['jpush_secret'];
					$userinfo=$this->getUserInfo($videoinfo['uid']);
					
					if($app_key && $master_secret){
						require './JPush/autoload.php';

						// 初始化
						$client = new \JPush\Client($app_key, $master_secret,null);
						
						$anthorinfo=array(
							"uid"=>$userinfo['uid'],
							"avatar"=>$userinfo['avatar'],
							"avatar_thumb"=>$userinfo['avatar_thumb'],
							"user_nicename"=>$userinfo['user_nicename'],
							"title"=>$userinfo['title'],
							"city"=>$userinfo['city'],
							"stream"=>'',
							"pull"=>'',
							"thumb"=>$userinfo['thumb'],
						);
						$apns_production=false;
						if($configpri['jpush_sandbox']){
							$apns_production=true;
						}
						$alias=array();
						
						$alias[]=$videoinfo['uid'].'PUSH';								 
						 
						try{

							$result = $client->push()
									->setPlatform('all')
									->addAlias($alias)
									->setNotificationAlert('您的视频已被催更完成，快来发布新的视频吧')
									->iosNotification('您的视频已被催更完成，快来发布新的视频吧', array(
										'sound' => 'sound.caf',
										'category' => 'jiguang',
										'extras' => array(
											'userinfo' => $anthorinfo
										),
									))
									->androidNotification('您的视频已被催更完成，快来发布新的视频吧', array(
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
		/* 极光推送 */
				}
				return $data;
			}else{
				return 1000;
			}
		}else{
			return 1000;
		}	
	}
	/* 视频催更进度 */
	public function getVideoUrge($uid,$type,$videoid){
		//视频信息
		if($type==1){//方言秀视频
			$videoinfo=DI()->notorm->users_dialect->where("id=".$videoid)->fetchOne();
		}else{//短视频
			$videoinfo=DI()->notorm->users_video->where("id=".$videoid)->fetchOne();
		}
		$data=array(
			"shengyunums"=>$videoinfo['urge_nums'],
			"big_urgenums"=>$videoinfo['big_urgenums'],
			"urge_money"=>$videoinfo['urge_money']
		);
		return $data;

	}
	/* 我的催更视频列表 */
	public function getMyurgevideoList($uid,$p){
		$nums=20;
		$start=($p-1)*$nums;
		
		$urgelist=DI()->notorm->users_urge->where("uid=".$uid)->order("addtime desc")->fetchAll();
		$dialectids='';
		$videoids='';
		foreach($urgelist as $k=>$v){
			if($v['video_type']==1){//方言秀视频
				if($dialectids==''){
					$dialectids=$v['videoid'];
				}else{
					$dialectids=$dialectids.','.$v['videoid'];
				}
			}else{
				if($videoids==''){
					$videoids=$v['videoid'];
				}else{
					$videoids=$videoids.','.$v['videoid'];
				}
			}
		}
		$count=0;
		if($videoids){
			$video=DI()->notorm->users_video->where("id in(".$videoids.")")->order("addtime desc")->limit($start,$nums)->fetchAll();
		
			foreach($video as $k=>$v){
				$video[$k]['userinfo']=$this->getUserInfo($v['uid']);
	/* 			//神评
				$commentsinfo=$this->getShenping($v['video_type'],$videoinfo['id']);
				if(!$commentsinfo){
					$videoinfo['commentsinfo']=array();
				}else{
					$videoinfo['commentsinfo']=$commentsinfo;
				} */
				$video[$k]['datetime']=$this->datetime($v['addtime']);	
				$video[$k]['comments']=$this->NumberFormat($v['comments']);	
				$video[$k]['likes']=$this->NumberFormat($v['likes']);	
				$video[$k]['steps']=$this->NumberFormat($v['steps']);	
				$video[$k]['islike']=(string)$this->ifLike($uid,$v['id'],0);	
				$video[$k]['isstep']=(string)$this->ifStep($uid,$v['id'],0);	
				$video[$k]['isdialect']='0';	
				$count++;
			}
		}else{
			$video=array();
		}
		$dialect=DI()->notorm->users_dialect->where("id in(".$dialectids.")")->order("addtime desc")->limit($start,$nums)->fetchAll();
		
		foreach($dialect as $kd=>$vd){
			$video[$count]['dialect_material_id']=$vd['dialect_material_id'];//素材视频ID
			
			$video[$count]['dialect_type']=$vd['dialect_type'];//方言类型
			//方言名称
			$dialectname=DI()->notorm->dialect->select("name")->where("type={$vd['dialect_type']}")->fetchOne();
			$video[$count]['dialectname']=$dialectname['name'];
			$video[$count]['uid']=$vd['uid'];
			$video[$count]['title']=$vd['title'];
			$video[$count]['thumb']=$vd['thumb'];
			$video[$count]['thumb_s']=$vd['thumb_s'];
			$video[$count]['href']=$vd['href'];
			$video[$count]['lat']=$vd['lat'];
			$video[$count]['lng']=$vd['lng'];
			$video[$count]['city']=$vd['city'];
			$video[$count]['isdel']=$vd['isdel'];
			$video[$count]['ishow']=$vd['ishow'];//是否显示：0：否；1：显示；2：拒绝显示
			$video[$count]['reason']=$vd['reason'];
			$video[$count]['addtime']=$vd['addtime'];
			
			$video[$count]['userinfo']=$this->getUserInfo($vd['uid']);
			$video[$count]['datetime']=$this->datetime($vd['addtime']);
			$video[$count]['comments']=$this->NumberFormat($vd['comments']);	
			$video[$count]['likes']=$this->NumberFormat($vd['likes']);	
			$video[$count]['steps']=$this->NumberFormat($vd['steps']);
			$video[$count]['shares']=$this->NumberFormat($vd['shares']);
			$video[$count]['islike']=(string)$this->ifLike($uid,$v['id'],1);	
			$video[$count]['isstep']=(string)$this->ifStep($uid,$v['id'],1);
			$video[$count]['isdialect']='1';	//方言秀视频：0：否；1：是
			$count++;
		}
			
		$arr1 = array_map(create_function('$n', 'return $n["addtime"];'), $video);
		
		array_multisort($arr1,SORT_DESC,$video);//多维数组的排序	
		return $video;

	}
	/* 累计视频观看时长 */
	public function addlooktimes($uid,$looktimes) {
		
		$rs=DI()->notorm->users
					->select("*")
					->where("id=".$uid)
					->update( array('all_looktimes' => new NotORM_Literal("all_looktimes + ".$looktimes.""),'looktimes' => new NotORM_Literal("looktimes + ".$looktimes."") ) );
		return $rs;		
	}	
	
	public function getUrgenum($uid) {
		
		$info=DI()->notorm->users
					->select('live_level,unexchange_like,all_like,urge_moneys')
					->where('id=? and user_type="2"',$uid)
					->fetchOne();	
		//主播等级
		$level_anchor=$this->getLiveLevelAnchor($info['all_like'],$info['urge_moneys'],$info['live_level'],$info['unexchange_like'],$uid);
		//主播等级信息:读取对应主播等级设置的催更次数限制
		$levelinfo=$this->getLevelAnchorinfo($level_anchor);
		return $levelinfo;
	}	
}
