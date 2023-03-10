<?php

/**
 * 登录奖励
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class SpreadController extends AdminbaseController {
    function index(){

    	$spread=M("spread");
    	$count=$spread->count();
    	$page = $this->page($count, 20);
    	$lists = $spread
    	->order("id asc")
    	->limit($page->firstRow . ',' . $page->listRows)
    	->select();

    	$this->assign('lists', $lists);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display();
    }
		
		function del(){
			 	$id=intval($_GET['id']);
					if($id){
						$result=M("spread")->delete($id);				
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
	

		function add(){
				$this->display();				
		}	
		function add_post(){
				if(IS_POST){			
					 $bonus=M("spread");
					 $bonus->create();
					 $bonus->addtime=time();
					 $result=$bonus->add(); 
					 if($result){
						  $this->success('添加成功');
					 }else{
						  $this->error('添加失败');
					 }
				}			
		}		
		function edit(){
			$id=intval($_GET['id']);
			if($id){
				$bonus=M("spread")->where("id={$id}")->find();
				$this->assign('bonus', $bonus);						
			}else{				
				$this->error('数据传入失败！');
			}								  
			$this->display();				
		}
		
		function edit_post(){
			if(IS_POST){			
				 $bonus=M("spread");
				 $bonus->create();
				 $bonus->addtime=time();
				 $result=$bonus->save(); 
				 if($result){
					  $this->success('修改成功');
				 }else{
					  $this->error('修改失败');
				 }
			}			
		}
		
}
