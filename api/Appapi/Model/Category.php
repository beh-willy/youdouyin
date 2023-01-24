<?php
session_start();
class Model_Category extends Model_Common {

	/* 获取一级分类 */
	public function getTag($num,$type){
        if($num==0)
        {
            $rs=DI()->notorm->live_category
                ->select("name,thumb,id")
                ->where("delete_time=0 and parent_id=0")
                ->where("belongto",$type)
                ->order("list_order asc")
                ->fetchAll();
        }
        else
        {
            $rs=DI()->notorm->live_category
                ->select("name,thumb,id")
                ->where("delete_time=0 and parent_id=0")
                ->where("belongto",$type)
                ->order("list_order asc")
                ->limit($num)
                ->fetchAll();
        }

		foreach($rs as $k=>$v){
			$rs[$k]['thumb']=$this->get_upload_path($v['thumb']);
		}				

		return $rs;				
	}

    /* 后台获取一级分类 */
    public function getCategory($num){
        
       
            $rs=DI()->notorm->live_category
                ->select("name,thumb,id")
                ->where("delete_time=0 and parent_id=0")
                ->where("belongto",$num)
                ->order("list_order asc")
                ->fetchAll();
      

        foreach($rs as $k=>$v){
            $rs[$k]['thumb']=$this->get_upload_path($v['thumb']);
        }               

        return $rs;             
    }

    /* 获取一级分类的子分类 */
    public function getSubCategory($cid){

        $rs=DI()->notorm->live_category
            ->select("name,thumb,id")
            ->where("delete_time=0 and parent_id=".$cid)
            ->order("list_order asc")
            ->fetchAll();
        foreach($rs as $k=>$v){
            $rs[$k]['thumb']=$this->get_upload_path($v['thumb']);
        }

        return $rs;
    }


    /* 获取指定类型下的所有视频信息 */
    public function getCategoryVideo($cid,$p,$type,$uid) {

        $pnum=50;
        $start=($p-1)*$pnum;
        //$where="subcate=".$cid;
        // $where="cate=".$cid;
      
      /////////////////////////////////////////////////////////////
        if(!$type){

            //判断缓存中是否存有该类型的视频集合
            $info = $this->getcaches('getVideoId_'.$cid);
            $info = '';
            if(!$info){
               $info=DI()->notorm->users_video
                    ->select("*")
                    ->where("isdel=0 and status=1")
                    ->where('cate LIKE ?', "%{$cid}%")
                    ->order("addtime desc")
                    ->limit($start,$pnum)
                    ->fetchAll();
                $this->setcaches('getVideoId_'.$cid,$info,60);
            }
         
            foreach ($info as $k => $v) {
               
                $info[$k]['cate']=$this->getCateNameById(explode(',', $v['cate']));
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
          //////////////////////////////////////////////////////

           /* $prefix= DI()->config->get('dbs.tables.__default__.prefix');//获取表前缀
            $result=DI()->notorm->users
                ->queryAll("select u.id,u.user_nicename,u.avatar,u.category,u.votestotal,l.avatar_thumb,l.title,l.city,l.stream,l.pull,l.thumb,l.isvideo,l.type,l.type_val,l.goodnum from {$prefix}users u left join {$prefix}users_live l on l.uid=u.id where {$where} order by u.isrecommend desc,l.starttime desc limit {$start},{$pnum}");
                //->queryAll("select l.uid,l.avatar,l.avatar_thumb,l.user_nicename,l.title,l.city,l.stream,l.pull,l.thumb,l.isvideo,l.type,l.type_val,l.goodnum,u.votestotal from {$prefix}users_live l left join {$prefix}users u on l.uid=u.id where {$where} order by u.isrecommend desc,l.starttime desc limit {$start},{$pnum}")
    //              ->select("id,user_nicename,avatar,city,category")
    //              ->where("category=".$cid)
    //              ->limit($start,$pnum)
    //              ->order("score asc")
    //              ->fetchAll();

            foreach($result as $k=>$v){
                if($v['stream'])
                {
                    $nums=DI()->redis->hlen('userlist_'.$v['stream']);
                }
                else
                {
                    $nums=DI()->redis->hlen('userlist_'.$v['id'].'_'.$v['id']);
                }
                $result[$k]['nums']=(string)$nums;

                $result[$k]['level_anchor']=$this->getLevelAnchor($v['votestotal']);

                if(!$v['thumb']){
                    $result[$k]['thumb']=$v['avatar'];
                }
                if($v['isvideo']==0 and $v['stream']!=null){
                    $result[$k]['pull']=$this->PrivateKeyA('rtmp',$v['stream'],0);
                }
                if($v['isvideo']==0 and $v['stream']==null)
                {
                    $result[$k]['pull']='';
                }
                if($v['type']==1){
                    $result[$k]['type_val']='';
                }

            }
            /* if($result){
                $last=array_slice($result,-1,1);
                $_SESSION['new_starttime']=$last['starttime'];
            } */

        }else{
             //判断缓存中是否存有该类型的圈子集合
            $info = $this->getcaches('getCommunityId_'.$cid);
           
            if(!$info){
              $info=DI()->notorm->users_community
                    ->select("*")
                    ->where("isdel=0 and status=1")
                    ->where('cate LIKE ?', "%{$cid}%")
                    ->order("addtime desc")
                    ->limit($start,$pnum)
                    ->fetchAll();
                $this->setcaches('getVideoId_'.$cid,$info,60);  
            }
         
      
            //return $info;
           
            foreach ($info as $k => $v) {
                
                $userinfo=(array)$this->getUserInfo($v['uid']);
                if(!$userinfo){
                    $userinfo['user_nicename']="已删除";
                }
                   
                $info[$k]['userinfo']=$userinfo;
                $info[$k]['cate']=$this->getCateNameById(explode(',', $v['cate']));
                $info[$k]['datetime']=$this->datetime($v['addtime']);
                $info[$k]['comments']=$this->NumberFormat($v['comments']); //评论总数
                $info[$k]['likes']=$this->NumberFormat($v['likes']);   //点赞总数
                $info[$k]['views']=$this->NumberFormat($v['views']);   //浏览总数
                
                if($uid){
                    
                    $info[$k]['islike']=DI()->redis->sismember('user_community_like'.$uid,$v['id']) ? 1 : $this->isLike($uid,$v['id']);    //是否点赞
                    
                    $info[$k]['isattent']=DI()->redis->sismember('users_attent'.$uid,$v['uid']) ? 1 : $this->isAttention($uid,$v['uid']);  //是否关注
                    $info[$k]['isCollect']=DI()->redis->sismember('user_community_collect'.$uid,$v['id']) ? 1 : $this->isCollect($uid,$v['id']);//是否收藏
                }else{
                    $info[$k]['islike']=0; 
                    
                    $info[$k]['isattent']=0;
                    $info[$k]['isCollect']=0;  
                }


                //处理视频连接
                if($v['videolink']){
                    $link = explode(',', $v['videolink']);
                    $videolink[0]['thumb'] = $this->get_upload_path($link[0]);
                    $videolink[0]['link'] = $this->get_upload_path($link[1]);
                    
                }else{
                    $videolink=array();
                }
                $info[$k]['videolink']=$videolink;  

                //处理图片连接
                if($v['imgs']){
                    $images = explode(',', $v['imgs']);
                    foreach ($images as $key => &$value) {
                        $value = $this->get_upload_path($value);
                    }
                }else{
                    $images=array();
                }
                $info[$k]['imgs']=$images;

            }
        }
      

        return $info;
    }
	

    /* 查询分类是否存在 不存在新增 */
    public function gatCateIdByName($data,$belongto) {

       
        if(is_array($data)){
           
            foreach ($data as $k => $v) {
                $arr[$k]=$this->checkCateByName($v,$belongto);
            }

            return implode(',', $arr);
        }
        return $this->checkCateByName($data,$belongto);
    }


    private function checkCateByName($name,$belongto){


         $info=DI()->notorm->live_category
            ->select("id,name")
            ->where('belongto = ? AND name = ?', $belongto,$name)
            ->fetch();

        if($info){
            return $info['id'];
        }else{
            $data = array('name' => $name,'belongto'=>$belongto,'type'=>1);//type=1用户添加
            $userORM = DI()->notorm->live_category->insert($data);
            
            $id = $userORM['id'];
        }
       
        return $id;
    }
    


}
