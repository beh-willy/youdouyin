<?php
/**
 * 贡献榜
 */
namespace Appapi\Controller;
use Common\Controller\HomebaseController;
class ContributeController extends HomebaseController {
	
	function index(){
		$uid=I("uid");
		$p=1;
		$page_nums=20;
		$start=($p-1)*$page_nums;
		$list=M("users_coinrecord")->field("uid,sum(totalcoin) as total")->where(" action in ('sendgift','sendbarrage','votechange','urgemoneys') and touid='{$uid}'")->group("uid")->order("total desc")->limit($start,$page_nums)->select();
		foreach($list as $k=>$v){
			$list[$k]['userinfo']=getUserInfo($v['uid']);
		}
		/* var_dump($list); */
		$this->assign("uid",$uid);
		$this->assign("p",$p+1);
		$this->assign("list",$list);


		$this->display();		
	}
	
	function getmore(){
		$uid=I("uid");
		$p=I("page");
		$page_nums=20;
		$start=($p-1)*$page_nums;
		
		$list=M("users_coinrecord")->field("uid,sum(totalcoin) as total")->where(" action in ('sendgift','sendbarrage','votechange','urgemoneys') and touid='{$uid}'")->group("uid")->order("total desc")->limit($start,$page_nums)->select();
		foreach($list as $k=>$v){
			$list[$k]['userinfo']=getUserInfo($v['uid']);
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
			'start'=>$start,
			'isscroll'=>$isscroll,
		);
	 
		echo json_encode($result);
		exit;		
	}
	
	public function order(){
		$uid=I("uid");
		$type=I("type");
		
		if($type=='week'){
			
			$nowtime=time();
			//当天0点
			//$today=date("Ymd",$nowtime);
			//$today_start=strtotime($today);
			//当天 23:59:59
			//$today_end=strtotime("{$today} + 1 day")-1;

			$w=date('w',$nowtime); 
			//获取本周开始日期，如果$w是0，则表示周日，减去 6 天 
			$first=1;
			//周一
			$week=date('Y-m-d H:i:s',strtotime( date("Ymd")."-".($w ? $w - $first : 6).' days')); 
			$week_start=strtotime( date("Ymd")."-".($w ? $w - $first : 6).' days'); 

			//本周结束日期 
			//周天
			$week_end=strtotime("{$week} +1 week")-1;
			
			
			$list=M("users_coinrecord")->field("uid,sum(totalcoin) as total")->where(" action in ('sendgift','sendbarrage','votechange','urgemoneys') and touid='{$uid}' and addtime>{$week_start} and addtime<{$week_end}")->group("uid")->order("total desc")->limit(0,20)->select();
			
			foreach($list as $k=>$v){
				$list[$k]['userinfo']=getUserInfo($v['uid']);
			}
		}else{
			$list=M("users_coinrecord")->field("uid,sum(totalcoin) as total")->where(" action in ('sendgift','sendbarrage','votechange','urgemoneys') and touid='{$uid}'")->group("uid")->order("total desc")->limit(0,20)->select();
			foreach($list as $k=>$v){
				$list[$k]['userinfo']=getUserInfo($v['uid']);
			}
		}

		$this->assign("list",$list);
		
		
		
		
		


		$this->display();			
		
		
	}

}