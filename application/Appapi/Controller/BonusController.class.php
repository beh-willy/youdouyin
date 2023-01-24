<?php
/**
 * 票房和分红列表
 */
namespace Appapi\Controller;
use Common\Controller\HomebaseController;
class BonusController extends HomebaseController {

	var $status=array(
        '1'=>'审核中',
        '2'=>'成功',
        '3'=>'失败',
    );

    var $type=array(
        '1'=>'微信',
        '2'=>'支付宝',
    );
	
	/*金币提现记录*/

	function tacket(){
           
		$uid=I("uid");
		$token=I("token");
		if(checkToken($uid,$token)==700){
			$this->assign("reason",'您的登陆状态失效，请重新登陆！');
			$this->display(':error');
			exit;
		}

		$this->assign("uid",$uid);
        $this->assign("token",$token);

        $total=M("users_coin_cashlist")->where("uid='{$uid}'")->count();
        $this->assign("total",$total);
        

        $list=M("users_coin_cashlist")->where("uid='{$uid}'")->order("addtime desc")->limit(0,20)->select();

        $count1=count($list);
        foreach($list as $k=>$v){
            $list[$k]['addtime']=date("y-m-d H:i",$v['addtime']);
            $list[$k]['status']=$this->status[$v['status']];
            $list[$k]['type']=$this->type[$v['type']];
        }
        
        $this->assign("list",$list);
        $this->assign("count1",$count1);
        $this->assign("time",time());

		$this->display();
	    
	}


	function getticketmore(){       
        $uid=checkNull(I('uid'));
        $token=checkNull(I('token'));
        $p=checkNull(I('page'));
        
        $result=array(
			'data'=>array(),
			'nums'=>0,
			'isscroll'=>0,
		);
  
		$page_nums=20;
		$start=($p-1)*$page_nums;
        
        if(checkToken($uid,$token)==700){
			echo json_encode($result);
            exit;	
		} 
        
        $list=M("users_coin_cashlist")->where("uid='{$uid}'")->order("addtime desc")->limit($start,$page_nums)->select();
        foreach($list as $k=>$v){
            $list[$k]['addtime']=date("y-m-d H:i",$v['addtime']);
            $list[$k]['status']=$this->status[$v['status']];
            $list[$k]['type']=$this->type[$v['type']];
        }
        
        $nums=count($list);
		if($nums<$page_nums){
			$isscroll=0;
		}else{
			$isscroll=1;
		}
        
        
		$result=array(
			'data'=>$list,
			'nums'=>$nums,
			'isscroll'=>$isscroll,
		);
	 
		echo json_encode($result);
		exit;	
	    
	}

	function bonuslist(){

		$uid=checkNull(I("uid"));
        $token=checkNull(I("token"));
        if(checkToken($uid,$token)==700){
            $this->assign("reason",'您的登陆状态失效，请重新登陆！');
            $this->display(':error');
            exit;
        }

        $this->assign("uid",$uid);
        $this->assign("token",$token);
        $this->assign("time",time());
        $this->display();
	}

	/*收入获取*/

     function getBonusIncome(){

        $uid=checkNull(I("uid"));
        $token=checkNull(I("token"));
        $p=(int)checkNull(I('p'));

        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        if(checkToken($uid,$token)==700){
            $rs['code']=700;
            $rs['msg']='您的登陆状态失效，请重新登陆！';
            echo json_encode($rs);
            exit;
        }


        if(!$p){
            $p=1;
        }
        $pnum=20;
        $start=($p-1)*$pnum;

        $list=M("users_bonus_income")
            ->field("bonus,addtime")
            ->where("uid={$uid}")
            ->order("addtime desc")
            ->limit($start,$pnum)
            ->select();

        foreach ($list as $k => $v) {
        	$list[$k]['addtime']=date("y-m-d H:i",$v['addtime']);
        }
        
        $nums=count($list);
        if($nums<$pnum){
            $isscroll=0;
        }else{
            $isscroll=1;
        }


        $rs['info']['list']=$list;
        $rs['info']['isscroll']=$isscroll;
        $rs['info']['nums']=$nums;

        echo json_encode($rs);
        exit;
    

    } 

	
    /*分红提现记录*/

     function getBonusForward(){
        $uid=checkNull(I("uid"));
        $token=checkNull(I("token"));
        $p=(int)checkNull(I('p'));
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        if(checkToken($uid,$token)==700){
            $rs['code']=700;
            $rs['msg']='您的登陆状态失效，请重新登陆！';
            echo json_encode($rs);
            exit;
        }


        if(!$p){
            $p=1;
        }
        $pnum=10;
        $start=($p-1)*$pnum;

        $list=M("users_bonus_cashlist")
            ->where("uid={$uid}")
            ->order("addtime desc")
            ->limit($start,$pnum)
            ->select();

       
        foreach ($list as $k => $v) {
            $list[$k]['addtime']=date("y-m-d H:i",$v['addtime']);
            
            if($v['type']==1){
                $list[$k]['type']='微信';
            }else{
                $list[$k]['type']='支付宝';
            }

            $list[$k]['type']=$this->type[$v['type']];
            $list[$k]['status']=$this->status[$v['status']];
           
        }
        $nums=count($list);
        if($nums<$pnum){
            $isscroll=0;
        }else{
            $isscroll=1;
        }


        $rs['info']['list']=$list;
        $rs['info']['isscroll']=$isscroll;
        $rs['info']['nums']=$nums;

        echo json_encode($rs);
        exit;
     }  
	
	
}