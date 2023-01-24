<?php

class Model_Community extends Model_Common {
	/* 发布圈子 */
	public function setCommunity($data) {
		$uid=$data['uid'];

		//获取后台配置的初始曝光值
		$configPri=$this->getConfigPri();
		$data['show_val']=$configPri['show_val'];
		if($configPri['community_audit_switch']==0){
			$data['status']=1;
		}
		$result= DI()->notorm->users_community->insert($data);
		
		if($result){
			
			foreach (explode(',',$data['cate']) as $key => $value) {
				DI()->notorm->live_category
		            ->where("id = '{$value}'")
				 	->update( array('post_count' => new NotORM_Literal("post_count + 1") ) );
			}
		}
		return $result;
	}	

	/* 评论/回复 */
    public function setComment($data) {
    	$communityId=$data['communityId'];

		/* 更新 圈子 */
		DI()->notorm->users_community
            ->where("id = '{$communityId}'")
		 	->update( array('comments' => new NotORM_Literal("comments + 1") ) );
		
        DI()->notorm->users_community_comments
            ->insert($data);
			
		$c_info=DI()->notorm->users_community
					->select("comments")
					->where('id=?',$communityId)
					->fetchOne();
					
		$count=DI()->notorm->users_community_comments
					->where("commentid='{$data['commentid']}'")
					->count();
		$rs=array(
			'comments'=>$c_info['comments'],
			'replys'=>$count,
		);

		//$aaa='[{"id":1,"name":"asdsadsadad"},{"id":1,"name":"asdsadsadad"},{"id":1,"name":"asdsadsadad"}]';
		
		//如果有人发评论@了其他人，写入评论@记录
		$arr=json_decode($data['at_info'],true); //将json串转为数组

		if(!empty($arr)){

			$data1=array("communityId"=>$data['communityId'],"addtime"=>time(),"uid"=>$data['uid']);
			foreach ($arr as $k => $v) {
				$data1['touid']=$v['uid'];
				DI()->notorm->users_community_comments_at_messages->insert($data1);
			}
		}

		//直接对圈子进行的评论，向评论信息表中写入记录
		if($data['commentid']==0){
			$data2=array("uid"=>$data['uid'],"touid"=>$data['touid'],"communityId"=>$data['communityId'],"content"=>$data['content'],"addtime"=>time());
			DI()->notorm->users_community_comments_messages->insert($data2);
		}	
		
		

		return $rs;	
    }			

	/* 阅读圈子 */
	public function addView($uid,$communityId){
		/*$view=DI()->notorm->users_community_view
				->select("id")
				->where("uid='{$uid}' and communityId='{$communityId}'")
				->fetchOne();

		if(!$view){
			DI()->notorm->users_community_view
						->insert(array("uid"=>$uid,"communityId"=>$communityId,"addtime"=>time() ));
						
			DI()->notorm->users_community
				->where("id = '{$communityId}'")
				->update( array('view' => new NotORM_Literal("view + 1") ) );
		}*/

		$readLists=DI()->redis -> get('readcommunity_'.$uid);
		$readArr=array();

		if($readLists){
			$readArr=json_decode($readLists,true);
			if(!in_array($communityId,$readArr)){
				$readArr[]=$communityId;
			}
		}else{
			$readArr[]=$communityId;
		}
		
		//file_put_contents('./addView.txt',date('Y-m-d H:i:s').' 提交参数信息 :'.$uid.'---'.$communityId.'---'.json_encode($readArr)."\r\n",FILE_APPEND);

		DI()->redis -> set('readcommunity_'.$uid,json_encode($readArr));

		DI()->notorm->users_community
				->where("id = '{$communityId}'")
				->update( array('views' => new NotORM_Literal("views + 1") ) );
		
		return 0;
	}
	/* 圈子点赞 */
	public function addLike($uid,$communityId){
		$rs=array(
			'islike'=>'0',
			'likes'=>'0',
		);
		$community=DI()->notorm->users_community
				->select("likes,uid")
				->where("id = '{$communityId}'")
				->fetchOne();

		if(!$community){
			return 1001;
		}
		if($community['uid']==$uid){
			return 1002;//不能给自己点赞
		}

		$like=DI()->notorm->users_community_like
						->select("id")
						->where("uid='{$uid}' and communityId='{$communityId}'")
						->fetchOne();
		if(!$like){
			DI()->redis->sismember('user_community_like'.$uid,$communityId);
			DI()->notorm->users_community_like
						->insert(array("uid"=>$uid,"communityId"=>$communityId,"addtime"=>time() ));
			
			DI()->notorm->users_community
				->where("id = '{$communityId}'")
				->update( array('likes' => new NotORM_Literal("likes + 1") ) );
			$rs['islike']='1';
		}


		//获取今天开始结束时间
		$todayBeginDate=strtotime(date('Y-m-d 00:00:00'));//今天开始时间
		$todayEndDate=strtotime(date('Y-m-d 23:59:59'));//今天开始时间

		//获取配置表中单IP每天限制点赞个数
		$configPri=$this->getConfigPri();
		$day_ip_limit=$configPri['day_ip_limit'];
        $ip=$this->getUserIp();
        /* 点赞者处理 */
        //file_put_contents('./addLike.txt',date('Y-m-d H:i:s').' 提交参数信息 day_ip_limit:'.json_encode($day_ip_limit)."\r\n",FILE_APPEND);
        //file_put_contents('./addLike.txt',date('Y-m-d H:i:s').' 提交参数信息 uid:'.json_encode($uid)."\r\n",FILE_APPEND);
        //file_put_contents('./addLike.txt',date('Y-m-d H:i:s').' 提交参数信息 ip:'.json_encode($ip)."\r\n",FILE_APPEND);
        $is_user_add2=0;
        $count2=DI()->notorm->praise_list2->where("uid=? and addtime > ? and addtime < ?",$uid,$todayBeginDate,$todayEndDate)->sum("count");
        //file_put_contents('./addLike.txt',date('Y-m-d H:i:s').' 提交参数信息 count2:'.json_encode($count2)."\r\n",FILE_APPEND);
        if(!$count2){
            $count2=0;
        }
        if($count2<$day_ip_limit){
            $is_user_add2=1;
            
        }
        //file_put_contents('./addLike.txt',date('Y-m-d H:i:s').' 提交参数信息 is_user_add2:'.json_encode($is_user_add2)."\r\n",FILE_APPEND);
        /* 点赞者IP处理 */
        $is_user_add3=0;
        $count3=DI()->notorm->praise_list2->where("ip=? and addtime > ? and addtime < ?",$ip,$todayBeginDate,$todayEndDate)->sum("count");
        //file_put_contents('./addLike.txt',date('Y-m-d H:i:s').' 提交参数信息 count3:'.json_encode($count3)."\r\n",FILE_APPEND);
        if(!$count3){
            $count3=0;
        }
        if($count3<$day_ip_limit){
            $is_user_add3=1;
        }
        //file_put_contents('./addLike.txt',date('Y-m-d H:i:s').' 提交参数信息 is_user_add3:'.json_encode($is_user_add3)."\r\n",FILE_APPEND);
        
        /* 记录 */
        if($is_user_add2==1 && $is_user_add3==1){
            $isexist=DI()->notorm->praise_list2->select("*")->where("uid=? and ip=? and addtime > ? and addtime < ?",$uid,$ip,$todayBeginDate,$todayEndDate)->fetchOne();
            if($isexist){
                DI()->notorm->praise_list2->where("id=? ",$isexist['id'])->update(array("count"=>new NotORM_Literal("count + 1"),"addtime"=>time() ));
            }else{
                DI()->notorm->praise_list2->insert(array("uid"=>$uid,"ip"=>$ip,"addtime"=>time(),"count"=>1 ));
            }
        }

        DI()->notorm->praise_list2->where("uid=? and ip=? and addtime <?",$uid,$ip,$todayBeginDate)->delete();
		/*圈子发布者点赞ip记录start*/
		


		/*圈子发布者点赞ip记录end*/



		/*圈子点赞ip记录start*/




		//从ip点赞记录表中判断数据
		$praiseList=DI()->notorm->praise_list->where("uid=? and ip=? ",$community['uid'],$ip)->fetchOne();

		$is_user_add=0;

		if(!$praiseList){

			//向ip点赞记录表中写入数据
			DI()->notorm->praise_list
				->insert(array("uid"=>$community['uid'],"ip"=>$ip,"addtime"=>time(),"count"=>1 ));
			$is_user_add=1;
		}else{

			//判断记录的添加时间
			if($praiseList['addtime']>$todayBeginDate&&$praiseList['addtime']<$todayEndDate){

				if($praiseList['count']<$day_ip_limit){

					DI()->notorm->praise_list->where("uid=? and ip=? ",$community['uid'],$ip)->update(array("count"=>new NotORM_Literal("count + 1"),"addtime"=>time() ));

					$is_user_add=1;
				}

				

			}else{

				DI()->notorm->praise_list->where("uid=? and ip=? ",$community['uid'],$ip)->update(array("count"=>1,"addtime"=>time() ));
			}	

			

		}


		//将此用户其他失效的ip记录删除
		DI()->notorm->praise_list->where("uid=? and ip=? and addtime <?",$community['uid'],$ip,$todayBeginDate)->delete();


		if($is_user_add==1 && $is_user_add2==1 && $is_user_add3==1){
            //file_put_contents('./addLike.txt',date('Y-m-d H:i:s').' 提交参数信息 praise:'."\r\n",FILE_APPEND);
			//向用户表中添加赞数
			DI()->notorm->users->where("id=?",$community['uid'])->update(array("praise"=>new NotORM_Literal("praise + 1"),"praisetotal"=>new NotORM_Literal("praisetotal + 1") ));
		}
		
		/*圈子点赞ip记录end*/
		
		$community=DI()->notorm->users_community
				->select("likes,uid")
				->where("id = '{$communityId}'")
				->fetchOne();
				
		$rs['likes']=$community['likes'];
		
		//获取圈子点赞信息列表
		$fabulous=DI()->notorm->praise_messages->where("uid='{$uid}' and obj_id='{$communityId}' and type=1")->fetchOne();
		if(!$fabulous){
			DI()->notorm->praise_messages->insert(array("uid"=>$uid,"touid"=>$community['uid'],"obj_id"=>$communityId,"addtime"=>time(),"type"=>3));
		}else{
			DI()->notorm->praise_messages->where("uid='{$uid}' and type=3 and obj_id='{$communityId}'")->update(array("addtime"=>time()));
		}
		
		return $rs; 		
	}

	/*收藏/取消收藏圈子*/
	public function collectCommunity($uid,$communityId){

		//判断圈子是否存在
		$info=DI()->notorm->users_community->select("title,addtime")->where("id=? and isdel=0 and status=1",$communityId)->fetchOne();


		if(!$info){ 
			return 1001;
		}

		//判断用户是否收藏过该圈子
		$isexist=DI()->notorm->users_community_collection->select("*")->where("uid='{$uid}' and communityId='{$communityId}'")->fetchOne();


		//已经收藏过
		if($isexist){

			if($isexist['status']==1){ //已收藏
				//将状态改为取消收藏
				$result=DI()->notorm->users_community_collection->where("uid=? and communityId=?",$uid,$communityId)->update(array("status"=>0,"updatetime"=>time()));
				DI()->notorm->users_community->where("id=? and isdel=0 and status=1",$communityId)->update( array('collect' => new NotORM_Literal("collect - 1") ) );
				if($result!==false){
					DI()->redis->srem('user_community_collect'.$uid,$communityId);
					return 200;
				}else{
					return 201;
				}
			}else{ //改为收藏

				//将状态改为收藏
				$result=DI()->notorm->users_community_collection->where("uid=? and communityId=?",$uid,$communityId)->update(array("status"=>1,"updatetime"=>time()));
				DI()->notorm->users_community->where("id=? and isdel=0 and status=1",$communityId)->update( array('collect' => new NotORM_Literal("collect + 1") ) );
				if($result!==false){
					DI()->redis->sadd('user_community_collect'.$uid,$communityId);
					return 300;
				}else{
					return 301;
				}
			}
			
		}else{

			//向收藏表中写入记录
			$data=array("uid"=>$uid,"communityId"=>$communityId,'addtime'=>time(),'status'=>1);
			$result=DI()->notorm->users_community_collection->insert($data);
			DI()->notorm->users_community->where("id=? and isdel=0 and status=1",$communityId)->update( array('collect' => new NotORM_Literal("collect + 1") ) );
			if($result!==false){
				DI()->redis->sadd('user_community_collect'.$uid,$communityId);
				return 300;
			}else{
				return 301;
			}
		}

	}

	/*获取用户收藏圈子列表*/
	public function getCollectLists($uid,$p){
		$nums=20;
		$start=($p-1)*$nums;

		$where="uid='{$uid}' and status=1";

		$list=DI()->notorm->users_community_collection->select("*")->where($where)->order("addtime desc")->limit($start,$nums)->fetchAll();


		if(!$list){
			return 0;
		}

		foreach ($list as $k => $v) {
			$info=DI()->notorm->users_community->select("*")->where("id=?",$v['communityId'])->fetchOne();
			
			$list[$k]['cate'] = $this->getCateNameById(explode(',', $info['cate']));
			$list[$k]['userinfo']=$this->getUserInfo($info['uid']);	
			$list[$k]['datetime']=$this->datetime($info['addtime']);
			$list[$k]['addtime']=date("Y-m-d H:i:s",$info['addtime']);	
			$list[$k]['comments']=$this->NumberFormat($info['comments']);	
			$list[$k]['likes']=$this->NumberFormat($info['likes']);	
			$list[$k]['views']=$this->NumberFormat($info['views']);	
			
			$list[$k]['islike']=$this->isLike($uid,$info['id']);	//是否点赞	
			$list[$k]['isattent']=$this->isAttention($uid,$info['uid']);	//是否关注
			

			$arr = $this->url_to_arr($info['videolink'],$info['imgs']);
			//处理视频连接
			$list[$k]['videolink']=$arr['videolink'];	

			//处理图片连接
			$list[$k]['imgs']=$arr['imgs'];


		}

		return $list;
	}

	/* 分享 */
	public function addShare($uid,$communityId){

		
		$rs=array(
			'isshare'=>'0',
			'shares'=>'0',
		);
		DI()->notorm->users_community
			->where("id = '{$communityId}'")
			->update( array('shares' => new NotORM_Literal("shares + 1") ) );
		$rs['isshare']='1';

		
		$community=DI()->notorm->users_community
				->select("shares")
				->where("id = '{$communityId}'")
				->fetchOne();
		$rs['shares']=$community['shares'];
		
		return $rs; 		
	}

	/* 拉黑圈子 */
	public function setBlack($uid,$communityId){
		$rs=array(
			'isblack'=>'0',
		);
		$like=DI()->notorm->users_community_black
						->select("id")
						->where("uid='{$uid}' and communityId='{$communityId}'")
						->fetchOne();
		if($like){
			DI()->notorm->users_community_black
						->where("uid='{$uid}' and communityId='{$communityId}'")
						->delete();
			$rs['isshare']='0';
		}else{
			DI()->notorm->users_community_black
						->insert(array("uid"=>$uid,"communityId"=>$communityId,"addtime"=>time() ));
			$rs['isshare']='1';
		}	
		return $rs; 		
	}


	/* 评论/回复 点赞 */
	public function addCommentLike($uid,$commentid){
		$rs=array(
			'islike'=>'0',
			'likes'=>'0',
		);

		//根据commentid获取对应的评论信息
		$commentinfo=DI()->notorm->users_community_comments
			->where("id='{$commentid}'")
			->fetchOne();

		if(!$commentinfo){
			return 1001;
		}

		$like=DI()->notorm->users_community_comments_like
			->select("id")
			->where("uid='{$uid}' and commentid='{$commentid}'")
			->fetchOne();

		if($like){
			DI()->notorm->users_community_comments_like
						->where("uid='{$uid}' and commentid='{$commentid}'")
						->delete();
			
			DI()->notorm->users_community_comments
				->where("id = '{$commentid}' and likes>0")
				->update( array('likes' => new NotORM_Literal("likes - 1") ) );
			$rs['islike']='0';

		}else{
			DI()->notorm->users_community_comments_like
						->insert(array("uid"=>$uid,"commentid"=>$commentid,"addtime"=>time(),"touid"=>$commentinfo['uid'],"communityId"=>$commentinfo['communityId'] ));
			
			DI()->notorm->users_community_comments
				->where("id = '{$commentid}'")
				->update( array('likes' => new NotORM_Literal("likes + 1") ) );
			$rs['islike']='1';
		}	
		
		$community=DI()->notorm->users_community_comments
				->select("likes")
				->where("id = '{$commentid}'")
				->fetchOne();

		

		$rs['likes']=$community['likes'];


		//获取评论点赞信息列表
		$fabulous=DI()->notorm->praise_messages->where("uid='{$uid}' and obj_id='{$commentid}' and type=0")->fetchOne();
		if(!$fabulous){
			DI()->notorm->praise_messages->insert(array("uid"=>$uid,"touid"=>$commentinfo['uid'],"obj_id"=>$commentid,"communityId"=>$commentinfo['communityId'],"addtime"=>time(),"type"=>0));
		}else{
			DI()->notorm->praise_messages->where("uid='{$uid}' and type=0 and obj_id='{$commentid}'")->update(array("addtime"=>time()));
		}
		return $rs; 		
	}
	
	/* 热门圈子列表 
	 * $order 排序类型
	*/

	public function getCommunityList($uid,$cid,$p,$order){
		
		$nums=20;
		$start=($p-1)*$nums;
		$communityIds_s='';
		$where="isdel=0 and status=1";  //上架且审核通过
DI()->redis->del('communityByViews');
DI()->redis->del('communityById');

		if($order){
			$orderBy = 'istop DESC, views DESC';
			$community = $this->getcaches('communityByViews');
		}else{
			$orderBy = 'istop DESC, id DESC';
			$community = $this->getcaches('communityById');
		}



		if(empty($community)){//判断缓存是否存在
			$community = array();
			$community=DI()->notorm->users_community
				->select("*")
				->where($where)
				->where('cate LIKE ?', "%{$cid}%")
				->order($orderBy)
				->limit($start,$nums)
				->fetchAll();
			/*$community2=DI()->notorm->users_community
				->select("*")
				->where("isdel=0 and status=1 and istop=1")
				->fetchAll();*/
				
			if($community){
				if($order){
					DI()->redis->set('communityByViews',serialize($community),300);
				}else{
					DI()->redis->set('communityById',serialize($community),300);
				}
			}
			

		}else{
			$community= unserialize($community);
		}
				
		
		foreach($community as $k=>$v){
			
			$userinfo=(array)$this->getUserInfo($v['uid']);
			if(!$userinfo){
				$userinfo['user_nicename']="已删除";
			}

			$community[$k]['userinfo']=$userinfo;
			$community[$k]['cate']=$this->getCateNameById(explode(',', $v['cate']));
			$community[$k]['datetime']=$this->datetime($v['addtime']);
			$community[$k]['addtime']=date("Y-m-d H:i:s",$v['addtime']);
			$community[$k]['comments']=$this->NumberFormat($v['comments']);	//评论总数
			$community[$k]['likes']=$this->NumberFormat($v['likes']);	//点赞总数
			$community[$k]['views']=$this->NumberFormat($v['views']);	//浏览总数
			
			if($uid){
				
				$community[$k]['islike']=$this->isLike($uid,$v['id']);	//是否点赞
				
				$community[$k]['isattent']=$this->isAttention($uid,$v['uid']);	//是否关注
				$community[$k]['isCollect']=$this->isCollect($uid,$v['id']);//是否收藏
			}else{
				$community[$k]['islike']=0;	
				
				$community[$k]['isattent']=0;
				$community[$k]['isCollect']=0;	
			}


			$arr = $this->url_to_arr($v['videolink'],$v['imgs']);
			//处理视频连接
			$community[$k]['videolink']=$arr['videolink'];	

			//处理图片连接
			$community[$k]['imgs']=$arr['imgs'];

		}


		return $community;
	}

	
	/*public function getcommunityList($uid,$p){


		$nums=20;
		$start=($p-1)*$nums;
		if($uid){
			
				$communityIds_s=$this->getcommunityBlack($uid);
				$where="id not in ({$communityIds_s}) and isdel=0 and status=1";
			
		}else{
			$communityIds_s='';
			$where="isdel=0 and status=1";  //上架且审核通过
		}
	
		$numsp=DI()->redis -> hVals('usercommunityp_'.$uid);//上次浏览页数缓存

		if($p==1){
			for($i=1;$i<=$numsp[0];$i++){
				DI()->redis -> hDel('usercommunitys_'.$uid,$i);
			}
		}else{
			$list=DI()->redis -> hVals('usercommunitys_'.$uid);
			// $keylist=DI()->redis -> hKeys('usercommunitys_'.$uid); 
			$numsr=DI()->redis -> hLen('usercommunitys_'.$uid); 
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


		$community=DI()->notorm->users_community
				->select("*")
				->where($where)
				// ->order("addtime desc") 
				->order("RAND()")
				// ->limit($start,$nums) 
				->limit($start,$nums)
				->fetchAll();

		DI()->redis -> hSet('usercommunityp_'.$uid,"p",$p);//分页最后页数
		DI()->redis -> hSet('usercommunitys_'.$uid,$p,json_encode($community));//所有浏览过数据记录
		
		foreach($community as $k=>$v){

			
			$userinfo=(array)$this->getUserInfo($v['uid']);
			if(!$userinfo){
				$userinfo['user_nicename']="已删除";
			}

			
			$community[$k]['userinfo']=$userinfo;
			$community[$k]['datetime']=$this->datetime($v['addtime']);	
			$community[$k]['comments']=$this->NumberFormat($v['comments']);	
			$community[$k]['likes']=$this->NumberFormat($v['likes']);	
			$community[$k]['steps']=$this->NumberFormat($v['steps']);	
			if($uid){
				$community[$k]['islike']=(string)$this->isLike($uid,$v['id']);	
				$community[$k]['isstep']=(string)$this->ifStep($uid,$v['id']);	
				$community[$k]['isattent']=(string)$this->isAttention($uid,$v['uid']);	
			}else{
				$community[$k]['islike']=0;	
				$community[$k]['isstep']=0;	
				$community[$k]['isattent']=0;	
			}

			$community[$k]['musicinfo']=$this->getMusicInfo($community[$k]['userinfo']['user_nicename'],$v['music_id']);	
			

		}


		
		return $community;
	}*/


	/* 热门圈子 */
	
	public function test(){
		$uid='11655';
		$type=0;
		$p=2;
	
		DI()->redis ->hSet('h', 'key6', 'hello1');
		
		
		$list=DI()->redis -> hVals('usertesta_'.$uid);
		/* $list=DI()->redis -> hVals('usertest_'.$uid); */
		/* $list=DI()->redis -> hVals('h');
		return $list; */
			foreach($list as $v){
				$lists=json_decode($v,true);
				if($n==$times){
					break;
				}
				$n++;
			}
			 
			return $lists;
		
		
		
		$nums=20;
		$start=($p-1)*$nums;
		if($uid){
			if($type==1){//我催更的圈子
				$urgecommunityIds= $this->getUrgeIds($uid,0);
				if(!$urgecommunityIds){
					return 10010;
				}
				$where .=" id in(".$urgecommunityIds.")   and isdel=0";
			}else{
				$communityIds_s=$this->getcommunityBlack($uid);
				$where="id not in ({$communityIds_s}) and isdel=0";
			}
		}else{
			$communityIds_s='';
			$where="isdel=0";
		}
		
	
		$community=DI()->notorm->users_community
				->select("*")
				->where($where)
				->order("RAND()") 
				/* ->order("addtime desc") */
				->limit($start,$nums)
				->fetchAll();
		
			DI()->redis -> hSet('usertest_'.$uid,$uid.$p,json_encode($community));		
			
		
		foreach($community as $k=>$v){
			$userinfo=(array)$this->getUserInfo($v['uid']);
			if(!$userinfo){
				$userinfo['user_nicename']="已删除";
			}
			$community[$k]['userinfo']=$userinfo;
			$community[$k]['cate']=$this->getCateNameById(explode(',', $v['cate']));
			$community[$k]['datetime']=$this->datetime($v['addtime']);
			$community[$k]['addtime']=date("Y-m-d H:i:s",$v['addtime']);
			$community[$k]['comments']=$this->NumberFormat($v['comments']);	
			$community[$k]['likes']=$this->NumberFormat($v['likes']);	
			$community[$k]['steps']=$this->NumberFormat($v['steps']);	
			if($uid){
				$community[$k]['islike']=$this->isLike($uid,$v['id']);	//是否点赞
				
				$community[$k]['isattent']=$this->isAttention($uid,$v['uid']);	//是否关注
				$community[$k]['isCollect']=$this->isCollect($uid,$v['id']);//是否收藏
			}else{
				$community[$k]['islike']=0;	
				$community[$k]['isCollect']=0;	
				$community[$k]['isattent']=0;	
			}
			$community[$k]['isdialect']=0;

			$arr = $this->url_to_arr($v['videolink'],$v['imgs']);
			//处理视频连接
			$community[$k]['videolink']=$arr['videolink'];	

			//处理图片连接
			$community[$k]['imgs']=$arr['imgs'];

			}		
		
		return $community;
	} 
	/* 关注人圈子 */
	public function getAttentionCommunity($uid,$p){
		$nums=20;
		$start=($p-1)*$nums;
		
		$community=array();
		$attention=DI()->notorm->users_attention
				->select("touid")
				->where("uid='{$uid}'")
				->fetchAll();
		
		if($attention){
			
			$uids=$this->array_column2($attention,'touid');
			$touids=implode(",",$uids);
			
			$communityIds_s=$this->getcommunityBlack($uid);
			$where="uid in ({$touids}) and id not in ({$communityIds_s})  and isdel=0 and status=1";
			
			$community=DI()->notorm->users_community
					->select("*")
					->where($where)
					->order("addtime desc")
					->limit($start,$nums)
					->fetchAll();


			if(!$community){
				return 0;
			}
			
			foreach($community as $k=>$v){
				$community[$k]['userinfo']=$this->getUserInfo($v['uid']);
				$community[$k]['cate']=$this->getCateNameById(explode(',', $v['cate']));
				$community[$k]['datetime']=$this->datetime($v['addtime']);
				$community[$k]['addtime']=date("Y-m-d H:i:s",$v['addtime']);
				$community[$k]['comments']=$this->NumberFormat($v['comments']);	
				$community[$k]['likes']=$this->NumberFormat($v['likes']);
				$community[$k]['views']=$this->NumberFormat($v['views']);	
				// $community[$k]['steps']=$this->NumberFormat($v['steps']);	
			
				// $community[$k]['isstep']=(string)$this->isStep($uid,$v['id']);	
				$community[$k]['islike']=$this->isLike($uid,$v['id']);	//是否点赞
				
				$community[$k]['isattent']=$this->isAttention($uid,$v['uid']);	//是否关注
				$community[$k]['isCollect']=$this->isCollect($uid,$v['id']);//是否收藏

				$arr = $this->url_to_arr($v['videolink'],$v['imgs']);
				//处理视频连接
				$community[$k]['videolink']=$arr['videolink'];	

				//处理图片连接
				$community[$k]['imgs']=$arr['imgs'];
				
			}
			
			
			
			$arr1 = array_map(create_function('$n', 'return $n["addtime"];'), $community);
			
			array_multisort($arr1,SORT_DESC,$community);//多维数组的排序					
			
		}
		

		return $community;		
	} 			
	
	/* 圈子详情 */
	public function getCommunity($uid,$communityId){
		$community=DI()->notorm->users_community
					->select("*")
					->where("id = {$communityId}")
					->fetchOne();
		if(!$community){
			return 1000;
		}
		$community['cate'] = $this->getCateNameById(explode(',', $community['cate']));
		$community['userinfo']=$this->getUserInfo($community['uid']);	
		$community['datetime']=$this->datetime($community['addtime']);
		$community['addtime']=date("Y-m-d H:i:s",$community['addtime']);	
		$community['comments']=$this->NumberFormat($community['comments']);	
		$community['likes']=$this->NumberFormat($community['likes']);	
		$community['steps']=$this->NumberFormat($community['steps']);	
	
		$community['islike']=$this->isLike($uid,$communityId);	//是否点赞
				
		$community['isattent']=$this->isAttention($uid,$community['uid']);	//是否关注
		$community['isCollect']=$this->isCollect($uid,$communityId);//是否收藏



		$arr = $this->url_to_arr($community['videolink'],$community['imgs']);
		//处理视频连接
		$community['videolink']=$arr['videolink'];	

		//处理图片连接
		$community['imgs']=$arr['imgs'];	
		
		
		return 	$community;
	}
	
	/* 评论列表 */
	public function getComments($uid,$communityId,$p){
		$nums=20;
		$start=($p-1)*$nums;
		$comments=DI()->notorm->users_community_comments
					->select("*")
					->where("communityId='{$communityId}' and parentid='0'")
					->order("addtime desc")
					->limit($start,$nums)
					->fetchAll();
		foreach($comments as $k=>$v){

			$comments[$k]['userinfo']=$this->getUserInfo($v['uid']);				
			$comments[$k]['datetime']=$this->datetime($v['addtime']);
			$comments[$k]['addtime']=date("Y-m-d H:i:s",$v['addtime']);	
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

			$count=DI()->notorm->users_community_comments
					->where("commentid='{$v['id']}'")
					->count();
			$comments[$k]['replys']=$count;
		}
		
		$commentnum=DI()->notorm->users_community_comments
					->where("communityId='{$communityId}'")
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
		$comments=DI()->notorm->users_community_comments
					->select("*")
					->where("commentid='{$commentid}'")
					->order("addtime desc")
					->limit($start,$nums)
					->fetchAll();


		foreach($comments as $k=>$v){
			$comments[$k]['userinfo']=$this->getUserInfo($v['uid']);				
			$comments[$k]['datetime']=$this->datetime($v['addtime']);
			$comments[$k]['addtime']=date("Y-m-d H:i:s",$v['addtime']);	
			$comments[$k]['likes']=$this->NumberFormat($v['likes']);	
			$comments[$k]['islike']=(string)$this->ifCommentLike($uid,$v['id']);
			if($v['touid']>0){
				$touserinfo=$this->getUserInfo($v['touid']);
			}
			if(!$touserinfo){
				$touserinfo=(object)array();
				$comments[$k]['touid']='0';
			}
			


			if($v['parentid']>0 && $v['parentid']!=$commentid){
				$tocommentinfo=DI()->notorm->users_community_comments
					->select("content,at_info")
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
	
	
	/* 方言秀是否点赞 */
	public function ifDialectLike($uid,$communityId){
		$like=DI()->notorm->users_dialect_like
				->select("id")
				->where("uid='{$uid}' and dialectid='{$communityId}'")
				->fetchOne();
		if($like){
			return 1;
		}else{
			return 0;
		}	
	}
	
	/* 方言秀是否踩 */
	public function ifDialectStep($uid,$communityId){
		$like=DI()->notorm->users_dialect_step
				->select("id")
				->where("uid='{$uid}' and dialectid='{$communityId}'")
				->fetchOne();
		if($like){
			return 1;
		}else{
			return 0;
		}	
	}
	/* 评论/回复 是否点赞 */
	public function ifCommentLike($uid,$commentid){
		$like=DI()->notorm->users_community_comments_like
				->select("id")
				->where("uid='{$uid}' and commentid='{$commentid}'")
				->fetchOne();
		if($like){
			return 1;
		}else{
			return 0;
		}	
	}
	
	/* 我的圈子 */
	public function getMycommunity($uid,$p){
		$nums=20;
		$start=($p-1)*$nums;
		
		$community=DI()->notorm->users_community
				->select("*")
				->where('uid=?  and isdel=0',$uid)
				->order("addtime desc")
				->limit($start,$nums)
				->fetchAll();
		
		foreach($community as $k=>$v){
			$community[$k]['cate']=$this->getCateNameById(explode(',', $v['cate']));
			$community[$k]['userinfo']=$this->getUserInfo($v['uid']);
			$community[$k]['datetime']=$this->datetime($v['addtime']);
			$community[$k]['addtime']=date("Y-m-d H:i:s",$v['addtime']);
			$community[$k]['comments']=$this->NumberFormat($v['comments']);	
			$community[$k]['likes']=$this->NumberFormat($v['likes']);	
			$community[$k]['steps']=$this->NumberFormat($v['steps']);
			$community[$k]['islike']='0';	
			$community[$k]['isattent']='0';	
			$community[$k]['isdialect']='0';

			$arr = $this->url_to_arr($v['videolink'],$v['imgs']);
			//处理视频连接
			$community[$k]['videolink']=$arr['videolink'];	

			//处理图片连接
			$community[$k]['imgs']=$arr['imgs'];	
			
		}		
		
		
		$arr1 = array_map(create_function('$n', 'return $n["addtime"];'), $community);
		
		array_multisort($arr1,SORT_DESC,$community);//多维数组的排序
				
		return $community;
	} 	
	/* 删除圈子 */
	public function del($uid,$communityId){
		
		$result=DI()->notorm->users_community
					->select("*")
					->where("id='{$communityId}' and uid='{$uid}'")
					->fetchOne();	
		if($result){
			/* 删除 评论记录 */
			/* DI()->notorm->users_community_comments
						->where("communityId='{$communityId}'")
						->delete(); */
			/* 删除  阅读*/
			/* DI()->notorm->users_community_comments
						->where("communityId='{$communityId}'")
						->delete(); */
			/* 删除  点赞*/
			/* DI()->notorm->users_community_like
						->where("communityId='{$communityId}'")
						->delete(); */
			/* 删除圈子 */
			/* DI()->notorm->users_community
						->where("id='{$communityId}'")
						->delete();	 */
			DI()->notorm->users_community
						->where("id='{$communityId}'")
						->update( array( 'isdel'=>1 ) );
		}				
		return 0;
	}	
	/* 拉黑方言秀圈子名单 */
	public function getDialectBlack($uid){
		$communityIds=array('0');
		$list=DI()->notorm->users_dialect_black
						->select("dialectid")
						->where("uid='{$uid}'")
						->fetchAll();
		if($list){
			$communityIds=$this->array_column2($list,'dialectid');
		}
		
		$communityIds_s=implode(",",$communityIds);
		
		return $communityIds_s;
	}
	/* 个人主页圈子 */
	public function getHomeCommunity($uid,$touid,$p){
		$nums=20;
		$start=($p-1)*$nums;
		
		//$communityIds_s=$this->getcommunityBlack($uid);
		if($uid==$touid){  //自己的圈子（需要返回圈子的状态前台显示）
			$where=" uid={$uid} and isdel='0' and status=1";
		}else{  //访问其他人的主页圈子
			//$where="id not in ({$communityIds_s}) and uid={$touid} and isdel='0' and status=1";
			$where="uid={$touid} and isdel='0' and status=1";
		}
		
		
		$community=DI()->notorm->users_community
				->select("*")
				->where($where)
				->order("addtime desc")
				->limit($start,$nums)
				->fetchAll();

		foreach($community as $k=>$v){
			$community[$k]['userinfo']=$this->getUserInfo($v['uid']);
			$community[$k]['cate']=$this->getCateNameById(explode(',', $v['cate']));
			$community[$k]['datetime']=$this->datetime($v['addtime']);	
			$community[$k]['comments']=$this->NumberFormat($v['comments']);	
			$community[$k]['likes']=$this->NumberFormat($v['likes']);	
			$community[$k]['views']=$this->NumberFormat($v['views']);
			$community[$k]['addtime']=date("Y-m-d H:i:s",$v['addtime']);	
			$community[$k]['isdialect']='0';

			$community[$k]['islike']=$this->isLike($uid,$v['id']);	//是否点赞
				
			$community[$k]['isattent']=$this->isAttention($uid,$v['uid']);	//是否关注
			$community[$k]['isCollect']=$this->isCollect($uid,$v['id']);//是否收藏
			if($uid==$touid){
				$community[$k]['islike']='0';	
				$community[$k]['isattent']='0';
				$community[$k]['isCollect']='0';
			}
			
			$arr = $this->url_to_arr($v['videolink'],$v['imgs']);
			//处理视频连接
			$community[$k]['videolink']=$arr['videolink'];	

			//处理图片连接
			$community[$k]['imgs']=$arr['imgs'];	
		}		

		
		$arr1 = array_map(create_function('$n', 'return $n["addtime"];'), $community);
		
		array_multisort($arr1,SORT_DESC,$community);//多维数组的排序
		return $community;
		
	}
	/* 举报 */
	public function report($data) {
		
		$community=DI()->notorm->users_community
					->select("uid")
					->where("id='{$data['communityId']}'")
					->fetchOne();
		if(!$community){
			return 1000;
		}
		
		$data['touid']=$community['uid'];
					
		$result= DI()->notorm->users_community_report->insert($data);
		return 0;
	}	
	
	/* 拉黑圈子名单 */
	public function getcommunityBlack($uid){
		$communityIds=array('0');
		$list=DI()->notorm->users_community_black
						->select("communityId")
						->where("uid='{$uid}'")
						->fetchAll();
		if($list){
			$communityIds=$this->array_column2($list,'communityId');
		}
		
		$communityIds_s=implode(",",$communityIds);
		
		return $communityIds_s;
	}

	/*获取推荐圈子列表*/
	/*public function getRecommendcommunitys($uid,$p){
		$pnums=20;
		$start=($p-1)*$pnums;


		//获取私密配置里的评论权重和点赞权重
		$configPri=$this->getConfigPri();

		$comment_weight=$configPri['comment_weight'];
		$like_weight=$configPri['like_weight'];
		$share_weight=$configPri['share_weight'];

		$prefix= DI()->config->get('dbs.tables.__default__.prefix');

		//按照评论数*评论权重值+点赞数*点赞权重值进行排序
		$info=DI()->notorm->users_community->queryAll("select *, (comments *".$comment_weight." + likes *".$like_weight.") as weight from ".$prefix."users_community where isdel=0 and status=1  order by weight desc limit ".$start.",".$pnums);


		if(!$info){
			return 1001;
		}


		foreach ($info as $k => $v) {
			$info[$k]['userinfo']=$this->getUserInfo($v['uid']);
			$info[$k]['datetime']=$this->datetime($v['addtime']);
			$info[$k]['addtime']=date("Y-m-d H:i:s",$v['addtime']);
			if($uid<0){
				$info[$k]['islike']='0';
				$info[$k]['isattent']='0';

			}else{
				$info[$k]['islike']=(string)$this->isLike($uid,$v['id']);
				$info[$k]['isattent']=(string)$this->isAttention($uid,$v['uid']);
			}


			$info[$k]['musicinfo']=$this->getMusicInfo($info[$k]['userinfo']['user_nicename'],$v['music_id']);	

			$info[$k]['isstep']='0'; //以下字段基本无用
			$info[$k]['isdialect']='0';
			unset($info[$k]['weight']);
			unset($info[$k]['is_urge']);
			unset($info[$k]['urge_nums']);
			unset($info[$k]['urge_money']);
			unset($info[$k]['big_urgenums']);
			unset($info[$k]['status']);
		}


		return $info;
	}*/


	public function getRecommendcommunitys($uid,$p){
      
      

		$pnums=50;
		$start=($p-1)*$pnums;
        
        //获取私密配置里的评论权重和点赞权重
		$configPri=$this->getConfigPri();

		$comment_weight=$configPri['comment_weight'];
		$like_weight=$configPri['like_weight'];
		$share_weight=$configPri['share_weight'];

		$prefix= DI()->config->get('dbs.tables.__default__.prefix');
		$where=array();

		$readLists=DI()->redis -> Get('readcommunity_'.$uid);
    

        
        
		if($readLists){
			$where=json_decode($readLists,true);
            //$where=implode(',',$where);
		}
		
       /* $uidlist=DI()->redis -> Get('community_list_'.$uid);
          //return 'uid:'.DI()->redis -> Get('community_list_');
        if($uidlist && !empty($uid)){
			$where_uid=json_decode($uidlist,true);
            //$where_uid=implode(',',$where_uid);
		}*/
        
        //$info=DI()->notorm->users_community->queryAll("select *,(ceil(comments * ".$comment_weight." + likes * ".$like_weight." + shares * ".$share_weight.") + show_val)* if(format(watch_ok/views,2) >1,'1',format(watch_ok/views,2)) as recomend from ".$prefix."users_community where isdel=0 and status=1 and id not in ({$where}) and uid not in ({$where_uid})  group by uid order by recomend desc,addtime desc limit 0,".$pnums);
        
		$info=DI()->notorm->users_community
            ->select("*,(ceil(comments * ".$comment_weight." + likes * ".$like_weight.") + show_val) as recomend")
            ->where("isdel=0 and status=1")
            //->where('not id',$where)
            ->where('not uid',$uid)
            ->group("uid")
            ->order("recomend desc,addtime desc")
            ->limit($start,$pnums)
            ->fetchAll();
      
        //return $info;
        $uid_list=array();
		foreach ($info as $k => $v) {
			$uid_list[]=$v['uid'];
			$info[$k]['userinfo']=$this->getUserInfo($v['uid']);
			$info[$k]['cate']=$this->getCateNameById(explode(',', $v['cate']));
			$info[$k]['datetime']=$this->datetime($v['addtime']);
			$info[$k]['comments']=$this->NumberFormat($v['comments']);	
			$info[$k]['likes']=$this->NumberFormat($v['likes']);	
			$info[$k]['views']=$this->NumberFormat($v['views']);
			$info[$k]['addtime']=date("Y-m-d H:i:s",$v['addtime']);
			if($uid<0){
				$info[$k]['islike']='0';
				$info[$k]['isattent']='0';
				$info[$k]['isCollect']= '0';
			}else{
				$info[$k]['islike']=$this->isLike($uid,$v['id']);	//是否点赞
				
			$info[$k]['isattent']=$this->isAttention($uid,$v['uid']);	//是否关注
			$info[$k]['isCollect']=$this->isCollect($uid,$v['id']);//是否收藏
				
			}

			$arr = $this->url_to_arr($v['videolink'],$v['imgs']);
			//处理视频连接
			$info[$k]['videolink']=$arr['videolink'];	

			//处理图片连接
			$info[$k]['imgs']=$arr['imgs'];	
		}
        
        /*if($uid_list && !empty($uid)){//狗屁逻辑，看过就拉黑吗？
            DI()->redis -> Set('community_list_'.$uid,json_encode($uid_list));
        }*/

		return $info;

	}


	public function getRecommendcommunitysBF($uid,$p){
		$pnums=20;
		$start=($p-1)*$pnums;


		//获取私密配置里的评论权重和点赞权重
		$configPri=$this->getConfigPri();

		$comment_weight=$configPri['comment_weight'];
		$like_weight=$configPri['like_weight'];
		$share_weight=$configPri['share_weight'];

		$prefix= DI()->config->get('dbs.tables.__default__.prefix');

		//热度值 = 点赞数*点赞权重+评论数*评论权重+分享数*分享权重
		//转化率 = 完整观看次数/总观看次数
		//排序规则：（曝光值+热度值）*转化率
		//曝光值从圈子发布开始，每小时递减1，直到0为止


		//$info=DI()->notorm->users_community->queryAll("select *,format(watch_ok/views,2) as aaa, (ceil(comments *".$comment_weight." + likes *".$like_weight." + shares *".$share_weight.") + show_val)*format(watch_ok/views,2) as recomend from ".$prefix."users_community where isdel=0 and status=1  order by recomend desc,addtime desc limit ".$start.",".$pnums);

		$info=DI()->notorm->users_community->queryAll("select *,(ceil(comments * ".$comment_weight." + likes * ".$like_weight." + shares * ".$share_weight.") + show_val)* if(format(watch_ok/views,2) >1,'1',format(watch_ok/views,2)) as recomend from ".$prefix."users_community where isdel=0 and status=1  order by recomend desc,addtime desc limit ".$start.",".$pnums);

		if(!$info){
			return 1001;
		}


		foreach ($info as $k => $v) {
			$info[$k]['userinfo']=$this->getUserInfo($v['uid']);
			$info[$k]['cate']=$this->getCateNameById(explode(',', $v['cate']));
			$info[$k]['datetime']=$this->datetime($v['addtime']);
			$info[$k]['addtime']=date("Y-m-d H:i:s",$v['addtime']);
			$info[$k]['comments']=$this->NumberFormat($v['comments']);	
			$info[$k]['likes']=$this->NumberFormat($v['likes']);	
			$info[$k]['views']=$this->NumberFormat($v['views']);
			if($uid<0){
				$info[$k]['islike']='0';
				$info[$k]['isattent']='0';
				$info[$k]['isCollect']= '0';
			}else{
				$info[$k]['islike']=$this->isLike($uid,$v['id']);	//是否点赞
				
			$info[$k]['isattent']=$this->isAttention($uid,$v['uid']);	//是否关注
			$info[$k]['isCollect']=$this->isCollect($uid,$v['id']);//是否收藏
				
			}

			$arr = $this->url_to_arr($v['videolink'],$v['imgs']);
			//处理视频连接
			$info[$k]['videolink']=$arr['videolink'];	

			//处理图片连接
			$info[$k]['imgs']=$arr['imgs'];	

			
		
		}


		return $info;
	}

	/*获取附近的圈子*/
	public function getNearby($uid,$lng,$lat,$p){
		$pnum=20;
		$start=($p-1)*$pnum;

		$prefix= DI()->config->get('dbs.tables.__default__.prefix');

		$info=DI()->notorm->users_community->queryAll("select *, round(6378.138 * 2 * ASIN(SQRT(POW(SIN(( ".$lat." * PI() / 180 - lat * PI() / 180) / 2),2) + COS(".$lat." * PI() / 180) * COS(lat * PI() / 180) * POW(SIN((".$lng." * PI() / 180 - lng * PI() / 180) / 2),2))) * 1000) AS distance FROM ".$prefix."users_community  where uid !=".$uid." and isdel=0 and status=1 order by distance asc,addtime desc limit ".$start.",".$pnum);

		if(!$info){
			return 1001;
		}


		foreach ($info as $k => $v) {
			$info[$k]['userinfo']=$this->getUserInfo($v['uid']);
			$info[$k]['cate']=$this->getCateNameById(explode(',', $v['cate']));
			$info[$k]['datetime']=$this->datetime($v['addtime']);
			$info[$k]['addtime']=date("Y-m-d H:i:s",$v['addtime']);
			$info[$k]['comments']=$this->NumberFormat($v['comments']);	
			$info[$k]['likes']=$this->NumberFormat($v['likes']);	
			$info[$k]['views']=$this->NumberFormat($v['views']);		
			$info[$k]['distance']=$this->distanceFormat($v['distance']);
			
			if($uid<0){
				$info[$k]['islike']='0';
				$info[$k]['isattent']='0';
				$info[$k]['isCollect']= '0';
			}else{
				$info[$k]['islike']=$this->isLike($uid,$v['id']);	//是否点赞
				
			$info[$k]['isattent']=$this->isAttention($uid,$v['uid']);	//是否关注
			$info[$k]['isCollect']=$this->isCollect($uid,$v['id']);//是否收藏
				
			}

			$arr = $this->url_to_arr($v['videolink'],$v['imgs']);
			//处理视频连接
			$info[$k]['videolink']=$arr['videolink'];	

			//处理图片连接
			$info[$k]['imgs']=$arr['imgs'];	

			
		}
		
		return $info;
	}

	/* 举报分类列表 */
	public function getReportContentlist() {
		
		$reportlist=DI()->notorm->users_community_report_classify
					->select("*")
					->order("orderno asc")
					->fetchAll();
		if(!$reportlist){
			return 1001;
		}
		
		return $reportlist;
		
	}

    public function checkOutcommunity($type,$communityId){
        
        $isexist=DI()->notorm->users_community_out->select("id")->where("type=? and community=?",$type,$communityId)->fetchOne();
        if($isexist){
            return 1;
        }
       return 0;
        
    }

    public function setOutcommunity($data){
        DI()->notorm->users_community_out->insert($data);
       return 0;
        
    }
	public function ceshi(){
		return $this->getUserIp();
	}


	/* 踩 */
	public function addStep($uid,$communityId){
		$rs=array(
			'isstep'=>'0',
			'steps'=>'0',
		);
		$like=DI()->notorm->users_community_step
						->select("id")
						->where("uid='{$uid}' and communityId='{$communityId}'")
						->fetchOne();
		if($like){
			DI()->notorm->users_community_step
						->where("uid='{$uid}' and communityId='{$communityId}'")
						->delete();
			
			DI()->notorm->users_community
				->where("id = '{$communityId}' and steps>0")
				->update( array('steps' => new NotORM_Literal("steps - 1") ) );
			$rs['isstep']='0';
		}else{
			DI()->notorm->users_community_step
						->insert(array("uid"=>$uid,"communityId"=>$communityId,"addtime"=>time() ));
			
			DI()->notorm->users_community
				->where("id = '{$communityId}'")
				->update( array('steps' => new NotORM_Literal("steps + 1") ) );
			$rs['isstep']='1';
		}	
		
		$community=DI()->notorm->users_community
				->select("steps")
				->where("id = '{$communityId}'")
				->fetchOne();
		$rs['steps']=$community['steps'];
		return $rs; 		
	}

	/*
	@ $str1 图片链接字符串
	@ $str2 视频链接字符串
	*/
	//分割圈子图片/视频链接为数组
	protected function url_to_arr($str1,$str2){

		//处理视频连接
		if($str1){
			$link = explode(',', $str1);
			$videolink[0]['thumb'] = $this->get_upload_path($link[0]);
			$videolink[0]['link'] = $this->get_upload_path($link[1]);
			
		}else{
			$videolink=array();
		}
		$info['videolink']=$videolink;	

		//处理图片连接
		if($str2){
			$images = explode(',', $str2);
			foreach ($images as $key => &$value) {
				$value = $this->get_upload_path($value);
			}
		}else{
			$images=array();
		}
		$info['imgs']=$images;

	

		return $info;
	}

}
