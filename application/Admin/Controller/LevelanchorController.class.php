<?php

/**
 * 主播等级
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class LevelanchorController extends AdminbaseController {
	

    function index(){			
    	$experlevel=M("experlevel_anchor");
    	$count=$experlevel->count();
    	$page = $this->page($count, 20);
    	$lists = $experlevel
    	->order("levelid asc")
    	->limit($page->firstRow . ',' . $page->listRows)
    	->select();
    	$this->assign('lists', $lists);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display();
    }
		
	function del(){
		$id=intval($_GET['id']);
		if($id){
			$result=M("experlevel_anchor")->where("id='{$id}'")->delete();				
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



	function add(){
		$this->display();				
	}	
	function add_post(){
		if(IS_POST){
			if($_POST['levelid'] == ''){
				$this->error('等级不能为空');
			}else{
				$check = M('experlevel_anchor')->where("levelid='{$_POST['levelid']}'")->find();
				if($check){
					$this->error('等级不能重复');
				}
			}			
			$experlevel=M("experlevel_anchor");
			if($experlevel->create()){
				$experlevel->addtime=time();
				$result=$experlevel->add(); 
				if($result!==false){
					$this->success('添加成功');
				}else{
					$this->error('添加失败');
				}						 
				 
			}else{

			}
		}			
	}		
	function edit(){
		$id=intval($_GET['id']);
		if($id){
			$experlevel=M("experlevel_anchor")->where("id='{$id}'")->find();
			$this->assign('experlevel', $experlevel);						
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display();				
	}
	
	function edit_post(){
		if(IS_POST){
			
			if($_POST['levelid'] == ''){
				$this->error('等级不能为空');
			}else{
				$check = M('experlevel_anchor')->where("levelid='{$_POST['levelid']}' and id !='{$_POST['id']}'")->find();
				if($check){
					$this->error('等级不能重复');
				}
			}

			$experlevel=M("experlevel_anchor");
			$experlevel->create();
				
			$result=$experlevel->save(); 
			if($result!==false){
				$this->success('修改成功');
			}else{
				$this->error('修改失败');
			}					 
		}	
	}
		
}
