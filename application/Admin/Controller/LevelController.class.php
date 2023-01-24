<?php

/**
 * 经验等级
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class LevelController extends AdminbaseController {
	
		protected $experlevel_model;
	
		function _initialize() {
			parent::_initialize();
			$this->experlevel_model = D("Common/Experlevel");

		}	
	
    function experlevel_index(){			
    	$experlevel=$this->experlevel_model;
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
		
	function experlevel_del(){
		$id=intval($_GET['id']);
		if($id){
			$result=M("experlevel")->where("id='{$id}'")->delete();				
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



	function experlevel_add(){
		$this->display();				
	}	
	function experlevel_add_post(){
		if(IS_POST){
			if($_POST['levelid'] == ''){
				$this->error('等级不能为空');
			}else{
				$check = M('experlevel')->where("levelid='{$_POST['levelid']}'")->find();
				if($check){
					$this->error('等级不能重复');
				}
			}			
			$experlevel=$this->experlevel_model;
			if($experlevel->create()){
				$experlevel->addtime=time();
				$result=$experlevel->add(); 
				if($result!==false){
					$this->success('添加成功');
				}else{
					$this->error('添加失败');
				}						 
				 
			}else{
				$this->error($this->experlevel_model->getError());
			}
		}			
	}		
	function experlevel_edit(){
		$id=intval($_GET['id']);
		if($id){
			$experlevel=M("experlevel")->where("id='{$id}'")->find();
			$this->assign('experlevel', $experlevel);						
		}else{				
			$this->error('数据传入失败！');
		}								  
		$this->display();				
	}
	
	function experlevel_edit_post(){
		if(IS_POST){
			
			if($_POST['levelid'] == ''){
				$this->error('等级不能为空');
			}else{
				$check = M('experlevel')->where("levelid='{$_POST['levelid']}' and id !='{$_POST['id']}'")->find();
				if($check){
					$this->error('等级不能重复');
				}
			}
			$data = $_POST;
			unset($data['id']);
			
				$result=M('experlevel')->where("id = {$_POST['id']}")->save($data); 
				if($result!==false){
					$this->success('修改成功');
				}else{
					$this->error('修改失败');
				}					 
			}else{
				$this->error($this->experlevel_model->getError());
			}

			
	}
		
}
