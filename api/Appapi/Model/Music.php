<?php

class Model_Music extends Model_Common {
	

	/*获取分类列表*/
	public function classify_list(){
		$list=DI()->notorm->users_music_classify->select("id,title,img_url")->where("isdel=0")->order("orderno")->fetchAll();
		if(!$list){
			return 1001;
		}

		foreach ($list as $k => $v) {
			$list[$k]['img_url']=$this->get_upload_path($v['img_url']);
		}

		return $list;
	}

	/*获取音乐列表*/
	public function music_list($classify,$uid,$p){

		$nums=20;
		$start=($p-1)*$nums;

		$where=" isdel=0 and status=1";

		if($classify>0){
			$where.=" and classify_id={$classify}";
		}



		$list=DI()->notorm->users_music->select("id,title,author,img_url,length,file_url,use_nums")->where($where)->order("use_nums desc")->limit($start,$nums)->fetchAll();

		if(!$list){
			return 1001;
		}

		
		if($uid<1){ //游客

			foreach ($list as $k => $v) {

				$list[$k]['iscollect']=0;
				$list[$k]['img_url']=$this->get_upload_path($v['img_url']);
				$list[$k]['file_url']=$this->get_upload_path($v['file_url']);
			}
		}else{

			
			//获取本人收藏列表
			$collectLists=DI()->notorm->users_music_collection->select("music_id")->where("uid=? and status=1",$uid)->fetchAll();
			$collects=array();
			foreach ($collectLists as $val) {
				$collects[]=$val['music_id'];
			}


			foreach ($list as $k => $v) {
				if(in_array($v['id'],$collects)){
					$list[$k]['iscollect']="1";
				}else{
					$list[$k]['iscollect']="0";
				}

				$list[$k]['img_url']=$this->get_upload_path($v['img_url']);
				$list[$k]['file_url']=$this->get_upload_path($v['file_url']);
			}

		}

		
		return $list;

	}

	/*搜索音乐(模糊查询本地，按照使用量排序)*/
	public function searchMusic($keywords,$uid,$p){

		

		$nums=50;
		$start=($p-1)*$nums;

		$where="isdel=0 and status=1";
		if($keywords!=""){
			$where .=" and (title like '%".$keywords."%') or author like '%".$keywords."%'";
		}

		$list=DI()->notorm->users_music->select("id,title,author,img_url,length,file_url,use_nums")->where($where)->order("use_nums desc")->limit($start,$nums)->fetchAll();

		if(!$list){
			return 1001;
		}

		if($uid<1){ //游客

			foreach ($list as $k => $v) {
				$list[$k]['iscollect']=0;
				$list[$k]['img_url']=$this->get_upload_path($v['img_url']);
				$list[$k]['file_url']=$this->get_upload_path($v['file_url']);
			}
		}else{

			//获取本人收藏列表
			$collectLists=DI()->notorm->users_music_collection->select("music_id")->where("uid=? and status=1",$uid)->fetchAll();
			$collects=array();

			foreach ($collectLists as $val) {
				$collects[]=$val['music_id'];
			}

			foreach ($list as $k => $v) {
				if(in_array($v['id'],$collects)){
					$list[$k]['iscollect']="1";

				}else{
					$list[$k]['iscollect']="0";
				}

				$list[$k]['img_url']=$this->get_upload_path($v['img_url']);
				$list[$k]['file_url']=$this->get_upload_path($v['file_url']);
			}

		}

		return $list;
	}

	/*收藏/取消收藏音乐*/
	public function collectMusic($uid,$musicid){

		//判断音乐是否存在
		$info=DI()->notorm->users_music->select("title,addtime")->where("id=? and isdel=0 and status=1",$musicid)->fetchOne();


		if(!$info){
			return 1001;
		}

		//判断用户是否收藏过该视频
		$isexist=DI()->notorm->users_music_collection->select("*")->where("uid='{$uid}' and music_id='{$musicid}'")->fetchOne();


		//已经收藏过
		if($isexist){

			if($isexist['status']==1){ //已收藏
				//将状态改为取消收藏
				$result=DI()->notorm->users_music_collection->where("uid=? and music_id=?",$uid,$musicid)->update(array("status"=>0,"updatetime"=>time()));
				if($result!==false){
					return 200;
				}else{
					return 201;
				}
			}else{ //改为收藏

				//将状态改为收藏
				$result=DI()->notorm->users_music_collection->where("uid=? and music_id=?",$uid,$musicid)->update(array("status"=>1,"updatetime"=>time()));
				if($result!==false){
					return 300;
				}else{
					return 301;
				}
			}
			
		}else{

			//向收藏表中写入记录
			$data=array("uid"=>$uid,"music_id"=>$musicid,'addtime'=>time(),'status'=>1);
			$result=DI()->notorm->users_music_collection->insert($data);
			if($result!==false){
				return 300;
			}else{
				return 301;
			}
		}

	}

	/*获取用户收藏背景音乐列表*/
	public function getCollectMusicLists($uid,$p){
		$nums=20;
		$start=($p-1)*$nums;

		$where="uid='{$uid}' and status=1";

		$list=DI()->notorm->users_music_collection->select("music_id,addtime")->where($where)->order("addtime desc")->limit($start,$nums)->fetchAll();


		if(!$list){
			return 0;
		}

		foreach ($list as $k => $v) {
			$musicinfo=DI()->notorm->users_music->select("title,author,img_url,length,file_url,use_nums")->where("id=?",$v['music_id'])->fetchOne();
			unset($list[$k]['addtime']);
			$list[$k]['collecttime']=$this->datetime($v['addtime']); //收藏时间
			$list[$k]['title']=$musicinfo['title'];
			$list[$k]['author']=$musicinfo['author'];
			$list[$k]['img_url']=$this->get_upload_path($musicinfo['img_url']);
			$list[$k]['length']=$musicinfo['length'];
			$list[$k]['file_url']=$this->get_upload_path($musicinfo['file_url']);
			$list[$k]['use_nums']=$musicinfo['use_nums'];
			$list[$k]['iscollect']='1';
			$list[$k]['id']=$v['music_id'];  //为了app端模板解析统一，所以重新添加了一个id字段


		}

		return $list;
	}


	/*获取热门音乐列表*/
	public function hotLists($uid){

		$start=0;
		$nums=10;
		$where=" isdel=0 and status=1";

		$list=DI()->notorm->users_music->select("id,title,author,img_url,length,file_url,use_nums")->where($where)->order("use_nums desc")->limit($start,$nums)->fetchAll();

		if(!$list){
			return 1001;
		}

		
		if($uid<1){ //游客

			foreach ($list as $k => $v) {
				$list[$k]['iscollect']=0;
				$list[$k]['img_url']=$this->get_upload_path($v['img_url']);
				$list[$k]['file_url']=$this->get_upload_path($v['file_url']);
			}
		}else{

			
			//获取本人收藏列表
			$collectLists=DI()->notorm->users_music_collection->select("music_id")->where("uid=? and status=1",$uid)->fetchAll();
			$collects=array();
			foreach ($collectLists as $val) {
				$collects[]=$val['music_id'];
			}


			foreach ($list as $k => $v) {
				if(in_array($v['id'],$collects)){
					$list[$k]['iscollect']=1;
				}else{
					$list[$k]['iscollect']=0;
				}

				$list[$k]['img_url']=$this->get_upload_path($v['img_url']);
				$list[$k]['file_url']=$this->get_upload_path($v['file_url']);
			}

		}

		
		return $list;

	}

	public function addmusic($uid,$data){
		//获取用户昵称
		$userinfo=DI()->notorm->users->where("id=?",$uid)->select("user_nicename,avatar")->fetchOne();
		$data['author']=$userinfo['user_nicename'];
		$data['img_url']=$userinfo['avatar'];

		$data['classify_id']=16;  //固定好

		$res=DI()->notorm->users_music->insert($data);

		if($res==false){
			return 1002;
		}

		return 1;
	}

	public function getmymusic($uid,$p){

		$nums=20;
		$start=($p-1)*$nums;

		$list=DI()->notorm->users_music->select("id,title,author,img_url,length,file_url,use_nums,addtime")->where("uploader=? and upload_type=2 and classify_id=16 and isdel=0",$uid)->order("use_nums desc,addtime desc")->limit($start,$nums)->fetchAll();
		if(!$list){
			return 1001;
		}

		foreach ($list as $k => $v) {
			$list[$k]['addtime']=$this->datetime($v['addtime']);
			$list[$k]['img_url']=$this->get_upload_path($v['img_url']);
			$list[$k]['file_url']=$this->get_upload_path($v['file_url']);
		}

		return $list;
	}

	public function delmusic($uid,$musicid){


		//判断音乐是否存在
		$musicinfo=DI()->notorm->users_music->where("uploader=? and upload_type=2 and classify_id=16 and isdel=0 and id=?",$uid,$musicid)->fetchOne();

		if(!$musicinfo){
			return 1001;
		}


		$res=DI()->notorm->users_music->where("id=?",$musicid)->update(array("isdel"=>1,"updatetime"=>time()));   //更改音乐状态
		if($res==false){
			return 1002;
		}

		return 1;
	}
	
}
