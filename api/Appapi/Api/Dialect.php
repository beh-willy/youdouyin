<?php
/****
**方言秀
***/
class Api_Dialect extends Api_Common {

	public function getRules() {
		return array(
			'getDialect' => array(
            ),
			'getDialectmaterial' => array(
            ),
			'setDialectshow' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
				'title' => array('name' => 'title', 'type' => 'string',  'desc' => '标题'),
				'thumb' => array('name' => 'thumb', 'type' => 'string',  'require' => true, 'desc' => '封面图'),
				'href' => array('name' => 'href', 'type' => 'string',  'require' => true, 'desc' => '视频链接'),
				'lat' => array('name' => 'lat', 'type' => 'string',  'desc' => '纬度'),
				'lng' => array('name' => 'lng', 'type' => 'string',  'desc' => '经度'),
				'city' => array('name' => 'city', 'type' => 'string',  'desc' => '城市'),
				'dialect_type' => array('name' => 'dialect_type', 'type' => 'int', 'min' => 1, 'desc' => '方言类型'),
				'dialect_material_id' => array('name' => 'dialect_material_id', 'type' => 'int', 'min' => 1, 'desc' => '方言素材视频ID'),
				'urgenum' => array('name' => 'urgenum', 'type' => 'int', 'desc' => '用户设置催更人数'),
			),
			 'addLike' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'dialectid' => array('name' => 'dialectid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
                'type' => array('name' => 'type', 'type' => 'int', 'desc' => '点赞是否免费：0：免费；1：付费'),
            ),
			
            'setComment' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
				'dialectid' => array('name' => 'dialectid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '方言秀视频ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'default'=>0, 'desc' => '回复的评论UID'),
                'commentid' => array('name' => 'commentid', 'type' => 'int',  'default'=>0,  'desc' => '回复的评论commentid：即评论视频的评论ID'),
                'parentid' => array('name' => 'parentid', 'type' => 'int',  'default'=>0,  'desc' => '回复的评论ID即回复评论者的评论ID'),
                'content' => array('name' => 'content', 'type' => 'string',  'default'=>'', 'desc' => '内容'),
            ),
          
           	'addCommentLike' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'commentid' => array('name' => 'commentid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '评论/回复 ID'),
            ),
			
			'addStep' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'dialectid' => array('name' => 'dialectid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '方言秀视频ID'),
            ),
			
			'addShare' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
                'dialectid' => array('name' => 'dialectid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '方言秀视频ID'),
            ),
			'addView' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '方言秀视频ID'),
            ),
			
			'setBlack' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'dialectid' => array('name' => 'dialectid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '方言秀视频ID'),
            ),
            'getDialectList' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
            	'dialecttype' => array('name' => 'dialecttype', 'type' => 'int', 'desc' => '方言类型'),
            	'type' => array('name' => 'type', 'type' => 'int', 'desc' => '读取类型：0：全部视频；1：我的催更视频'),
            	'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),
			 'getVideo' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
                'dialectid' => array('name' => 'dialectid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '视频ID'),
            ),
            'getAttentionVideo' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
            	'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),
           
            'getComments' => array(
                'uid' => array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
                'dialectid' => array('name' => 'dialectid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '方言秀视频ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),
			
			'getReplys' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'commentid' => array('name' => 'commentid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '评论ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),
			
			'getMyVideo' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),
            'del' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => 'token'),
                'dialectid' => array('name' => 'dialectid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '方言秀视频ID'),
            ),
			
			'report' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => 'token'),
                'dialectid' => array('name' => 'dialectid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '方言秀视频ID'),
                'content' => array('name' => 'content', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => '举报内容'),
                'type' => array('name' => 'type', 'type' => 'int',  'desc' => '举报视频类型：0：短视频；1：方言秀'),
            ),
            
			'getReportContentlist' => array(
            ),
			
			'getHomeVideo' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),
			'getCreateNonreusableSignature' => array(
                'imgname' => array('name' => 'imgname', 'type' => 'string', 'desc' => '图片名称'),
                'videoname' => array('name' => 'videoname', 'type' => 'string', 'desc' => '视频名称'),
				'folderimg' => array('name' => 'folderimg', 'type' => 'string','desc' => '图片文件夹'),
				'foldervideo' => array('name' => 'foldervideo', 'type' => 'string', 'desc' => '视频文件夹'),
            ),
			'getOscarVideo' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),
			'likeIspay' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
            ),
			'urgeVideo' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
				'type' => array('name' => 'type', 'type' => 'int',  'desc' => '催更视频类型：0：短视频；1：方言秀'),
				'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '催更视频ID'),
            ),
			'getVideoUrge' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
				'type' => array('name' => 'type', 'type' => 'int',  'desc' => '催更视频类型：0：短视频；1：方言秀'),
				'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '催更视频ID'),
            ),
			'getMyurgevideoList' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),
			'addlooktimes' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
				'looktimes' => array('name' => 'looktimes', 'type' => 'int', 'desc' => '观看时间'),
            ),
			'getUrgenum' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
            ),
		);
	}
	/**
     * 方言名称列表
     * @desc 用于发布方言秀视频选择方言类型
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function getDialect() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $domain = new Domain_Dialect();
        $rs['info'] = $domain->getDialect();

        return $rs;
    }	
	
	/**
     * 方言秀素材列表
     * @desc 用于发布方言秀视频选择方言类型
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function getDialectmaterial() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $domain = new Domain_Dialect();
        $rs['info'] = $domain->getDialectmaterial();

        return $rs;
    }	
	
	
	/**
	 * 发布方言秀短视频
	 * @desc 用于发布短视频
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].id 视频记录ID
	 * @return string msg 提示信息
	 */
	public function setDialectshow() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		$title=$this->checkNull($this->title);
		$thumb=$this->checkNull($this->thumb);
		$href=$this->checkNull($this->href);
		$lat=$this->checkNull($this->lat);
		$lng=$this->checkNull($this->lng);
		$city=$this->checkNull($this->city);
		$dialect_type=$this->checkNull($this->dialect_type);
		$dialect_material_id=$this->checkNull($this->dialect_material_id);
		$urgenum=$this->checkNull($this->urgenum);
		
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
		
		/* $qiniu_space_host=DI()->config->get('app.Qiniu.space_host');
		
		$thumb=$qiniu_space_host.'/'.$thumb;
		$thumb_s=$thumb.'?imageView2/2/w/200/h/200';
		$href=$qiniu_space_host.'/'.$href; */
		$thumb_s=$thumb.'?imageView2/2/w/200/h/200';

		$data=array(
			"dialect_type"=>$dialect_type,
			"dialect_material_id"=>$dialect_material_id,
			"uid"=>$uid,
			"title"=>$title,
			"thumb"=>$thumb,
			"thumb_s"=>$thumb_s,
			"href"=>$href,
			"lat"=>$lat,
			"lng"=>$lng,
			"city"=>$city,
			"likes"=>0,
			"views"=>0,
			/* 'isdel'=>0, */
			"comments"=>0,
			"addtime"=>time(),
			'urge_nums'=>$urgenum,//剩余催更次数
		    'big_urgenums'=>$urgenum,//最大催更次数
		);
		
		$domain = new Domain_Dialect();
		$info = $domain->setDialectshow($data);
		if(!$info){
			$rs['code']=1001;
			$rs['msg']='发布失败';
		}

		$rs['info'][0]['id']=$info['id'];
		$rs['info'][0]['thumb_s']=$thumb_s;
		return $rs;
	}		
	
   	/**
     * 评论/回复
     * @desc 用于用户评论/回复 别人视频
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
		$videoid=$this->dialectid;
		$commentid=$this->commentid;
		$parentid=$this->parentid;
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
			'dialectid'=>$videoid,
			'commentid'=>$commentid,
			'parentid'=>$parentid,
			'content'=>$content,
			'addtime'=>time(),
		);

        $domain = new Domain_Dialect();
        $result = $domain->setComment($data);
		
		$info=array(
			'isattent'=>'0',
			'u2t'=>'0',
			't2u'=>'0',
			'comments'=>$result['comments'],
			'replys'=>$result['replys'],
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
     * @desc 用于视频阅读数累计
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function addView() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		$isBlackUser=$this->isBlackUser($this->uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
        $domain = new Domain_Dialect();
        $rs['info'] = $domain->addView($this->uid,$this->videoid);

        return $rs;
    }	
   	/**
     * 点赞
     * @desc 用于方言秀视频点赞数累计
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].islike 是否点赞 
     * @return string info[0].likes 点赞数量
     * @return string msg 提示信息
     */
	public function addLike() {
        $rs = array('code' => 0, 'msg' => '点赞成功', 'info' => array());
		$isBlackUser=$this->isBlackUser($this->uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		$type=$this->type;
		if(!$type){
			$type=0;
		}
        $domain = new Domain_Dialect();
        $rs['info'][0] = $domain->addLike($this->uid,$this->dialectid,$type);
		if($rs['info'][0]==1001){
			$rs['code'] = 1001;
			$rs['msg'] = "视频已删除";
			return $rs;
		}else if($result==1002){
			$rs['code'] = 1002;
			$rs['msg'] = "不能给自己点赞";
			return $rs;
		}
        return $rs;
    }	

   	/**
     * 踩一下
     * @desc 用于视频踩数累计
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
        $domain = new Domain_Dialect();
        $rs['info'][0] = $domain->addStep($this->uid,$this->dialectid);

        return $rs;
    }

   	/**
     * 视频分享
     * @desc 用于视频分享数累计
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].isshare 是否分享
     * @return string info[0].shares 分享数量
     * @return string msg 提示信息
     */
	public function addShare() {
        $rs = array('code' => 0, 'msg' => '分享成功', 'info' => array());
		$isBlackUser=$this->isBlackUser($this->uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
        $domain = new Domain_Dialect();
        $rs['info'][0] = $domain->addShare($this->uid,$this->dialectid);

        return $rs;
    }	

   	/**
     * 拉黑视频
     * @desc 用于拉黑视频
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

        $domain = new Domain_Dialect();
        $rs['info'][0] = $domain->setBlack($this->uid,$this->dialectid);

        return $rs;
    }	
	
   	/**
     *  点赞
     * @desc 用于评论/回复 点赞数累计
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].islike 是否点赞 
     * @return string info[0].likes 点赞数量
     * @return string msg 提示信息
     */
	public function addCommentLike() {
        $rs = array('code' => 0, 'msg' => '点赞成功', 'info' => array());
		$isBlackUser=$this->isBlackUser($this->uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

        $domain = new Domain_Dialect();
        $rs['info'][0] = $domain->addCommentLike($this->uid,$this->commentid);

        return $rs;
    }	
	/**
     * 获取方言秀视频
     * @desc 用于获取方言秀视频
     * @return int code 操作码，0表示成功
     * @return array info 视频列表
     * @return object info[].userinfo 用户信息
     * @return string info[].datetime 格式后的发布时间
     * @return string info[].islike 是否点赞
     * @return string info[].isattent 是否关注
     * @return string info[].thumb_s 封面小图，分享用
     * @return string info[].comments 评论总数
     * @return string info[].likes 点赞数
     * @return string msg 提示信息
     */
	public function getDialectList() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		$isBlackUser=$this->isBlackUser($this->uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

        $domain = new Domain_Dialect();
		$dialecttype=$this->dialecttype;
		$type=$this->type;
		if(!$dialecttype){
			$dialecttype=0;
		}
        $result = $domain->getDialectList($this->uid,$dialecttype,$type,$this->p);
		if($result==10010){
			$rs['code'] = 10010;
			$rs['msg'] = "您没有催更的方言秀视频";
			return $rs;
		}
		$rs['info'] =$result;
        return $rs;
    }	
	/**
     * 获取关注视频
     * @desc 用于获取关注视频
     * @return int code 操作码，0表示成功
     * @return array info 视频列表
     * @return array info[].userinfo 用户信息
     * @return string info[].datetime 格式后的发布时间
	 * @return string info[].islike 是否点赞 
	 * @return string info[].comments 评论总数
     * @return string info[].likes 点赞数
     * @return string msg 提示信息
     */
	public function getAttentionVideo() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		$isBlackUser=$this->isBlackUser($this->uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

        $domain = new Domain_Dialect();
        $rs['info'] = $domain->getAttentionVideo($this->uid,$this->p);

        return $rs;
    }		
	/**
     * 视频详情
     * @desc 用于获取视频详情
     * @return int code 操作码，0表示成功，1000表示视频不存在
     * @return array info[0] 视频详情
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
	public function getVideo() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		$isBlackUser=$this->isBlackUser($this->uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

        $domain = new Domain_Dialect();
        $result = $domain->getVideo($this->uid,$this->dialectid);
		if($result==1000){
			$rs['code'] = 1000;
			$rs['msg'] = "视频已删除";
			return $rs;
			
		}
		$rs['info'][0]=$result;

        return $rs;
    }
	/**
     * 视频评论列表
     * @desc 用于获取视频评论列表
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

        $domain = new Domain_Dialect();
        $rs['info'][0] = $domain->getComments($this->uid,$this->dialectid,$this->p);

        return $rs;
    }	
	
	/**
     * 回复列表
     * @desc 用于获取视频评论列表
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

        $domain = new Domain_Dialect();
        $rs['info'] = $domain->getReplys($this->uid,$this->commentid,$this->p);

        return $rs;
    }	
	
	
	/**
     * 我的视频
     * @desc 用于获取我发布的视频
     * @return int code 操作码，0表示成功
     * @return array info 视频列表
     * @return array info[].userinfo 用户信息
     * @return string info[].datetime 格式后的发布时间
     * @return string info[].islike 是否点赞
     * @return string msg 提示信息
     */
	public function getMyVideo() {
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

        $domain = new Domain_Dialect();
        $rs['info'] = $domain->getMyVideo($uid,$p);

        return $rs;
    }	
	
	/**
     * 删除视频
     * @desc 用于删除视频以及相关信息
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function del() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		$dialectid=$this->dialectid;

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
		
        $domain = new Domain_Dialect();
        $info = $domain->del($uid,$dialectid);

        return $rs;
    }	

	/**
     * 举报视频
     * @desc 用于举报视频以及相关信息
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function report() {
        $rs = array('code' => 0, 'msg' => '举报成功', 'info' => array());
		
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		$dialectid=$this->dialectid;
		$type=$this->type;
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
		if($type==1){
			$data=array(
				'uid'=>$uid,
				'dialectid'=>$dialectid,
				'content'=>$content,
				'addtime'=>time(),
			);
		}else{
			$data=array(
				'uid'=>$uid,
				'videoid'=>$dialectid,
				'content'=>$content,
				'addtime'=>time(),
			);
		}
		
        $domain = new Domain_Dialect();
        $info = $domain->report($data,$type);
		
		if($info==1000){
			$rs['code'] = 1000;
			$rs['msg'] = '视频不存在';
			return $rs;
		}else if($info==1001){
			$rs['code'] = 1001;
			$rs['msg'] = '举报失败';
			return $rs;
		}

        return $rs;
    }	

    /**
     * 举报内容列表
     * @desc 用于用户举报选择举报内容
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function getReportContentlist() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $domain = new Domain_Dialect();
        $rs['info'] = $domain->getReportContentlist();

        return $rs;
    }	
	
	/**
     * 个人主页视频:暂未使用；详细查看Video.getHomeVideo()
     * @desc 用于获取个人主页视频
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function getHomeVideo() {
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

        $domain = new Domain_Dialect();
        $info = $domain->getHomeVideo($uid,$touid,$p);
		
		if($info==1000){
			$rs['code'] = $checkToken;
			$rs['msg'] = '视频不存在';
			return $rs;
		}
		$rs['info']=$info;

        return $rs;
    }	
	/**
     * 奥思卡获奖视频列表
     * @desc 用于获取入围奥思卡奖项的视频列表
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function getOscarVideo() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		$isBlackUser=$this->isBlackUser($this->uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		$uid=$this->uid;
		$p=$this->p;

        $domain = new Domain_Dialect();
        $info = $domain->getOscarVideo($uid,$p);
		
		if($info==1000){
			$rs['code'] = $checkToken;
			$rs['msg'] = '视频不存在';
			return $rs;
		}
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
     * @desc 用于删除视频以及相关信息
     * @return int code 操作码，0表示成功
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function getQiniuToken(){
	
	   	$rs = array('code' => 0, 'msg' => '', 'info' =>array());
		 
		$token = DI()->qiniu->getQiniuToken();
		$rs['info'][0]['token']=$token ; 
		return $rs; 
		
	}
	/**
     * 获取腾讯云上传图片签名
     * @desc 用于删除视频以及相关信息
     * @return int code 操作码，0表示成功
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function getCreateReusableSignatureImg(){
	
	   	$rs = array('code' => 0, 'msg' => '', 'info' =>array());
	
		require(API_ROOT.'/public/txcloud/include.php');
	/* 	$config = array(
			'app_id' => '1255500835',
			'secret_id' => 'AKIDbBcrfKT7EE3gBUQqjPxKWWJvPxPk3thI',
			'secret_key' => 'XvCLJ7j8NSN6f7QcfXZR7g2C9tRCm5pQ',
			'region' => 'sh',   // bucket所属地域：华北 'tj' 华东 'sh' 华南 'gz'
			'timeout' => 60
		); */
		$bucket = 'aosika';
        /* $config = $this->config; */
		$config=DI()->config->get('app.TxCloud.config');
		$filepath = '/test1/9261c8066f05a46903f3d2341e8203cd.jpg';
		$expiration = time() + 3600; 
		$auth = new \QCloud\Cos\Auth($config['app_id'], $config['secret_id'], $config['secret_key']);
		$signature = $auth->createReusableSignature($expiration, $bucket, $filepath);
		 
		/* $signature = DI()->txcloud->getCreateReusableSignatureImg(); */
		$rs['info'][0]['signature']=$signature ; 
		return $signature; 
		
	}
	/**
     *有效签名
     * @return string 
     */
    public function getCreateNonreusableSignature()
    {
		$rs = array('code' => 0, 'msg' => '', 'info' =>array());
		$imgname=$this->imgname;
		$videoname=$this->videoname;
		$folderimg=$this->folderimg;
		$foldervideo=$this->foldervideo;
		
		require(API_ROOT.'/public/txcloud/include.php');
	
		$config=DI()->config->get('app.TxCloud.config');
		$bucketname=DI()->config->get('app.TxCloud.bucketname');
		/* $bucketname=DI()->config->get('app.TxCloud.bucketname'); */
		/* $folder = DI()->config->get('app.TxCloud.folder');//'/test1/1.jpg'; */
		
	
		$auth = new \QCloud\Cos\Auth($config['app_id'], $config['secret_id'], $config['secret_key']);
		if($imgname){
			$filepathimg="/".$folderimg."/".$imgname;
			
			$signatureimg = $auth->createNonreusableSignature($bucketname, $filepathimg);
		}
		if($videoname){
			$filepathvideo="/".$foldervideo."/".$videoname;
			$signaturevideo = $auth->createNonreusableSignature($bucketname, $filepathvideo);
		} 
		$data=array(
			"imgsign"=>$signatureimg,
			"videosign"=>$signaturevideo
		);
		$rs['info']=$data ; 
        return $rs;	
    }
	
	/**
     * 判断点赞是否付费
     * @desc 用于用户点赞视频是否超出免费次数，超过的需要收费
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function likeIspay() {
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
        $domain = new Domain_Dialect();
		
        $result= $domain->likeIspay($this->uid);
		//判断免费点赞次数$today+60*60*24;
		$configpri=$this->getConfigPri();
		if($result==1){
			$rs['msg'] = '今天免费点赞次数已用完，此次点赞需要扣费';
		}
		$rs['info']['isfree'] = $result;
		$rs['info']['likemoney'] = $configpri['likemoney'];
        return $rs;
    }	
	/**
     * 催更视频
     * @desc 用于用户催更短视频
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function urgeVideo() {
        $rs = array('code' => 0, 'msg' => '催更成功', 'info' => array());
		$uid=$this->uid;
		$type=$this->type;
		$videoid=$this->videoid;
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
        $domain = new Domain_Dialect();
		
        $result= $domain->urgeVideo($uid,$type,$videoid);
		if($result==1000){
			$rs['code'] = 1000;
			$rs['msg'] = '催更失败';
		}else if($result==1001){
			$rs['code'] = 1001;
			$rs['msg'] = '余额不足';
		}else if($result==1002){
			$rs['code'] = 1002;
			$rs['msg'] = '催更次数已达上限';
		}
		$rs['info'] = $result;
	
        return $rs;
    }	
	/**
     * 查看催更进度
     * @desc 显示视频催更进度
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function getVideoUrge() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		$isBlackUser=$this->isBlackUser($this->uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		$uid=$this->uid;
		$type=$this->type;
		$videoid=$this->videoid;
        $domain = new Domain_Dialect();
		
        $result= $domain->getVideoUrge($uid,$type,$videoid);
		
		$rs['info'] = $result;
	
        return $rs;
    }	
	/**
     * 我的催更视频列表
     * @desc 用于显示底部导航：我的催更视频
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function getMyurgevideoList() {
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
        $domain = new Domain_Dialect();
		
        $result= $domain->getMyurgevideoList($uid,$this->p);
		
		$rs['info'] = $result;
	
        return $rs;
    }	
	/**
     * 累计观看时长
     * @desc 用于用户观看视频累计观看时长
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function addlooktimes() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		$uid=$this->uid;
		$looktimes=$this->looktimes;
		
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
        $domain = new Domain_Dialect();
		
        $result= $domain->addlooktimes($uid,$looktimes);
		if($result===false){
			$rs['code'] = 1001;
			$rs['msg'] = '添加累计观看时长失败';
			return $rs;
		}
		$rs['info'][0] = $result;
        return $rs;
    }	
	/**
     * 获取等级列表设置的催更最大人数
     * @desc 
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     */
	public function getUrgenum() {
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
        $domain = new Domain_Dialect();
		
        $result= $domain->getUrgenum($uid);
		
		$rs['info'] = $result;
	
        return $rs;
    }	
}
