<?php

class Model_Video extends Model_Common {
	/* 发布视频 */
	public function setVideo($data,$music_id,$ispass) {
		$uid=$data['uid'];

		//获取后台配置的初始曝光值
		$configPri=$this->getConfigPri();
		$data['show_val']=$configPri['show_val'];

		if($ispass==1){
			$data['status']=1;
		}else{
			
			if($configPri['video_audit_switch']==0){
				$data['status']=1;
				if($data['price']>0 && $configPri['video_fee_switch']!=0){
					$data['status']=0;
				}
			}
		}

		$result= DI()->notorm->users_video->insert($data);
		
		if($result){
			DI()->redis->incrby('video_'.$uid,1);// 增加用户作品量
			$up_num = serialize(array($uid=>DI()->redis->get('video_'.$uid)));
			DI()->redis->sadd('video_up_num',$up_num);//添加用户和作品量关系
			foreach (explode(',',$data['cate']) as $key => $value) {
				DI()->notorm->live_category
		            ->where("id = '{$value}'")
				 	->update( array('post_count' => new NotORM_Literal("post_count + 1") ) );
			}
		}
		
		if($music_id>0){ //更新背景音乐被使用次数
			DI()->notorm->users_music
            ->where("id = '{$music_id}'")
		 	->update( array('use_nums' => new NotORM_Literal("use_nums + 1") ) );
		}
		
		return $result;
	}	

	/* 评论/回复 */
    public function setComment($data) {
    	$videoid=$data['videoid'];

		/* 更新 视频 */
		DI()->notorm->users_video
            ->where("id = '{$videoid}'")
		 	->update( array('comments' => new NotORM_Literal("comments + 1") ) );
		
        DI()->notorm->users_video_comments
            ->insert($data);
			
		$videoinfo=DI()->notorm->users_video
					->select("comments")
					->where('id=?',$videoid)
					->fetchOne();
					
		$count=DI()->notorm->users_video_comments
					->where("commentid='{$data['commentid']}'")
					->count();
		$rs=array(
			'comments'=>$videoinfo['comments'],
			'replys'=>$count,
		);

		//$aaa='[{"id":1,"name":"asdsadsadad"},{"id":1,"name":"asdsadsadad"},{"id":1,"name":"asdsadsadad"}]';
		
		//如果有人发评论@了其他人，写入评论@记录
		$arr=json_decode($data['at_info'],true); //将json串转为数组

		if(!empty($arr)){

			$data1=array("videoid"=>$data['videoid'],"addtime"=>time(),"uid"=>$data['uid']);
			foreach ($arr as $k => $v) {
				$data1['touid']=$v['uid'];
				DI()->notorm->users_video_comments_at_messages->insert($data1);
			}
		}

		//直接对视频进行的评论，向评论信息表中写入记录
		if($data['commentid']==0){
			$data2=array("uid"=>$data['uid'],"touid"=>$data['touid'],"videoid"=>$data['videoid'],"content"=>$data['content'],"addtime"=>time());
			DI()->notorm->users_video_comments_messages->insert($data2);
		}	
		
		

		return $rs;	
    }			

	/* 阅读 */
	public function addView($uid,$videoid){

		$userinfo = $this->getUserInfo($uid);
		if(DI()->redis->smembers('readvideo_'.$uid.date('Y-m-d',time()))){
			$ifView =DI()->redis->sismember('readvideo_'.$uid.date('Y-m-d',time()),$videoid);
			if(!$ifView){
				if($userinfo['free_count']>=1){
						DI()->notorm->users //减少观看次数
						->where("id = '{$uid}'")
						->update( array('free_count' => new NotORM_Literal("free_count - 1") ) );
					}else{
						if(!$userinfo['isVip']){
							if($userinfo['free_endtime']<time()){
							return 1000;
							}
						}
						
					}
			}

		}else{
			if($userinfo['free_count']>=1){
						DI()->notorm->users //减少观看次数
						->where("id = '{$uid}'")
						->update( array('free_count' => new NotORM_Literal("free_count - 1") ) );
				}else{
					if(!$userinfo['idVip']){
						if($userinfo['free_endtime']<time()){
							return 1000;
						}
					}
				}

			$endtime = strtotime('23:59:59')-time();
			DI()->redis->sadd('readvideo_'.$uid.date('Y-m-d',time()),$videoid);
			DI()->redis->expire('readvideo_'.$uid.date('Y-m-d',time()),$endtime); 
		}

		$view=DI()->notorm->users_video_view
				->select("id")
				->where("uid='{$uid}' and videoid='{$videoid}'")
				->fetchOne();

		if(!$view){
			DI()->notorm->users_video_view
						->insert(array("uid"=>$uid,"videoid"=>$videoid,"addtime"=>time() ));
						
			DI()->notorm->users_video
				->where("id = '{$videoid}'")
				->update( array('views' => new NotORM_Literal("views + 1") ) );
		}else{
			DI()->notorm->users_video_view
				->where("uid='{$uid}' and videoid='{$videoid}'")
				->update( array('addtime' =>time()) );
		}
		
		return 1;
	}

	/* 购买视频 */
	public function buyVideo($uid,$videoinfo){
		//获取配置表中视频分成
		$configPri=$this->getConfigPri();
		/*$view=DI()->notorm->users_video_buy
				->select("id")
				->where("uid='{$uid}' and videoid='{$videoid}'")
				->fetchOne();*/

		
		$videoid = $videoinfo['id'];
		$coin = $videoinfo['price'];

		//平台分到的
		$admin_num = ceil(($configPri['coin_prorata']*$coin)/100);
		//发布者分到的
		$user_num = $coin - $admin_num;
		$nowtime = time();
       
		//第一步：先指定待进行事务的数据库（通过获取一个notorm表实例来指定；否则会提示：PDO There is no active transaction）
		$user = DI()->notorm->users;
		$buy_user = DI()->notorm->users;
		$users_video_buy = DI()->notorm->users_video_buy;
		$admin_user = DI()->notorm->users;
		
		 //第三步：进行数据库操作
		$users_video_buy->insert(array("uid"=>$uid,"videoid"=>$videoid,'num'=>$coin,"addtime"=>$nowtime ));

		$res1 = $buy_user->where("id = '{$uid}'")->update( array('coin' => new NotORM_Literal("coin - {$coin}") ) );//购买用户减少

		$this->add_coin_log($uid,$videoid,$coin,'-','buyVideo',$nowtime);

		$res2 = true;
        if($videoinfo['uid']){//如果非管理员发布收费视频
            $res2 = $user->where("id = '{$videoinfo['uid']}'")->update( array('coin' => new NotORM_Literal("coin + {$user_num}") ) );//发布用户增加
            $this->add_coin_log($videoinfo['uid'],$videoid,$user_num,'+','workProceeds',$nowtime);
            $admin_num = $coin;
        }
		
		$res3 = $admin_user->where("id = 1")->update( array('coin' => new NotORM_Literal("coin + {$admin_num}") ) );//平台增加金币
	
		$this->add_coin_log(1,$videoid,$admin_num,'+','buyVideoProceeds',$nowtime);
		
			if($res1 && $res2 && $res3){
               
				DI()->redis -> sadd('buyvideo_'.$uid,$videoid);
				
				return 1;
			}else{
               
				return 0;
			}
		}

	/* 点赞 */
	public function addLike($uid,$videoid){
		$rs=array(
			'islike'=>'0',
			'likes'=>'0',
		);
		$video=DI()->notorm->users_video
				->select("likes,uid,thumb")
				->where("id = '{$videoid}'")
				->fetchOne();

		if(!$video){
			return 1001;
		}
		if($video['uid']==$uid){
			return 1002;//不能给自己点赞
		}

		$like=DI()->notorm->users_video_like
						->select("id")
						->where("uid='{$uid}' and videoid='{$videoid}'")
						->fetchOne();
		if(!$like){
			DI()->notorm->users_video_like
						->insert(array("uid"=>$uid,"videoid"=>$videoid,"addtime"=>time() ));
			DI()->redis->sadd('user_video_like'.$uid,$videoid);//队列添加点赞的视频id
			DI()->notorm->users_video
				->where("id = '{$videoid}'")
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
		/*视频发布者点赞ip记录start*/
		


		/*视频发布者点赞ip记录end*/



		/*视频点赞ip记录start*/




		//从ip点赞记录表中判断数据
		$praiseList=DI()->notorm->praise_list->where("uid=? and ip=? ",$video['uid'],$ip)->fetchOne();

		$is_user_add=0;

		if(!$praiseList){

			//向ip点赞记录表中写入数据
			DI()->notorm->praise_list
				->insert(array("uid"=>$video['uid'],"ip"=>$ip,"addtime"=>time(),"count"=>1 ));
			$is_user_add=1;
		}else{

			//判断记录的添加时间
			if($praiseList['addtime']>$todayBeginDate&&$praiseList['addtime']<$todayEndDate){

				if($praiseList['count']<$day_ip_limit){

					DI()->notorm->praise_list->where("uid=? and ip=? ",$video['uid'],$ip)->update(array("count"=>new NotORM_Literal("count + 1"),"addtime"=>time() ));

					$is_user_add=1;
				}

				

			}else{

				DI()->notorm->praise_list->where("uid=? and ip=? ",$video['uid'],$ip)->update(array("count"=>1,"addtime"=>time() ));
			}	

			

		}


		//将此用户其他失效的ip记录删除
		DI()->notorm->praise_list->where("uid=? and ip=? and addtime <?",$video['uid'],$ip,$todayBeginDate)->delete();


		if($is_user_add==1 && $is_user_add2==1 && $is_user_add3==1){
            //file_put_contents('./addLike.txt',date('Y-m-d H:i:s').' 提交参数信息 praise:'."\r\n",FILE_APPEND);
			//向用户表中添加赞数
			DI()->notorm->users->where("id=?",$video['uid'])->update(array("praise"=>new NotORM_Literal("praise + 1"),"praisetotal"=>new NotORM_Literal("praisetotal + 1") ));
		}
		
		/*视频点赞ip记录end*/
		
		$video=DI()->notorm->users_video
				->select("likes,uid,thumb")
				->where("id = '{$videoid}'")
				->fetchOne();
				
		$rs['likes']=$video['likes'];
		
		//获取视频点赞信息列表
		$fabulous=DI()->notorm->praise_messages->where("uid='{$uid}' and obj_id='{$videoid}' and type=1")->fetchOne();
		if(!$fabulous){
			DI()->notorm->praise_messages->insert(array("uid"=>$uid,"touid"=>$video['uid'],"obj_id"=>$videoid,"videoid"=>$videoid,"addtime"=>time(),"type"=>1,"video_thumb"=>$video['thumb']));
		}else{
			DI()->notorm->praise_messages->where("uid='{$uid}' and type=1 and obj_id='{$videoid}'")->update(array("addtime"=>time()));
		}
		
		return $rs; 		
	}

	/* 踩 */
	public function addStep($uid,$videoid){
		$rs=array(
			'isstep'=>'0',
			'steps'=>'0',
		);
		$like=DI()->notorm->users_video_step
						->select("id")
						->where("uid='{$uid}' and videoid='{$videoid}'")
						->fetchOne();
		if($like){
			DI()->notorm->users_video_step
						->where("uid='{$uid}' and videoid='{$videoid}'")
						->delete();
			
			DI()->notorm->users_video
				->where("id = '{$videoid}' and steps>0")
				->update( array('steps' => new NotORM_Literal("steps - 1") ) );
			$rs['isstep']='0';
		}else{
			DI()->notorm->users_video_step
						->insert(array("uid"=>$uid,"videoid"=>$videoid,"addtime"=>time() ));
			
			DI()->notorm->users_video
				->where("id = '{$videoid}'")
				->update( array('steps' => new NotORM_Literal("steps + 1") ) );
			$rs['isstep']='1';
		}	
		
		$video=DI()->notorm->users_video
				->select("steps")
				->where("id = '{$videoid}'")
				->fetchOne();
		$rs['steps']=$video['steps'];
		return $rs; 		
	}

	/* 分享 */
	public function addShare($uid,$videoid){

		
		$rs=array(
			'isshare'=>'0',
			'shares'=>'0',
		);
		DI()->notorm->users_video
			->where("id = '{$videoid}'")
			->update( array('shares' => new NotORM_Literal("shares + 1") ) );
		$rs['isshare']='1';

		
		$video=DI()->notorm->users_video
				->select("shares")
				->where("id = '{$videoid}'")
				->fetchOne();
		$rs['shares']=$video['shares'];
		
		return $rs; 		
	}

	/* 拉黑视频 */
	public function setBlack($uid,$videoid){
		$rs=array(
			'isblack'=>'0',
		);
		$like=DI()->notorm->users_video_black
						->select("id")
						->where("uid='{$uid}' and videoid='{$videoid}'")
						->fetchOne();
		if($like){
			DI()->notorm->users_video_black
						->where("uid='{$uid}' and videoid='{$videoid}'")
						->delete();
			$rs['isshare']='0';
		}else{
			DI()->notorm->users_video_black
						->insert(array("uid"=>$uid,"videoid"=>$videoid,"addtime"=>time() ));
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
		$commentinfo=DI()->notorm->users_video_comments
			->where("id='{$commentid}'")
			->fetchOne();

		if(!$commentinfo){
			return 1001;
		}

		$like=DI()->notorm->users_video_comments_like
			->select("id")
			->where("uid='{$uid}' and commentid='{$commentid}'")
			->fetchOne();

		if($like){
			DI()->notorm->users_video_comments_like
						->where("uid='{$uid}' and commentid='{$commentid}'")
						->delete();
			
			DI()->notorm->users_video_comments
				->where("id = '{$commentid}' and likes>0")
				->update( array('likes' => new NotORM_Literal("likes - 1") ) );
			$rs['islike']='0';

		}else{
			DI()->notorm->users_video_comments_like
						->insert(array("uid"=>$uid,"commentid"=>$commentid,"addtime"=>time(),"touid"=>$commentinfo['uid'],"videoid"=>$commentinfo['videoid'] ));
			
			DI()->notorm->users_video_comments
				->where("id = '{$commentid}'")
				->update( array('likes' => new NotORM_Literal("likes + 1") ) );
			$rs['islike']='1';
		}	
		
		$video=DI()->notorm->users_video_comments
				->select("likes")
				->where("id = '{$commentid}'")
				->fetchOne();

		//获取视频信息
		$videoinfo=DI()->notorm->users_video->select("thumb")->where("id='{$commentinfo['videoid']}'")->fetchOne();

		$rs['likes']=$video['likes'];


		//获取评论点赞信息列表
		$fabulous=DI()->notorm->praise_messages->where("uid='{$uid}' and obj_id='{$commentid}' and type=0")->fetchOne();
		if(!$fabulous){
			DI()->notorm->praise_messages->insert(array("uid"=>$uid,"touid"=>$commentinfo['uid'],"obj_id"=>$commentid,"videoid"=>$commentinfo['videoid'],"addtime"=>time(),"type"=>0,"video_thumb"=>$videoinfo['thumb']));
		}else{
			DI()->notorm->praise_messages->where("uid='{$uid}' and type=0 and obj_id='{$commentid}'")->update(array("addtime"=>time()));
		}
		return $rs; 		
	}
	
	/* 热门视频 */

	public function getVideoList($uid,$p,$type=0){
        $ConfigPub=$this->getConfigPub();

        $ad_space=$ConfigPub['ad_space'];
		$nums=50;
		$start=($p-1)*$nums;


		if($uid){
			$videoids_s=$this->getVideoBlack($uid);
			$where="id not in ({$videoids_s}) and isdel=0 and status=1";
		}else{
			$where="isdel=0 and status=1";  //上架且审核通过
		}
		

		$video= $this->getcaches('getVideoList_'.$type.'_'.$p);
		if(!$video){
			switch ($type) {
            case 1:
                $video=DI()->notorm->users_video->select("*")->where("is_ad=0 and isdel=0 and status=1 and price>0")->order('id desc')->limit($start,$nums)->fetchAll();break;
            case 2:
                $video=DI()->notorm->users_video->select("*")->where("is_ad=0 and isdel=0 and status=1 and uid=0")->order('id desc')->limit($start,$nums)->fetchAll();break;
            case 3:
                $video=DI()->notorm->users_video->select("*")->where("is_ad=0 and isdel=0 and status=1")->order('id desc')->limit($start,$nums)->fetchAll();break;
            case 4:
                $video=DI()->notorm->users_video->select("*")->where("is_ad=0 and isdel=0 and status=1 and views>0")->order('views desc')->limit($start,$nums)->fetchAll();break;
            case 5:
                $video=DI()->notorm->users_video->select("*")->where("is_ad=0 and isdel=0 and status=1 and comments>0")->order('comments desc')->limit($start,$nums)->fetchAll();break;
            case 6:
                $video=DI()->notorm->users_video->select("*")->where("is_ad=0 and isdel=0 and status=1 and likes>0")->order('likes desc')->limit($start,$nums)->fetchAll();break;
                
            default:$video=DI()->notorm->users_video
				->select("*")
                ->where('is_ad',0)
				->where($where)
				->order("RAND()")
				->limit($start,$nums)
				->fetchAll();
                break;
           
        	}
        	$this->setcaches('getVideoList_'.$type.'_'.$p,$video,300);
		}

		foreach($video as $k=>$v){
			
			$userinfo=$this->getUserInfo($v['uid']);
			if(!$userinfo){
				$userinfo['user_nicename']="已删除";
			}


			//是否购买过 购买过则免费	jjj
			// $isBuy=$this->ifBuy($uid,$v['id']);

			// if($isBuy){
			// 	$video[$k]['price']=0;
			// }

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
       

        //获取所有广告

        $ads=DI()->notorm->users_video
            ->select("*")
            ->where("is_ad=1 and isdel=0")
            ->fetchAll();

        foreach ($video as $k => $v){
            $k=$k+1;
            if ($k%$ad_space==0){
                $adnum=rand(0,count($ads)-1);
                $ad1= $ads[$adnum];

                $userinfo=$this->getUserInfo($ad1['uid']);
                if(!$userinfo){
                    $userinfo['user_nicename']="已删除";
                }


                //是否购买过 购买过则免费	jjj
                $isBuy=$this->ifBuy($uid,$ad1['id']);

                if($isBuy){
                    $ad1['price']=0;
                }

                //jjj
                $ad1['cate']=$this->getCateNameById(explode(',', $ad1['cate']));
                $ad1['userinfo']=$userinfo;
                $ad1['datetime']=$this->datetime($v['addtime']);
                $ad1['comments']=$this->NumberFormat($v['comments']);
                $ad1['likes']=$this->NumberFormat($v['likes']);
                $ad1['steps']=$this->NumberFormat($v['steps']);
                if($uid){
                    $ad1['islike']=(string)$this->ifLike($uid,$ad1['id']);
                    $ad1['isstep']=(string)$this->ifStep($uid,$ad1['id']);
                    $ad1['isattent']=(string)$this->isAttention($uid,$ad1['uid']);
                }else{
                    $ad1['islike']=0;
                    $ad1['isstep']=0;
                    $ad1['isattent']=0;
                }

                $ad1['musicinfo']=$this->getMusicInfo($ad1['userinfo']['user_nicename'],$ad1['music_id']);

                $ad1['thumb']=$this->get_upload_path($ad1['thumb']);
                $ad1['thumb_s']=$this->get_upload_path($ad1['thumb_s']);
                $ad1['href']=$this->get_upload_path($ad1['href']);


                $inf2[]=$ad1;
            }else{
                $inf2[]=$v;
            }

        }

        if(!$inf2){
            $inf2=[];
        }
        return $inf2;
	}

	
	/*public function getVideoList($uid,$p){


		$nums=20;
		$start=($p-1)*$nums;
		if($uid){
			
				$videoids_s=$this->getVideoBlack($uid);
				$where="id not in ({$videoids_s}) and isdel=0 and status=1";
			
		}else{
			$videoids_s='';
			$where="isdel=0 and status=1";  //上架且审核通过
		}
	
		$numsp=DI()->redis -> hVals('uservideop_'.$uid);//上次浏览页数缓存

		if($p==1){
			for($i=1;$i<=$numsp[0];$i++){
				DI()->redis -> hDel('uservideos_'.$uid,$i);
			}
		}else{
			$list=DI()->redis -> hVals('uservideos_'.$uid);
			// $keylist=DI()->redis -> hKeys('uservideos_'.$uid); 
			$numsr=DI()->redis -> hLen('uservideos_'.$uid); 
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


		$video=DI()->notorm->users_video
				->select("*")
				->where($where)
				// ->order("addtime desc") 
				->order("RAND()")
				// ->limit($start,$nums) 
				->limit($start,$nums)
				->fetchAll();

		DI()->redis -> hSet('uservideop_'.$uid,"p",$p);//分页最后页数
		DI()->redis -> hSet('uservideos_'.$uid,$p,json_encode($video));//所有浏览过数据记录
		
		foreach($video as $k=>$v){

			
			$userinfo=$this->getUserInfo($v['uid']);
			if(!$userinfo){
				$userinfo['user_nicename']="已删除";
			}

			
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
			

		}


		
		return $video;
	}*/


	/* 热门视频 */
	
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
			if($type==1){//我催更的视频
				$urgevideoids= $this->getUrgeIds($uid,0);
				if(!$urgevideoids){
					return 10010;
				}
				$where .=" id in(".$urgevideoids.")   and isdel=0";
			}else{
				$videoids_s=$this->getVideoBlack($uid);
				$where="id not in ({$videoids_s}) and isdel=0";
			}
		}else{
			$videoids_s='';
			$where="isdel=0";
		}
		
	
		$video=DI()->notorm->users_video
				->select("*")
				->where($where)
				->order("RAND()") 
				/* ->order("addtime desc") */
				->limit($start,$nums)
				->fetchAll();
		
			DI()->redis -> hSet('usertest_'.$uid,$uid.$p,json_encode($video));		
			
		
		foreach($video as $k=>$v){
			$userinfo=$this->getUserInfo($v['uid']);
			if(!$userinfo){
				$userinfo['user_nicename']="已删除";
			}
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
			$video[$k]['isdialect']=0;	
		}		
		
		return $video;
	} 
	/* 关注人de视频 */
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
			$where="uid in ({$touids}) and id not in ({$videoids_s})  and isdel=0 and status=1";
			
			$video=DI()->notorm->users_video
					->select("*")
					->where($where)
					->order("addtime desc")
					->limit($start,$nums)
					->fetchAll();


			if(!$video){
				return 0;
			}
			
			foreach($video as $k=>$v){

				$video[$k]['userinfo']=$this->getUserInfo($v['uid']);
				$video[$k]['datetime']=$this->datetime($v['addtime']);	
				$video[$k]['comments']=$this->NumberFormat($v['comments']);	
				$video[$k]['likes']=$this->NumberFormat($v['likes']);	
				$video[$k]['steps']=$this->NumberFormat($v['steps']);	
				$video[$k]['islike']=(string)$this->ifLike($uid,$v['id']);	
				$video[$k]['isstep']=(string)$this->ifStep($uid,$v['id']);	
				$video[$k]['isdialect']='0';
				$video[$k]['musicinfo']=$this->getMusicInfo($video[$k]['userinfo']['user_nicename'],$v['music_id']);
				$video[$k]['thumb']=$this->get_upload_path($v['thumb']);	
				$video[$k]['thumb_s']=$this->get_upload_path($v['thumb_s']);	
				$video[$k]['href']=$this->get_upload_path($v['href']);
				$video[$k]['cate']=$this->getCateNameById(explode(',', $v['cate']));
				
			}
			
			
			
			$arr1 = array_map(create_function('$n', 'return $n["addtime"];'), $video);
			
			array_multisort($arr1,SORT_DESC,$video);//多维数组的排序					
			
		}
		

		return $video;		
	} 			
	
	/* 视频详情 */
	public function getVideo($uid,$videoid){
		$video=DI()->notorm->users_video
					->select("*")
					->where("id = {$videoid}")
					->fetchOne();
		if(!$video){
			return 1000;
		}
		$video['cate']=$this->getCateNameById(explode(',', $video['cate']));
		$video['userinfo']=$this->getUserInfo($video['uid']);	
		$video['isattent']=(string)$this->isAttention($uid,$video['uid']);	
		$video['datetime']=$this->datetime($video['addtime']);	
		$video['comments']=$this->NumberFormat($video['comments']);	
		$video['likes']=$this->NumberFormat($video['likes']);	
		$video['steps']=$this->NumberFormat($video['steps']);	
		$video['shares']=$this->NumberFormat($video['shares']);	
		$video['islike']=(string)$this->ifLike($uid,$videoid);			
		$video['isstep']=(string)$this->ifStep($uid,$videoid);

		$video['musicinfo']=$this->getMusicInfo($video['userinfo']['user_nicename'],$v['music_id']);

		$video['thumb']=$this->get_upload_path($video['thumb']);	
		$video['thumb_s']=$this->get_upload_path($video['thumb_s']);	
		$video['href']=$this->get_upload_path($video['href']);
		
		
		return 	$video;
	}
	
	/* 评论列表 */
	public function getComments($uid,$videoid,$p){
		$nums=20;
		$start=($p-1)*$nums;
		$comments=DI()->notorm->users_video_comments
					->select("*")
					->where("videoid='{$videoid}' and parentid='0'")
					->order("addtime desc")
					->limit($start,$nums)
					->fetchAll();
		foreach($comments as $k=>$v){
			$comments[$k]['userinfo']=$this->getUserInfo($v['uid']);				
			$comments[$k]['datetime']=$this->datetime($v['addtime']);	
			$comments[$k]['likes']=$this->NumberFormat($v['likes']);	
			if($uid){
				$comments[$k]['islike']=(string)$this->ifCommentLike($uid,$v['id']);	
			}else{
				$comments[$k]['islike']='0';	
			}
			
			if($v['touid']>0){
				$touserinfo=$this->getUserInfo($v['touid']);
			}
			if(!$touserinfo){
				$touserinfo=(object)array();
				$comments[$k]['touid']='0';
			}
			$comments[$k]['touserinfo']=$touserinfo;

			$count=DI()->notorm->users_video_comments
					->where("commentid='{$v['id']}'")
					->count();
			$comments[$k]['replys']=$count;
		}
		
		$commentnum=DI()->notorm->users_video_comments
					->where("videoid='{$videoid}'")
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
		$comments=DI()->notorm->users_video_comments
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
				$touserinfo=$this->getUserInfo($v['touid']);
			}
			if(!$touserinfo){
				$touserinfo=(object)array();
				$comments[$k]['touid']='0';
			}
			


			if($v['parentid']>0 && $v['parentid']!=$commentid){
				$tocommentinfo=DI()->notorm->users_video_comments
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
	public function ifDialectLike($uid,$videoid){
		$like=DI()->notorm->users_dialect_like
				->select("id")
				->where("uid='{$uid}' and dialectid='{$videoid}'")
				->fetchOne();
		if($like){
			return 1;
		}else{
			return 0;
		}	
	}
	
	/* 方言秀是否踩 */
	public function ifDialectStep($uid,$videoid){
		$like=DI()->notorm->users_dialect_step
				->select("id")
				->where("uid='{$uid}' and dialectid='{$videoid}'")
				->fetchOne();
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
			$video[$k]['cate']=$this->getCateNameById(explode(',', $v['cate']));
			$video[$k]['datetime']=$this->datetime($v['addtime']);
			$video[$k]['comments']=$this->NumberFormat($v['comments']);	
			$video[$k]['likes']=$this->NumberFormat($v['likes']);	
			$video[$k]['steps']=$this->NumberFormat($v['steps']);
			$video[$k]['islike']='0';	
			$video[$k]['isattent']='0';	
			$video[$k]['isdialect']='0';
			$video[$k]['musicinfo']=$this->getMusicInfo($video[$k]['userinfo']['user_nicename'],$v['music_id']);
			$video[$k]['thumb']=$this->get_upload_path($v['thumb']);	
			$video[$k]['thumb_s']=$this->get_upload_path($v['thumb_s']);	
			$video[$k]['href']=$this->get_upload_path($v['href']);	
			
		}		
		
		
		$arr1 = array_map(create_function('$n', 'return $n["addtime"];'), $video);
		
		array_multisort($arr1,SORT_DESC,$video);//多维数组的排序
				
		return $video;
	} 	


	/* 我购买的视频 */
	public function getMyBuyVideo($uid,$p){
		$nums=20;
		$start=($p-1)*$nums;
		$videoIdList = DI()->redis -> smembers('buyvideo_'.$uid);
		if(!$videoIdList){
			$videoIdList=DI()->notorm->users_video_buy
				->where('uid',$uid)
				->fetchAll();
			$videoIdList= $this->array_column2($videoIdList,'videoid');	
		}
		$video=DI()->notorm->users_video
				->select("*")
				->where('id',$videoIdList)
				->order("addtime desc")
				->limit($start,$nums)
				->fetchAll();
		
		foreach($video as $k=>$v){
			$video[$k]['userinfo']=$this->getUserInfo($v['uid']);
			$video[$k]['cate']=$this->getCateNameById(explode(',', $v['cate']));
			$video[$k]['datetime']=$this->datetime($v['addtime']);
			$video[$k]['comments']=$this->NumberFormat($v['comments']);	
			$video[$k]['likes']=$this->NumberFormat($v['likes']);	
			$video[$k]['steps']=$this->NumberFormat($v['steps']);
			$video[$k]['islike']=(string)$this->ifLike($uid,$v['id']);	
			$video[$k]['isstep']=(string)$this->ifStep($uid,$v['id']);	
			$video[$k]['isattent']=(string)$this->isAttention($uid,$v['uid']);	
			$video[$k]['isdialect']='0';
			$video[$k]['musicinfo']=$this->getMusicInfo($video[$k]['userinfo']['user_nicename'],$v['music_id']);
			$video[$k]['thumb']=$this->get_upload_path($v['thumb']);	
			$video[$k]['thumb_s']=$this->get_upload_path($v['thumb_s']);	
			$video[$k]['href']=$this->get_upload_path($v['href']);	
			
		}		
		
		
		$arr1 = array_map(create_function('$n', 'return $n["addtime"];'), $video);
		
		array_multisort($arr1,SORT_DESC,$video);//多维数组的排序
				
		return $video;
	} 	


	/* 删除视频 */
	public function del($uid,$videoid){
		
		$result=DI()->notorm->users_video
					->select("*")
					->where("id='{$videoid}' and uid='{$uid}'")
					->fetchOne();	
		if($result){
			/* 删除 评论记录 */
			/* DI()->notorm->users_video_comments
						->where("videoid='{$videoid}'")
						->delete(); */
			/* 删除  阅读*/
			/* DI()->notorm->users_video_comments
						->where("videoid='{$videoid}'")
						->delete(); */
			/* 删除  点赞*/
			/* DI()->notorm->users_video_like
						->where("videoid='{$videoid}'")
						->delete(); */
			/* 删除视频 */
			/* DI()->notorm->users_video
						->where("id='{$videoid}'")
						->delete();	 */
			DI()->notorm->users_video
						->where("id='{$videoid}'")
						->update( array( 'isdel'=>1 ) );
		}				
		return 0;
	}	
	/* 拉黑方言秀视频名单 */
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
	/* 个人主页视频 */
	public function getHomeVideo($uid,$touid,$p){
		$nums=20;
		$start=($p-1)*$nums;
		
		$videoids_s=$this->getVideoBlack($uid);
		if($uid==$touid){  //自己的视频（需要返回视频的状态前台显示）
			$where=" uid={$uid} and isdel='0' and status=1";
		}else{  //访问其他人的主页视频
			$where="id not in ({$videoids_s}) and uid={$touid} and isdel='0' and status=1";
		}
		
		
		$video=DI()->notorm->users_video
				->select("*")
				->where($where)
				->order("addtime desc")
				->limit($start,$nums)
				->fetchAll();

		foreach($video as $k=>$v){
			$video[$k]['userinfo']=$this->getUserInfo($v['uid']);
			$video[$k]['cate']=$this->getCateNameById(explode(',', $v['cate']));
			$video[$k]['datetime']=$this->datetime($v['addtime']);	
			$video[$k]['comments']=$this->NumberFormat($v['comments']);	
			$video[$k]['likes']=$this->NumberFormat($v['likes']);	
			$video[$k]['steps']=$this->NumberFormat($v['steps']);	
			$video[$k]['islike']=(string)$this->ifLike($uid,$v['id']);	
			$video[$k]['isstep']=(string)$this->ifStep($uid,$v['id']);	
			$video[$k]['isattent']=(string)$this->isAttention($uid,$v['uid']);	
			$video[$k]['isdialect']='0';
			$video[$k]['musicinfo']=$this->getMusicInfo($video[$k]['userinfo']['user_nicename'],$v['music_id']);

			$video[$k]['thumb']=$this->get_upload_path($v['thumb']);	
			$video[$k]['thumb_s']=$this->get_upload_path($v['thumb_s']);	
			$video[$k]['href']=$this->get_upload_path($v['href']);
		}		

		
		$arr1 = array_map(create_function('$n', 'return $n["addtime"];'), $video);
		
		array_multisort($arr1,SORT_DESC,$video);//多维数组的排序
		return $video;
		
	}
	/* 举报 */
	public function report($data) {
		
		$video=DI()->notorm->users_video
					->select("uid")
					->where("id='{$data['videoid']}'")
					->fetchOne();
		if(!$video){
			return 1000;
		}
		
		$data['touid']=$video['uid'];
					
		$result= DI()->notorm->users_video_report->insert($data);
		return 0;
	}	
	
	/* 拉黑视频名单 */
	public function getVideoBlack($uid){
		$videoids=array('0');
		$list=DI()->notorm->users_video_black
						->select("videoid")
						->where("uid='{$uid}'")
						->fetchAll();
		if($list){
			$videoids=$this->array_column2($list,'videoid');
		}
		
		$videoids_s=implode(",",$videoids);
		
		return $videoids_s;
	}

	/*获取推荐视频列表*/
	/*public function getRecommendVideos($uid,$p){
		$pnums=20;
		$start=($p-1)*$pnums;


		//获取私密配置里的评论权重和点赞权重
		$configPri=$this->getConfigPri();

		$comment_weight=$configPri['comment_weight'];
		$like_weight=$configPri['like_weight'];
		$share_weight=$configPri['share_weight'];

		$prefix= DI()->config->get('dbs.tables.__default__.prefix');

		//按照评论数*评论权重值+点赞数*点赞权重值进行排序
		$info=DI()->notorm->users_video->queryAll("select *, (comments *".$comment_weight." + likes *".$like_weight.") as weight from ".$prefix."users_video where isdel=0 and status=1  order by weight desc limit ".$start.",".$pnums);


		if(!$info){
			return 1001;
		}


		foreach ($info as $k => $v) {
			$info[$k]['userinfo']=$this->getUserInfo($v['uid']);
			$info[$k]['datetime']=$this->datetime($v['addtime']);
			$info[$k]['thumb']=$this->get_upload_path($v['thumb']);
			$info[$k]['thumb_s']=$this->get_upload_path($v['thumb_s']);
			$info[$k]['addtime']=date("Y-m-d H:i:s",$v['addtime']);
			if($uid<0){
				$info[$k]['islike']='0';
				$info[$k]['isattent']='0';

			}else{
				$info[$k]['islike']=(string)$this->ifLike($uid,$v['id']);
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


	public function getRecommendVideos($uid,$p){
      
      

		$pnums=50;
		$start=($p-1)*$pnums;
        
        //获取私密配置里的评论权重和点赞权重
		$configPri=$this->getConfigPri();
		 $ConfigPub=$this->getConfigPub();

        $ad_space=$ConfigPub['ad_space'];
		$comment_weight=$configPri['comment_weight'];
		$like_weight=$configPri['like_weight'];
		$share_weight=$configPri['share_weight'];

		$prefix= DI()->config->get('dbs.tables.__default__.prefix');
		$where=array();

		$readLists=DI()->redis->get('readvideo_'.$uid);
    

        
        
		if($readLists){
			$where=json_decode($readLists,true);
            //$where=implode(',',$where);
		}
		
       /* $uidlist=DI()->redis -> Get('video_list_'.$uid);
          //return 'uid:'.DI()->redis -> Get('video_list_');
        if($uidlist && !empty($uid)){
			$where_uid=json_decode($uidlist,true);
            //$where_uid=implode(',',$where_uid);
		}*/

        //$info=DI()->notorm->users_video->queryAll("select *,(ceil(comments * ".$comment_weight." + likes * ".$like_weight." + shares * ".$share_weight.") + show_val)* if(format(watch_ok/views,2) >1,'1',format(watch_ok/views,2)) as recomend from ".$prefix."users_video where isdel=0 and status=1 and id not in ({$where}) and uid not in ({$where_uid})  group by uid order by recomend desc,addtime desc limit 0,".$pnums);
        
		$info=DI()->notorm->users_video
            ->select("*,(ceil(comments * ".$comment_weight." + likes * ".$like_weight." + shares * ".$share_weight.") + show_val)* if(format(watch_ok/views,2) >1,'1',format(watch_ok/views,2)) as recomend")
            ->where("isdel=0 and status=1")
            //->where('not id',$where)
            ->where('not uid',$uid)
            ->order("RAND()")
            //->order("recomend desc,addtime desc")
            ->limit($start,$pnums)
            ->fetchAll();
    
        //return $info;
        $uid_list=array();
		foreach ($info as $k => $v) {
			$uid_list[]=$v['uid'];
			$info[$k]['userinfo']=$this->getUserInfo($v['uid']);
			$info[$k]['cate']=$this->getCateNameById(explode(',', $v['cate']));
			$info[$k]['datetime']=$this->datetime($v['addtime']);
			$info[$k]['thumb']=$this->get_upload_path($v['thumb']);
			$info[$k]['thumb_s']=$this->get_upload_path($v['thumb_s']);
			$info[$k]['addtime']=date("Y-m-d H:i:s",$v['addtime']);
			if($uid<0){
				$info[$k]['islike']='0';
				$info[$k]['isattent']='0';

			}else{
				$info[$k]['islike']=(string)$this->ifLike($uid,$v['id']);
				$info[$k]['isattent']=(string)$this->isAttention($uid,$v['uid']);

			//	$info[$k]['price']=$this->ifBuy($uid,$v['id'])==1?'0':$info[$k]['price'];
			}
		
			

			$info[$k]['musicinfo']=$this->getMusicInfo($info[$k]['userinfo']['user_nicename'],$v['music_id']);

			$info[$k]['href']=$this->get_upload_path($v['href']);	

			$info[$k]['isstep']='0'; //以下字段基本无用
			$info[$k]['isdialect']='0';
			unset($info[$k]['weight']);
			unset($info[$k]['is_urge']);
			unset($info[$k]['urge_nums']);
			unset($info[$k]['urge_money']);
			unset($info[$k]['big_urgenums']);
			unset($info[$k]['status']);
		}
		
		 //获取所有广告

        $ads=DI()->notorm->users_video
            ->select("*")
            ->where("is_ad=1 and isdel=0")
            ->fetchAll();

        foreach ($info as $k1 => $v1){
            $k1=$k1+1;
            if ($k1%$ad_space==0){
                $adnum=rand(0,count($ads)-1);
                $ad1= $ads[$adnum];

                $userinfo=$this->getUserInfo($ad1['uid']);
                if(!$userinfo){
                    $userinfo['user_nicename']="已删除";
                }


                //是否购买过 购买过则免费	jjj
                $isBuy=$this->ifBuy($uid,$ad1['id']);

                if($isBuy){
                    $ad1['price']=0;
                }

                //jjj
                $ad1['cate']=$this->getCateNameById(explode(',', $ad1['cate']));
                $ad1['userinfo']=$userinfo;
                $ad1['datetime']=$this->datetime($v1['addtime']);
                $ad1['comments']=$this->NumberFormat($v1['comments']);
                $ad1['likes']=$this->NumberFormat($v1['likes']);
                $ad1['steps']=$this->NumberFormat($v1['steps']);
                if($uid){
                    $ad1['islike']=(string)$this->ifLike($uid,$ad1['id']);
                    $ad1['isstep']=(string)$this->ifStep($uid,$ad1['id']);
                    $ad1['isattent']=(string)$this->isAttention($uid,$ad1['uid']);
                }else{
                    $ad1['islike']=0;
                    $ad1['isstep']=0;
                    $ad1['isattent']=0;
                }

                $ad1['musicinfo']=$this->getMusicInfo($ad1['userinfo']['user_nicename'],$ad1['music_id']);

                $ad1['thumb']=$this->get_upload_path($ad1['thumb']);
                $ad1['thumb_s']=$this->get_upload_path($ad1['thumb_s']);
                $ad1['href']=$this->get_upload_path($ad1['href']);


                $inf2[]=$ad1;
            }else{
                $inf2[]=$v1;
            }

        }

        if(!$inf2){
            $inf2=[];
        }
        
      

		return $inf2;

	}


	public function getRecommendVideosBF($uid,$p){
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
		//曝光值从视频发布开始，每小时递减1，直到0为止


		//$info=DI()->notorm->users_video->queryAll("select *,format(watch_ok/views,2) as aaa, (ceil(comments *".$comment_weight." + likes *".$like_weight." + shares *".$share_weight.") + show_val)*format(watch_ok/views,2) as recomend from ".$prefix."users_video where isdel=0 and status=1  order by recomend desc,addtime desc limit ".$start.",".$pnums);

		$info=DI()->notorm->users_video->queryAll("select *,(ceil(comments * ".$comment_weight." + likes * ".$like_weight." + shares * ".$share_weight.") + show_val)* if(format(watch_ok/views,2) >1,'1',format(watch_ok/views,2)) as recomend from ".$prefix."users_video where isdel=0 and status=1  order by recomend desc,addtime desc limit ".$start.",".$pnums);

		if(!$info){
			return 1001;
		}


		foreach ($info as $k => $v) {
			$info[$k]['userinfo']=$this->getUserInfo($v['uid']);
			$info[$k]['datetime']=$this->datetime($v['addtime']);
			$info[$k]['thumb']=$this->get_upload_path($v['thumb']);
			$info[$k]['thumb_s']=$this->get_upload_path($v['thumb_s']);
			$info[$k]['addtime']=date("Y-m-d H:i:s",$v['addtime']);
			if($uid<0){
				$info[$k]['islike']='0';
				$info[$k]['isattent']='0';

			}else{
				$info[$k]['islike']=(string)$this->ifLike($uid,$v['id']);
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
	}

	/*获取附近的视频*/
	public function getNearby($uid,$lng,$lat,$p){
		$pnum=20;
		$start=($p-1)*$pnum;

		$prefix= DI()->config->get('dbs.tables.__default__.prefix');

		$info=DI()->notorm->users_video->queryAll("select *, round(6378.138 * 2 * ASIN(SQRT(POW(SIN(( ".$lat." * PI() / 180 - lat * PI() / 180) / 2),2) + COS(".$lat." * PI() / 180) * COS(lat * PI() / 180) * POW(SIN((".$lng." * PI() / 180 - lng * PI() / 180) / 2),2))) * 1000) AS distance FROM ".$prefix."users_video  where uid !=".$uid." and isdel=0 and status=1 order by distance asc,addtime desc limit ".$start.",".$pnum);

		if(!$info){
			return 1001;
		}


		foreach ($info as $k => $v) {
			$info[$k]['userinfo']=$this->getUserInfo($v['uid']);
			$info[$k]['datetime']=$this->datetime($v['addtime']);
			$info[$k]['cate']=$this->getCateNameById(explode(',', $v['cate']));	
			$info[$k]['thumb']=$this->get_upload_path($v['thumb']);
			$info[$k]['thumb_s']=$this->get_upload_path($v['thumb_s']);
			$info[$k]['addtime']=date("Y-m-d H:i:s",$v['addtime']);
			if($uid<0){
				$info[$k]['islike']='0';
				$info[$k]['isattent']='0';

			}else{
				$info[$k]['islike']=(string)$this->ifLike($uid,$v['id']);
				$info[$k]['isattent']=(string)$this->isAttention($uid,$v['uid']);
			}


			$info[$k]['musicinfo']=$this->getMusicInfo($info[$k]['userinfo']['user_nicename'],$v['music_id']);

			$info[$k]['distance']=$this->distanceFormat($v['distance']);

			$info[$k]['href']=$this->get_upload_path($v['href']);

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
	}

	/* 举报分类列表 */
	public function getReportContentlist() {
		
		$reportlist=DI()->notorm->users_video_report_classify
					->select("*")
					->order("orderno asc")
					->fetchAll();
		if(!$reportlist){
			return 1001;
		}
		
		return $reportlist;
		
	}

	/*更新视频看完次数*/
	public function setConversion($uid,$videoid){


		//更新视频看完次数
		$res=DI()->notorm->users_video
				->where("id = '{$videoid}' and isdel=0 and status=1")
				->update( array('watch_ok' => new NotORM_Literal("watch_ok + 1") ) );


		if($uid<0){  //游客不加金币
			return 1001;
		}

		$videoinfo=DI()->notorm->users_video->select("uid")->where("id=?",$videoid)->fetchOne();

		if(!$videoinfo){
			return 1001;
		}

		if($uid==$videoinfo['uid']){ //自己看自己的不加
			return 1001;
		}

		//获取完整看完一个视频的reward_tacket
		$configPri=$this->getConfigPri();

		//给用户加金币
		DI()->notorm->users->where("id=?",$uid)->update(array('coin' => new NotORM_Literal("coin + '{$configPri['reward_tacket']}'") ));

		return 1;
	}

    public function checkOutVideo($type,$videoid){
        
        $isexist=DI()->notorm->users_video_out->select("id")->where("type=? and video=?",$type,$videoid)->fetchOne();
        if($isexist){
            return 1;
        }
       return 0;
        
    }

    public function setOutVideo($data){
        DI()->notorm->users_video_out->insert($data);
       return 0;
        
    }
	public function ceshi(){
		return $this->getUserIp();
	}

}
