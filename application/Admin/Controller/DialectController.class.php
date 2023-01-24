<?php

/**
 * 方言秀管理
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
use QCloud\Cos\Api;
use QCloud\Cos\Auth;
class DialectController extends AdminbaseController {
	/**方言列表**/
    function dialectindex(){

		$map=array();
			
		
	/* 	if($_REQUEST['type']!=''){
			$map['type']=$_REQUEST['type']; 
			$_GET['type']=$_REQUEST['type'];
		} */

		if($_REQUEST['keyword']!=''){
			$map['name']=array("like","%".$_REQUEST['keyword']."%");  
			$_GET['keyword']=$_REQUEST['keyword'];
		}
		
    	$dialect=M("dialect");
    	$Users=M("users");
    	$count=$dialect->where($map)->count();
    	$page = $this->page($count, 20);
    	$lists = $dialect
			->where($map)
			->order("type asc")
			->limit($page->firstRow . ',' . $page->listRows)
			->select();
	
    	$this->assign('lists', $lists);
    	$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display();
    }
	/**删除方言*/
	function del_dialect(){
		$id=intval($_GET['id']);
		if($id){
			$result=M("dialect")->where("id={$id}")->delete();				
			if($result!==false){
				$this->success('删除成功');
			}else{
				$this->error('删除失败');
			}			
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display();				
	}	
	/**添加方言*/	
	function add_dialect(){
		
		
		$this->display();				
	}	
	
	function add_dialectpost(){
		if(IS_POST){			
			$dialect=M("dialect");
			
			$type=I("type");//方言类型
			$name=I("name");//方言名称
			if(!trim($type)){
				$this->error('方言类型不能为空');
			}
			if(!trim($name)){
				$this->error('方言名称不能为空');
			}
			$isexit=M("dialect")->where("type={$type} or name='{$name}'")->find();	
			if($isexit){
				$this->error('该方言类型或名称已存在');
			}
			
			$dialect->create();
			$dialect->addtime=time();
			$result=$dialect->add(); 
			if($result){
				$this->success('添加成功');
			}else{
				$this->error('添加失败');
			}
		}			
	}		
	function edit_dialect(){
		$id=intval($_GET['id']);
		if($id){
			$dialectinfo=M("dialect")->where("id={$id}")->find();
			
			$this->assign('dialectinfo', $dialectinfo);						
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display();				
	}
	
	function edit_dialectpost(){
		if(IS_POST){			
			$dialect=M("dialect");
			
			$id=I("id");
			$type=I("type");//方言类型
			$name=I("name");//方言名称
			if(!trim($type)){
				$this->error('方言类型不能为空');
			}
			if(!trim($name)){
				$this->error('方言名称不能为空');
			}
			$isexit=M("dialect")->where("id!={$id} and (type={$type} or name='{$name}')")->find();	
			if($isexit){
				$this->error('该方言类型或名称已存在');
			}
			
			$dialect->create();
			$result=$dialect->save(); 
			if($result!==false){
				  $this->success('修改成功');
			 }else{
				  $this->error('修改失败');
			 }
		}			
	}
	/**方言秀素材*/
	 function materialindex(){
		
		if($_REQUEST['keyword']!=''){
			$map['id']=array("eq",$_REQUEST['keyword']); 
			$_GET['keyword']=$_REQUEST['keyword'];
		}		
		if($_REQUEST['keyword1']!=''){
			$map['name']=array("like","%".$_REQUEST['keyword1']."%");  
			$_GET['keyword1']=$_REQUEST['keyword1'];
		}
			
			
    	$material=M("dialectshow_material");
    	$count=$material->where($map)->count();
    	$page = $this->page($count, 20);
    	$lists = $material
			->where($map)
			->order("addtime DESC")
			->limit($page->firstRow . ',' . $page->listRows)
			->select();
		
    	$this->assign('lists', $lists);
		$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display();
    }
	
	/**添加方言秀模板*/	
	function add_material(){
		
		$this->display();				
	}	
	
	function add_materialpost(){
		if(IS_POST){			
			$material=M("dialectshow_material");
			
			$title=I("title");//视频标题
			$name=I("name");//方言名称
			
			if(!trim($title)){
				$this->error('视频标题不能为空');
			}
			$configpri=getConfigPri();//私密配置信息
			$istxcloud=$configpri['istxcloud'];
			if($istxcloud==1){
				/* 腾讯云 */
				require(SITE_PATH.'api/public/txcloud/include.php');
				//bucketname
				$bucket = 'aosika';
				//uploadlocalpath
				/* $src = $_FILES['file'];//'./hello.txt'; */
				$src = $_FILES["file"]["tmp_name"];//'./hello.txt';
				
			
			
				//cosfolderpath
				$folder = '/'.$configpri['tximgfolder'];
				//cospath
				$dst = $folder.'/'.$_FILES["file"]["name"];
			 
				$houzhui = substr($dst, -3, 3);
				if($houzhui!=='mp4' && $houzhui!=='MP4'){
					$this->error("请上传mp4格式的视频文件");    
				}
				/* $this->error($houzhui);     */
				//config your information
				$config = array(
					'app_id' => '1255500835',
					'secret_id' => 'AKIDbBcrfKT7EE3gBUQqjPxKWWJvPxPk3thI',
					'secret_key' => 'XvCLJ7j8NSN6f7QcfXZR7g2C9tRCm5pQ',
					'region' => 'sh',   // bucket所属地域：华北 'tj' 华东 'sh' 华南 'gz'
					'timeout' => 60
				);

				date_default_timezone_set('PRC');
				$cosApi = new 	Api($config);

				// Upload file into bucket.
				$ret = $cosApi->upload($bucket, $src, $dst);
				if($ret['code']!=0){
					//上传失败，返回错误
					exit("0," . $ret['message']);
				}
				$url = $ret['data']['source_url'];
			}else{
				 $url='';
				 $filepath='data/upload/dialect';//方言秀视频存储路径
				 $upload = new \Think\Upload();// 实例化上传类    
				 $upload->maxSize   =     0 ;// 设置附件上传大小    
				 $upload->exts      =     array('mp4');// 设置附件上传类型    
				 $upload->rootPath = './';
				 /* $upload->savePath = '/Uploads/'. $position; */
				 $upload->savePath  =$filepath; // 设置附件上传目录    // 上传文件    
				  /* $this->error( $upload); */
				 /* $info   =   $upload->upload();    */
				  /* $this->error($upload->savePath); */
				  $info   =  $upload->uploadOne($_FILES['file']);
				
				 if(!$info)
				 {// 上传错误提示错误信息        
					$this->error($upload->getError());    
				 }else{// 上传成功
					$url=  $info['savepath'].$info['savename'];
				 }
			}
			
			$material->create();
			$material->href=$url;
			$material->addtime=time();
			$result=$material->add(); 
			if($result){
				$this->success('添加成功');
			}else{
				$this->error('添加失败');
			}
		}			
	}	
	//编辑方言秀素材
	function edit_material(){
		$id=intval($_GET['id']);
		if($id){
			$video=M("dialectshow_material")->where("id={$id}")->find();
			
			
			$this->assign('video', $video);						
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display();				
	}
	//编辑方言秀素材
	function edit_materialpost(){
		if(IS_POST){
			$material=M("dialectshow_material");
			
			$title=I("title");//视频标题
			if(!trim($title)){
				$this->error('视频标题不能为空');
			}
			$material->create();
			
			if($_FILES["file"]){
				$configpri=getConfigPri();//私密配置信息
				$istxcloud=$configpri['istxcloud'];
				if($istxcloud==1){
					/* 腾讯云 */
					require(SITE_PATH.'api/public/txcloud/include.php');
					//bucketname
					$bucket = 'aosika';
					//uploadlocalpath
					/* $src = $_FILES['file'];//'./hello.txt'; */
					$src = $_FILES["file"]["tmp_name"];//'./hello.txt';
					
					
				
					//cosfolderpath
					$folder = '/'.$configpri['tximgfolder'];
					//cospath
					$dst = $folder.'/'.$_FILES["file"]["name"];
					//config your information
					$config = array(
						'app_id' => '1255500835',
						'secret_id' => 'AKIDbBcrfKT7EE3gBUQqjPxKWWJvPxPk3thI',
						'secret_key' => 'XvCLJ7j8NSN6f7QcfXZR7g2C9tRCm5pQ',
						'region' => 'sh',   // bucket所属地域：华北 'tj' 华东 'sh' 华南 'gz'
						'timeout' => 60
					);

					date_default_timezone_set('PRC');
					$cosApi = new 	Api($config);

					// Upload file into bucket.
					$ret = $cosApi->upload($bucket, $src, $dst);
					if($ret['code']!=0){
						//上传失败，返回错误
						exit("0," . $ret['message']);
					}
					$url = $ret['data']['source_url'];
				}else{
					 $url='';
					 $filepath='data/upload/dialect';//方言秀视频存储路径
					 $upload = new \Think\Upload();// 实例化上传类    
					 $upload->maxSize   =     0 ;// 设置附件上传大小    
					 $upload->exts      =     array('mp4', 'gif', 'png', 'jpeg');// 设置附件上传类型    
					 $upload->rootPath = './';
					 /* $upload->savePath = '/Uploads/'. $position; */
					 $upload->savePath  =$filepath; // 设置附件上传目录    // 上传文件    
					  /* $this->error( $upload); */
					 /* $info   =   $upload->upload();    */
					  /* $this->error($upload->savePath); */
					  $info   =  $upload->uploadOne($_FILES['file']);
					
					 if(!$info)
					 {// 上传错误提示错误信息        
						$this->error($upload->getError());    
					 }else{// 上传成功
						$url=  $info['savepath'].$info['savename'];
					 }
				}
				$material->href=$url;
			}
			
			$material->addtime=time();
			$result=$material->save(); 
			if($result!==false){
				  $this->success('修改成功');
			 }else{
				  $this->error('修改失败');
			 }
		}			
	}
	
	//删除方言秀素材
	function del_material(){
		$id=intval($_GET['id']);
		if($id){
			$result=M("dialectshow_material")->where("id={$id}")->delete();				
			if($result!==false){
				$this->success('删除成功');
			}else{
				$this->error('删除失败');
			}			
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display();		
	}
	
	//用户方言秀列表
	function userdialectindex(){
		if($_REQUEST['ordertype']!=''){
			$ordertype=$_REQUEST['ordertype'];
			$_GET['ordertype']=$_REQUEST['ordertype'];
		}
		
		if($_REQUEST['isdel']!=''){
			$map['isdel']=$_REQUEST['isdel'];
			$_GET['isdel']=$_REQUEST['isdel'];
		}
		if($_REQUEST['keyword']!=''){
			$map['uid|id']=array("eq",$_REQUEST['keyword']); 
			$_GET['keyword']=$_REQUEST['keyword'];
		}	
		if($_REQUEST['keyword1']!=''){
			$map['title']=array("like","%".$_REQUEST['keyword1']."%");  
			$_GET['keyword1']=$_REQUEST['keyword1'];
		}
		//用户名称
		if($_REQUEST['keyword2']!=''){
			/* $map['title']=array("like","%".$_REQUEST['keyword2']."%");   */
			$_GET['keyword2']=$_REQUEST['keyword2'];
			$username=$_REQUEST['keyword2'];
			$userlist =M("users")->field("id")->where("user_nicename like '%".$username."%'")->select();
			$strids="";
			foreach($userlist as $ku=>$vu){
				if($strids==""){
					$strids=$vu['id'];
				}else{
					$strids.=",".$vu['id'];
				}
			}
			$map['uid']=array("in",$strids);
		}
		//方言名称
		if($_REQUEST['keyword3']!=''){
			/* $map['title']=array("like","%".$_REQUEST['keyword2']."%");   */
			$_GET['keyword3']=$_REQUEST['keyword3'];
			$dialectname=$_REQUEST['keyword3'];
			$dialectlist =M("dialect")->field("type")->where("name like '%".$dialectname."%'")->select();
			$strtypes="";
			foreach($dialectlist as $kd=>$vd){
				if($strtypes==""){
					$strtypes=$vd['type'];
				}else{
					$strtypes.=",".$vd['type'];
				}
			}
			$map['dialect_type']=array("in",$strtypes);
		}
		$orderstr="";
		if($ordertype==1){//评论数排序
			$orderstr="comments DESC";
		}else if($ordertype==2){//点赞数量排序（点赞）
			$orderstr="likes DESC";
		}else if($ordertype==3){//分享数量排序
			$orderstr="shares DESC";
		}else{
			$orderstr="addtime DESC";
		}
    	$users_dialect=M("users_dialect");
    	$count=$users_dialect->where($map)->count();
    	$page = $this->page($count, 20);
    	$lists = $users_dialect
			->where($map)
			/* ->order("addtime DESC") */
			->order($orderstr)
			->limit($page->firstRow . ',' . $page->listRows)
			->select();
		foreach($lists as $k=>$v){
			$dialectinfo=M("dialect")->where("type=".$v['dialect_type'])->find();
			if(!$dialectinfo){
				 $lists[$k]['dialectname']="该用户没有设置方言哦";
			}else{
				$lists[$k]['dialectname']=$dialectinfo['name'];
			}
			
			$userinfo=getUserInfo($v['uid']);
			if(!$userinfo){
				$userinfo=array(
					'user_nicename'=>'已删除'
				);
			}
			$lists[$k]['userinfo']=$userinfo;
			$hasurgemoney=($v['big_urgenums']-$v['urge_nums'])*$v['urge_money'];
			$lists[$k]['hasurgemoney']=$hasurgemoney;
		}
    	$this->assign('lists', $lists);
    	
    	$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display();
		
		
	}
	///编辑用户方言秀
	function edit_userdialect(){
		$id=intval($_GET['id']);
		if($id){
			$video=M("users_dialect")->where("id={$id}")->find();
			
			$userinfo=getUserInfo($video['uid']);
			if(!$userinfo){
				$userinfo=array(
					'user_nicename'=>'已删除'
				);
			}
			$video['userinfo']=$userinfo;
			$this->assign('video', $video);						
		}else{				
			$this->error('数据传入失败！');
		}				
		$this->display();	
	}
	function edit_userdialectpost(){
		if(IS_POST){			
			$user=M("users_dialect");
			$user->create();
			$result=$user->save(); 
			if($result!==false){
				  $this->success('修改成功');
			 }else{
				  $this->error('修改失败');
			 }
		}			
	}

	//删除用户方言秀
	function del_userdialect(){
		$id=intval($_GET['id']);
		if($id){
			$result=M("users_dialect")->where("id={$id}")->delete();				
			if($result!==false){
				$this->success('删除成功');
			}else{
				$this->error('删除失败');
			}			
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display();		
	}
	

	
	//用户举报列表
	public function userreportindex(){
		if($_REQUEST['status']!=''){
			$map['status']=$_REQUEST['status'];
			$_GET['status']=$_REQUEST['status'];
		}
		if($_REQUEST['start_time']!=''){
			$map['addtime']=array("gt",strtotime($_REQUEST['start_time']));
			$_GET['start_time']=$_REQUEST['start_time'];
		}

		if($_REQUEST['end_time']!=''){
		 
			$map['addtime']=array("lt",strtotime($_REQUEST['end_time']));
			$_GET['end_time']=$_REQUEST['end_time'];
		}
		if($_REQUEST['start_time']!='' && $_REQUEST['end_time']!='' ){
		 
			$map['addtime']=array("between",array(strtotime($_REQUEST['start_time']),strtotime($_REQUEST['end_time'])));
			$_GET['start_time']=$_REQUEST['start_time'];
			$_GET['end_time']=$_REQUEST['end_time'];
		}

		if($_REQUEST['keyword']!=''){
			$map['uid']=array("like","%".$_REQUEST['keyword']."%"); 
			$_GET['keyword']=$_REQUEST['keyword'];
		}		
			
    	$Report=M("users_dialectreport");
    	$Users=M("users");
    	$count=$Report->where($map)->count();
    	$page = $this->page($count, 20);
    	$lists = $Report
			->where($map)
			->order("addtime DESC")
			->limit($page->firstRow . ',' . $page->listRows)
			->select();
			
		foreach($lists as $k=>$v){
			$userinfo=$Users->field("user_nicename")->where("id='{$v[uid]}'")->find();
			if(!$userinfo){
				$userinfo=array(
					'user_nicename'=>'已删除'
				);
			}
			$lists[$k]['userinfo']= $userinfo;
			$touserinfo=$Users->field("user_nicename")->where("id='{$v[touid]}'")->find();
			if(!$touserinfo){
				$touserinfo=array(
					'user_nicename'=>'已删除'
				);
			}
			$lists[$k]['touserinfo']= $touserinfo;
		}			
			
    	$this->assign('lists', $lists);
    	$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display();
		
	}
	//标记处理：被举报视频
	function setstatus_report(){
		$id=intval($_GET['id']);
		if($id){
			$data['status']=1;
			$data['uptime']=time();
			$result=M("users_dialectreport")->where("id='{$id}'")->save($data);				
			if($result!==false){
				$this->success('标记成功');
			}else{
				$this->error('标记失败');
			}			
		}else{				
			$this->error('数据传入失败！');
		}								  		
	}		
	
	//系统点赞视频记录列表
	function systemlikesindex(){
		$map=array();
			
		
		if($_REQUEST['type']!=''){
			$map['type']=$_REQUEST['type']; 
			$_GET['type']=$_REQUEST['type'];
		}

		if($_REQUEST['keyword']!=''){
			/* $map['oscarid|videoid']=array("eq",$_REQUEST['keyword']);  */
			$map['videoid']=array("like","%".$_REQUEST['keyword']."%");  
			$_GET['keyword']=$_REQUEST['keyword'];
		}
		
		if($_REQUEST['start_time']!=''){
			$map['addtime']=array("gt",strtotime($_REQUEST['start_time']));
			$_GET['start_time']=$_REQUEST['start_time'];
		}

		if($_REQUEST['end_time']!=''){
		 
			$map['addtime']=array("lt",strtotime($_REQUEST['end_time']));
			$_GET['end_time']=$_REQUEST['end_time'];
		}
		if($_REQUEST['start_time']!='' && $_REQUEST['end_time']!='' ){
		 
			$map['addtime']=array("between",array(strtotime($_REQUEST['start_time']),strtotime($_REQUEST['end_time'])));
			$_GET['start_time']=$_REQUEST['start_time'];
			$_GET['end_time']=$_REQUEST['end_time'];
		}
		
    	$likes=M("system_likes");
    	$count=$likes->where($map)->count();
    	$page = $this->page($count, 20);
    	$lists = $likes
			->where($map)
			->order("addtime desc")
			->limit($page->firstRow . ',' . $page->listRows)
			->select();
		foreach($lists as $k=>$v){
			//用户信息
			$userinfo=M("users")->where("id=".$v['uid'])->find();
			if($userinfo){
				$lists[$k]['user_nicename']=$userinfo['user_nicename'];
			}else{
				$lists[$k]['user_nicename']="已删除";
			}
			//视频信息
			if($v['type']==1){//方言秀视频
				$videoinfo=M("users_dialect")->where("id=".$v['videoid'])->find();
			}else{
				$videoinfo=M("users_video")->where("id=".$v['videoid'])->find();
			}
			if($videoinfo){
				$lists[$k]['videoname']=$videoinfo['title'];
			}
			
		}
		$lists=array_values($lists);
    	$this->assign('lists', $lists);
    	$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	
    
		$this->display();		
	}
	//系统添加点赞
	function add_likevideo(){
		
		$this->display();	
		
	}
	
	function add_likevideopost(){
		if(IS_POST){			
			$likes=M("system_likes");

			$uid=I("uid");
			$videoid=I("videoid");
			//$type=I("type");
			$type=0;
			$userinfo=M("users")->where("id=".$uid)->find();
			if(!$userinfo){
				$this->error('该用户不存在');
			}
			if($type==1){//方言秀视频
				$dialectinfo=M("users_dialect")->where("id='".$videoid."'")->find();
				if(!$dialectinfo){
					$this->error('该视频不存在');
				}
			}else{
				$videoinfo=M("users_video")->where("id='".$videoid."'")->find();
				if(!$videoinfo){
					$this->error('该视频不存在');
				}
			}
		
			$likes->create();
			$likes->addtime=time();
			$result=$likes->add(); 
			if($result){
				if($type==1){//方言秀视频
					//方言秀点赞表添加
					$data=array("uid"=>$uid,"dialectid"=>$videoid,"addtime"=>time() );
					M("users_dialect_like")->add($data);
					M("users_dialect")->where("id='".$videoid."'")->setInc("likes",1);//方言秀累计用户点赞次数
				}else if($type==0){//短视频
					//短视频点赞表添加
					$data=array("uid"=>$uid,"videoid"=>$videoid,"addtime"=>time() );
					M("users_video_like")->add($data);
					M("users_video")->where("id='".$videoid."'")->setInc("likes",1);//短视频累计用户点赞次数
				}
			
				//用户添加点赞
				M()->execute("update __PREFIX__users set unexchange_like=unexchange_like+1,all_like=all_like+1 where id='{$uid}'");
				$this->success('添加成功');
			}else{
				$this->error('添加失败');
			}
		}	
	}
	//删除点赞记录
	function del_likevideo(){
		$id=intval($_GET['id']);
		if($id){
			$result=M("system_likes")->where("id={$id}")->delete();				
			if($result!==false){
				$this->success('删除成功');
			}else{
				$this->error('删除失败');
			}			
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display();		
	}
	//系统评论视频
	function systemcommentsindex(){
		$map=array();
			
		
		if($_REQUEST['type']!=''){
			$map['type']=$_REQUEST['type']; 
			$_GET['type']=$_REQUEST['type'];
		}

		if($_REQUEST['keyword']!=''){
			/* $map['oscarid|videoid']=array("eq",$_REQUEST['keyword']);  */
			$map['videoid']=array("like","%".$_REQUEST['keyword']."%");  
			$_GET['keyword']=$_REQUEST['keyword'];
		}
		
		if($_REQUEST['start_time']!=''){
			$map['addtime']=array("gt",strtotime($_REQUEST['start_time']));
			$_GET['start_time']=$_REQUEST['start_time'];
		}

		if($_REQUEST['end_time']!=''){
		 
			$map['addtime']=array("lt",strtotime($_REQUEST['end_time']));
			$_GET['end_time']=$_REQUEST['end_time'];
		}
		if($_REQUEST['start_time']!='' && $_REQUEST['end_time']!='' ){
		 
			$map['addtime']=array("between",array(strtotime($_REQUEST['start_time']),strtotime($_REQUEST['end_time'])));
			$_GET['start_time']=$_REQUEST['start_time'];
			$_GET['end_time']=$_REQUEST['end_time'];
		}
		
    	$likes=M("system_comments");
    	$count=$likes->where($map)->count();
    	$page = $this->page($count, 20);
    	$lists = $likes
			->where($map)
			->order("addtime desc")
			->limit($page->firstRow . ',' . $page->listRows)
			->select();
		foreach($lists as $k=>$v){
			//用户信息
			$userinfo=M("users")->where("id=".$v['uid'])->find();
			if($userinfo){
				$lists[$k]['user_nicename']=$userinfo['user_nicename'];
			}else{
				$lists[$k]['user_nicename']="已删除";
			}
			//视频信息
			if($v['type']==1){//方言秀视频
				$videoinfo=M("users_dialect")->where("id=".$v['videoid'])->find();
			}else{
				$videoinfo=M("users_video")->where("id=".$v['videoid'])->find();
			}
			if($videoinfo){
				$lists[$k]['videoname']=$videoinfo['title'];
			}
			
		}
		$lists=array_values($lists);
    	$this->assign('lists', $lists);
    	$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	
    
		$this->display();		
	}
	//系统添加评论
	function add_commentvideo(){
		
		$this->display();
	}
	function add_commentvideopost(){
		if(IS_POST){			
			$likes=M("system_comments");

			$uid=I("uid");
			$videoid=I("videoid");
			//$type=I("type");//类型：0：短视频；1：方言秀
			$type=0;
			$content=I("content");//评论内容
			$userinfo=M("users")->where("id=".$uid)->find();
			if(!$userinfo){
				$this->error('该用户不存在');
			}
			if($type==1){//方言秀视频
				$dialectinfo=M("users_dialect")->where("id='".$videoid."'")->find();
				if(!$dialectinfo){
					$this->error('该视频不存在');
				}
			}else{
				$videoinfo=M("users_video")->where("id='".$videoid."'")->find();
				if(!$videoinfo){
					$this->error('该视频不存在');
				}
			}
		
		
			$likes->create();
			$likes->addtime=time();
			$result=$likes->add(); 
			if($result){
				if($type==1){//方言秀视频
					 
					//方言秀点赞表添加
					$data=array(
						'uid'=>$uid,
						'touid'=>$dialectinfo['uid'],
						'dialectid'=>$videoid,
						'content'=>$content,
						'addtime'=>time(),
					);
					M("users_dialect_comments")->add($data);
					M("users_dialect")->where("id='".$videoid."'")->setInc("comments",1);//方言秀累计用户点赞次数
				}else if($type==0){//短视频
					//短视频点赞表添加
					$data=array(
						'uid'=>$uid,
						'touid'=>$videoinfo['uid'],
						'videoid'=>$videoid,
						'content'=>$content,
						'addtime'=>time(),
					);
					M("users_video_comments")->add($data);
					M("users_video")->where("id='".$videoid."'")->setInc("comments",1);//短视频累计用户点赞次数
				}
				$this->success('添加成功');
			}else{
				$this->error('添加失败');
			}
		}	
	}

	//删除评论记录
	function del_commentvideo(){
		$id=intval($_GET['id']);
		if($id){
			$result=M("system_comments")->where("id={$id}")->delete();				
			if($result!==false){
				$this->success('删除成功');
			}else{
				$this->error('删除失败');
			}			
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display();		
	}
	
	//系统警告视频
	function systemwarningindex(){
		$map=array();
			
		
		if($_REQUEST['type']!=''){
			$map['type']=$_REQUEST['type']; 
			$_GET['type']=$_REQUEST['type'];
		}

		if($_REQUEST['keyword']!=''){
			/* $map['oscarid|videoid']=array("eq",$_REQUEST['keyword']);  */
			$map['videoid']=array("like","%".$_REQUEST['keyword']."%");  
			$_GET['keyword']=$_REQUEST['keyword'];
		}
		
		if($_REQUEST['start_time']!=''){
			$map['addtime']=array("gt",strtotime($_REQUEST['start_time']));
			$_GET['start_time']=$_REQUEST['start_time'];
		}

		if($_REQUEST['end_time']!=''){
		 
			$map['addtime']=array("lt",strtotime($_REQUEST['end_time']));
			$_GET['end_time']=$_REQUEST['end_time'];
		}
		if($_REQUEST['start_time']!='' && $_REQUEST['end_time']!='' ){
		 
			$map['addtime']=array("between",array(strtotime($_REQUEST['start_time']),strtotime($_REQUEST['end_time'])));
			$_GET['start_time']=$_REQUEST['start_time'];
			$_GET['end_time']=$_REQUEST['end_time'];
		}
		
    	$likes=M("system_warning");
    	$count=$likes->where($map)->count();
    	$page = $this->page($count, 20);
    	$lists = $likes
			->where($map)
			->order("addtime desc")
			->limit($page->firstRow . ',' . $page->listRows)
			->select();
		foreach($lists as $k=>$v){
			//用户信息
			$userinfo=M("users")->where("id=".$v['uid'])->find();
			if($userinfo){
				$lists[$k]['user_nicename']=$userinfo['user_nicename'];
			}else{
				$lists[$k]['user_nicename']="已删除";
			}
			//视频信息
			if($v['type']==1){//方言秀视频
				$videoinfo=M("users_dialect")->where("id=".$v['videoid'])->find();
			}else{
				$videoinfo=M("users_video")->where("id=".$v['videoid'])->find();
			}
			if($videoinfo){
				$lists[$k]['videoname']=$videoinfo['title'];
			}
			
		}
		$lists=array_values($lists);
    	$this->assign('lists', $lists);
    	$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	
    
		$this->display();		
	}
	//警告视频
	function add_warningvideo(){
		$this->display();
	}
	
	function add_warningvideopost(){
		if(IS_POST){			
			$warning=M("system_warning");

			
			$videoid=I("videoid");
			//$type=I("type");//类型：0：短视频；1：方言秀
			$type=0;
			$content=I("content");//评论内容
			if( $content==''){
				$this->error("信息不全，请填写完整");
			}
			if($type==1){//方言秀视频
				$videoinfo=M("users_dialect")->where("id='".$videoid."'")->find();
				if(!$videoinfo){
					$this->error('该视频不存在');
				}
			}else{
				$videoinfo=M("users_video")->where("id='".$videoid."'")->find();
				if(!$videoinfo){
					$this->error('该视频不存在');
				}
			}
			$uid=$videoinfo["uid"];
			$userinfo=M("users")->where("id=".$uid)->find();
			if(!$userinfo){
				$this->error('该用户不存在');
			}
			$warning->create();
			$warning->uid=$uid;
			$warning->addtime=time();
			$result=$warning->add(); 
			if($result){
				$configpri=getConfigPri();
				/* 极光推送 */
				$app_key = $configpri['jpush_key'];
				$master_secret = $configpri['jpush_secret'];
				
				if($app_key && $master_secret){
					require SITE_PATH.'api/public/JPush/autoload.php';
					// 初始化
					$client = new \JPush\Client($app_key, $master_secret,null);
					$alias=array();
					
					$alias[]=$videoinfo['uid'].'PUSH';		
					try{
						$result1 = $client->push()
								->setPlatform('all')
								->addAlias($alias)
								->setNotificationAlert('您的标题为"'.$videoinfo['title'].'"的视频：'.$content)
								 ->iosNotification('您的标题为"'.$videoinfo['title'].'"的视频：'.$content, array(
									'sound' => 'sound.caf',
									'category' => 'jiguang',
									/* 'extras' => array(
										'userinfo' => $anthorinfo 
									),*/
								)) 
								->androidNotification('您的标题为"'.$videoinfo['title'].'"的视频：'.$content, array(
									/* 'extras' => array(
										'userinfo' => $anthorinfo
									), */
								))
								->options(array(
									'sendno' => 100,
									'time_to_live' => 0,
									'apns_production' =>  $configpri['jpush_sandbox'],
								))
								->send();		
							$this->success("发送成功");
					
					} catch (Exception $e) {   
					/* 	file_put_contents('./jpush.txt',date('y-m-d h:i:s').'提交参数信息 设备名:'.json_encode($alias)."\r\n",FILE_APPEND);
						file_put_contents('./jpush.txt',date('y-m-d h:i:s').'提交参数信息:'.$e."\r\n",FILE_APPEND); */
					}					
					// }
				}
				$this->error('添加失败');
			}else{
				$this->error('添加失败');
			}
		}	
	}


	//删除警告记录
	function del_woringvideo(){
		$id=intval($_GET['id']);
		if($id){
			$result=M("system_warning")->where("id={$id}")->delete();				
			if($result!==false){
				$this->success('删除成功');
			}else{
				$this->error('删除失败');
			}			
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display();		
	}

}
