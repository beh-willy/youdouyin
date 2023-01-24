<?php
/**
 * 音乐
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class Api_Music extends Api_Common {

	public function getRules() {
        return array(
            'music_list'=>array(
                'classify'=>array('name' => 'classify', 'type' => 'int','default'=>0,'desc' => '音乐分类ID，0表示全部'),
                'uid'=>array('name' => 'uid', 'type' => 'int','desc' => '用户id'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'), //返回Top10，此参数暂时不用
            ),
            'searchMusic' => array(
                'key' => array('name' => 'key', 'type' => 'string','require' => true,'desc' => '关键词'),
				'uid' => array('name' => 'uid', 'type' => 'int','desc' => '用户id'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),
            ),
            'collectMusic'=>array(
                'uid'=>array('name'=>'uid','type' => 'int','require' => true,'desc' => '用户id'),
                'token'=>array('name'=>'token','type' => 'string','require' => true,'desc' => '用户token'),
                'musicid'=>array('name'=>'musicid','type' => 'int','require' => true,'desc' => '音乐id'),
            ),
            'getCollectMusicLists'=>array(
                'uid'=>array('name'=>'uid','type' => 'int','require' => true,'desc' => '用户id'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),
            ),
            'hotLists'=>array(
                'uid'=>array('name'=>'uid','type' => 'int','require' => true,'desc' => '用户id'),
            ),
            'addmusic'=>array(
                'uid'=>array('name'=>'uid','type' => 'int','require' => true,'desc' => '用户id'),
                'token'=>array('name'=>'token','type' => 'string','require' => true,'desc' => '用户token'),
                'title'=>array('name'=>'title','type' => 'string','require' => true,'desc' => '音乐'),
                'length'=>array('name'=>'length','type' => 'string','require' => true,'desc' => '音乐长度'),
                'file_url'=>array('name'=>'file_url','type' => 'string','require' => true,'desc' => '音乐地址'),
            ),
            'getmymusic'=>array(
                'uid'=>array('name'=>'uid','type' => 'int','require' => true,'desc' => '用户id'),
                'token'=>array('name'=>'token','type' => 'string','require' => true,'desc' => '用户token'),
                'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),

            ),

            'delmusic'=>array(
                'uid'=>array('name'=>'uid','type' => 'int','require' => true,'min' => 1,'desc' => '用户id'),
                'token'=>array('name'=>'token','type' => 'string','require' => true,'desc' => '用户token'),
                'musicid'=>array('name'=>'musicid','type' => 'int','require' => true,'min' => 1,'desc' => '音乐id'), 
            ),
            
            
        );
	}
	
	/**
     * 背景音乐分类列表
     * @desc 用于获取背景音乐分类列表
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     * @return array info
     * @return string info[0].title 分类名称
     * @return string info[0].addtime 分类添加时间
     * @return string info[0].img_url 分类图标地址
     */
    
    public function classify_list(){
         $rs = array('code' => 0, 'msg' => '', 'info' =>array());
         $domain=new Domain_Music();
         $res=$domain->classify_list();

         if($res==1001){
            $rs['code']=0;
            $rs['msg']="暂无分类列表";
            return $rs;
         }

         $rs['info']=$res;

         return $rs;
    }


    /**
     * 音乐列表
     * @desc 用户获取音乐列表
     * @return int code 操作码，0表示成功
     * @return string msg 提示信息
     * @return array info 
     * @return string info[0].title 音乐名称
     * @return string info[0].author 演唱者
     * @return string info[0].img_url 封面
     * @return string info[0].length 音乐长度
     * @return string info[0].file_url 音乐文件地址
     * @return int info[0].use_nums 音乐被使用次数
     * @return int info[0].iscollect 音乐是否被该用户收藏
     */
    public function music_list(){
        $rs = array('code' => 0, 'msg' => '', 'info' =>array());

        $classify=$this->checkNull($this->classify);
        $uid=$this->checkNull($this->uid);
        $p=$this->checkNull($this->p);

        $domain=new Domain_Music();
        $res=$domain->music_list($classify,$uid,$p);

        if($res==1001){
            $rs['code']=0;
            $rs['msg']="无音乐列表";
            return $rs;
        }

        $rs['info']=$res;

        return $rs;

    }

    /**
     * 搜索音乐
     * @desc 用于搜索音乐
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     * @return string info[0].title 音乐名称
     * @return string info[0].author 演唱者
     * @return string info[0].img_url 封面
     * @return string info[0].length 音乐长度
     * @return string info[0].file_url 音乐文件地址
     * @return int info[0].use_nums 音乐被使用次数
     * @return int info[0].iscollect 音乐是否被该用户收藏
     */
    public function searchMusic(){

        $rs = array('code' => 0, 'msg' => '', 'info' =>array());
        $keywords=$this->checkNull($this->key);
        $uid=$this->checkNull($this->uid);
        $p=$this->p;

        $domain=new Domain_Music();
        $res=$domain->searchMusic($keywords,$uid,$p);

        if($res==1001){
            $rs['code']=0;
            $rs['msg']="无音乐列表";
            return $rs;
        }

        $rs['info']=$res;

        return $rs;
    }

    /**
     * 收藏音乐/取消收藏
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function collectMusic(){
       $rs = array('code' => 0, 'msg' => '', 'info' =>array());
       $uid=$this->checkNull($this->uid);
       $token=$this->checkNull($this->token);
       $musicid=$this->checkNull($this->musicid);

       if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }else if($checkToken==10020){
            $rs['code'] = 10020;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }



        $domain=new Domain_Music();
        $res=$domain->collectMusic($uid,$musicid);

        if($res==1001){
            $rs['code']=1001;
            $rs['msg']='该音乐已下架';
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
     * 获取用户收藏背景音乐列表
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回数据
     */
    public function getCollectMusicLists(){

        $rs = array('code' => 0, 'msg' => '', 'info' =>array());
        $uid=$this->checkNull($this->uid);
        $p=$this->checkNull($this->p);

        $domain=new Domain_Music();
        $res=$domain->getCollectMusicLists($uid,$p);
        if($res==0){
            $rs['code']=0;
            $rs['msg']="暂无收藏背景音乐";
            return $rs;
        }

        $rs['info']=$res;

        return $rs;

    }
    /**
     * 获取热门音乐列表
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回数据
     * @return string info[0].title 音乐名称
     * @return string info[0].author 演唱者
     * @return string info[0].img_url 封面
     * @return string info[0].length 音乐长度
     * @return string info[0].file_url 音乐文件地址
     * @return int info[0].use_nums 音乐被使用次数
     * @return int info[0].iscollect 音乐是否被该用户收藏
     */
    public function hotLists(){

        $rs = array('code' => 0, 'msg' => '', 'info' =>array());

        $uid=$this->checkNull($this->uid);

        $domain=new Domain_Music();
        $res=$domain->hotLists($uid);

        if($res==1001){
            $rs['code']=0;
            $rs['msg']="无音乐列表";
            return $rs;
        }

        $rs['info']=$res;

        return $rs;
    }

    /**
     * 用户添加音乐
     * @desc 用于用户添加音乐
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @rerurn array info 返回信息
     * 
     */
    public function addmusic(){
        $rs = array('code' => 0, 'msg' => '上传音乐成功', 'info' =>array());
        $uid=$this->checkNull($this->uid);
        $token=$this->checkNull($this->token);
        $title=$this->checkNull($this->title);
        $length=$this->checkNull($this->length);
        $file_url=$this->checkNull($this->file_url);

        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }else if($checkToken==10020){
            $rs['code'] = 10020;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }

        if($title==""){
            $rs['code'] = 1001;
            $rs['msg'] = '请填写音乐标题';
            return $rs; 
        }

        if($length==""){
            $rs['code'] = 1001;
            $rs['msg'] = '请填写音乐长度';
            return $rs; 
        }

        if($file_url==""){
            $rs['code'] = 1001;
            $rs['msg'] = '请上传音频文件';
            return $rs; 
        }

        $data=array(
           'title'=> $title,
           'uploader'=>$uid,
           'upload_type'=>2,
           'length'=>$length,
           'file_url'=>$file_url,
           'use_nums'=>0,
           'addtime'=>time()
        );

        $domain=new Domain_Music();
        $res=$domain->addmusic($uid,$data);
        if($res==1002){
            $rs['code']=1002;
            $rs['msg']="上传音乐失败";
            return $rs;
        }

        return $rs;

    }

    /**
     * 获取我的音乐
     * @desc 用于获取我的音乐
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function getmymusic(){

        $rs = array('code' => 0, 'msg' => '', 'info' =>array());
        $uid=$this->checkNull($this->uid);
        $token=$this->checkNull($this->token);
        $p=$this->p;

        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }else if($checkToken==10020){
            $rs['code'] = 10020;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }

        $key="personal_music_".$uid.'_'.$p;

        $info=$this->getcache($key);
        if(!$info){
            $domain=new Domain_Music();
            $info=$domain->getmymusic($uid,$p);

            if($info==1001){
                $rs['code']=0;
                $rs['msg']="暂无音乐列表";
                return $rs;
            }

            $this->setcaches($key,$info,2);
        }
        
        $rs['info'] = $info;
        return $rs;

    }

    public function  delmusic(){
        $rs = array('code' => 0, 'msg' => '', 'info' =>array());
        $uid=$this->checkNull($this->uid);
        $token=$this->checkNull($this->token);
        $musicid=$this->checkNull($this->musicid);

        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = '您的登陆状态失效，请重新登陆！';
            return $rs;
        }else if($checkToken==10020){
            $rs['code'] = 10020;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }

        $domain=new Domain_Music();
        $info=$domain->delmusic($uid,$musicid);

        if($info==1001){
            $rs['code']=1001;
            $rs['msg']="音乐不存在";
            return $rs;
        }

        if($info==1002){
            $rs['code']=1002;
            $rs['msg']="音乐删除失败";
            return $rs;
        }

        $rs['info']="删除成功";

        return $rs;
    }

	
} 
