<?php

/**
 * 礼物
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class GiftController extends AdminbaseController {
    function index(){

			$gift_sort=M("gift_sort")->getField("id,sortname");
			$gift_sort[0]="默认分类";
			$this->assign('gift_sort', $gift_sort);
			
    	$gift_model=M("gift");
    	$count=$gift_model->count();
    	$page = $this->page($count, 20);
    	$lists = $gift_model
    	//->where()
    	->order("addtime DESC")
    	->limit($page->firstRow . ',' . $page->listRows)
    	->select();
    	$this->assign('lists', $lists);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display();
    }
		
		function del(){
			 	$id=intval($_GET['id']);
					if($id){
						$result=M("gift")->delete($id);				
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
    //排序
    public function listorders() { 
		
        $ids = $_POST['listorders'];
        foreach ($ids as $key => $r) {
            $data['orderno'] = $r;
            M("gift")->where(array('id' => $key))->save($data);
        }
				
        $status = true;
        if ($status) {
            $this->success("排序更新成功！");
        } else {
            $this->error("排序更新失败！");
        }
    }	
    

		function add(){
				$gift_sort=M("gift_sort")->getField("id,sortname");
				$this->assign('gift_sort', $gift_sort);					
			
				$this->display();				
		}	
		function add_post(){
				if(IS_POST){			
					 $gift=M("gift");
					 $gift->create();
					 $gift->addtime=time();
					 $result=$gift->add(); 
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
						$gift=M("gift")->find($id);
						$this->assign('gift', $gift);						
					}else{				
						$this->error('数据传入失败！');
					}								  
					$this->display();				
		}
		
		function edit_post(){
				if(IS_POST){			
					 $user=M("gift");
					 $user->create();
					 $result=$user->save(); 
					 if($result){
						  $this->success('修改成功');
					 }else{
						  $this->error('修改失败');
					 }
				}			
		}
		
    function sort_index(){
	
    	$gift_sort=M("gift_sort");
    	$count=$gift_sort->count();
    	$page = $this->page($count, 20);
    	$lists = $gift_sort
    	//->where()
    	->order("orderno asc")
    	->limit($page->firstRow . ',' . $page->listRows)
    	->select();
    	$this->assign('lists', $lists);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display();
    }		
		
		function sort_del(){
			 	$id=intval($_GET['id']);
					if($id){
						$result=M("gift_sort")->delete($id);				
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
    //排序
    public function sort_listorders() { 
		
        $ids = $_POST['listorders'];
        foreach ($ids as $key => $r) {
            $data['orderno'] = $r;
            M("gift_sort")->where(array('id' => $key))->save($data);
        }
				
        $status = true;
        if ($status) {
            $this->success("排序更新成功！");
        } else {
            $this->error("排序更新失败！");
        }
    }				
    function sort_add(){
		    	
    	$this->display();
    }		
		function do_sort_add(){

				if(IS_POST){	
            if($_POST['sortname']==''){
							  $this->error('分类名称不能为空');
							
						}
				
					 $gift_sort=M("gift_sort");
					 $gift_sort->create();
					 $gift_sort->addtime=time();
					 
					 $result=$gift_sort->add(); 
					 if($result){
						  $this->success('添加成功');
					 }else{
						  $this->error('添加失败');
					 }
				}				
    }		
    function sort_edit(){

			 	$id=intval($_GET['id']);
					if($id){
						$sort	=M("gift_sort")->find($id);
						$this->assign('sort', $sort);						
					}else{				
						$this->error('数据传入失败！');
					}								      	
    	$this->display();
    }			
		function do_sort_edit(){
				if(IS_POST){			
					 $gift_sort=M("gift_sort");
					 $gift_sort->create();
					 $result=$gift_sort->save(); 
					 if($result){
						  $this->success('修改成功');
					 }else{
						  $this->error('修改失败');
					 }
				}	
    }
}
