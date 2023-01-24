<?php

/**
 * 靓号管理
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class LiangController extends AdminbaseController {
	public $status=array(
		'0'=>'出售中',
		'1'=>'已售',
		'2'=>'停售',
	);

    function index(){
		$map=array();

		if($_REQUEST['status']!='-1' && $_REQUEST['status']!='' ){
			$map['status']=$_REQUEST['status'];
			$_GET['status']=$_REQUEST['status'];
		}else{
			$_GET['status']='-1';
		}

		if($_REQUEST['length']!='-1' && $_REQUEST['length']!='' ){
			$map['length']=$_REQUEST['length'];
			$_GET['length']=$_REQUEST['length'];
		}else{
			$_GET['length']='-1';
		}

		if($_REQUEST['uid']!=''){
			$map['uid']=$_REQUEST['uid']; 
			$_GET['uid']=$_REQUEST['uid'];
		}

    	$Liang=M("liang");
    	$count=$Liang->where($map)->count();
    	$page = $this->page($count, 20);
    	$lists = $Liang
			->where($map)
			->order("id desc")
			->limit($page->firstRow . ',' . $page->listRows)
			->select();
		foreach($lists as $k=>$v){
			if($v['uid']>0){
				$userinfo=getUserInfo($v['uid']);
				$lists[$k]['userinfo']=$userinfo;
			}
		}
		
		$length=$Liang 
			->field("length")
			->order("length asc")
			->group("length")
			->select();
    	$this->assign('lists', $lists);
    	$this->assign('length', $length);
		$this->assign('formget', $_GET);
    	$this->assign('status', $this->status);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display();
    }
		
	function del(){
		$id=intval($_GET['id']);
		if($id){
			$result=M("liang")->delete($id);				
				if($result){
					$this->success('删除成功');
				}else{
					$this->error('删除失败');
				}			
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display();				
	}		

	function setStatus(){
		$id=intval($_GET['id']);
		$status=intval($_GET['status']);
		if($id){
			$result=M("liang")->where("id={$id}")->setField("status",$status);				
			if($result!==false){
				$this->success('操作成功');
			}else{
				$this->error('操作失败');
			}			
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display();				
	}		
    //排序
    public function listorders() { 
		
        $ids = $_POST['listorders'];
        foreach ($ids as $key => $r) {
            $data['orderno'] = $r;
            M("liang")->where(array('id' => $key))->save($data);
        }
				
        $status = true;
        if ($status) {
            $this->success("排序更新成功！");
        } else {
            $this->error("排序更新失败！");
        }
    }	
    

	function add(){
		$this->assign('long', $this->long);
    	$this->assign('type', $this->type);
		$this->display();				
	}	
	function add_post(){
		if(IS_POST){			
			$Liang=M("liang");
			$name=I("name");
			$coin=(int)I("coin");
			
			if($name==''){
				$this->error('靓号不能为空');
			}
			
			if($coin<=0){
				$this->error('所需点数不能小于等于0');
			}
			
			$isexist=$Liang->where("name='{$name}'")->find();
			
			if($isexist){
				$this->error('该靓号已存在');
			}
			$Liang->create();
			$Liang->addtime=time();
			$Liang->length=mb_strlen($name);
			$result=$Liang->add(); 
			if($result!==false){
				$this->success('添加成功');
			}else{
				$this->error('添加失败');
			}
		}			
	}		
	function edit(){
		$id=intval($_GET['id']);
		if($id){
			$liang=M("liang")->find($id);
			$this->assign('liang', $liang);		
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display();				
	}
	
	function edit_post(){
		if(IS_POST){	
			$Liang=M("liang");
			$name=I("name");
			$coin=(int)I("coin");
			$id=I("id");
			
			if($name==''){
				$this->error('靓号不能为空');
			}
			
			if($coin<=0){
				$this->error('所需点数不能小于等于0');
			}
			
			$isexist=$Liang->where("name='{$name}' and id!='{$id}'")->find();
			if($isexist){
				$this->error('该靓号已存在');
			}
			
			
			$Liang->create();
			$Liang->length=mb_strlen($name);
			$result=$Liang->save(); 
			if($result!==false){
				$this->success('修改成功');
			}else{
				$this->error('修改失败');
			}
		}			
	}
		
    function user_index(){
	
    	$Vip_u=M("users_vip");
    	$count=$Vip_u->count();
    	$page = $this->page($count, 20);
    	$lists = $Vip_u
    	->order("endtime asc")
    	->limit($page->firstRow . ',' . $page->listRows)
    	->select();
		foreach($lists as $k=>$v){
			$lists[$k]['userinfo']=getUserInfo($v['uid']);
		}
    	$this->assign('lists', $lists);
    	$this->assign("page", $page->show('Admin'));
    	$this->assign('type', $this->type);    	
    	$this->display();
    }		
		
	function user_del(){
		$id=intval($_GET['id']);
		if($id){
			$result=M("users_vip")->delete($id);				
			if($result){
				$this->success('删除成功');
			}else{
				$this->error('删除失败');
			}						
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display();				
	}		
			
    function user_add(){
		$this->assign('type', $this->type);    	
    	$this->display();
    }		
	function do_user_add(){

		if(IS_POST){	
			$uid=$_POST['uid'];
            if($uid==''){
				$this->error('用户ID不能为空');
			}
			$isexist=M("users")->field("id")->where("id={$uid}")->find();
			if(!$isexist){
				$this->error('该用户不存在');
			}
			
			$Vip_u=M("users_vip");
			$isexist2=$Vip_u->field("id")->where("uid={$uid}")->find();
			if($isexist2){
				$this->error('该用户已购买过会员');
			}
			
			$Vip_u->create();
			$Vip_u->addtime=time();
			$Vip_u->endtime=strtotime($_POST['endtime']);
			$result=$Vip_u->add(); 
			if($result!==false){
				$this->success('添加成功');
			}else{
				$this->error('添加失败');
			}
		}				
    }		
    function user_edit(){

		$id=intval($_GET['id']);
		if($id){
			$data	=M("users_vip")->where("id={$id}")->find();
			$data['userinfo']=getUserInfo($data['uid']);
			$this->assign('data', $data);	
			$this->assign('type', $this->type);
		}else{				
			$this->error('数据传入失败！');
		}								      	
    	$this->display();
    }			
	function do_user_edit(){
		if(IS_POST){			
			$Vip_u=M("users_vip");
			$Vip_u->create();
			$Vip_u->endtime=strtotime($_POST['endtime']);
			$result=$Vip_u->save(); 
			if($result!==false){
				$this->success('修改成功');
			}else{
				$this->error('修改失败');
			}
		}	
    }
}
