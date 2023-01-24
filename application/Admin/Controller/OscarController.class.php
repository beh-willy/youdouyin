<?php

/**
 * 奥思卡管理
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
use QCloud\Cos\Api;
use QCloud\Cos\Auth;
class OscarController extends AdminbaseController {
	/**奖项列表**/
    function index(){

		$map=array();
			
		
		/* 	if($_REQUEST['type']!=''){
			$map['type']=$_REQUEST['type']; 
			$_GET['type']=$_REQUEST['type'];
		} */

		if($_REQUEST['keyword']!=''){
			$map['name']=array("like","%".$_REQUEST['keyword']."%");  
			$_GET['keyword']=$_REQUEST['keyword'];
		}
		
    	$oscar=M("oscar");
    	$count=$oscar->where($map)->count();
    	$page = $this->page($count, 20);
    	$lists = $oscar
			->where($map)
			->order("orderno asc")
			->limit($page->firstRow . ',' . $page->listRows)
			->select();
	
    	$this->assign('lists', $lists);
    	$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display();
    }
	 //排序
    public function listorders() { 
		
        $ids = $_POST['listorders'];
        foreach ($ids as $key => $r) {
            $data['orderno'] = $r;
            M("oscar")->where(array('id' => $key))->save($data);
        }	
        $status = true;
        if ($status) {
            $this->success("排序更新成功！");
        } else {
            $this->error("排序更新失败！");
        }
    }	
	
	/**删除奖项*/
	function del_oscar(){
		$id=intval($_GET['id']);
		if($id){
			$result=M("oscar")->where("id={$id}")->delete();				
			if($result!==false){
				//删除获奖视频列表对应数据
				$video=M("oscar_video")->where("oscarid={$id}")->delete();	
				$this->success('删除成功');
			}else{
				$this->error('删除失败');
			}			
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display();				
	}	
	/**添加奖项*/	
	function add_oscar(){
		
		
		$this->display();				
	}
	
	function add_oscarpost(){
		if(IS_POST){			
			$oscar=M("oscar");
			
			$name=I("name");//奖项名称
		
			if(!trim($name)){
				$this->error('奖项名称不能为空');
			}
			$isexit=M("oscar")->where("name='{$name}'")->find();	
			if($isexit){
				$this->error('该奖项名称已存在');
			}
			
			$oscar->create();
			$oscar->addtime=time();
			$result=$oscar->add(); 
			if($result){
				$this->success('添加成功');
			}else{
				$this->error('添加失败');
			}
		}			
	}		
	function edit_oscar(){
		$id=intval($_GET['id']);
		if($id){
			$oscartinfo=M("oscar")->where("id={$id}")->find();
			
			$this->assign('oscartinfo', $oscartinfo);						
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display();				
	}
	
	function edit_oscarpost(){
		if(IS_POST){			
			$oscar=M("oscar");
			
			$id=I("id");
			$name=I("name");//奖项名称
		
			if(!trim($name)){
				$this->error('奖项名称不能为空');
			}
			$isexit=M("oscar")->where("id!={$id} and name='{$name}'")->find();	
			if($isexit){
				$this->error('该奖项名称已存在');
			}
			
			$oscar->create();
			$result=$oscar->save(); 
			if($result!==false){
				  $this->success('修改成功');
			 }else{
				  $this->error('修改失败');
			 }
		}			
	}
	
	//获奖视频列表
	function videoindex(){
		$map=array();
			
		
		if($_REQUEST['type']!=''){
			$map['type']=$_REQUEST['type']; 
			$_GET['type']=$_REQUEST['type'];
		}

		if($_REQUEST['keyword']!=''){
			/* $map['oscarid|videoid']=array("eq",$_REQUEST['keyword']);  */
			$map['oscarid|videoid']=array("like","%".$_REQUEST['keyword']."%");  
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
		
    	$oscarvideo=M("oscar_video");
    	$count=$oscarvideo->where($map)->count();
    	$page = $this->page($count, 20);
    	$lists = $oscarvideo
			->where($map)
			->order("addtime asc")
			->limit($page->firstRow . ',' . $page->listRows)
			->select();
		foreach($lists as $k=>$v){
			//奖项信息
			$oscarinfo=M("oscar")->where("id=".$v['oscarid'])->find();
			$lists[$k]['oscarname']=$oscarinfo['name'];
			//视频信息
			if($v['type']==1){//方言秀视频
				$videoinfo=M("users_dialect")->where("id=".$v['videoid'])->find();
			}else{
				$videoinfo=M("users_video")->where("id=".$v['videoid'])->find();
			}
			if($videoinfo){
				$lists[$k]['videoname']=$videoinfo['title'];
				$lists[$k]['uid']=$videoinfo['uid'];
				//用户信息
				$userinfo=M("users")->where("id=".$videoinfo['uid'])->find();
				if($userinfo){
					$lists[$k]['user_nicename']=$userinfo['user_nicename'];
				}else{
					$lists[$k]['user_nicename']="已删除";
				}
			}else{
				//删除获奖视频列表对应数据
				M("oscar_video")->where("oscarid={$v['oscarid']}")->delete();
				unset($lists[$k]);
			}
			
		}
		$lists=array_values($lists);
    	$this->assign('lists', $lists);
    	$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	
    
		$this->display();		
	}
	
	/**删除获奖视频*/
	function del_oscarvideo(){
		$id=intval($_GET['id']);
		if($id){
			$result=M("oscar_video")->where("id={$id}")->delete();				
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
	/**添加获奖视频*/	
	function add_oscarvideo(){
		
		$oscarlists=M("oscar")->select();
	
		$this->assign('oscarlists', $oscarlists);		
		$this->display();				
	}
	
	function add_oscarvideopost(){
		if(IS_POST){			
			$video=M("oscar_video");
			
			$oscarid=I("oscarid");//奖项ID
			$videoid=I("videoid");//视频ID
			$type=I("type");//视频类型：0：短视频；1：方言秀
		
			if(!trim($videoid)){
				$this->error('视频ID不能为空');
			}
			if($type==1){
				$isexitvideo=M("users_dialect")->where("id='{$videoid}'")->find();
			}else{
				$isexitvideo=M("users_video")->where("id='{$videoid}'")->find();
			}
			
			if(!$isexitvideo){
				$this->error('该视频ID不存在');
			}
			$isexit=M("oscar_video")->where("oscarid='{$oscarid}' or (type={$type} and videoid='{$videoid}')")->find();	
			if($isexit){
				$this->error('该奖项名称已颁发或该视频ID已得奖');
			}
			
			$video->create();
			$video->addtime=time();
			$result=$video->add(); 
			if($result){
				$this->success('添加成功');
			}else{
				$this->error('添加失败');
			}
		}			
	}		
	//编辑获奖视频
	function edit_oscarvideo(){
		$id=intval($_GET['id']);
		if($id){
			$videoinfo=M("oscar_video")->where("id={$id}")->find();
			
			$this->assign('videoinfo', $videoinfo);		
			//奖项名称列表
			$oscarlists=M("oscar")->select();
			$this->assign('oscarlists', $oscarlists);				
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display();				
	}
	
	function edit_oscarvideopost(){
		if(IS_POST){			
			$video=M("oscar_video");
			
			$id=I("id");
			$oscarid=I("oscarid");//奖项ID
			$videoid=I("videoid");//视频ID
			$type=I("type");//视频类型：0：短视频；1：方言秀
		
			if(!trim($videoid)){
				$this->error('视频ID不能为空');
			}
			if($type==1){
				$isexitvideo=M("users_dialect")->where("id='{$videoid}'")->find();
			}else{
				$isexitvideo=M("users_video")->where("id='{$videoid}'")->find();
			}
			if(!$isexitvideo){
				$this->error('该视频ID不存在');
			}
			/* $isexit=M("oscar_video")->where("id!={$id} and  type={$type} and (oscarid='{$oscarid}' or videoid='{$videoid}')")->find();	 */
			$isexit=M("oscar_video")->where("id !='{$id}' and (oscarid='{$oscarid}' or (type={$type} and videoid='{$videoid}'))")->find();	
			
			if($isexit){
				$this->error('该奖项名称已颁发或该视频ID已得奖');
			}
			
			
			$video->create();
			$result=$video->save(); 
			if($result!==false){
				  $this->success('修改成功');
			 }else{
				  $this->error('修改失败');
			 }
		}			
	}
	
}
