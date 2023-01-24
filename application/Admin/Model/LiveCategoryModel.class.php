<?php
namespace Admin\Model;
use Common\Model\CommonModel;
class LiveCategoryModel extends CommonModel {
//	//自动验证
	protected $_validate = array(
			//array(验证字段,验证规则,错误提示,验证条件,附加规则,验证时间)
			array('name', 'require', '分类名称不能为空！', 1, 'regex', 3),
            //array('parent_id', 'require', '分名称不能为空！', 1, 'regex', 3),
	);
	//拼接path字段，新增时
    protected function _after_insert($data,$options){
        parent::_after_insert($data,$options);
        $id=$data['id'];
        $parent_id=$data['parent_id'];
        if($parent_id==0){
            $d['path']="0-$id";
        }else{
            $parent=$this->where("id=$parent_id")->find();
            $d['path']=$parent['path'].'-'.$id;
        }
        $this->where("id=$id")->save($d);
    }

    //拼接path字段，更新时
    protected function _after_update($data,$options){
        parent::_after_update($data,$options);
        if(isset($data['parent_id'])){
            $id=$data['id'];
            $parent_id=$data['parent_id'];
            if($parent_id==0){
                $d['path']="0-$id";
            }else{
                $parent=$this->where("id=$parent_id")->find();
                $d['path']=$parent['path'].'-'.$id;
            }
            $result=$this->where("id=$id")->save($d);
            if($result){
                $children=$this->where(array("parent_id"=>$id))->select();
                foreach ($children as $child){
                    $this->where(array("id"=>$child['id']))->save(array("parent_id"=>$id,"id"=>$child['id']));
                }
            }
        }

    }
	

	

	
	protected function _before_write(&$data) {
		parent::_before_write($data);
	}
	

}