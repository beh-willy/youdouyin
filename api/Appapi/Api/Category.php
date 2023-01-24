<?php

class Api_Category extends Api_Common {

	public function getRules() {
		return array(
			'getTag' => array(
				'num' => array('name' => 'num',  'default'=>'0' ,'desc' => '获取数量，默认全部'),
                'type' => array('name' => 'type',  'default'=>'1' ,'desc' => '分类类型'),
			),
            'getCategory' => array(
                'num' => array('name' => 'num',  'default'=>'0' ,'desc' => '获取数量，默认全部'),
               
            ),
            'getSubCategory' => array(
                'cid' => array('name' => 'cid',  'require' => true,'desc' => '父类id'),
            ),
            'getTagVideo' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => -1,'default'=>-1, 'require' => false, 'desc' => '用户ID'),
                'cid' => array('name' => 'cid', 'type' => 'int', 'require' => true,'desc' => '指定二级分类id'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1','desc' => '页数'),
                'type' => array('name' => 'type',  'default'=>'1' ,'desc' => '分类类型')
            ),
             'getTagCommunity' => array(
                'cid' => array('name' => 'cid', 'type' => 'int', 'require' => true,'desc' => '指定二级分类id'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1','desc' => '页数'),
                'type' => array('name' => 'type',  'default'=>'1' ,'desc' => '分类类型')
            ),

			

		);
	}
	
    /**
     * 获取前一级分类(标签)信息
     * @desc 用于获取一级分类信息
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[name] 分类名称
     * @return string info[thumb] 分类封面
     * @return string info[id] 分类id
     * @return string msg 提示信息
     */

   // public function getCategory() {
    public function getTag() {
        $num=0;
        $type=$this->type;

        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $domain = new Domain_Category();
        $Category = $domain->getTag($num,$type);
        $rs['info'] = $Category;

        return $rs;
    }

     /**
     * 后台获取前一级分类(标签)信息
     * @desc 用于获取一级分类信息
     * @return int code 操作码，0表示成功
     * @return array info
     * @return string info[name] 分类名称
     * @return string info[thumb] 分类封面
     * @return string info[id] 分类id
     * @return string msg 提示信息
     */

    public function getCategory() {
    
        $type=$this->num;
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $domain = new Domain_Category();
        $Category = $domain->getCategory($type);
        $rs['info'] = $Category;

        return $rs;
    }



    /**
     * 获取一级子分类的所有下级分类
     * @desc 用于子分类
     * @return int code 操作码，0表示成功
     * @return array info 分类信息
     * @return string msg 提示信息
     */
    public function getSubCategory() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $domain = new Domain_Category();
        $SubCategory = $domain->getSubCategory($this->cid);
        if (empty($SubCategory)) {
            DI()->logger->debug('cid not found', $this->cid);

            $rs['code'] = 1;
            $rs['msg'] = T('cid not exists');
            return $rs;
        }
        $rs['info'] = $SubCategory;

        return $rs;
    }
    /**
     * 获取指定分类下所有视频列表
     * @desc 获取指定分类下所有视频列表，按添加时间倒序
     * request cid int 分类(标签)Id
     * request p int 分页
     * request type int 分类(标签)类型 1代表视频分类 0代表社区分类
     * @return int code 操作码，0表示成功
     * @return array info 视频信息
     * @return string msg 提示信息
     */
   // public function getCategoryVideo() {
    public function getTagVideo() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $domain = new Domain_Category();
        $key="getCategoryVideo_".$this->p."_".$this->cid;
        $list=$this->getcaches($key);
        $list = '';
        if(!$list){
            
            $list = $domain->getCategoryVideo($this->cid,$this->p,$this->type,$this->uid);
            if(!$list)
            {
                $rs['code']=1001;
                $rs['msg']=$this->type?'该分类下暂无圈子':'该分类下暂无视频';
                return $rs;
            }
            else
            {
                $this->setCaches($key,$list,200);
            }

        }

        $rs['info']= $list;

        return $rs;
    }

     /**
     * 获取指定分类下所有圈子列表
     * @desc 获取指定分类下所有圈子列表，按添加时间倒序
     * request cid int 分类(标签)Id
     * request p int 分页
     * request type int 分类(标签)类型 1代表视频分类 0代表社区分类
     * @return int code 操作码，0表示成功
     * @return array info 圈子信息
     * @return string msg 提示信息
     */
   // public function getCategoryVideo() {
    public function getTagCommunity() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $domain = new Domain_Category();
        $key="getCategoryVideo_".$this->p."_".$this->cid;
        $list=$this->getcaches($key);
        if(!$list){
            
            $list = $domain->getCategoryVideo($this->cid,$this->p,$this->type);
            if(!$list)
            {
                $rs['code']=1001;
                $rs['msg']='该分类下暂无视频';
                return $rs;
            }
            else
            {
                $this->setCaches($key,$list,200);
            }

        }

        $rs['info']= $list;

        return $rs;
    }


} 
