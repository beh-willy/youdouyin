<?php
/****
**兑换：金币、观看时长
***/
class Model_Exchange extends Model_Common {
	/* 获取映票信息 */
	public function getVotesAndLike($uid){
		$userinfo=DI()->notorm->users
						->select("unexchange_like,all_like,live_level")
						->where("id=".$uid)
						->fetchOne();
		if(!$userinfo){
			return 0;
		}
		$levelinfo=$this->getLevelAnchorinfo($userinfo['live_level']);
		$userinfo['changeset']=$levelinfo['like_to_vote'];
		return $userinfo;
	}
	/* 获取观看时间信息 */
	public function getLooktimesAndCoin($uid){
		$userinfo=DI()->notorm->users
						->select("all_looktimes,looktimes")
						->where("id=".$uid)
						->fetchOne();
		if(!$userinfo){
			return 0;
		}
		$configpri=$this->getConfigPri();
		$userinfo['changeset']=$configpri['looktime_to_coin'];
		return $userinfo;
	}
	/* 兑换界面输入兑换的映票返回的信息集合 */
	public function showExchangevotes($votestotal,$unexchange_like,$changeset){
		$needlike=$votestotal*$changeset;
		
		if($unexchange_like>$needlike){
			$overlike=$unexchange_like-$needlike;
		}else{
			return 1001;//剩余金币不足兑换输入的映票数量
		}
		return $overlike;
	}
	
	
	/* 金币兑换映票 */
	public function changelikeTovotes($uid,$votes,$type) {
		$userinfo=DI()->notorm->users
						->select("unexchange_like,all_like,live_level,urge_moneys,looktimes")
						->where("id=".$uid)
						->fetchOne();
		if(!$userinfo){
			return 1001;
		}
		if($type==0){//金币兑换映票
			$levelinfo=$this->getLevelAnchorinfo($userinfo['live_level']);
			$changeset=$levelinfo['like_to_vote'];
			$needlike=$changeset*$votes;
			if($userinfo['unexchange_like']<$needlike){
				return 1002;
			}
			/* 更新 用户金币、映票数据 */
			DI()->notorm->users
				->where("id = '{$uid}'")
				->update( array('unexchange_like' => new NotORM_Literal("unexchange_like - ".$needlike."") ) );
		}else{//观看时长兑换钻石
			$configpri=$this->getConfigPri();//looktime_to_coin观看时长兑换钻石比例
			//剩余观看时长
			$unlooktime=intval(floor($userinfo['looktimes']/60));//秒转换成为分钟
			$shengyutimes=$userinfo['looktimes']%60;//剩余未满一分钟的时间
			$needlike=$configpri['looktime_to_coin']*$votes;
			if($unlooktime<$needlike){
				return 1002;
			}
			
			/* 更新 用户观看时长、钻石数据 */
			/* $newtimes=($unlooktime-$needlike)*60+$shengyutimes;//剩余观看时间（单位：秒） */
			$newtimes=$needlike*60;//已兑换的时间（单位：秒）
			
			DI()->notorm->users
				->where("id = '{$uid}'")
				->update( array('looktimes' => new NotORM_Literal("looktimes - ".$newtimes."") ) );
			/* 记录 */
			$insert=array("type"=>'income',"action"=>'exchangecoin',"uid"=>$uid,"touid"=>$uid,"giftid"=>'0',"giftcount"=>'0',"totalcoin"=>$votes,"showid"=>'0',"addtime"=>time() );
			$isup=DI()->notorm->users_coinrecord->insert($insert);
		}
		
		
		//添加兑换记录
		$data=array(
			"uid"=>$uid,
			"likenums"=>$needlike,
			"votenums"=>$votes,
			"type"=>$type,
			"addtime"=>time(),
		);
		$result= DI()->notorm->exchange->insert($data);
		if($result){
			if($type==0){
				$result['shengyulike']=$userinfo['unexchange_like']-$needlike;
			}else{
				$newtimes=($unlooktime-$needlike)*60+$shengyutimes;//剩余观看时间（单位：秒）
				$result['shengyulike']=$newtimes;
			}
			/* $userinfo=DI()->notorm->users
						->select("unexchange_like,all_like,live_level,urge_moneys")
						->where("id=".$uid)
						->fetchOne();
			//兑换映票后判断该用户主播等级是否可以升级
			if($type==0){//金币兑换映票
				$this->getLiveLevelAnchor($userinfo['all_like'],$userinfo['urge_moneys'],$userinfo['live_level'],$userinfo['unexchange_like'],$uid);
			}else{//判断观众等级是否可以升级
				
			} */
		}
		if(!$result['shengyulike']){
			$result['shengyulike']='0';
		}
		
		return $result;
	}	
	
	
	/* 兑换记录 */
	public function getExchangeRecord($uid,$type){
		$lists=DI()->notorm->exchange
						->select("*")
						->where("uid=".$uid." and type=".$type)
						->order("addtime desc")
						->fetchAll();
		
		foreach($lists as $k=>$v){
			$lists[$k]['addtime']=date("Y-m-d",$v['addtime']);
			if($lists[$k]['uptime']){
				$lists[$k]['uptime']=date("Y-m-d",$v['uptime']);
			}
			
		}
		return $lists;
	}

}
