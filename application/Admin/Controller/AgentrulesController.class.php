<?php

/**
 * 充值规则
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class AgentrulesController extends AdminbaseController {

		
    function index(){
	
    	$rules=M("agent_rules");
    	$count=$rules->count();
    	$page = $this->page($count, 20);
    	$lists = $rules
				->order("orderno asc")
				->limit($page->firstRow . ',' . $page->listRows)
				->select();
    	$this->assign('lists', $lists);
    	$this->assign("page", $page->show('Admin'));
    	
    	$this->display();
    }		
		
	function del(){
		$id=intval($_GET['id']);
		if($id){
			$result=M("agent_rules")->where("id='{$id}'")->delete();				
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
            M("agent_rules")->where(array('id' => $key))->save($data);
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
	
	function do_add(){
		if(IS_POST){	
			$rules=M("agent_rules");
			$rules->create();
			$rules->addtime=time();
			 
			$result=$rules->add(); 
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
			$rules	=M("agent_rules")->where("id='{$id}'")->find();
			$this->assign('rules', $rules);						
		}else{				
			$this->error('数据传入失败！');
		}								      	
    	$this->display();
    }		
	
	function do_edit(){
		if(IS_POST){			
			 $rules=M("agent_rules");
			 $rules->create();
			 $result=$rules->save(); 
			 if($result){
				  $this->success('修改成功');
			 }else{
				  $this->error('修改失败');
			 }
		}	
    }				
}
