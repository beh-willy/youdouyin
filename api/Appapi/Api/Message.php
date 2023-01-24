<?php
/**
 * 消息
 *
 * @author: sunny
 */

class Api_Message extends Api_Common {

	public function getRules() {
        return array(
            'fansLists'=>array(
                'uid'=>array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
                'p' => array('name' => 'p', 'type' =>'int','min' => 1,'default'=>1,'desc' => '页数'), 
            ),
            
            'praiseLists'=>array(
                'uid'=>array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
                'p' => array('name' => 'p', 'type' =>'int','min' => 1,'default'=>1,'desc' => '页数'),
            ),

            'atLists'=>array(
                'uid'=>array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
                'p' => array('name' => 'p', 'type' =>'int','min' => 1,'default'=>1,'desc' => '页数'),
            ),
            'commentLists'=>array(
                'uid'=>array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
                'p' => array('name' => 'p', 'type' =>'int','min' => 1,'default'=>1,'desc' => '页数'),
            ),
            'officialLists'=>array(
                'p' => array('name' => 'p', 'type' =>'int','min' => 1,'default'=>1,'desc' => '页数'),
            ),
            'systemnotifyLists'=>array(
                'uid'=>array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
                'p' => array('name' => 'p', 'type' =>'int','min' => 1,'default'=>1,'desc' => '页数'),
            ),

            'getLastTime'=>array(
                'uid'=>array('name' => 'uid', 'type' => 'int','desc' => '用户ID'),
            ),
            
        );
	}
	
	
    /**
     * 获取粉丝关注信息列表
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回数据
     * @return string info[0].addtime 关注时间
     * @return string info[0].isattention 当前用户是否关注了粉丝用户
     * @return array info[0].userinfo 粉丝用户信息
     * @return array info[0].userinfo.id 粉丝用户id
     * @return array info[0].userinfo.user_nicename 粉丝用户昵称
     * @return array info[0].userinfo.avatar 粉丝用户头像
     * @return array info[0].userinfo.age 粉丝用户年龄
     * @return array info[0].userinfo.praise 粉丝用户发布视频的被点赞总数
     * @return array info[0].userinfo.fans 粉丝用户的粉丝总数
     * @return array info[0].userinfo.follows 粉丝用户关注的用户总数
     * @return array info[0].userinfo.workVideos 粉丝用户发布的视频总数
     * @return array info[0].userinfo.likeVideos 粉丝用户喜欢的视频总数
     */
    public function fansLists(){

        $rs = array('code' => 0, 'msg' => '', 'info' =>array());
        $uid=$this->checkNull($this->uid);
        $p=$this->checkNull($this->p);

        $key='message_fansLists_'.$uid.'_'.$p;
        $info=$this->getcache($key);
        if(!$info){
            $domain=new Domain_Message();
            $info=$domain->fansLists($uid,$p);
            if($info==0){
                $rs['code']=0;
                $rs['msg']="暂无粉丝列表";
                return $rs;
            }
            
            $this->setcaches($key,$info,2);
  
        }
        
        $rs['info']=$info;

        return $rs;

    }

    /**
     * 获取赞的列表（赞的评论和视频）
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回数据
     * @return string info[0].addtime 关注时间
     * @return string info[0].isattention 当前用户是否关注了粉丝用户
     * @return array info[0].userinfo 粉丝用户信息
     * @return array info[0].userinfo.id 粉丝用户id
     * @return array info[0].userinfo.user_nicename 粉丝用户昵称
     * @return array info[0].userinfo.avatar 粉丝用户头像
     * @return array info[0].userinfo.age 粉丝用户年龄
     * @return array info[0].userinfo.praise 粉丝用户发布视频的被点赞总数
     * @return array info[0].userinfo.fans 粉丝用户的粉丝总数
     * @return array info[0].userinfo.follows 粉丝用户关注的用户总数
     * @return array info[0].userinfo.workVideos 粉丝用户发布的视频总数
     * @return array info[0].userinfo.likeVideos 粉丝用户喜欢的视频总数
     */
    public function praiseLists(){
        $rs = array('code' => 0, 'msg' => '', 'info' =>array());
        $uid=$this->checkNull($this->uid);
        $p=$this->checkNull($this->p);

        $key='message_praiseLists_'.$uid.'_'.$p;
        $info=$this->getcache($key);
        if(!$info){
            $domain=new Domain_Message();
            $info=$domain->praiseLists($uid,$p);

            if($info==0){
                $rs['code']=0;
                $rs['msg']="暂无获赞列表";
                return $rs;
            }

             $this->setcaches($key,$info,2);
        }
        

        $rs['info']=$info;

        return $rs;
    }

    /**
     * 获取用户被@的信息
     * @return int code 状态码，0表示成功
     * @return sring msg 提示信息
     * @return array info 返回信息
     * @return int info[0].uid 主动@其他人的id
     * @return int info[0].videoid 视频id
     * @return int info[0].touid 被@人的id
     * @return string info[0].addtime 添加时间
     * @return string info[0].avatar 主动@其他人的头像
     * @return string info[0].user_nicename 主动@其他人的昵称
     * @return string info[0].video_title 视频标题
     */
    public function atLists(){
        $rs = array('code' => 0, 'msg' => '', 'info' =>array());
        $uid=$this->checkNull($this->uid);
        $p=$this->checkNull($this->p);

        $key='message_atLists_'.$uid.'_'.$p;
        $info=$this->getcache($key);

        if(!$info){
            $domain=new Domain_Message();
            $info=$domain->atLists($uid,$p);

            if($info==0){
                $rs['code']=0;
                $rs['msg']="暂无列表";
                return $rs;
            }

            $this->setcaches($key,$info,2);

        }

        

        $rs['info']=$info;

        return $rs;
    }

    /**
     * 评论信息列表
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     * @return array info 返回信息
     */
    public function commentLists(){

        $rs = array('code' => 0, 'msg' => '', 'info' =>array());
        $uid=$this->checkNull($this->uid);
        $p=$this->checkNull($this->p);

        $key='message_commentLists_'.$uid.'_'.$p;

        $info=$this->getcache($key);

        if(!$info){
            $domain=new Domain_Message();
            $info=$domain->commentLists($uid,$p);
            if($info==0){
                $rs['code']=0;
                $rs['msg']="暂无列表";
                return $rs;
            }

             $this->setcaches($key,$info,2);
        }
        
        $rs['info']=$info;

        return $rs;
    }


    /**
     * 官方通知列表
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     * @return int info[0].id 通知信息的id
     * @return string info[0].title 通知信息的标题
     * @return string info[0].synopsis 通知信息的简介
     * @return string info[0].url 通知信息的地址
     * @return string info[0].addtime 通知信息的添加时间
     */
    public function officialLists(){

        

        $rs = array('code' => 0, 'msg' => '', 'info' =>array());
        $p=$this->checkNull($this->p);

        $key='message_officialLists_'.$p;

        $info=$this->getcache($key);

        if(!$info){
            $domain=new Domain_Message();
            $info=$domain->officialLists($p);

            if($info==0){
                $rs['code']=0;
                $rs['msg']="暂无列表";
                return $rs;
            }

            $this->setcaches($key,$info,2);

        }

        $rs['info']=$info;

        return $rs;
    }

    /**
     * 系统通知列表
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     * @return int info[0].id 通知信息的id
     * @return string info[0].title 通知信息的标题
     * @return string info[0].title 通知信息的标题
     */
    public function systemnotifyLists(){

        $rs = array('code' => 0, 'msg' => '', 'info' =>array());
        $uid=$this->checkNull($this->uid);
        $p=$this->checkNull($this->p);

        $key='message_systemnotifyLists_'.$uid.'_'.$p;

        $info=$this->getcache($key);

        if(!$info){
            $domain=new Domain_Message();
            $info=$domain->systemnotifyLists($uid,$p);

            if($info==0){
                $rs['code']=0;
                $rs['msg']="暂无列表";
                return $rs;
            }

            $this->setcaches($key,$info,2);

        }

        $rs['info']=$info;

        return $rs;

    }

    /**
     * 获取用户系统消息最新的时间
     * @desc 用于获取用户系统消息最新的时间 
     * @return int code 状态码0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function getLastTime(){
        $rs = array('code' => 0, 'msg' => '', 'info' =>array());
        $uid=$this->checkNull($this->uid);
        $domain=new Domain_Message();
        $info=$domain->getLastTime($uid);

        if($info==1001){
            $rs['msg']="没有最新消息时间";
            return $rs;
        }

        $rs['info'][0]=$info;
        return $rs;
    }


	
} 
