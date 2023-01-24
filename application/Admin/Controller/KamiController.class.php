<?php
/* 
   卡密管理
 */

namespace Admin\Controller;
use Common\Controller\AdminbaseController;
class KamiController extends AdminbaseController{
	
	function index(){
		
					
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
 
					
		$p=I("p");
		if(!$p){
			$p=1;
		}
    	$kami=M("kami");
    	$count=$kami->where($map)->count();
    	$page = $this->page($count, 20);
    	$lists = $kami
	    	->where($map)
	    	->order("addtime DESC")
	    	->limit($page->firstRow . ',' . $page->listRows)
	    	->select();
		
		$counts = $kami
					->where($map)
					->count();	
					
			foreach($lists as $k=>$v){
				if($v['type']==1){
					$info=M("vip")->where("id=".$v['product_id'])->find();
					$lists[$k]['name'] = explode('|', $info['name'])[0];
					$lists[$k]['money'] = $info['coin'];
				}else{
					$info=M("charge_rules")->where("id=".$v['product_id'])->find();
					$lists[$k]['name'] = explode('|', $info['name'])[0];
					$lists[$k]['money'] = $info['money'];
				}
				 		 
			}
			
    	$this->assign('counts', $counts);
    	$this->assign('lists', $lists);
    	$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	$this->assign("p",$p);
    	$this->display();
	}
	function add(){
				$gift_sort=M("gift_sort")->getField("id,sortname");
				$this->assign('gift_sort', $gift_sort);					
			
				$this->display();				
		}	
	
	 //提交生成卡密
    function do_add(){
       
        $num = (int)I("num");
        $type = (int)I("type");
        $product_id = (int)I("product_id");
        $ctime= time();

        M()->startTrans();
        $result1=M("kami")->add(array('type'=>$type,'num'=>$num,'product_id'=>$product_id,'res_num'=>$num,'addtime'=>$ctime));//插入卡密组表

        /*$lastid=M("kami")->getLastInsID();//getLastInsID*/
        if($result1){
        	for ($i=0; $i < $num; $i++) { 
	        	$kamino=$this->str_rand();
	        	$ifres=M("kami_detail")->where(array('id'=>$kamino))->find();
	        	if($ifres){
	        		continue;
	        	}
	        	$data[$i]['id'] =$kamino;
	            $data[$i]['kami_id'] =$result1;
	            $data[$i]['addtime'] =$ctime;
        	}

	        if(count($data) !=$num){
	        	M("kami")->where(array('id'=>$result1))->save(array('num'=>count($data),'res_num'=>count($data)));
	        }
	        
	        $result2=M("kami_detail")->addAll($data);//插入卡密详情表
	        if($result2){
	        	M()->commit();
	        	$this->success('成功生成'.count($data).'个', U("Kami/add"));
	        }else{
	        	M()->rollback();
	        	$this->error('生成失败');
	        }
        }else{
        	$this->error('生成失败');
        }

    
    }

    protected function  str_rand(){
		$str="abcdefghijkmnpqrstuvwxyz0123456789ABCDEFGHIGKLMNPQRSTUVWXYZ";//设置被随机采集的字符串
		$codeLen='11';//设置生成的随机数个数
	    $rand="";
	    for($i=0; $i<$codeLen-1; $i++){
	        $rand .= $str[mt_rand(0, strlen($str)-1)];  //如：随机数为30  则：$str[30]
	    }
	    return $rand;
	}

	function kamilists(){
    	
    	$kamiid=I("kamiid");
    	$product_type=I("product_type");
    
    	$kami_detail=M("kami_detail");
    	$map=array();
    	//$map['parentid']=0;
    	$map['kami_id']=$kamiid;
    	$count=$kami_detail->where($map)->count();
    	$page = $this->page($count, 20);
    	//获取列表
    	$lists=$kami_detail->where($map)->order("addtime desc")->limit($page->firstRow . ',' . $page->listRows)->select();

    	//var_dump($kami_detail->getLastSql());
    	$status = array('未使用','已使用');
    	foreach ($lists as $k => $v) {
    		$lists[$k]['status'] = $status[$v['status']];	
    	}

    	//var_dump($lists);

    	$this->assign("lists",$lists);
    	$this->assign("page", $page->show('Admin'));
    	$this->display();

    }
//导出卡密
    function export(){
		$id=I("id");
		$type=I("type");//1-vip充值卡密 2-金币充值卡密
		$product_id=I("product");  
		$kami_detail=M("kami_detail");
		$xlsData=$kami_detail->where('kami_id='.$id)->select();
		if($type==1){
				$info=M("vip")->where("id=".$product_id)->find();
				$name = explode('|', $info['name'])[0];
				$money = $info['coin'];
			}else{
				$info=M("charge_rules")->where("id=".$product_id)->find();
				$name = explode('|', $info['name'])[0];
				$money = $info['money'];
		}
		$xlsName  = "Excel";
		$status = array('未使用','已使用');
        foreach ($xlsData as $k => $v)
        {
        	$xlsData1[$k]['sort'] = $k+1;
        	$xlsData1[$k]['id'] = $v['id'];
        	
			$xlsData1[$k]['name'] = $name;
			$xlsData1[$k]['money'] = $money.'元';
		
		    $xlsData1[$k]['status']=$status[$v['status']];
		    if($v['usetime']){
		  	    $xlsData1[$k]['addtime']=date("Y-m-d H:i:s",$v['usetime']);
		    }else{
		  	    $xlsData1[$k]['addtime']='';
		    }
				
        }
				$cellName = array('A','B','C','D','E','F');
				$xlsCell  = array(
            array('sort','序号'),
            array('id','卡号'),
            array('name','名称'),
            array('money','金额'),
            array('status','订单状态'),
            array('addtime','使用时间')
        );
				
        exportExcel($xlsName,$xlsCell,$xlsData1,$cellName);
	}



	function del(){

		$res=array("code"=>0,"msg"=>"删除成功","info"=>array());
		$id=I("id");
		$reason=I("reason");
		if(!$id){
			$res['code']=1001;
			$res['msg']='卡密信息加载失败';
			echo json_encode($res);
			exit;
		}	
		$result=M("kami")->where("id={$id}")->delete();
		if($result!==false){
			M("kami_detail")->where("kami_id={$id}")->delete();	 //删除卡密详情
			$res['msg']='卡密组删除成功';
			echo json_encode($res);
			exit;
		}else{
			$res['code']=1002;
			$res['msg']='删除失败';
			echo json_encode($res);
			exit;
		}			
										  			
	}		
}