<?php

/**
 * 举报
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;


class ReportController extends AdminbaseController {

	function classify(){

		$map=array();

		if($_REQUEST['keyword']!=''){
			$map['title']=array("like","%".$_REQUEST['keyword']."%"); 
			$_GET['keyword']=$_REQUEST['keyword'];
		}


		$Report=M("users_report_classify");
    	$count=$Report->where($map)->count();
    	$page = $this->page($count, 20);
    	$lists = $Report
    	->where($map)
    	->order("orderno")
    	->limit($page->firstRow . ',' . $page->listRows)
    	->select();

    	$this->assign("lists",$lists);
    	$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
		$this->display();
	}

	/*分类添加*/
	function classify_add(){

		$this->display();
	}


	/*分类添加提交*/
	function classify_add_post(){

		if(IS_POST){

			$classify=M("users_report_classify");
			$classify->create();
			$classify->addtime=time();
			

			$title=trim($_POST['title']);
			$orderno=$_POST['orderno'];

			if($title==""){
				$this->error("请填写分类名称");
			}


			if(!is_numeric($orderno)){
				$this->error("排序号请填写数字");
			}

			if($orderno<0){
				$this->error("排序号必须大于0");
			}

			$classify->orderno=$orderno;

			
			$isexit=$classify->where("title='{$title}'")->find();	
			if($isexit){
				$this->error('该分类已存在');
			}

			$result=$classify->add();

			if($result){
				$this->success('添加成功','Admin/Report/classify',3);
			}else{
				$this->error('添加失败');
			}
		}

	}

	//分类排序
    function classify_listorders() { 
		
        $ids = $_POST['listorders'];
        foreach ($ids as $key => $r) {
            $data['orderno'] = $r;
            M("users_report_classify")->where(array('id' => $key))->save($data);
        }
				
        $status = true;
        if ($status) {
            $this->success("排序更新成功！");
        } else {
            $this->error("排序更新失败！");
        }
    }

    /*分类删除*/
	function classify_del(){

		$id=intval($_GET['id']);
		if($id){
			$result=M("users_report_classify")->where("id={$id}")->delete();				
			if($result){
				$this->success('删除成功');
			}else{
				$this->error('删除失败');
			}			
		}else{				
			$this->error('数据传入失败！');
		}
	}

	/*分类编辑*/
	function classify_edit(){
		$id=intval($_GET['id']);
		if($id){
			$info=M("users_report_classify")->where("id={$id}")->find();

			$this->assign("classify_info",$info);
		}else{
			$this->error('数据传入失败！');
		}
		
		$this->display();
	}

	/*分类编辑提交*/
	function classify_edit_post(){

		if(IS_POST){			
			$classify=M("users_report_classify");
			
			$id=I("id");
			$title=I("title");
			$orderno=I("orderno");

			if(!trim($title)){
				$this->error('分类标题不能为空');
			}

			if(!is_numeric($orderno)){
				$this->error("排序号请填写数字");
			}

			if($orderno<0){
				$this->error("排序号必须大于0");
			}
		
			$isexit=$classify->where("id!={$id} and title='{$title}'")->find();
			if($isexit){
				$this->error('该分类已存在');
			}
			
			$classify->create();
			$classify->updatetime=time();
			$result=$classify->save();
			if($result!==false){
				  $this->success('修改成功');
			 }else{
				  $this->error('修改失败');
			 }
		}

	}

    function index(){

    	$map=array();

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
			
    	$Report=M("users_report");
    	$count=$Report->where($map)->count();
    	$page = $this->page($count, 20);
    	$lists = $Report
    	->where($map)
    	->order("addtime DESC")
    	->limit($page->firstRow . ',' . $page->listRows)
    	->select();
			
		foreach($lists as $k=>$v){
				$lists[$k]['userinfo']= M("users")->field("user_nicename")->where("id='{$v[uid]}'")->find();
			  $lists[$k]['touserinfo']= M("users")->field("user_nicename,user_status")->where("id='{$v[touid]}'")->find();
		}			
			
    	$this->assign('lists', $lists);
    	$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display();
    }
		
	function setstatus(){
		 	$id=intval($_GET['id']);
				if($id){
					 $data['status']=1;
					 $data['uptime']=time();
					 $result=M("users_report")->where("id='{$id}'")->save($data);
						if($result){

							$reportInfo=M("users_report")->where("id={$id}")->find();
							$reportedUserInfo=M("users")->where("id={$reportInfo['touid']}")->field("id,user_nicename")->find();
							//发送极光IM
							$id=$_SESSION['ADMIN_ID'];
							$user=M("Users")->where("id='{$id}'")->find();

							//向系统通知表中写入数据
				    		$sysInfo=array(
				    			'title'=>'用户举报处理提醒',
				    			'addtime'=>time(),
				    			'admin'=>$user['user_login'],
				    			'ip'=>$_SERVER['REMOTE_ADDR'],
				    			'uid'=>$reportInfo['uid']

				    		);

				    		$baseMsg='您于'.date("Y-m-d H:i:s",$reportInfo['addtime']).'对'.$reportedUserInfo['user_nicename'].'的举报已被管理员于'.date("Y-m-d H:i:s",time()).'进行处理';

				    		$sysInfo['content']=$baseMsg;

				    		$result1=M("system_push")->add($sysInfo);

				    		if($result1!==false){

				    			$test="用户举报处理提醒";
				    			$uid=$reportInfo['uid'];
				    			jMessageIM($test,$uid);


				    		}

							$this->success('标记成功');
						 }else{
							$this->error('标记失败');
						 }			
				}else{				
					$this->error('数据传入失败！');
				}								  		
	}		
		
	function del(){
		 	$id=intval($_GET['id']);
				if($id){
					$result=M("users_report")->delete($id);				
						if($result){
								$this->success('删除成功');
						 }else{
								$this->error('删除失败');
						 }			
				}else{				
					$this->error('数据传入失败！');
				}								  
	}		

		
	function edit(){
 		$id=intval($_GET['id']);
		if($id){
			$Report=M("users_report")->find($id);
			$Report['userinfo']=M("users")->field("user_nicename")->where("id='$Report[uid]'")->find();
			$this->assign('Report', $Report);						
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display();				
	}
		
	function edit_post(){
		if(IS_POST){		
		    if($_POST['status']=='0'){							
				  $this->error('未修改状态');			
			}
		
			 $Report=M("users_report");
			 $Report->create();
			 $Report->uptime=time();
			 $result=$Report->save(); 
			 if($result){
				  $this->success('修改成功',U('Report/index'));
			 }else{
				  $this->error('修改失败');
			 }
		}			
	}

	function ban(){
    	$id=intval($_GET['id']);
    	if ($id) {
    		$rst = M("Users")->where(array("id"=>$id,"user_type"=>2))->setField('user_status','0');
    		if ($rst!==false) {
				
    			$this->success("会员拉黑成功！");
    		} else {
    			$this->error('会员拉黑失败！');
    		}
    	} else {
    		$this->error('数据传入失败！');
    	}
    }

    function ban_video(){
    	$id=intval($_GET['id']);
    	if($id){
    		$rst = M("users_video")->where(array("uid"=>$id))->setField('isdel','1');
    		if ($rst!==false) {
				
    			$this->success("被举报用户所有视频下架成功！");
    		} else {
    			$this->error('视频下架失败！');
    		}
    	}else {
    		$this->error('数据传入失败！');
    	}
    }

    function ban_all(){
    	$id=intval($_GET['id']);
    	if($id){

    		$data['status']=1;
			$data['uptime']=time();
			$result=M("users_report")->where("id='{$id}'")->save($data);  //标记处理
			//获取该举报信息对应的用户
			$info=M("users_report")->where("id='{$id}'")->find();
    		M("Users")->where(array("id"=>$info['touid'],"user_type"=>2))->setField('user_status','0');  //用户禁用
    		
    		M("users_video")->where(array("uid"=>$info['touid']))->setField('isdel','1'); //下架视频
    		
				
    		$this->success("操作成功！");
    		
    	}else {
    		$this->error('数据传入失败！');
    	}
    }
    
}
