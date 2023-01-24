<?php

class Model_Bonus extends Model_Common {


	/* 金币提现 */
	public function setCash($uid,$money,$type,$account) {
		
		//获取用户的金币数
		$userInfo=DI()->notorm->users->select("coin")->where("id=?",$uid)->fetchOne();
		if($userInfo['coin']==0){
			return 1001;
		}


		$configPri=$this->getConfigPri();
		//计算可提现金额（用户金币*后台配置的金币提现比例）
		$ticket=floor($userInfo['coin']/$configPri['ticket_percent']*100)/100;

		//获取后台配置的最低提现金额
		$draw_min_cash=$configPri['draw_min_cash'];


		if($ticket<$draw_min_cash){
			return 1002;
		}

		if($money<$draw_min_cash){
			return 1002;
		}

		//计算需要扣除的金币
		$needCoin=$money*$configPri['ticket_percent'];

		if($needCoin>$userInfo['coin']){
			return 1003;
		}

		//写入提现记录
		$data=array(
			'uid'=>$uid,
			'type'=>$type,
			'account'=>$account,
			'percent'=>$configPri['ticket_percent'],
			'money'=>$money,
			'coin'=>$needCoin,
			'addtime'=>time(),
			'status'=>1,
		);

		$result=DI()->notorm->users_coin_cashlist->insert($data);
		if($result!==false){
			//去除用户的金币
			DI()->notorm->users->where("id=?",$uid)->update(array('coin' => new NotORM_Literal("coin - {$needCoin}")));
		}else{
			return 1004;
		}

		return 1;

	}

	public function getCashLists($uid,$p){
		
		$nums=20;
		$start=($p-1)*$nums;

		$list=DI()->notorm->users_coin_cashlist->select("id,type,account,money,addtime,status")->where("uid=?",$uid)->order("addtime desc")->limit($start,$nums)->fetchAll();
		if (!$list) {
			return 1001;
		}




		foreach ($list as $k => $v) {
			$list[$k]['addtime']=date("Y-m-d H:i:s",$v['addtime']);
			if($v['type']==1){
				$list[$k]['type']="微信";
			}else if($v['type']==2){
				$list[$k]['type']="支付宝";
			}else{
				$list[$k]['type']="银行卡";
			}

			if($v['status']==1){
				$list[$k]['status']="审核中";
			}else if($v['status']==2){
				$list[$k]['status']="审核成功";
			}else if($v['status']==3){
				$list[$k]['status']="审核失败";
			}


		}

		return $list;
	}


	/*分红提现*/
	public function setBonus($uid,$money,$type,$account){

		//获取用户的金额
		$userInfo=DI()->notorm->users->select("money")->where("id=?",$uid)->fetchOne();
		if($userInfo['money']==0){
			return 1001;
		}

		$configPri=$this->getConfigPri();

		//获取后台配置的最低提现金额
		$bonus_min_cash=$configPri['bonus_min_cash'];


		if($money<$bonus_min_cash){
			return 1002;
		}

		if($money>$userInfo['money']){
			return 1003;
		}


		//写入提现记录
		$data=array(
			'uid'=>$uid,
			'type'=>$type,
			'account'=>$account,
			'money'=>$money,
			'addtime'=>time(),
			'status'=>1,
		);

		$result=DI()->notorm->users_bonus_cashlist->insert($data);
		if($result!==false){
			//去除用户的金额
			DI()->notorm->users->where("id=?",$uid)->update(array('money' => new NotORM_Literal("money - {$money}")));
		}else{
			return 1003;
		}

		return 1;


	}	

	public function getBonusLists($uid,$p){
		
		$nums=20;
		$start=($p-1)*$nums;

		$list=DI()->notorm->users_bonus_cashlist->select("id,type,account,money,addtime,status")->where("uid=?",$uid)->order("addtime desc")->limit($start,$nums)->fetchAll();
		if (!$list) {
			return 1001;
		}

		foreach ($list as $k => $v) {
			$list[$k]['addtime']=date("Y-m-d H:i:s",$v['addtime']);
			if($v['type']==1){
				$list[$k]['type']="微信";
			}else{
				$list[$k]['type']="支付宝";
			}

			if($v['status']==1){
				$list[$k]['status']="审核中";
			}else if($v['status']==2){
				$list[$k]['status']="审核成功";
			}else if($v['status']==3){
				$list[$k]['status']="审核失败";
			}


		}

		return $list;
	}	

}
