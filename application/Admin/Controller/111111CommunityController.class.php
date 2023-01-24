<?php

/**
 * 短圈子
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
use QCloud\Cos\Api;
use QCloud\Cos\Auth;



class CommunityController extends AdminbaseController {

	/*待审核圈子列表*/
    function index(){
		
		if($_REQUEST['ordertype']!=''){
			$ordertype=$_REQUEST['ordertype'];
			$_GET['ordertype']=$_REQUEST['ordertype'];
		}
		 $map['isdel']=0;
		 $map['status']=0; 
		
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
		
		$p=I("p");
		if(!$p){
			$p=1;
		}

    	$community_model=M("users_community");
    	$count=$community_model->where($map)->count();
    	$page = $this->page($count, 20);
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
		
    	$lists = $community_model
			->where($map)
			->order($orderstr)
			->limit($page->firstRow . ',' . $page->listRows)
			->select();
			
		foreach($lists as $k=>$v){
			if($v['uid']==0){
				$userinfo=array(
					'user_nicename'=>'系统管理员'
				);
			}else{
				$userinfo=getUserInfo($v['uid']);
				if(!$userinfo){
					$userinfo=array(
						'user_nicename'=>'已删除'
					);
				}
				
			}

			$lists[$k]['imgs']=explode(',', $v['imgs']);
			$lists[$k]['userinfo']=$userinfo;
			
			$hasurgemoney=($v['big_urgenums']-$v['urge_nums'])*$v['urge_money'];
			$lists[$k]['hasurgemoney']=$hasurgemoney;
		}
    	$this->assign('lists', $lists);
		$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	$this->assign("p",$p);
    	$this->display();
    }


     /*未通过圈子列表*/
	
    function nopassindex(){
		
		if($_REQUEST['ordertype']!=''){
			$ordertype=$_REQUEST['ordertype'];
			$_GET['ordertype']=$_REQUEST['ordertype'];
		}
		 $map['isdel']=0; 
		 $map['status']=2; 
		
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
		
		$p=I("p");
		if(!$p){
			$p=1;
		}

    	$community_model=M("users_community");
    	$count=$community_model->where($map)->count();
    	$page = $this->page($count, 20);
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
		
    	$lists = $community_model
			->where($map)
			->order($orderstr)
			->limit($page->firstRow . ',' . $page->listRows)
			->select();
		foreach($lists as $k=>$v){
			if($v['uid']==0){
				$userinfo=array(
					'user_nicename'=>'系统管理员'
				);
			}else{
				$userinfo=getUserInfo($v['uid']);
				if(!$userinfo){
					$userinfo=array(
						'user_nicename'=>'已删除'
					);
				}
				
			}

			
			$lists[$k]['userinfo']=$userinfo;
			
			$hasurgemoney=($v['big_urgenums']-$v['urge_nums'])*$v['urge_money'];
			$lists[$k]['hasurgemoney']=$hasurgemoney;
		}
    	$this->assign('lists', $lists);
		$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	$this->assign("p",$p);
    	$this->display();
    }


    /*审核通过圈子列表*/
	
    function passindex(){
		
		if($_REQUEST['ordertype']!=''){
			$ordertype=$_REQUEST['ordertype'];
			$_GET['ordertype']=$_REQUEST['ordertype'];
		}
		 $map['isdel']=0; 
		 $map['status']=1; 
		 $map['cate']=0;//普通圈子列表不含频道编号
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
		
		$p=I("p");
		if(!$p){
			$p=1;
		}

    	$community_model=M("users_community");
    	$count=$community_model->where($map)->count();
    	$page = $this->page($count, 20);
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
		
    	$lists = $community_model
			->where($map)
			->order($orderstr)
			->limit($page->firstRow . ',' . $page->listRows)
			->select();
		foreach($lists as $k=>$v){
			if($v['uid']==0){
				$userinfo=array(
					'user_nicename'=>'系统管理员'
				);
			}else{
				$userinfo=getUserInfo($v['uid']);
				if(!$userinfo){
					$userinfo=array(
						'user_nicename'=>'已删除'
					);
				}
				
			}

			
			$lists[$k]['userinfo']=$userinfo;
			
			$hasurgemoney=($v['big_urgenums']-$v['urge_nums'])*$v['urge_money'];
			$lists[$k]['hasurgemoney']=$hasurgemoney;
		}
    	$this->assign('lists', $lists);
		$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	$this->assign("p",$p);
    	$this->display();
    }
   function cateindex(){
		
		if($_REQUEST['ordertype']!=''){
			$ordertype=$_REQUEST['ordertype'];
			$_GET['ordertype']=$_REQUEST['ordertype'];
		}
		 $map['isdel']=0; 
		 $map['status']=1; 
		 $map['cate'] = array('gt',0);//圈子列表包含频道编号
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
		
		$p=I("p");
		if(!$p){
			$p=1;
		}

    	$community_model=M("users_community");
    	$count=$community_model->where($map)->count();
    	$page = $this->page($count, 20);
		$orderstr="";
		if($ordertype==1){//评论数排序
			$orderstr="comments DESC";
		}else if($ordertype==2){//点赞数量排序（点赞）
			$orderstr="likes DESC";
		}else if($ordertype==3){//浏览数量排序
			$orderstr="views DESC";
		}else{
			$orderstr="addtime DESC";
		}
		
    	$lists = $community_model
			->where($map)
			->order($orderstr)
			->limit($page->firstRow . ',' . $page->listRows)
			->select();
		foreach($lists as $k=>$v){
			if($v['uid']==0){
				$userinfo=array(
					'user_nicename'=>'系统管理员'
				);
			}else{
				$userinfo=getUserInfo($v['uid']);
				if(!$userinfo){
					$userinfo=array(
						'user_nicename'=>'已删除'
					);
				}
				
			}

			$lists[$k]['imgs']=explode(',', $v['imgs']);
			$lists[$k]['userinfo']=$userinfo;
			
			$hasurgemoney=($v['big_urgenums']-$v['urge_nums'])*$v['urge_money'];
			$lists[$k]['hasurgemoney']=$hasurgemoney;
		}
    	$this->assign('lists', $lists);
		$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	$this->assign("p",$p);
    	$this->display();
    }

		
	function del(){

		$res=array("code"=>0,"msg"=>"删除成功","info"=>array());
		$id=I("id");
		$reason=I("reason");
		if(!$id){

			$res['code']=1001;
			$res['msg']='圈子信息加载失败';
			echo json_encode($res);
			exit;
		}	

		//获取圈子信息
		$communityInfo=M("users_community")->where("id={$id}")->find();

		$result=M("users_community")->where("id={$id}")->delete();
		
		//$result=M("users_community")->where("id={$id}")->setField("isdel","1");

		if($result!==false){

			M("users_community_black")->where("communityid={$id}")->delete();	 //删除圈子拉黑
			M("users_community_comments_at_messages")->where("communityid={$id}")->delete(); //删除圈子评论@信息列表
			M("users_community_comments_messages")->where("communityid={$id}")->delete(); //删除圈子评论信息列表
			M("praise_messages")->where("communityid={$id}")->delete(); //删除赞通知列表
			M("users_community_comments")->where("communityid={$id}")->delete();	 //删除圈子评论
			M("users_community_like")->where("communityid={$id}")->delete();	 //删除圈子喜欢
			M("users_community_report")->where("communityid={$id}")->delete();	 //删除圈子举报
			
			/*//获取该圈子的评论id
			$commentlists=M("users_community_comments")->field("id")->where("communityid={$id}")->select();
			$commentids="";
			foreach($commentlists as $k=>$v){
				if($commentids==""){
					$commentids=$v['id'];
				}else{
					$commentids.=",".$v['id'];
				}
			}

			//删除圈子评论喜欢
			$map['commentid']=array("in",$commentids);*/


			M("users_community_comments_like")->where("communityid={$id}")->delete(); //删除圈子评论喜欢

			

			if($communityInfo['isdel']==0){ //圈子上架情况下被删除发送通知
				//极光IM
				$id=$_SESSION['ADMIN_ID'];
				$user=M("Users")->where("id='{$id}'")->find();

	    		//向系统通知表中写入数据
	    		$sysInfo=array(
	    			'title'=>'圈子删除提醒',
	    			'addtime'=>time(),
	    			'admin'=>$user['user_login'],
	    			'ip'=>$_SERVER['REMOTE_ADDR'],
	    			'uid'=>$communityInfo['uid']

	    		);

	    		$baseMsg='您于'.date("Y-m-d H:i:s",$communityInfo['addtime']).'上传的《'.$communityInfo['title'].'》圈子被管理员于'.date("Y-m-d H:i:s",time()).'删除';

	    		if(!$reason){
	    			$sysInfo['content']=$baseMsg;
	    		}else{
	    			$sysInfo['content']=$baseMsg.',删除原因为：'.$reason;
	    		}

	    		$result1=M("system_push")->add($sysInfo);

	    		if($result1!==false){

	    			$text="圈子删除提醒";
	    			$uid=$communityInfo['uid'];
	    			jMessageIM($text,$uid);

	    		}
			}
			



			$res['msg']='圈子删除成功';
			echo json_encode($res);
			exit;
		}else{
			$res['code']=1002;
			$res['msg']='圈子删除失败';
			echo json_encode($res);
			exit;
		}			
										  			
	}		
    //排序
    public function listorders() { 
		
        $ids = $_POST['listorders'];
        foreach ($ids as $key => $r) {
            $data['orderno'] = $r;
            M("users_community")->where(array('id' => $key))->save($data);
        }
				
        $status = true;
        if ($status) {
            $this->success("排序更新成功！");
        } else {
            $this->error("排序更新失败！");
        }
    }	
    

	function add(){
		$this->display();				
	}
  function cateadd(){
		$this->display();				
	}

	function add_post(){


		/*Array ( [status] => 1 [owner] => 0 [cate] => 71 [owner_uid] => [title] => 我是猪3 [content] =>
萨芬搭嘎岁的肤色
[show_types] => 1 [imgs] => http://ceshi.maiyatang.info//20200115_5e1eb6afd908c.png [video_upload_type] => 1 [thumb] => [file] => [istop] => 0 [views] => 26 [videolink] => )


Array ( [status] => 1 [owner] => 0 [cate] => 76 [owner_uid] => [title] => 我是猪6 [content] =>
打算放过合法合规

[show_types] => 2 [imgs] => [video_upload_type] => 0 [thumb] => http://ceshi.maiyatang.info//20200115_5e1eba07c72df.png [file] => [istop] => 0 [views] => 0 [videolink] => http://ceshi.maiyatang.info//video_298560_IOS_20200115110928.mp4 )
*/


		if(IS_POST){
			
			$community=M("users_community");
			$community->create();
			$community->addtime=time();
			$community->uid=0;
			
			$owner=$_POST['owner'];
			$owner_uid=$_POST['owner_uid'];
          

			if($owner==1){

				if($owner_uid==""||!is_numeric($owner_uid)){
					$this->error("请填写圈子所有者id");
					return;
				}

				//判断用户是否存在
				$ownerInfo=M("users")->where("user_type=2 and id={$owner_uid}")->find();
				if(!$ownerInfo){
					$this->error("圈子所有者不存在");
					return;
				}

				$community->uid=$owner_uid;

			}

			/**
            *  后台圈子必须提交所属圈子分类，记录一、二级分类id
            */
          	if($_POST["cate"]=='' || empty($_POST["cate"]))
          	{
            	$this->error("请选择一、二级分类");
				return;
          	}
          	

			if($_POST['show_types']==1){
				if(($_POST['videolink'] && !$_POST['thumb']) || (!$_POST['videolink'] && $_POST['thumb'])){
					$this->error('视频链接或封面必传');return;
				}
			}else{
				if(!$_POST['thumb']){
					$this->error('请上传视频封面');return;
				}
			}
          	

          	$community->cate =$_POST["cate"];

			$url=$_POST['videolink'];
			$thumb=$_POST['thumb'];//视频封面图
			$imgs=$_POST['imgs'];

			$title=$_POST['title'];
			if($title==""){
				$this->error("请填写圈子标题");
			}
			$content=$_POST['content'];
			if($content==""){
				$this->error("请填写圈子内容");
			}

			
			$community->imgs=$_POST['imgs'];
			$community->cate =$_POST["cate"];
			if($url!="" || $imgs !=''){

				//判断链接地址的正确性
				if($url){
					if(strpos($url,'http')!==false||strpos($url,'https')!==false){

						$community_type1=substr(strrchr($url, '.'), 1);
						if(strtolower($community_type1)!='mp4'){
							$this->error("文件名后缀必须为mp4格式");
						}

						$community->videolink=$thumb.','.$url;

					}else{

						$this->error("请填写正确的圈子视频地址");

					}
				}
			
				if($imgs){
					if(strpos($thumb,'http')!==false||strpos($thumb,'https')!==false){

						$community_type2=substr(strrchr($thumb, '.'), 1);
						if(!in_array(strtolower($community_type2), ['jpg','jpeg','png','tif','gif','bmp'])){
							$this->error("图片格式不正确");
						}

						$community->imgs=$imgs;

					}else{

						$this->error("请填写正确的圈子图片地址");

					}
				}

				
				

			}else{



				//获取后台上传配置
				$configpri=getConfigPri();

				$show_val=$configpri['show_val'];

				$community->show_val=$show_val;

				//var_dump($configpri);
				if($configpri['cloudtype']==1){  //七牛云存储

					$savepath=date('Ymd').'/';
					//上传处理类
		            $config=array(
		            		'rootPath' => './'.C("UPLOADPATH"),
		            		'savePath' => $savepath,
		            		'maxSize' => 100*1048576, //100M
		            		'saveName'   =>    array('uniqid',''),
		            		'exts'       =>    array('mp4'),
		            		'autoSub'    =>    false,
		            );

					$config_qiniu = array(
 
						'accessKey' => $configpri['qiniu_accesskey'], //这里填七牛AK
						'secretKey' => $configpri['qiniu_secretkey'], //这里填七牛SK
						'domain' => $configpri['qiniu_domain'],//这里是域名
						'bucket' => $configpri['qiniu_bucket']//这里是七牛中的“空间”
					);


		            $upload = new \Think\Upload($config,'Qiniu',$config_qiniu);


		            $info = $upload->upload();

		            if ($info) {
		                //上传成功
		                //写入附件数据库信息
		                $first=array_shift($info);
		                if(!empty($first['url'])){
		                	$url=$first['url'];
		                	
		                }else{
		                	$url=C("TMPL_PARSE_STRING.__UPLOAD__").$savepath.$first['savename'];
		                	
		                }
		                
						/*echo "1," . $url.",".'1,'.$first['name'];
						exit;*/


		            } else {
		                //上传失败，返回错误
		                //exit("0," . $upload->getError());
		                $this->error('添加失败3');
		            }



				}else if($configpri['cloudtype']==2){ //腾讯云存储

					/* 腾讯云 */
					require(SITE_PATH.'api/public/txcloud/include.php');
					//bucketname
					$bucket = $configpri['txcloud_bucket'];

					$src = $_FILES["file"]["tmp_name"];
					
					//var_dump("src".$src);

					//cosfolderpath
					$folder = '/'.$configpri['tximgfolder'];
					//cospath
					$dst = $folder.'/'.$_FILES["file"]["name"];
					//config your information
					$config = array(
						'app_id' => $configpri['txcloud_appid'],
						'secret_id' => $configpri['txcloud_secret_id'],
						'secret_key' => $configpri['txcloud_secret_key'],
						'region' => $configpri['txcloud_region'],   // bucket所属地域：华北 'tj' 华东 'sh' 华南 'gz'
						'timeout' => 60
					);

					date_default_timezone_set('PRC');
					$cosApi = new 	Api($config);

					$ret = $cosApi->upload($bucket, $src, $dst);

					

					if($ret['code']!=0){
						//上传失败，返回错误
						//exit("0," . $ret['message']);
						$this->error('添加失败1');
					}

					$url = $ret['data']['source_url'];
					
					$url = $this->get_path($url);
				}else if($configpri['cloudtype']==3){
					$savepath=date('Ymd').'/';
					//上传处理类
		            $config=array(
		            		'rootPath' => './'.C("UPLOADPATH"),
		            		'savePath' => $savepath,
		            		'maxSize' => 100*1048576, //100M
		            		'saveName'   =>    array('uniqid',''),
		            		'exts'       =>    array('mp4'),
		            		'autoSub'    =>    false,
		            );

					$upload = new \Think\Upload($config,'LOCAL');//指定本地驱动
					$info=$upload->upload();
					if($info){
						$url = 'http://'.$_SERVER['HTTP_HOST'].'/data/upload/'.$savepath.$info["file"]["savename"]; //加速地址
					}else{
						echo $upload->getError();
					}
				}


				$community->videolink=$url;
			}

          
     
			$result=$community->add();

			if($result){
				$this->success('添加成功','Admin/Community/add',3);
			}else{
				$this->error('添加失败2');
			}
		}			
	}

  function cateadd_post(){
		if(IS_POST){			
			$community=M("users_community");
			$community->create();
			$community->addtime=time();
			$community->uid=0;
			
			$owner=$_POST['owner'];
			$owner_uid=$_POST['owner_uid'];
          

			if($owner==1){

				if($owner_uid==""||!is_numeric($owner_uid)){
					$this->error("请填写圈子所有者id");
					return;
				}

				//判断用户是否存在
				$ownerInfo=M("users")->where("user_type=2 and id={$owner_uid}")->find();
				if(!$ownerInfo){
					$this->error("圈子所有者不存在");
					return;
				}

				$community->uid=$owner_uid;

			}



			$url=$_POST['href'];
			$title=$_POST['title'];
			$thumb=$_POST['thumb'];

			if($title==""){
				$this->error("请填写圈子标题");
			}

			if($thumb==""){
				$this->error("请上传圈子封面");
			}

			$community->thumb_s=$_POST['thumb'];

			if($url!=""){

				//判断链接地址的正确性
				if(strpos($url,'http')!==false||strpos($url,'https')!==false){

					$community_type=substr(strrchr($url, '.'), 1);
					if(strtolower($community_type)!='mp4'){
						$this->error("文件名后缀必须为mp4格式");
					}

					$community->href=$url;

				}else{

					$this->error("请填写正确的圈子地址");

				}

				
				

			}else{



				//获取后台上传配置
				$configpri=getConfigPri();

				$show_val=$configpri['show_val'];

				$community->show_val=$show_val;

				//var_dump($configpri);
				if($configpri['cloudtype']==1){  //七牛云存储

					$savepath=date('Ymd').'/';
					//上传处理类
		            $config=array(
		            		'rootPath' => './'.C("UPLOADPATH"),
		            		'savePath' => $savepath,
		            		'maxSize' => 100*1048576, //100M
		            		'saveName'   =>    array('uniqid',''),
		            		'exts'       =>    array('mp4'),
		            		'autoSub'    =>    false,
		            );

					$config_qiniu = array(
 
						'accessKey' => $configpri['qiniu_accesskey'], //这里填七牛AK
						'secretKey' => $configpri['qiniu_secretkey'], //这里填七牛SK
						'domain' => $configpri['qiniu_domain'],//这里是域名
						'bucket' => $configpri['qiniu_bucket']//这里是七牛中的“空间”
					);


		            $upload = new \Think\Upload($config,'Qiniu',$config_qiniu);


		            $info = $upload->upload();

		            if ($info) {
		                //上传成功
		                //写入附件数据库信息
		                $first=array_shift($info);
		                if(!empty($first['url'])){
		                	$url=$first['url'];
		                	
		                }else{
		                	$url=C("TMPL_PARSE_STRING.__UPLOAD__").$savepath.$first['savename'];
		                	
		                }
		                
						/*echo "1," . $url.",".'1,'.$first['name'];
						exit;*/


		            } else {
		                //上传失败，返回错误
		                //exit("0," . $upload->getError());
		                $this->error('添加失败');
		            }



				}else if($configpri['cloudtype']==2){ //腾讯云存储

					/* 腾讯云 */
					require(SITE_PATH.'api/public/txcloud/include.php');
					//bucketname
					$bucket = $configpri['txcloud_bucket'];

					$src = $_FILES["file"]["tmp_name"];
					
					//var_dump("src".$src);

					//cosfolderpath
					$folder = '/'.$configpri['tximgfolder'];
					//cospath
					$dst = $folder.'/'.$_FILES["file"]["name"];
					//config your information
					$config = array(
						'app_id' => $configpri['txcloud_appid'],
						'secret_id' => $configpri['txcloud_secret_id'],
						'secret_key' => $configpri['txcloud_secret_key'],
						'region' => $configpri['txcloud_region'],   // bucket所属地域：华北 'tj' 华东 'sh' 华南 'gz'
						'timeout' => 60
					);

					date_default_timezone_set('PRC');
					$cosApi = new 	Api($config);

					$ret = $cosApi->upload($bucket, $src, $dst);

					

					if($ret['code']!=0){
						//上传失败，返回错误
						//exit("0," . $ret['message']);
						$this->error('添加失败');
					}

					$url = $ret['data']['source_url'];
					
					
				}


				$community->href=$url;
			}

          
          
           /**
            *  后台圈子必须提交所属直播分类，记录一、二级分类id
            */
          if($_POST["cate"]=='')
          {
            $this->error("请选择一、二级分类");
			return;
          }
          $community->cate =$_POST["cate"];
          // $community->subcate = $_POST["subcate"];
          
          
				


			$result=$community->add();

			if($result){
				$this->success('添加成功','Admin/Community/add',3);
			}else{
				$this->error('添加失败');
			}
		}			
	}
  
  
  
	function edit(){
		$id=intval($_GET['id']);
		$from=I("from");
		if($id){
			$community=M("users_community")->where("id={$id}")->find();
			if($community['uid']==0){
				$userinfo=array(
					'user_nicename'=>'系统管理员'
				);
			}else{
				$userinfo=getUserInfo($community['uid']);
				if(!$userinfo){
					$userinfo=array(
						'user_nicename'=>'已删除'
					);
				}
			}
			
			$community['userinfo']=$userinfo;
			$this->assign('community', $community);						
		}else{				
			$this->error('数据传入失败！');
		}
		$this->assign("from",$from);							  
		$this->display();				
	}
	
	function edit_post(){
		if(IS_POST){

			$community=M("users_community");
			$community->create();

			$id=$_POST['id'];
			$title=$_POST['title'];
			$thumb=$_POST['thumb'];
			$type=$_POST['community_upload_type'];
			$url=$_POST['href'];
			$status=$_POST['status'];
			$isdel=$_POST['isdel'];
			$nopasstime=$_POST['nopasstime'];
			


			/*if($title==""){
				$this->error("请填写圈子标题");
			}*/

			if($thumb==""){
				$this->error("请上传圈子封面");
			}

			$community->thumb_s=$_POST['thumb'];

			if($type!=''){

				if($type==0){ //视频链接型式
					if($url==''){
						$this->error("请填写视频链接地址");
					}

					//判断链接地址的正确性
					if(strpos($url,'http')!==false||strpos($url,'https')!==false){

						$community_type=substr(strrchr($url, '.'), 1);

						if(strtolower($community_type)!='mp4'){
							$this->error("文件名后缀必须为mp4格式");
						}

						$community->href=$url;

					}else{

						$this->error("请填写正确的圈子地址");

					}


				}else if($type==1){ //文件上传型式

					$savepath=date('Ymd').'/';

					//获取后台上传配置
					$configpri=getConfigPri();

					//var_dump($configpri);
					if($configpri['cloudtype']==1){  //七牛云存储


						//上传处理类
			            $config=array(
			            		'rootPath' => './'.C("UPLOADPATH"),
			            		'savePath' => $savepath,
			            		'maxSize' => 100*1048576, //100M
			            		'saveName'   =>    array('uniqid',''),
			            		'exts'       =>    array('mp4'),
			            		'autoSub'    =>    false,
			            );

						$config_qiniu = array(
	 
							'accessKey' => $configpri['qiniu_accesskey'], //这里填七牛AK
							'secretKey' => $configpri['qiniu_secretkey'], //这里填七牛SK
							'domain' => $configpri['qiniu_domain'],//这里是域名
							'bucket' => $configpri['qiniu_bucket']//这里是七牛中的“空间”
						);


			            $upload = new \Think\Upload($config,'Qiniu',$config_qiniu);


			            $info = $upload->upload();

			            if ($info) {
			                //上传成功
			                //写入附件数据库信息
			                $first=array_shift($info);
			                if(!empty($first['url'])){
			                	$url=$first['url'];
			                	
			                }else{
			                	$url=C("TMPL_PARSE_STRING.__UPLOAD__").$savepath.$first['savename'];
			                	
			                }
			                
							/*echo "1," . $url.",".'1,'.$first['name'];
							exit;*/


			            } else {
			                //上传失败，返回错误
			                //exit("0," . $upload->getError());
			                $this->error('圈子文件上传失败');
			            }



					}else if($configpri['cloudtype']==2){ //腾讯云存储

						/* 腾讯云 */
						require(SITE_PATH.'api/public/txcloud/include.php');
						//bucketname
						$bucket = $configpri['txcloud_bucket'];

						$src = $_FILES["file"]["tmp_name"];
						
						//var_dump("src".$src);

						//cosfolderpath
						$folder = '/'.$configpri['tximgfolder'];
						//cospath
						$dst = $folder.'/'.$_FILES["file"]["name"];
						//config your information
						$config = array(
							'app_id' => $configpri['txcloud_appid'],
							'secret_id' => $configpri['txcloud_secret_id'],
							'secret_key' => $configpri['txcloud_secret_key'],
							'region' => $configpri['txcloud_region'],   // bucket所属地域：华北 'tj' 华东 'sh' 华南 'gz'
							'timeout' => 60
						);

						date_default_timezone_set('PRC');
						$cosApi = new 	Api($config);

						$ret = $cosApi->upload($bucket, $src, $dst);

						

						if($ret['code']!=0){
							//上传失败，返回错误
							//exit("0," . $ret['message']);
							$this->error('圈子文件上传失败');
						}

						$url = $ret['data']['source_url'];
						
						
					}else if($configpri['cloudtype']==3){
						$savepath=date('Ymd').'/';
						//上传处理类
			            $config=array(
			            		'rootPath' => './'.C("UPLOADPATH"),
			            		'savePath' => $savepath,
			            		'maxSize' => 100*1048576, //100M
			            		'saveName'   =>    array('uniqid',''),
			            		'exts'       =>    array('mp4'),
			            		'autoSub'    =>    false,
			            );

						$upload = new \Think\Upload($config,'LOCAL');//指定本地驱动
						$info=$upload->upload();
						if($info){
							$url = 'http://'.$_SERVER['HTTP_HOST'].'/data/upload/'.$savepath.$info["file"]["savename"]; //加速地址
						}else{
							echo $upload->getError();
						}
					}


				}


				$community->href=$url;
			}else{

				//获取该圈子的href
				$url=$community->where("id={$id}")->getField("href");

				$community->href=$url;
			}

			if($status==2){
				$community->nopass_time=time();
			}

			//审核通过给该圈子添加曝光值（改为接口添加圈子时直接添加曝光值）
			// if($status==1){
			// 	$community->show_val=100;
			// }

			$result=$community->save();

			if($result!==false){

				if($status==2||$isdel==1){  //如果该圈子下架或圈子状态改为不通过，需要将圈子喜欢列表的状态更改
					M("users_community_like")->where("communityid={$id}")->setField('status',0);
				}

				if($status==2&&$nopasstime==0){  //圈子状态为审核不通过且为第一次审核为不通过，发送极光IM

					$communityInfo=M("users_community")->where("id={$id}")->find();

					$id=$_SESSION['ADMIN_ID'];
					$user=M("Users")->where("id='{$id}'")->find();

		    		//向系统通知表中写入数据
		    		$sysInfo=array(
		    			'title'=>'圈子未审核通过提醒',
		    			'addtime'=>time(),
		    			'admin'=>$user['user_login'],
		    			'ip'=>$_SERVER['REMOTE_ADDR'],
		    			'uid'=>$communityInfo['uid']

		    		);

		    		$baseMsg='您于'.date("Y-m-d H:i:s",$communityInfo['addtime']).'上传的《'.$communityInfo['title'].'》圈子被管理员于'.date("Y-m-d H:i:s",time()).'审核为不通过';;

		    		
		    		$sysInfo['content']=$baseMsg;
		    		

		    		$result1=M("system_push")->add($sysInfo);

		    		if($result1!==false){

		    			$text="圈子未审核通过提醒";
		    			$uid=$communityInfo['uid'];
		    			jMessageIM($text,$uid);

		    		}

				}

				$this->success('修改成功');
			 }else{
				$this->error('修改失败');
			 }
		}			
	}
	
    function reportlist(){

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

		$p=I("p");
		if(!$p){
			$p=1;
		}		
			
    	$Report=M("users_community_report");
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
    	$this->assign("p",$p);
    	$this->display();
    }
		
	function setstatus(){
		$id=intval($_GET['id']);
		if($id){
			$data['status']=1;
			$data['uptime']=time();
			$result=M("users_community_report")->where("id='{$id}'")->save($data);				
			if($result!==false){
				$this->success('标记成功');
			}else{
				$this->error('标记失败');
			}			
		}else{				
			$this->error('数据传入失败！');
		}								  		
	}		
	//删除用户举报列表
	function report_del(){
		$id=intval($_GET['id']);
		if($id){
			$result=M("users_community_report")->delete($id);				
			if($result){
				$this->success('删除成功');
			}else{
				$this->error('删除失败');
			}			
		}else{				
			$this->error('数据传入失败！');
		}								  
	}	
	//举报内容设置**************start******************
	
	//举报类型列表
	
	function reportset(){
		$report=M("users_community_report_classify");
		$lists = $report
			->order("orderno ASC")
			->select();
			
		$this->assign('lists', $lists);
		$this->display();
	}
	//添加举报理由
	function add_report(){
		
		$this->display();
	}
	function add_reportpost(){
		
		if(IS_POST){			
			$report=M("users_community_report_classify");
			
			$name=I("name");//举报类型名称
			if(!trim($name)){
				$this->error('举报类型名称不能为空');
			}
			$isexit=M("users_community_report_classify")->where("name='{$name}'")->find();	
			if($isexit){
				$this->error('该举报类型名称已存在');
			}
			
			$report->create();
			$report->addtime=time();
			$result=$report->add(); 
			if($result){
				$this->success('添加成功');
			}else{
				$this->error('添加失败');
			}
		}	
	}
	//编辑举报类型名称
	function edit_report(){
		$id=intval($_GET['id']);
		if($id){
			$reportinfo=M("users_community_report_classify")->where("id={$id}")->find();
			
			$this->assign('reportinfo', $reportinfo);						
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display();				
	}
	
	function edit_reportpost(){
		if(IS_POST){			
			$report=M("users_community_report_classify");
			
			$id=I("id");
			$name=I("name");//举报类型名称
			if(!trim($name)){
				$this->error('举报类型名称不能为空');
			}
		
			$isexit=M("users_community_report_classify")->where("id!={$id} and name='{$name}'")->find();	
			if($isexit){
				$this->error('该举报类型名称已存在');
			}
			
			$report->create();
			$result=$report->save(); 
			if($result!==false){
				  $this->success('修改成功');
			 }else{
				  $this->error('修改失败');
			 }
		}			
	}
	//删除举报类型名称
	function del_report(){
		$id=intval($_GET['id']);
		if($id){
			$result=M("users_community_report_classify")->where("id={$id}")->delete();				
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
	  //举报内容排序
    public function listordersset() { 
		
        $ids = $_POST['listorders'];
        foreach ($ids as $key => $r) {
            $data['orderno'] = $r;
            M("users_community_report_classify")->where(array('id' => $key))->save($data);
        }
				
        $status = true;
        if ($status) {
            $this->success("排序更新成功！");
        } else {
            $this->error("排序更新失败！");
        }
    }	
//举报内容设置**************end******************	
//
    //设置下架
    public function setXiajia(){
    	$res=array("code"=>0,"msg"=>"下架成功","info"=>array());
    	$id=I("id");
    	$reason=I("reason");
    	if(!$id){
    		$res['code']=1001;
    		$res['msg']="请确认圈子信息";
    		echo json_encode($res);
    		exit;
    	}

    	//判断此圈子是否存在
    	$communityInfo=M("users_community")->where("id={$id}")->find();
    	if(!$communityInfo){
    		$res['code']=1001;
    		$res['msg']="请确认圈子信息";
    		echo json_encode($res);
    		exit;
    	}

    	//更新圈子状态
    	$data=array("isdel"=>1,"xiajia_reason"=>$reason);

    	$result=M("users_community")->where("id={$id}")->save($data);

    	if($result!==false){

    		//将圈子喜欢列表的状态更改
    		M("users_community_like")->where("communityid={$id}")->setField('status',0);

    		//更新此圈子的举报信息
    		$data1=array(
    			'status'=>1,
    			'uptime'=>time()
    		);

    		M("users_community_report")->where("communityid={$id}")->save($data1);

    		$id=$_SESSION['ADMIN_ID'];
			$user=M("Users")->where("id='{$id}'")->find();

    		//向系统通知表中写入数据
    		$sysInfo=array(
    			'title'=>'圈子下架提醒',
    			'addtime'=>time(),
    			'admin'=>$user['user_login'],
    			'ip'=>$_SERVER['REMOTE_ADDR'],
    			'uid'=>$communityInfo['uid']

    		);

    		$baseMsg='您于'.date("Y-m-d H:i:s",$communityInfo['addtime']).'上传的《'.$communityInfo['title'].'》圈子被管理员于'.date("Y-m-d H:i:s",time()).'下架';;

    		if(!$reason){
    			$sysInfo['content']=$baseMsg;
    		}else{
    			$sysInfo['content']=$baseMsg.',下架原因为：'.$reason;
    		}

    		$result1=M("system_push")->add($sysInfo);


    		if($result1!==false){

    			$text="圈子下架提醒";
    			$uid=$communityInfo['uid'];
    			jMessageIM($text,$uid);

    		}

    		


    		echo json_encode($res);
    		exit;
    	}else{
    		$res['code']=1002;
    		$res['msg']="下架失败";
    		echo json_encode($res);
    		exit;
    	}
    	
    }

    /*下架圈子列表*/
    public  function lowercommunity(){

    	if($_REQUEST['ordertype']!=''){
			$ordertype=$_REQUEST['ordertype'];
			$_GET['ordertype']=$_REQUEST['ordertype'];
		}
		 $map['isdel']=1; 
		
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

		$p=I("p");
		if(!$p){
			$p=1;
		}
		
		
    	$community_model=M("users_community");
    	$count=$community_model->where($map)->count();
    	$page = $this->page($count, 20);
		$orderstr="";
		if($ordertype==1){//评论数排序
			$orderstr="comments DESC";
		}else if($ordertype==2){//点赞数量排序
			$orderstr="likes DESC";
		}else if($ordertype==3){//分享数量排序
			$orderstr="shares DESC";
		}else{
			$orderstr="addtime DESC";
		}
		
    	$lists = $community_model
			->where($map)
			->order($orderstr)
			->limit($page->firstRow . ',' . $page->listRows)
			->select();
		foreach($lists as $k=>$v){
			if($v['uid']==0){
				$userinfo=array(
					'user_nicename'=>'系统管理员'
				);
			}else{
				$userinfo=getUserInfo($v['uid']);
				if(!$userinfo){
					$userinfo=array(
						'user_nicename'=>'已删除'
					);
				}
				
			}

			
			$lists[$k]['userinfo']=$userinfo;
			
			$hasurgemoney=($v['big_urgenums']-$v['urge_nums'])*$v['urge_money'];
			$lists[$k]['hasurgemoney']=$hasurgemoney;
		}
    	$this->assign('lists', $lists);
		$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	$this->assign("p",$p);
    	$this->display();
    }


    public function  community_listen(){
    	$id=I("id");
    	if(!$id||$id==""||!is_numeric($id)){
    		$this->error("加载失败");
    	}else{
    		//获取音乐信息
    		$info=M("users_community")->where("id={$id}")->find();
    		$this->assign("info",$info);
    	}

    	$this->display();
    }


    /*圈子上架*/
    public function set_shangjia(){
    	$id=I("id");
    	if(!$id){
    		$this->error("圈子信息加载失败");
    	}

    	//获取圈子信息
    	$info=M("users_community")->where("id={$id}")->find();
    	if(!$info){
    		$this->error("圈子信息加载失败");
    	}

    	$data=array(
    		'xiajia_reason'=>'',
    		'isdel'=>0
    	);
    	$result=M("users_community")->where("id={$id}")->save($data);
    	if($result!==false){
    		$this->success("上架成功");
    	}
    	$this->display();
    }

    public function commentlists(){
    	
    	$communityid=I("communityid");
    	$community_comment=M("users_community_comments");
    	$map=array();
    	//$map['parentid']=0;
    	$map['communityid']=$communityid;
    	$count=$community_comment->where($map)->count();
    	$page = $this->page($count, 20);
    	//获取一级评论列表
    	$lists=$community_comment->where($map)->order("addtime desc")->limit($page->firstRow . ',' . $page->listRows)->select();

    	//var_dump($community_comment->getLastSql());
    	foreach ($lists as $k => $v) {
    		$lists[$k]['user_nicename']=M("users")->where("id={$v['uid']}")->getField("user_nicename");
    		/*$secondComments=$community_comment->where("communityid={$communityid} and commentid={$v['id']}")->select();
    		foreach ($secondComments as $k1 => $v1) {
    			$secondComments[$k1]['user_nicename']=M("users")->where("id={$v1['uid']}")->getField("user_nicename");
    			$lists[$k]['secondComments']=$secondComments;
    		}*/
    	}

    	//var_dump($lists);

    	$this->assign("lists",$lists);
    	$this->assign("page", $page->show('Admin'));
    	$this->display();

    }
}
