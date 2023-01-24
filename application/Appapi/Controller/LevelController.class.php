<?php
/**
 * 会员等级
 */
namespace Appapi\Controller;
use Common\Controller\HomebaseController;
class LevelController extends HomebaseController {
	
	function index(){       
		$uid=I("uid");
		$token=I("token");
		
		if(checkToken($uid,$token)==700){
			$this->assign("reason",'您的登陆状态失效，请重新登陆！');
			$this->display(':error');
			exit;
		} 
		
		$User=M("users");
		
		$userinfo=$User->field("consumption,votestotal,coin,live_level,unexchange_like,all_like,urge_moneys,send_likes,send_comments,send_shares,all_looktimes")->where("id={$uid}")->find();
		$this->assign("userinfo",$userinfo);
		
		$experienceuserlevel=$this->getuserlevel($userinfo['consumption'],$userinfo['send_likes'],$userinfo['send_comments'],$userinfo['send_shares'],$userinfo['all_looktimes'],$uid);
		
		/* 用户等级 */
		$Level=M("experlevel");  
		
		/* $levelinfo=$Level->where("level_up>='{$userinfo['consumption']}'")->order("levelid asc")->find(); */
		$levelinfo=$Level->where("level_up>='{$experienceuserlevel}'")->order("levelid asc")->find();
		if(!$levelinfo){
			$levelinfo=$Level->order("levelid desc")->find();
		}
		/* $cha=$levelinfo['level_up']+1-$userinfo['consumption']; */
		$cha=$levelinfo['level_up']+1-$experienceuserlevel;
		if($cha>0)
		{
			/* $baifen=floor($userinfo['consumption']/$levelinfo['level_up']*100); */
			$baifen=floor($experienceuserlevel/$levelinfo['level_up']*100);
			$type="1";
		}else{
			$baifen=100;
			$type="0";
		}
		
		$total_nums=$User->where("user_type='2'")->count();
		/* $less_nums=$User->where("user_type='2' and consumption< {$userinfo['consumption']}")->count(); */
		$less_nums=$this->getlosernums($experienceuserlevel,$uid);
		
		$compare=floor($less_nums/$total_nums*100);

		$this->assign("compare",$compare);
		$this->assign("baifen",$baifen);
		$this->assign("levelinfo",$levelinfo);
		$this->assign("cha",$cha);
		$this->assign("type",$type);
		$this->assign("experienceuserlevel",$experienceuserlevel);
		
		
	
		/* 主播等价 */
		$experiencelivelevel=$this->getlivelevelanchor($userinfo['all_like'],$userinfo['urge_moneys'],$userinfo['live_level'],$userinfo['unexchange_like'],$uid);
		$Level_a=M("experlevel_anchor");  
		
		/* $levelinfo_a=$Level_a->where("level_up>='{$userinfo['votestotal']}'")->order("levelid asc")->find(); */
		
		$levelinfo_a=$Level_a->where("level_up>='{$experiencelivelevel}'")->order("levelid asc")->find();
		if(!$levelinfo_a){
			$levelinfo_a=$Level_a->order("levelid desc")->find();
		}
		/* $cha_a=$levelinfo_a['level_up']+1-$userinfo['votestotal']; */
		$cha_a=$levelinfo_a['level_up']+1-$experiencelivelevel;
		if($cha_a)
		{
			/* $baifen_a=floor($userinfo['votestotal']/$levelinfo_a['level_up']*100); */
			$baifen_a=floor($experiencelivelevel/$levelinfo_a['level_up']*100);
			$type_a="1";
		}else{
			$baifen_a=0;
			$type_a="0";
		}
		//主播现在等级
		$nowlivelevelinfo=$Level_a->where("levelid='{$userinfo['live_level']}'")->find();
		//剩余票房大于等级设置兑换比例：只显示经验值，主播等级不升级，升级需兑换票房后
		
		if($userinfo['unexchange_like']>$nowlivelevelinfo['like_to_vote']){
			$levelinfo_a['levelid']=$userinfo['live_level'];
			$type_a="2";//不做升级，保持原有等级
		}
		$this->assign("cha_a",$cha_a);
		$this->assign("type_a",$type_a);
		$this->assign("baifen_a",$baifen_a);
		$this->assign("levelinfo_a",$levelinfo_a);
		$this->assign("experiencelivelevel",$experiencelivelevel);
		
		
		$this->display();
	    
	}
	//获取击败观众个数:$experienceuserlevel:当前用户的经验值
	function getlosernums($experienceuserlevel,$uid){
		$userlist=M("users")->where("user_type='2' and id !=".$uid)->select();
		$count=0;
		foreach($userlist as $k=>$v){
			$experience=$this->getuserlevel($v['consumption'],$v['send_likes'],$v['send_comments'],$v['send_shares'],$v['all_looktimes'],$v['id']);
			if($experienceuserlevel>$$experience){//当前用户经验值大于用户的经验值
				$count++;
			}
		}
		return $count;
	}
	//观众等级经验
	function getuserlevel($coin,$sendlikes,$sendcomments,$shares,$all_looktimes,$uid){
		
		$configpri=getConfigPri();
		//消费金额权重值
		$consumption_weight=$configpri['consumption_weight'];
		//点赞数量权重值
		$sendlikes__weight=$configpri['sendlikes__weight'];
		//评论数量权重值
		$sendcomments__weight=$configpri['sendcomments__weight'];
		//分享数量权重值
		$shares__weight=$configpri['shares__weight'];
		//观看时长权重值：单位：小时
		$looktime__weight=$configpri['looktime__weight'];
		//剩余观看时间：looktimes:单位秒
		/* $all_looktimes=4000; */
		$hourtimes=intval(floor($all_looktimes/(60*60)));
		$shengyutimes=$all_looktimes%(60*60);//剩余未满小时时间
		/* var_dump($hourtimes); */
		//当前经验值
		//升级计算方式：
		$experience=$consumption_weight*$coin+$sendcomments__weight*$sendcomments+$sendlikes__weight*$sendlikes+$shares__weight*$shares+$looktime__weight*$hourtimes;
		return $experience;
	}
	/* 主播等级 new*/
	function getlivelevelanchor($all_like,$urge_moneys,$livelevel,$unexchange_like,$uid){
		$configpri=getConfigPri();
		//视频点在权重值
		$like_weight=$configpri['like_weight'];
		//上传视频数量权重值
		$videonum_weight=$configpri['videonum_weight'];
		//催更金额权重值
		$reminder_weight=$configpri['reminder_weight'];
		//上传视频数量查询
		$dialectcount=M("users_dialect")
			->where('uid='.$uid)
			->count();	
		$videocount=M("users_video")
			->where('uid='.$uid)
			->count();	
		$allvideocount=$dialectcount+$videocount;
		//当前经验值
		//升级计算方式：视频总点赞*视频总点赞权重+上传视频数量*上传视频数量权重+催更收到的金额*催更收到的金额权重
		$experience=$all_like*$like_weight+$videonum_weight*$allvideocount+$reminder_weight*$urge_moneys;
		
		return $experience;
	}
	
	function level(){
		$list=M("experlevel")->order("levelid asc")->select();
		foreach($list as $k=>$v){
			$list[$k]['level_up']=number_format($v['level_up']);
		}
		$this->assign("list",$list);
		$this->display();
	}

	function level_a(){
		$list=M("experlevel_anchor")->order("levelid asc")->select();
		foreach($list as $k=>$v){
			$list[$k]['level_up']=number_format($v['level_up']);
		}
		$this->assign("list",$list);
		$this->display();
	}
}