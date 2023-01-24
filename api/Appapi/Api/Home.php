<?php

class Api_Home extends Api_Common {

    public function getRules() {
        return array(
            'getHot' => array(
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

            'getFollow' => array(
                'uid' => array('name' => 'uid', 'type' => 'int','min'=>1,'require' => true, 'desc' => '用户ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

            'getNew' => array(
                'lng' => array('name' => 'lng', 'type' => 'string', 'desc' => '经度值'),
                'lat' => array('name' => 'lat', 'type' => 'string','desc' => '纬度值'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

            'search' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
                'key' => array('name' => 'key', 'type' => 'string', 'default'=>'' ,'desc' => '用户ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),
            'getGameLive' => array(
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

            'getNearby' => array(
                'uid' => array('name' => 'uid', 'type' => 'int','min'=>1 ,'desc' => '用户ID'),
                'lng' => array('name' => 'lng', 'type' => 'string', 'desc' => '经度值'),
                'lat' => array('name' => 'lat', 'type' => 'string','desc' => '纬度值'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

            'videoSearch' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
                'key' => array('name' => 'key', 'type' => 'string', 'default'=>'' ,'desc' => '关键词'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),
            'getScreenad' => array(
                'type' => array('name' => 'type', 'type' => 'int','default'=>0 , 'desc' => '广告类型'),

            ),


        );
    }

    /**
     * 配置信息
     * @desc 用于获取配置信息
     * @return int code 操作码，0表示成功
     * @return array info
     * @return array info[0] 配置信息

     * @return string msg 提示信息
     */
    public function getConfig() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $configpri = $this->getConfigPri();
        $info = $this->getConfigPub();

        $list = $this->getLiveClass();

        $info['liveclass']=$list;
        /*print_r($info['share_type']);exit;
        if($info['login_type']) $info['login_type']=explode(',', $info['login_type']);//登录方式
        if($info['share_type']) $info['share_type']=explode(',', $info['share_type']);//分享方式
*/
        $info['sitebarname'] = explode('|', $info['sitebarname']);
        $info['tximgfolder']=$configpri['tximgfolder'];//腾讯云图片存储目录
        $info['txvideofolder']=$configpri['txvideofolder'];//腾讯云视频存储目录
        $info['cloudtype']=$configpri['cloudtype'];//视频云存储类型
        $info['qiniu_domain']=$configpri['qiniu_domain_url'];//七牛云存储空间地址（后台配置）
        $info['private_letter_switch']=$configpri['private_letter_switch']; //未关注时可发送私信开关
        $info['private_letter_nums']=$configpri['private_letter_nums']; //未关注时可发送私信条数
        $info['video_audit_switch']=$configpri['video_audit_switch']; //视频审核是否开启
        $info['set_max_price']=$configpri['set_max_price'];  //设置视频最高价格限制
        $info['draw_min_cash']=$configpri['draw_min_cash'];  //最低可提现金额
        $info['bonus_min_cash']=$configpri['bonus_min_cash'];  //分红提现最低金额
        $info['praise_percent']=$configpri['praise_percent'];  //1金币需要的点赞数
        $info['invite_tacket']=$configpri['invite_tacket']; //邀请双方获得金币数
        $info['ticket_percent']=$configpri['ticket_percent']; //金币提现比例
        $info['service_charge']=$configpri['service_charge']; //金币提现手续费
        $info['cash_withdraw']=$configpri['cash_withdraw']; //现金提现手续费
        $info['set_video_size']=$configpri['set_video_size'];//上传视频大小限制 
        $info['video_lenth_time']=$configpri['video_lenth_time'];//上传视频限制时长
        $info['signature']=$this->setEncrypt($configpri['signature']);


        $rs['info'][0] = $info;


        return $rs;
    }

    /**
     * 登录方式开关信息
     * @desc 用于获取登录方式开关信息
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[0].login_qq qq登录，0表示关闭，1表示开启
     * @return string info[0].login_wx 微信登录，0表示关闭，1表示开启
     * @return string info[0].login_sina 新浪微博登陆，0表示关闭，1表示开启
     * @return string info[0].login_fb facebook登陆，0表示关闭，1表示开启
     * @return string info[0].login_tw twitter登陆，0表示关闭，1表示开启
     * @return array info[0].login_type 开启的登录方式
     * @return string info[0].login_type[][0] 登录方式标识

     * @return string msg 提示信息
     */
    public function getLogin() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $info = $this->getConfigPub();
        $rs['info'][0]['login_type'] = $info['login_type'];

        return $rs;
    }

    //分类首页数据  jjj 
    public function cateIndex() {
        //首页广告banner
        $info = array('code' => 0, 'msg' => '', 'info' => array());
        /* $infos['banner']=DI()->notorm->ads
                 ->select("id,name,des,thumb,url,orderno as time")
                 ->where("sid=3")
                 ->fetchAll();*/
        $infos['banner']=(object)['img'=>'http://mmlba.space/data/upload/20200118165934.jpg'];
        //最热

        $zuire = $this->getcaches('zuire');

        if($zuire){
            $infos['hot'] =unserialize($zuire);
        }else{
            $infos['hot'] = array(
                array('id'=>1,'name'=>'金币专区','img'=>$this->hot_img(1)),
                array('id'=>2,'name'=>'官方推荐','img'=>$this->hot_img(2)),
                array('id'=>3,'name'=>'最新上传','img'=>$this->hot_img(3)),
                array('id'=>4,'name'=>'最多播放','img'=>$this->hot_img(4)),
                array('id'=>5,'name'=>'最多评论','img'=>$this->hot_img(5)),
                array('id'=>6,'name'=>'最多点赞','img'=>$this->hot_img(6))
            );
            $this->setcaches('zuire',serialize($infos['hot']),120);
        }

        //人气排行
        $invit_top = $this->getcaches('invit_top');//邀请排行

        if($invit_top){
            $infos['renqi']['invit_top']=unserialize($invit_top);
        }else{
            $invit_top = DI()->notorm->users_sharereg
                ->queryAll("SELECT uid,count(uid) as num FROM cmf_users_sharereg GROUP BY uid ORDER BY num desc limit 50");//邀请排行

            foreach ($invit_top as $k => $v) {
                $users =DI()->notorm->users->select('user_nicename,avatar')->where("id=?",$v['uid'])->fetchOne();
                $invit_top[$k]['user_nicename']=$users['user_nicename'];
                $invit_top[$k]['avatar']=$this->get_upload_path($users['avatar']);
            }
            $this->setcaches('invit_top',serialize($invit_top),3600*24);
            $infos['renqi']['invit_top']=$invit_top;
        }

        $upload_top = $this->getcaches('upload_top');//上传排行

        if($upload_top){
            $infos['renqi']['upload_top']=unserialize($upload_top);
        }else{
            $upload_top = DI()->notorm->users_video
                ->queryAll("SELECT uid,count(uid) as num FROM cmf_users_video where uid>0 GROUP BY uid ORDER BY num desc limit 50"); //上传排行
            foreach ($upload_top as $k => $v) {
                $users =DI()->notorm->users->select('user_nicename,avatar')->where("id=?",$v['uid'])->fetchOne();

                $upload_top[$k]['user_nicename']=$users['user_nicename'];
                $upload_top[$k]['avatar']=$this->get_upload_path($users['avatar']);
            }

            $this->setcaches('upload_top',serialize($upload_top),3600*24);
            $infos['renqi']['upload_top']=$upload_top;
        }

        //视频板块 
        $cate=DI()->notorm->live_category
            ->select("name,thumb,id")
            ->where("delete_time=0 and parent_id=0")
            ->where("belongto=0 and type=0")
            ->order("list_order asc")
            ->fetchAll();
        foreach ($cate as $k => $v) {
            $cate[$k]['thumb'] = $this->get_upload_path($v['thumb']);
        }
        $infos['cate'] = $cate;
        $info['info'] = $infos;

        return $info;
    }


    protected function hot_img($type){
        switch ($type) {
            case 1:
                $image=DI()->notorm->users_video->select("id,thumb_s")->where("isdel=0 and status=1 and price>0")->order('id desc')->fetchOne();break;
            case 2:
                $image=DI()->notorm->users_video->select("id,thumb_s")->where("isdel=0 and status=1 and isrecommend=1")->order('id desc')->fetchOne();break;
            case 3:
                $image=DI()->notorm->users_video->select("id,thumb_s")->where("isdel=0 and status=1")->order('id desc')->fetchOne();break;
            case 4:
                $image=DI()->notorm->users_video->select("id,thumb_s")->where("isdel=0 and status=1 and views>0")->order('id desc')->fetchOne();break;
            case 5:
                $image=DI()->notorm->users_video->select("id,thumb_s")->where("isdel=0 and status=1 and comments>0")->order('id desc')->fetchOne();break;
            case 6:
                $image=DI()->notorm->users_video->select("id,thumb_s")->where("isdel=0 and status=1 and likes>0")->order('id desc')->fetchOne();break;

        }
        return $this->get_upload_path($image['thumb_s']);

    }

    //jjj
    /**
     * 开屏广告
     */
    public function getScreenad() {
        $rs = array('code' => 0, 'msg' => '');


        if($this->type ==1){//轮播广告
            $info=DI()->notorm->ads
                ->select("name,des,thumb,url,orderno as time")
                ->where("sid=3")
                ->fetchAll();
        }else{
            $info=DI()->notorm->ads
                ->select("name,des,thumb,url,orderno as time")
                ->where("sid=1")
                ->fetchOne();
        }
        if(!empty($info)){
            $rs['code'] = 1;
            $rs['info'] = $info;
            $rs['msg'] = '';
            return $rs;
        }else{
            $rs['code'] = 1;
            $rs['info'] = [];
            $rs['msg'] = '';
            return $rs;
        }

    }

    /**
     * 视频广告
     */
    public function getVideoad() {
        $rs = array('code' => 0, 'msg' => '');
        $info=DI()->notorm->ads
            ->select("name,des,thumb,url,orderno as time")
            ->where("sid=2")
            ->fetchOne();
        if(!empty($info)){
            $rs['code'] = 1;
            $rs['info'] = $info;
            $rs['msg'] = '';
            return $rs;
        }
        return $rs;
    }


    /**
     * 获取热门主播
     * @desc 用于获取首页热门主播
     * @return int code 操作码，0表示成功
     * @return array info
     * @return array info[0]['slide']
     * @return string info[0]['slide'][].slide_pic 图片
     * @return string info[0]['slide'][].slide_url 链接
     * @return array info[0]['list'] 热门直播列表
     * @return string info[0]['list'][].uid 主播id
     * @return string info[0]['list'][].avatar 主播头像
     * @return string info[0]['list'][].avatar_thumb 头像缩略图
     * @return string info[0]['list'][].user_nicename 直播昵称
     * @return string info[0]['list'][].title 直播标题
     * @return string info[0]['list'][].city 主播位置
     * @return string info[0]['list'][].stream 流名
     * @return string info[0]['list'][].pull 播流地址
     * @return string info[0]['list'][].nums 人数
     * @return string info[0]['list'][].thumb 直播封面
     * @return string info[0]['list'][].level_anchor 主播等级
     * @return string info[0]['list'][].type 直播类型
     * @return string info[0]['list'][].goodnum 靓号
     * @return string msg 提示信息
     */
    public function getHot() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $domain = new Domain_Home();
        $key1='getSlide';
        $slide=$this->getcache($key1);

        if(!$slide){
            $slide = $domain->getSlide();
            $this->setcache($key1,$slide);
        }



        $key2="getHot_".$this->p;
        $list=$this->getcaches($key2);

        if(!$list){
            $list = $domain->getHot($this->p);
            $this->setCaches($key2,$list,2);
        }


        $rs['info'][0]['slide'] = $slide;
        $rs['info'][0]['list'] = $list;

        return $rs;
    }
    /**
     * 获取关注主播列表
     * @desc 用于获取用户关注的主播的直播列表
     * @return int code 操作码，0表示成功
     * @return array info 直播列表
     * @return string info[].uid 主播id
     * @return string info[].avatar 主播头像
     * @return string info[].avatar_thumb 头像缩略图
     * @return string info[].user_nicename 直播昵称
     * @return string info[].title 直播标题
     * @return string info[].city 主播位置
     * @return string info[].stream 流名
     * @return string info[].pull 播流地址
     * @return string info[].nums 人数
     * @return string info[].thumb 直播封面
     * @return string info[].level_anchor 主播等级
     * @return string info[].type 直播类型
     * @return string info[].goodnum 靓号
     * @return string msg 提示信息
     */
    public function getFollow() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $isblackuser=$this->isBlackUser($this->uid);

        if($isblackuser==0){
            $rs['code']=10020;
            $rs['msg']='账号已被禁用';
            return $rs;
        }
        $domain = new Domain_Home();
        $info = $domain->getFollow($this->uid,$this->p);


        $rs['info'] = $info;

        return $rs;
    }

    /**
     * 获取最新主播
     * @desc 用于获取首页最新开播的主播列表
     * @return int code 操作码，0表示成功
     * @return array info 主播列表
     * @return string info[].uid 主播id
     * @return string info[].avatar 主播头像
     * @return string info[].avatar_thumb 头像缩略图
     * @return string info[].user_nicename 直播昵称
     * @return string info[].title 直播标题
     * @return string info[].city 主播位置
     * @return string info[].stream 流名
     * @return string info[].pull 播流地址
     * @return string info[].nums 人数
     * @return string info[].distance 距离
     * @return string info[].thumb 直播封面
     * @return string info[].level_anchor 主播等级
     * @return string info[].type 直播类型
     * @return string info[].goodnum 靓号
     * @return string msg 提示信息
     */
    public function getNew() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $lng=$this->checkNull($this->lng);
        $lat=$this->checkNull($this->lat);
        $p=$this->checkNull($this->p);

        if(!$p){
            $p=1;
        }

        $key='getNew_'.$p;
        $info=$this->getcache($key);
        if(!$info){
            $domain = new Domain_Home();
            $info = $domain->getNew($lng,$lat,$p);

            $this->setCaches($key,$info,2);
        }

        $rs['info'] = $info;

        return $rs;
    }

    /**
     * 搜索
     * @desc 用于首页搜索会员
     * @return int code 操作码，0表示成功
     * @return array info 会员列表
     * @return string info[].id 用户ID
     * @return string info[].user_nicename 用户昵称
     * @return string info[].avatar 头像
     * @return string info[].sex 性别
     * @return string info[].signature 签名
     * @return string info[].level 等级
     * @return string info[].isattention 是否关注，0未关注，1已关注
     * @return string msg 提示信息
     */
    public function search() {

        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $isBlackUser=$this->isBlackUser($this->uid);
        if($isBlackUser=='0'){
            $rs['code'] = 700;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }

        $uid=$this->checkNull($this->uid);
        $key=$this->checkNull($this->key);
        $p=$this->checkNull($this->p);
        if($key==''){
            $rs['code'] = 1001;
            $rs['msg'] = "请填写关键词";
            return $rs;
        }



        if(!$p){
            $p=1;
        }


        $domain = new Domain_Home();
        $info = $domain->search($uid,$key,$p);

        $rs['info'] = $info;

        return $rs;
    }

    /**
     * 获取游戏直播
     * @desc 用于获取游戏直播的主播列表
     * @return int code 操作码，0表示成功
     * @return array info 主播列表
     * @return string info[].uid 主播id
     * @return string info[].avatar 主播头像
     * @return string info[].avatar_thumb 头像缩略图
     * @return string info[].user_nicename 直播昵称
     * @return string info[].title 直播标题
     * @return string info[].city 主播位置
     * @return string info[].stream 流名
     * @return string info[].pull 播流地址
     * @return string info[].nums 人数
     * @return string info[].distance 距离
     * @return string info[].thumb 直播封面
     * @return string msg 提示信息
     */
    public function getGameLive() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $p=$this->checkNull($this->p);


        if(!$p){
            $p=1;
        }

        $key='getGameLive_'.$p;
        $info=$this->getcache($key);
        if(!$info){
            $domain = new Domain_Home();
            $info = $domain->getGameLive($p);

            $this->setCaches($key,$info,2);
        }

        $rs['info'] = $info;

        return $rs;
    }

    /**
     * 获取附近主播
     * @desc 用于获取附近开播的主播列表
     * @return int code 操作码，0表示成功
     * @return array info
     * @return array info[0].live 主播列表
     * @return string info[0].live[].uid 主播id
     * @return string info[0].live[].avatar 主播头像
     * @return string info[0].live[].avatar_thumb 头像缩略图
     * @return string info[0].live[].user_nicename 直播昵称
     * @return string info[0].live[].title 直播标题
     * @return string info[0].live[].province 省份
     * @return string info[0].live[].city 主播位置
     * @return string info[0].live[].stream 流名
     * @return string info[0].live[].pull 播流地址
     * @return string info[0].live[].nums 人数
     * @return string info[0].live[].distance 距离
     * @return string info[0].live[].thumb 直播封面
     * @return string info[0].live[].level_anchor 主播等级
     * @return string info[0].live[].game 游戏名称
     * @return string info[0].live[].type 直播类型
     * @return string info[0].live[].goodnum 靓号
     * @return array info[0].video 视频列表
     * @return object info[0].video[].userinfo 用户信息
     * @return string info[0].video[].datetime 格式后的发布时间
     * @return string info[0].video[].thumb_s 封面小图，分享用
     * @return string msg 提示信息
     */
    public function getNearby() {
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

        $key='getNearby_'.$lng.'_'.$lat.'_'.$p;

        $info=$this->getcache($key);
        if(!$info){
            $domain = new Domain_Home();
            $info = $domain->getNearby($uid,$lng,$lat,$p);

            $this->setcaches($key,$info,2);
        }

        $rs['info'][0] = $info;

        return $rs;
    }

    /**
     * 视频搜索
     * @desc 视频搜索
     * @return int code 状态码 0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     * @return
     */
    public function videoSearch(){


        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $isBlackUser=$this->isBlackUser($this->uid);


        if($isBlackUser=='0'){
            $rs['code'] = 700;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }



        $uid=$this->checkNull($this->uid);
        $key=$this->checkNull($this->key);
        $p=$this->checkNull($this->p);
        if($key==''){
            $rs['code'] = 1001;
            $rs['msg'] = "请填写关键词";
            return $rs;
        }

        if(!$p){
            $p=1;
        }

        $key1='videoSearch'.'_'.$key.'_'.$p;

        $info=$this->getcache($key1);
        $info=false;
        if(!$info){
            $domain = new Domain_Home();
            $info = $domain->videoSearch($uid,$key,$p);
            $this->setcaches($key1,$info,2);
        }

        $rs['info'] = $info;

        return $rs;
    }

} 
