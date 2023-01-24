<?php

class Api_Community extends Api_Common {

	public function getRules() {
		return array(
			'setCommunity' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
				'title' => array('name' => 'title', 'type' => 'string',  'desc' => '标题'),
				'img' => array('name' => 'img', 'type' => 'string',  'desc' => '图片集合'),
				'videolink' => array('name' => 'videolink', 'type' => 'string','desc' => '圈子链接集合'),
				'thumb' => array('name' => 'thumb', 'type' => 'string','desc' => '视频封面'),
				'city' => array('name' => 'city', 'type' => 'string',  'desc' => '城市'),
				'content' => array('name' => 'content', 'type' => 'string','require' => true, 'desc' => '圈子内容'),
				'lat' => array('name' => 'lat', 'type' => 'string',  'desc' => '维度'),
				'lng' => array('name' => 'lng', 'type' => 'string',  'desc' => '经度'),
				'cate' => array('name' => 'cate', 'type' => 'string','require' => true, 'desc' => '圈子类型(标签)'),
				
			),
            'setComment' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
				'communityid' => array('name' => 'communityid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '圈子ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'default'=>0, 'desc' => '回复的评论UID'),
                'commentid' => array('name' => 'commentid', 'type' => 'int',  'default'=>0,  'desc' => '回复的评论commentid'),
                'parentid' => array('name' => 'parentid', 'type' => 'int',  'default'=>0,  'desc' => '回复的评论ID'),
                'content' => array('name' => 'content', 'type' => 'string',  'default'=>'', 'desc' => '内容'),
                'at_info'=>array('name'=>'at_info','type'=>'string','desc'=>'被@的用户json信息'),
            ),
            'addView' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true, 'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
                'communityid' => array('name' => 'communityid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '圈子ID'),
                'random_str'=>array('name' => 'random_str', 'type' => 'string', 'require' => true, 'desc' => '加密串'),

            ),
            'addLike' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
                'communityid' => array('name' => 'communityid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '圈子ID'),
            ),
			//收藏
			 'collectCommunity'=>array(
                'uid'=>array('name'=>'uid','type' => 'int','require' => true,'desc' => '用户id'),
                'token'=>array('name'=>'token','type' => 'string','require' => true,'desc' => '用户token'),
                'communityid'=>array('name'=>'communityid','type' => 'int','require' => true,'desc' => '圈子id'),
            ),

			 'getCollectLists'=>array(
                'uid'=>array('name'=>'uid','type' => 'int','require' => true,'desc' => '用户id'),
                'token'=>array('name'=>'token','type' => 'string','require' => true,'desc' => '用户token'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),
            ),
			
			'addShare' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int',  'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
                'communityid' => array('name' => 'communityid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '圈子ID'),
                'random_str'=>array('name' => 'random_str', 'type' => 'string', 'require' => true, 'desc' => '加密串'),
            ),
			
			'setBlack' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
                'communityid' => array('name' => 'communityid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '圈子ID'),
            ),
			
			'addCommentLike' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
                'commentid' => array('name' => 'commentid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '评论/回复 ID'),
            ),
            'getCommunityList' => array(
            	'uid'=>array('name'=>'uid','type' => 'int','desc' => '用户id'),
            	'cid' => array('name' => 'cid', 'type' => 'int',  'desc' => '分类ID'),
            	'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            	'order' => array('name' => 'order', 'type' => 'int', 'min' => 0, 'default'=>0, 'desc' => '圈子排序')
            	
            ),
            'getAttentionCommunity' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
            	'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
            	'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),
            'getCommunity' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
                'communityid' => array('name' => 'communityid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '圈子ID'),
            ),
            'getComments' => array(
                'uid' => array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
                'communityid' => array('name' => 'communityid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '圈子ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),
			
			'getReplys' => array(
				'uid' => array('name' => 'uid', 'type' => 'int',  'require' => true, 'desc' => '用户ID'),
                'commentid' => array('name' => 'commentid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '评论ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),
			
			'getMyCommunity' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),
            'del' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => 'token'),
                'communityid' => array('name' => 'communityid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '圈子ID'),
            ),
			
			'report' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => 'token'),
                'communityid' => array('name' => 'communityid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '圈子ID'),
                'content' => array('name' => 'content', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => '举报内容'),
            ),
			
			'getHomeCommunity' => array(
                'uid' => array('name' => 'uid', 'type' => 'int',  'desc' => '用户ID'),
                'touid' => array('name' => 'touid', 'type' => 'int', 'require' => true, 'desc' => '对方ID'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),
			'getCreateNonreusableSignature' => array(
                'imgname' => array('name' => 'imgname', 'type' => 'string', 'desc' => '图片名称'),
                'Communityname' => array('name' => 'Communityname', 'type' => 'string', 'desc' => '圈子名称'),
				'folderimg' => array('name' => 'folderimg', 'type' => 'string','desc' => '图片文件夹'),
				'folderCommunity' => array('name' => 'folderCommunity', 'type' => 'string', 'desc' => '圈子文件夹'),
            ),


            'getRecommendCommunitys'=>array(
            	'uid' => array('name' => 'uid', 'type' => 'int',  'desc' => '用户ID'),
            	'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),

            'getNearby'=>array(
            	'uid' => array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
                'lng' => array('name' => 'lng', 'type' => 'string', 'desc' => '经度值'),
                'lat' => array('name' => 'lat', 'type' => 'string','desc' => '纬度值'),
				'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),



            'getOutCommunityUrl'=>array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => 'token'),
            	'url' => array('name' => 'url', 'type' => 'string', 'require' => true, 'desc' => '外链地址'),
            ),

			'addStep' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'communityid' => array('name' => 'communityid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '圈子ID'),
            ),
            
		);
	}
	
	/**
	 * 发布社区热点
	 * @desc 用于发布社区热点
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].id 圈子记录ID
	 * @return string msg 提示信息
	 */
	public function setCommunity() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());


		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		$title=$this->checkNull($this->title);
		$img=$this->checkNull($this->img);
		$videolink=$this->checkNull($this->videolink);
		$thumb=$this->checkNull($this->thumb);
		$city=$this->checkNull($this->city);
		$content=$this->checkNull($this->content);
		$cate=$this->checkNull($this->cate);
		$lat=$this->checkNull($this->lat);
		$lng=$this->checkNull($this->lng);

		if(strpos($cate,',') !==false){
			$cate=explode(',',$cate);
		}
		//分类名获取id
		$domain_Cate = new Domain_Category();
        $cate = $domain_Cate->gatCateIdByName($cate,1);
       
    
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
		$thumb = $this->get_path($thumb);
		$videolink = $this->get_path($videolink);
		$img = $this->get_path($img);
		if(($videolink && !$thumb) || (!$videolink && $thumb)){
			$rs['code'] = 600;
			$rs['msg'] = '获取视频链接或封面失败';
			return $rs;
		}else{
			if($videolink && $thumb){
				$videolink = $thumb.','.$videolink;
			}else{
				$videolink = '';
			}
			
		}
	

		$data=array(
			"uid"=>$uid,
			"title"=>$title,
			"imgs"=>$img,
			"lat"=>$lat,
			"lng"=>$lng,
			"videolink"=>$videolink,
			"city"=>$city,
			"content"=>$content,
			"cate"=>$cate,
			"addtime"=>time(),
		);

		$configPri=$this->getConfigPri();
		
		$domain = new Domain_Community();
		$info = $domain->setCommunity($data);
		if(!$info){
			$rs['code']=1001;
			$rs['msg']='发布失败';
		}
		foreach(explode(',',$cate) as $k => $v) {
			DI()->redis->sadd('getSheQuId_'.$v,$info['id']);
		}
      
		
		if($configPri['community_audit_switch']==0){
			$rs['msg']="发布成功";
		}else{
			$rs['msg']="发布成功,请等待审核";
		}
		
		return $rs;
	}		
	
   	/**
     * 评论/回复
     * @desc 用于用户评论/回复 别人圈子
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return int info[0].isattent 对方是否关注我
     * @return int info[0].u2t 我是否拉黑对方
     * @return int info[0].t2u 对方是否拉黑我
     * @return int info[0].comments 评论总数
     * @return int info[0].replys 回复总数
     * @return string msg 提示信息
     */
	public function setComment() {
        $rs = array('code' => 0, 'msg' => '评论成功', 'info' => array());
		
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		$touid=$this->touid;
		$communityid=$this->communityid;
		$commentid=$this->commentid;
		$parentid=$this->parentid;
		$content=$this->checkNull($this->content);
		$at_info=$this->at_info;

		//$arr = json_decode($at_info,true);
		if(!$at_info){
			$at_info='';
		}

		// $isVip = $this->getUserVip($uid);
		// if(!$isVip['type']){
		// 	$rs['code'] = 600;
		// 	$rs['msg'] = '非VIP用户不能评论！';
		// 	return $rs;
		// }

		$userinfo = $this->getUserInfo($uid);
		if(!$this->checkMoblieIsExist($userinfo['user_login'])){
			$rs['code'] = 800;
			$rs['msg'] = '请绑定手机号后评论！';
			return $rs;
		}
		if(strlen($content) >600){
			$rs['code'] = 900;
			$rs['msg'] = '评论内容过长';
			return $rs;
		}

		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 800;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
		if($touid>0){
			$isattent=$this->isAttention($touid,$uid);
			$u2t = $this->isBlack($uid,$touid);
			$t2u = $this->isBlack($touid,$uid);
			if($t2u==1){
				$rs['code'] = 1000;
				$rs['msg'] = '对方暂时拒绝接收您的消息';
				return $rs;
			}
		
		}
		
		if($commentid==0 && $commentid!=$parentid){
			$commentid=$parentid;
		}
		
		$data=array(
			'uid'=>$uid,
			'touid'=>$touid,
			'communityId'=>$communityid,
			'commentid'=>$commentid,
			'parentid'=>$parentid,
			'content'=>$content,
			'addtime'=>time(),
			'at_info'=>$at_info
		);

		/*var_dump($data);
		die;*/

        $domain = new Domain_Community();
        $result = $domain->setComment($data);
		

		
		$info=array(
			'isattent'=>'0',
			'u2t'=>'0',
			't2u'=>'0',
			'comments'=>$result['comments'],//总评论数
			'replys'=>$result['replys'],//回复数量
		);
		if($touid>0){
			$isattent=$this->isAttention($touid,$uid);
			$u2t = $this->isBlack($uid,$touid);
			$t2u = $this->isBlack($touid,$uid);
			
			$info['isattent']=(string)$isattent;
			$info['u2t']=(string)$u2t;
			$info['t2u']=(string)$t2u;
		}
		
		$rs['info'][0]=$info;
		
		if($parentid!=0){
			 $rs['msg']='回复成功';			
		}
        return $rs;
    }	
	
   	/**
     * 阅读
     * @desc 用于圈子阅读数累计
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function addView() {
        $rs = array('code' => 0, 'msg' => '更新圈子阅读次数成功', 'info' => array());

		$uid=$this->uid;
		$userinfo = $this->getUserInfo($uid);
		$token=$this->checkNull($this->token);
		$communityid=$this->communityid;
		$random_str=$this->checkNull($this->random_str);

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

		//md5加密验证字符串
		$str=md5($uid.'-'.$communityid.'-'.'#2hgfk85cm23mk58vncsark');

		if($random_str!==$str){
			$rs['code'] = 1001;
			$rs['msg'] = '更新圈子阅读次数失败';
			return $rs;
		}

		
        $domain = new Domain_Community();
        $res = $domain->addView($this->uid,$this->communityid);

        return $rs;
    }	
   	/**
     * 点赞
     * @desc 用于圈子点赞数累计
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].islike 是否点赞 
     * @return string info[0].likes 点赞数量
     * @return string msg 提示信息
     */
	public function addLike() {

        $rs = array('code' => 0, 'msg' => '点赞成功', 'info' => array());
        $uid=$this->uid;
		$token=$this->checkNull($this->token);

		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 800;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

	
		
        $domain = new Domain_Community();
        $result = $domain->addLike($uid,$this->communityid);
		if($result==1001){
			$rs['code'] = 1001;
			$rs['msg'] = "圈子已删除";
			return $rs;
		}else if($result==1002){
			$rs['code'] = 1002;
			$rs['msg'] = "不能给自己点赞";
			return $rs;
		}
		$rs['info'][0]=$result;
        return $rs;
    }	

   /**
     * 收藏圈子/取消收藏
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function collectCommunity(){
        $rs = array('code' => 0, 'msg' => '', 'info' =>array());
        $uid=$this->checkNull($this->uid);
        $token=$this->checkNull($this->token);
        $communityid=$this->checkNull($this->communityid);
		$checkToken=$this->checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }else if($checkToken==10020){
            $rs['code'] = 800;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }



        $domain=new Domain_Community();
        $res=$domain->collectCommunity($uid,$communityid);

        if($res==1001){
            $rs['code']=1001;
            $rs['msg']='该圈子已删除';
            return $rs;
        }

        if($res==200){
            $rs['msg']="取消收藏成功";
            $rs['info'][0]['iscollect']=0;
            return $rs;
        }

        if($res==201){
            $rs['code']=1002;
            $rs['msg']="取消收藏失败";
            return $rs;
        }

        if($res==300){
            $rs['msg']="收藏成功";
            $rs['info'][0]['iscollect']=1;
            return $rs;
        }

        if($res==301){
            $rs['code']=1002;
            $rs['msg']="收藏失败";
            return $rs;
        }


    }

    /**
     * 获取用户收藏圈子列表
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回数据
     */
    public function getCollectLists(){

        $rs = array('code' => 0, 'msg' => '', 'info' =>array());
        $uid=$this->checkNull($this->uid);
        $p=$this->checkNull($this->p);
        $token=$this->checkNull($this->token);
        $checkToken=$this->checkToken($uid,$token);
        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }else if($checkToken==10020){
            $rs['code'] = 800;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }

        $domain=new Domain_Community();
        $res=$domain->getCollectLists($uid,$p);
        if($res==0){
            $rs['code']=0;
            $rs['msg']="暂无收藏圈子";
            return $rs;
        }

        $rs['info']=$res;

        return $rs;

    }

   	/**
     * 圈子分享
     * @desc 用于圈子分享数累计
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].isshare 是否分享
     * @return string info[0].shares 分享数量
     * @return string msg 提示信息
     */
	public function addShare() {
        $rs = array('code' => 0, 'msg' => '分享成功', 'info' => array());

        $uid=$this->uid;
        //$token=$this->checkNull($this->token);
		$Communityid=$this->Communityid;
		$random_str=$this->checkNull($this->random_str);

		/*$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}*/

		//md5加密验证字符串
		$str=md5($uid.'-'.$Communityid.'-'.'#2hgfk85cm23mk58vncsark');

		if($random_str!==$str){
			$rs['code'] = 1001;
			$rs['msg'] = '圈子分享数修改失败';
			return $rs;
		}
		
        $domain = new Domain_Community();
        $rs['info'][0] = $domain->addShare($uid,$Communityid);

        return $rs;
    }	

   	/**
     * 拉黑圈子
     * @desc 用于拉黑圈子
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].isblack 是否拉黑
     * @return string msg 提示信息
     */
	public function setBlack() {
        $rs = array('code' => 0, 'msg' => '操作成功', 'info' => array());
		$isBlackUser=$this->isBlackUser($this->uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

        $domain = new Domain_Community();
        $rs['info'][0] = $domain->setBlack($this->uid,$this->Communityid);

        return $rs;
    }	
	
   	/**
     * 评论/回复 点赞
     * @desc 用于评论/回复 点赞数累计
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].islike 是否点赞 
     * @return string info[0].likes 点赞数量
     * @return string msg 提示信息
     */
	public function addCommentLike() {

        $rs = array('code' => 0, 'msg' => '点赞成功', 'info' => array());

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

        $domain = new Domain_Community();
         $res= $domain->addCommentLike($uid,$this->commentid);
         if($res==1001){
         	$rs['code']=1001;
         	$rs['msg']='评论信息不存在';
         	return $rs;
         }
         $rs['info'][0]=$res;
        return $rs;
    }	
	/**
     * 获取最新圈子列表
     * @desc 用于获取最新圈子列表
     * @return int code 操作码，0表示成功
     * @return array result 圈子列表
     * @return object result[].userinfo 用户信息
     * @return string result[].datetime 格式后的发布时间
     * @return string result[].islike 是否点赞
     * @return string result[].isattent 是否关注
     * @return string result[].img 封面小图，分享用
     * @return string result[].comments 评论总数
     * @return string result[].likes 点赞数
     * @return string msg 提示信息
     */
	public function getCommunityList() {

		
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		

		

        $domain = new Domain_Community();
        $result= $domain->getCommunityList($this->uid,$this->cid,$this->p,$this->order);
		if($result==10010){
			$rs['code'] = 0;
			$rs['msg'] = "暂无圈子列表";
			return $rs;
		}
		$rs['info'] =$result;
        return $rs;
    }	
	/**
     * 获取关注圈子
     * @desc 用于获取关注圈子
     * @return int code 操作码，0表示成功
     * @return array info 圈子列表
     * @return array info[].userinfo 用户信息
     * @return string info[].datetime 格式后的发布时间
	 * @return string info[].islike 是否点赞 
	 * @return string info[].comments 评论总数
     * @return string info[].likes 点赞数
     * @return string msg 提示信息
     */
	public function getAttentionCommunity() {
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

		$isBlackUser=$this->isBlackUser($uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		$key='attention_vidseoLists_'.$uid.'_'.$p;
        $info=$this->getcache($key);

        if(!$info){
        	$domain = new Domain_Community();
        	$info=$domain->getAttentionCommunity($uid,$this->p);
        	if($info==0){
        		 $rs['code']=0;
                $rs['msg']="暂无圈子列表";
                return $rs;
        	}
        }
        
        $rs['info'] = $info;

        return $rs;
    }		
	/**
     * 圈子详情
     * @desc 用于获取圈子详情
     * @return int code 操作码，0表示成功，1000表示圈子不存在
     * @return array info[0] 圈子详情
     * @return object info[0].userinfo 用户信息
     * @return string info[0].datetime 格式后的时间差
     * @return string info[0].isattent 是否关注
     * @return string info[0].likes 点赞数
     * @return string info[0].comments 评论数
     * @return string info[0].views 阅读数
     * @return string info[0].steps 踩一踩数量
     * @return string info[0].shares 分享数量
     * @return string info[0].islike 是否点赞
     * @return string info[0].isstep 是否踩
     * @return string msg 提示信息
     */
	public function getCommunity() {
		
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $domain = new Domain_Community();
        $result = $domain->getCommunity($this->uid,$this->communityid);
		if($result==1000){
			$rs['code'] = 1000;
			$rs['msg'] = "圈子已删除";
			return $rs;
			
		}
		$rs['info'][0]=$result;

        return $rs;
    }
	/**
     * 圈子评论列表
     * @desc 用于获取圈子评论列表
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].comments 评论总数
     * @return array info[0].commentlist 评论列表
     * @return object info[0].commentlist[].userinfo 用户信息
	 * @return string info[0].commentlist[].datetime 格式后的时间差
	 * @return string info[0].commentlist[].replys 回复总数
	 * @return string info[0].commentlist[].likes 点赞数
	 * @return string info[0].commentlist[].islike 是否点赞
     * @return string msg 提示信息
     */
	public function getComments() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		$isBlackUser=$this->isBlackUser($this->uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

        $domain = new Domain_Community();
        $rs['info'][0] = $domain->getComments($this->uid,$this->communityid,$this->p);

        return $rs;
    }	
	
	/**
     * 回复列表
     * @desc 用于获取圈子评论列表
     * @return int code 操作码，0表示成功
     * @return array info 评论列表
     * @return object info[].userinfo 用户信息
	 * @return string info[].datetime 格式后的时间差
	 * @return object info[].tocommentinfo 回复的评论的信息
	 * @return object info[].tocommentinfo.content 评论内容
	 * @return string info[].likes 点赞数
	 * @return string info[].islike 是否点赞
     * @return string msg 提示信息
     */
	public function getReplys() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		$isBlackUser=$this->isBlackUser($this->uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

        $domain = new Domain_Community();
        $rs['info'] = $domain->getReplys($this->uid,$this->commentid,$this->p);

        return $rs;
    }	
	
	
	/**
     * 我的圈子
     * @desc 用于获取我发布的圈子
     * @return int code 操作码，0表示成功
     * @return array info 圈子列表
     * @return array info[].userinfo 用户信息
     * @return string info[].datetime 格式后的发布时间
     * @return string info[].islike 是否点赞
     * @return string msg 提示信息
     */
	public function getMyCommunity() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		$p=$this->p;
		
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

        $domain = new Domain_Community();
        $rs['info'] = $domain->getMyCommunity($uid,$p);

        return $rs;
    }	
	
	/**
     * 删除圈子
     * @desc 用于删除圈子以及相关信息
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function del() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		$Communityid=$this->Communityid;

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
		
        $domain = new Domain_Community();
        $info = $domain->del($uid,$Communityid);

        return $rs;
    }	

	/**
     * 举报圈子
     * @desc 用于删除圈子以及相关信息
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function report() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		$Communityid=$this->Communityid;
		$content=$this->checkNull($this->content);
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
		$data=array(
			'uid'=>$uid,
			'communityid'=>$Communityid,
			'content'=>$content,
			'addtime'=>time(),
		);
        $domain = new Domain_Community();
        $info = $domain->report($data);
		
		if($info==1000){
			$rs['code'] = $checkToken;
			$rs['msg'] = '圈子不存在';
			return $rs;
		}

        return $rs;
    }	


	/**
     * 个人主页圈子
     * @desc 用于获取个人主页圈子
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function getHomeCommunity() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		$isBlackUser=$this->isBlackUser($this->uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
		$uid=$this->uid;
		$touid=$this->touid;
		$p=$this->p;

        $domain = new Domain_Community();
        $info = $domain->getHomeCommunity($uid,$touid,$p);
		
		
		$rs['info']=$info;

        return $rs;
    }	
	
	/* 检测文件后缀 */
	public function checkExt($filename){
		$config=array("jpg","png","jpeg");
		$ext   =   pathinfo(strip_tags($filename), PATHINFO_EXTENSION);
		 
		return empty($config) ? true : in_array(strtolower($ext), $config);
	}	
	
	/**
     * 获取七牛上传Token
     * @desc 用于删除圈子以及相关信息
     * @return int code 操作码，0表示成功
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function getQiniuToken(){
	
	   	$rs = array('code' => 0, 'msg' => '', 'info' =>array());

	   	//获取后台配置的腾讯云存储信息
		$configPri=$this->getConfigPri();
	  
		$token = DI()->qiniu->getQiniuToken($configPri['qiniu_accesskey'],$configPri['qiniu_secretkey'],$configPri['qiniu_bucket']);
		$rs['info'][0]['token']=$token ; 
		return $rs; 
		
	}
	/**
     * 获取腾讯云上传图片签名
     * @desc 用于删除圈子以及相关信息
     * @return int code 操作码，0表示成功
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function getCreateReusableSignatureImg(){
	
	   	$rs = array('code' => 0, 'msg' => '', 'info' =>array());
	
		require(API_ROOT.'/public/txcloud/include.php');
		
		//获取后台配置的腾讯云存储信息
		$configPri=$this->getConfigPri();


		$bucket = $configPri['txcloud_bucket'];
        
		//$config=DI()->config->get('app.TxCloud.config');
		

		$filepath = '/test1/9261c8066f05a46903f3d2341e8203cd.jpg';
		$expiration = time() + 3600; 
		$auth = new \QCloud\Cos\Auth($configPri['txcloud_appid'], $configPri['txcloud_secret_id'], $configPri['txcloud_secret_key']);

		$signature = $auth->createReusableSignature($expiration, $bucket, $filepath);
		 
		$rs['info'][0]['signature']=$signature;

		return $signature;
		
	}
	/**
     *有效签名
     * @return string 
     */
    public function getCreateNonreusableSignature(){
    	
		$rs = array('code' => 0, 'msg' => '', 'info' =>array());
		$imgname=$this->imgname;
		$Communityname=$this->Communityname;
		$folderimg=$this->folderimg;
		$folderCommunity=$this->folderCommunity;
		
		require(API_ROOT.'/public/txcloud/include.php');
	
		//无用$config=DI()->config->get('app.TxCloud.config');

		$configPri=$this->getConfigPri();

		$bucketname=$configPri['txcloud_bucket'];
	
		$auth = new \QCloud\Cos\Auth($configPri['txcloud_appid'], $configPri['txcloud_secret_id'], $configPri['txcloud_secret_key']);


		if($imgname){
			$filepathimg="/".$folderimg."/".$imgname;
			
			$signatureimg = $auth->createNonreusableSignature($bucketname, $filepathimg);
		}
		if($Communityname){
			$filepathCommunity="/".$folderCommunity."/".$Communityname;
			$signatureCommunity = $auth->createNonreusableSignature($bucketname, $filepathCommunity);
		} 
		$data=array(
			"imgsign"=>$signatureimg,
			"Communitysign"=>$signatureCommunity,
			"appid"=>$configPri['txcloud_appid'],  //腾讯云appid
			"region"=>$configPri['txcloud_region'], //腾讯云存储buctet所属地域
			"bucketname"=>$configPri['txcloud_bucket'], //腾讯云存储桶
		);


		$rs['info']=$data; 
        return $rs;	
    }

    /**
     * 获取推荐圈子
     * @desc 用户获取推荐圈子
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     * @return string info[0].id 圈子id
     * @return string info[0].uid 圈子发布者id
     * @return string info[0].title 圈子标题
     * @return string info[0].thumbs 圈子封面
     * @return string info[0].thumbs 圈子小封面
     * @return string info[0].href 圈子链接
     * @return string info[0].likes 圈子被喜欢总数
     * @return string info[0].views 圈子被观看总数
     * @return string info[0].comments 圈子评论总数
     * @return string info[0].steps 圈子被踩总数
     * @return string info[0].shares 圈子分享总数
     * @return string info[0].addtime 圈子发布时间
     * @return string info[0].lat 纬度
     * @return string info[0].lng 经度
     * @return string info[0].city 城市
     * @return string info[0].isdel 是否删除
     * @return string info[0].datetime 圈子发布时间格式化
     * @return string info[0].islike 是否喜欢了该圈子
     * @return string info[0].isattent 是否关注
     * @return string info[0].isstep 是否踩了该圈子
     * @return string info[0].isdialect 是否方言秀
     * @return array info[0].userinfo 圈子发布者信息
     * @return string info[0].userinfo.id 圈子发布者id
     * @return string info[0].userinfo.user_nicename 圈子发布者昵称
     * @return string info[0].userinfo.avatar 圈子发布者头像
     * @return string info[0].userinfo.coin 圈子发布者钻石
     * @return string info[0].userinfo.avatar_thumb 圈子发布者小头像
     * @return string info[0].userinfo.sex 圈子发布者性别
     * @return string info[0].userinfo.signature 圈子发布者签名
     * @return string info[0].userinfo.privince 圈子发布者省份
     * @return string info[0].userinfo.city 圈子发布者市
     * @return string info[0].userinfo.birthday 圈子发布者生日
     * @return string info[0].userinfo.age 圈子发布者年龄
     * @return string info[0].userinfo.praise 圈子发布者被赞总数
     * @return string info[0].userinfo.fans 圈子发布者粉丝数
     * @return string info[0].userinfo.follows 圈子发布者关注数
     * @return array info[0].musicinfo 背景音乐信息
     * @return array info[0].musicinfo.id 背景音乐id
     * @return array info[0].musicinfo.title 背景音乐标题
     * @return array info[0].musicinfo.author 背景音乐作者
     * @return array info[0].musicinfo.img_url 背景音乐封面地址
     * @return array info[0].musicinfo.length 背景音乐长度
     * @return array info[0].musicinfo.file_url 背景音乐地址
     * @return array info[0].musicinfo.use_nums 背景音乐使用次数
     */
    public function getRecommendCommunitys(){
    	$rs = array('code' => 0, 'msg' => '', 'info' => array());

    	$uid=$this->uid;
    	if($uid>0){ //非游客

    		$isBlackUser=$this->isBlackUser($this->uid);
			if($isBlackUser=='0'){
				$rs['code'] = 700;
				$rs['msg'] = '该账号已被禁用';
				return $rs;
			}
    	}
		

		$p=$this->p;


		/* $key='CommunityRecommend_'.$p;

		$info=$this->getcache($key);

		if(!$info){ */

			$domain=new Domain_Community();
			$info=$domain->getRecommendCommunitys($uid,$p);

			if($info==1001){
				$rs['code']=1001;
				$rs['msg']="暂无圈子列表";
				return $rs;
			}

			/* $this->setcaches($key,$info,2);

		} */

		$rs['info']=$info;

		return $rs;
    }



	public function test(){
	
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
        $domain = new Domain_Community();
        $info = $domain->test();
		
	
		$rs['info']=$info;

        return $rs;
	}

	/**
	 * 获取附近的圈子列表
	 * @desc 用于获取附近的圈子列表
	 * @return int code 状态码，0表示成功
	 * @return string msg 提示信息
	 * @return array info 返回信息
	 */
	public function getNearby(){
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$lng=$this->checkNull($this->lng);
		$lat=$this->checkNull($this->lat);
		$p=$this->checkNull($this->p);

		if($lng==''){
			return $rs;
		}
		
		if($lat==''){
			return $rs;
		}
		
		if(!$p){
			$p=1;
		}

		$key='communityNearby_'.$lng.'_'.$lat.'_'.$p;

		$info=$this->getcache($key);

		if(!$info){
			$domain = new Domain_Community();
			$info = $domain->getNearby($uid,$lng,$lat,$p);

			if($info==1001){
				return $rs;
			}
			
			$this->setcaches($key,$info,300);
		}

		$rs['info'] = $info;
        return $rs;
	}

	/**
     * 获取圈子举报分类列表
     * @desc 获取圈子举报分类列表
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
	public function getReportContentlist() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $domain = new Domain_Community();
        $res = $domain->getReportContentlist();

        if($res==1001){
        	$rs['code']=1001;
        	$rs['msg']='暂无举报分类列表';
        	return $rs;
        }
        $rs['info']=$res;
        return $rs;
    }

   

    /**
     * 获取非水印圈子地址
     * @desc 用户获取非水印圈子地址
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info  返回信息
     */
    public function getOutCommunityUrl(){

    	$rs = array('code' => 0, 'msg' => '地址获取成功', 'info' => array());

    	$uid=$this->uid;
		$token=$this->checkNull($this->token);
		$url=$this->url;

    	if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		if($url==""){
			$rs['code'] = 1001;
			$rs['msg'] = '请提供链接地址';
			return $rs;
		}

		$iiiLabCommunityDownloadURL = "http://service.iiilab.com/Community/download";   //iiiLab通用圈子解析接口
		$configPri=$this->getConfigPri();
		$client=$configPri['iiilab_client'];
		$clientSecretKey=$configPri['iiilab_key'];


		$timestamp = time() * 1000;
		$sign = md5($url . $timestamp . $clientSecretKey);

		$post=array("link" => $url, "timestamp" => $timestamp, "sign" => $sign, "client" => $client);

		$options = array(
            "http"=> array(
                "method"=>"POST",
                "header" => "Content-type: application/x-www-form-urlencoded",
                "content"=> http_build_query($post)
            ),
	    );

	    $result = file_get_contents($iiiLabCommunityDownloadURL,false, stream_context_create($options));



	    if($result==""||$result==false){
	    	$rs['code'] = 1002;
			$rs['msg'] = '链接地址获取失败';
			return $rs;
	    }

	    $resObj=json_decode($result,true);
        
        if($resObj['retCode']!=200){
            $rs['code'] = 1004;
			$rs['msg'] = $resObj['retDesc'];
			return $rs;
        }
        
        
        /* 判断圈子是否重复 */
        $type=0;
        $Communityid='';
        $Community_url=$resObj['data']['Community'];
        
        file_put_contents(API_ROOT.'/Runtime/checkOutCommunity'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 url:'.json_encode($url)."\r\n",FILE_APPEND);
        file_put_contents(API_ROOT.'/Runtime/checkOutCommunity'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 Community_url:'.json_encode($Community_url)."\r\n",FILE_APPEND);
        
        if(strstr($url,'douyin')){
            $type=1;
            
            $url_a=preg_split("/#|\?/",$Community_url);
            $url_b=preg_split('/\//',$url_a[0]);
            $Communityid=$url_b[count($url_b)-2];
            
        }else if(strstr($url,'huoshan')){
            $type=2;
            
            $url_a=preg_split("/\?/",$Community_url);
            $queryParts = explode('&', $url_a[1]);
            $params = array();
            foreach ($queryParts as $param) {
                $item = explode('=', $param);
                $params[$item[0]] = $item[1];
            }

            $Communityid=$params['Community_id'];

            
        }else if(strstr($url,'gifshow')){
            $type=3;
            
            $url_a=preg_split("/\?/",$Community_url);
            $url_b=preg_split('/\//',$url_a[0]);

            $url_c=preg_split('/\./',$url_b[count($url_b)-1]);

            array_pop($url_c);
            $url_d=implode(".",$url_c);
            
            $Communityid=$url_d;
            
        }else if(strstr($url,'meipai')){
            $type=4;
            
            $url_a=preg_split("/\?/",$Community_url);
            $url_b=preg_split('/\//',$url_a[0]);

            $url_c=preg_split('/\./',$url_b[count($url_b)-1]);

            array_pop($url_c);
            $url_d=implode(".",$url_c);
            
            $Communityid=$url_d;
            
        }else if(strstr($url,'weishi')){
            $type=5;
            
            $url_a=preg_split("/\?/",$Community_url);
            $url_b=preg_split('/\//',$url_a[0]);

            $url_c=preg_split('/\./',$url_b[count($url_b)-1]);

            array_pop($url_c);
            $url_d=implode(".",$url_c);
            
            $Communityid=$url_d;
        }
        
        
        if($Communityid){
            
            file_put_contents(API_ROOT.'/Runtime/checkOutCommunity'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 type:'.json_encode($type)."\r\n",FILE_APPEND);
            file_put_contents(API_ROOT.'/Runtime/checkOutCommunity'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 Communityid:'.json_encode($Communityid)."\r\n",FILE_APPEND);
            
            $domain = new Domain_Community();
            $res = $domain->checkOutCommunity($type,$Communityid);
            file_put_contents(API_ROOT.'/Runtime/checkOutCommunity'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 res:'.json_encode($res)."\r\n",FILE_APPEND);
            if($res){
                $rs['code'] = 1003;
                $rs['msg'] = '该圈子已被使用';
                return $rs; 
            }
        }
        

	    //$rs['info'][0]=$resObj;
	    $rs['info'][0]['Community']=$resObj['data']['Community'];
	    $rs['info'][0]['type']=(string)$type;
	    $rs['info'][0]['Communityid']=$Communityid;
	    return $rs;

    }

    public function ceshi(){
    	$rs = array('code' => 0, 'msg' => '', 'info' => array());
    	$domain = new Domain_Community();
        $res = $domain->ceshi();

        $rs['info']=long2ip($res);
        return $rs;
    }


    	/**
     * 踩一下
     * @desc 用于圈子踩数累计
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].isstep 是否踩
     * @return string info[0].steps 踩数量
     * @return string msg 提示信息
     */
	public function addStep() {
        $rs = array('code' => 0, 'msg' => '踩一踩成功', 'info' => array());
		$isBlackUser=$this->isBlackUser($this->uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

        $domain = new Domain_Community();
        $rs['info'][0] = $domain->addStep($this->uid,$this->Communityid);

        return $rs;
    }


}
