<?php
/**
 * Created by PhpStorm.
 * User: RM
 * Date: 2018/10/12
 * Time: 9:35
 */

namespace Admin\Controller;
use Common\Controller\AdminbaseController;

class CategoryController extends AdminbaseController
{
    protected $terms_model;
    protected $taxonomys=array("article"=>"文章","picture"=>"图片");

    function _initialize() {
        parent::_initialize();
        $this->category_model = D("Admin/live_category");//初始化模型
        $this->assign("taxonomys",$this->taxonomys);
    }

    /**
     * 直播分类管理
     * 返回树状结构，支持无限分级
     */
    function index(){
     
        $result = $this->category_model->where('type=0 and parent_id=0')->order(array("list_order"=>"asc"))->select();//正序排列所有分类项，排列号越小越靠前
        //初始化树状类库
        $tree = new \Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';

        foreach ($result as $r) {
            $r['str_manage'] = '<a href="' . U("Category/add", array("parent" => $r['id'])) . '">添加子分类</a> | <a href="' . U("Category/edit", array("id" => $r['id'])) . '">'.L('EDIT').'</a> | <a class="js-ajax-delete" href="' . U("Category/delete", array("id" => $r['id'])) . '">'.L('DELETE').'</a> ';
            //$r['str_manage'] = '<a href="' . U("AdminTerm/edit", array("id" => $r['term_id'])) . '">'.L('EDIT').'</a> | <a class="js-ajax-delete" href="' . U("AdminTerm/delete", array("id" => $r['term_id'])) . '">'.L('DELETE').'</a> ';
            $url=U('portal/list/index',array('id'=>$r['id']));
            $r['url'] = $url;
            //$r['taxonomys'] = ;
            $r['id']=$r['id'];
            $r['parentid']=$r['parent_id'];
            $r['belongto'] =$r['belongto'] ? '社区分类':'视频分类';
            $array[] = $r;
        }

       
        //根据模板返回树状结构html
        $tree->init($array);
        $str = "<tr>
					<td><input name='listorders[\$id]' type='text' size='3' value='\$list_order' class='input input-order'></td>
					<td>\$id</td>
					<td>\$spacer <a >\$name</a></td>
                    <td>\$spacer <a >\$belongto</a></td>
	    			<td>\$description</td>
					<td>\$str_manage</td>
				</tr>";

        $taxonomys = $tree->get_tree(0, $str);
        $this->assign("taxonomys", $taxonomys);
        $this->display();
    }

    /**
     * 渲染增加直播分类页面
     */
    function add(){
        $parentid = intval(I("get.parent"));
        $tree = new \Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $result = $this->category_model->order(array("path"=>"asc"))->select();

        $new_category=array();
        foreach ($result as $r) {
            //$r['id']=$r['id'];
            $r['parentid']=$r['parent_id'];
            $r['selected']= (!empty($parentid) && $r['id']==$parentid)? "selected":"";
            $new_category[] = $r;
        }
        $tree->init($new_category);
        $tree_tpl="<option value='\$id' \$selected>\$spacer\$name</option>";
        $tree=$tree->get_tree(0,$tree_tpl);

        $this->assign("category_tree",$tree);
        $this->assign("parent",$parentid);
        $this->display();
    }

    /**
     * 接收增加直播分类post并处理
     */
    function add_post(){
        if (IS_POST) {
            if ($this->category_model->create()) {
                if ($this->category_model->add()!==false) {
                    F('all_category',null);
                    $this->success("添加成功！",U("Category/index"));
                } else {
                    $this->error("添加失败！");
                }
            } else {
                $this->error($this->category_model->getError());
            }
        }
    }

    /**
     * 渲染直播分类编辑
     */
    function edit(){
        $id = intval(I("get.id"));
        $data=$this->category_model->where(array("id" => $id))->find();
        $tree = new \Tree();
        $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
        $terms = $this->category_model->where(array("id" => array("NEQ",$id), "path"=>array("notlike","%-$id-%")))->order(array("path"=>"asc"))->select();

        $new_terms=array();
        foreach ($terms as $r) {
            //$r['id']=$r['term_id'];
            $r['parentid']=$r['parent_id'];
            $r['selected']=$data['parent_id']==$r['id']?"selected":"";
            $new_category[] = $r;
        }

        $tree->init($new_category);
        $tree_tpl="<option value='\$id' \$selected>\$spacer\$name</option>";
        $tree=$tree->get_tree(0,$tree_tpl);

        $this->assign("category_tree",$tree);
        $this->assign("data",$data);
        $this->display();
    }

    function edit_post(){
        if (IS_POST) {
            if ($this->category_model->create()) {
                if ($this->category_model->save()!==false) {
                    F('all_category',null);
                    $this->success("修改成功！");
                } else {
                    $this->error("修改失败！");
                }
            } else {
                $this->error($this->category_model->getError());
            }
        }
    }

    //排序
    public function listorders() {
        $status = parent::_listorders($this->category_model);
        if ($status) {
            $this->success("排序更新成功！");
        } else {
            $this->error("排序更新失败！");
        }
    }

    /**
     *  删除
     */
    public function delete() {
        $id = intval(I("get.id"));
        $count = $this->category_model->where(array("parent_id" => $id))->count();
        if ($count > 0) {
            $this->error("该菜单下还有子类，无法删除！");
        }

        if ($this->category_model->delete($id)!==false) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }

}