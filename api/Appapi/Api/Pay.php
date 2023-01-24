<?php
session_start();
class Api_Pay extends Api_Common {
	private $secretId="1031319";
    private $sercretKey = "mV1X3NUPsxAv0S2m00chRWnt3ldetzeH";
	public function getRules() {
		return array(
			'chargepay' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'changeid' => array('name' => 'changeid', 'type' => 'int', 'require' => true, 'desc' => '兑换比例id'),
				'money' => array('name' => 'money', 'type' => 'int',  'require' => true, 'desc' => '金额'),
				'paytype' => array('name' => 'paytype', 'type' => 'string', 'require' => true, 'desc' => '支付类型'),
				'productype' => array('name' => 'productype', 'type' => 'int', 'require' => true, 'desc' => '产品类型'),
			),
			
			'getBaseInfo' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'version_ios' => array('name' => 'version_ios', 'type' => 'string', 'desc' => 'IOS版本号'),
			),
			
			'updateAvatar' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'file' => array('name' => 'file','type' => 'file', 'min' => 0, 'max' => 1024 * 1024 * 30, 'range' => array('image/jpg', 'image/jpeg', 'image/png'), 'ext' => array('jpg', 'jpeg', 'png')),
			),

			'submit_kami' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'kami' => array('name' => 'kami', 'type' => 'string', 'require' => true, 'desc' => '卡密号'),
			)

			
			
			
			
		);
	}
	


	//调用微信扫码支付接口=以及支付宝扫码支付===
    public function chargepay()
	{

		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 800;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		if($this->getcaches($uid."_".$productid)){
			$rs['code'] = 900;
			$rs['msg'] = '支付频繁，请稍后再试';
			return $rs;
		}

		
		$changeid=$this->checkNull($this->changeid);
		$paytype=$this->checkNull($this->paytype);
		$product_type=$this->checkNull($this->productype);
		$domain_Pay = new Domain_Pay();
        $re_info = $domain_Pay->getPayData($this->uid,$changeid,$paytype,$this->money,$product_type);
        if($re_info['code'] == 1001){
        	$rs['code'] = 1001;
			$rs['msg'] = '订单信息有误，请重新提交';
			return $rs;
        }

        if($re_info['code']==1000){
        	$rs['code'] = 1000;
			$rs['msg'] = '支付错误提示：'.$re_info['msg'];
			return $rs;
        }
        $rs['info'][0]= $re_info['msg'];
		return $rs;
	  
		
   			   
	}	
	
	
	
	//卡密充值
	public function submit_kami(){
		$rs = array('code' => 0, 'msg' => '兑换成功', 'info' => array());
		$uid=$this->uid;
		$token=$this->checkNull($this->token);
		$checkToken=$this->checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = '您的登陆状态失效，请重新登陆！';
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 800;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		$kamino=$this->kami;

		$kami_detail=DI()->notorm->kami_detail->select("*")->where("id='{$kamino}' and status=0")->fetchOne();
	
		$kami=DI()->notorm->kami->select("*")->where("id='{$kami_detail['kami_id']}'")->fetchOne();
		if(!$kami_detail || !$kami){
			$rs['code'] = 900;
			$rs['msg'] = '该卡密不存在或已失效';
			return $rs;
		}

		$data['usetime'] = time();
		$data['use_uid'] = $uid;
        $data['status'] = 1;//已使用
      
       
        DI()->notorm->beginTransaction('db_appapi');
        $res  = DI()->notorm->kami_detail->where("id='{$kamino}'")->update($data);
     
		if($kami['type']==1){//VIP充值

            	$charge=DI()->notorm->vip->select("*")->where("id='{$kami['product_id']}'")->fetchOne();
            	$user_vip=DI()->notorm->users_vip->select("*")->where("uid='{$uid}'")->fetchOne();
            	if($user_vip){//之前购买过累加
					$endtime = strtotime("+{$charge['length']} month",$user_vip['endtime']);
					$update_user=DI()->notorm->users_vip->where("uid='{$uid}'")->update(['endtime'=>$endtime]);
            	}else{
            		$endtime = strtotime("+{$charge['length']} month");
            		$update_user=DI()->notorm->users_vip->insert(['uid'=>$uid,'addtime'=>time(),'endtime'=>$endtime]);
            	}

            	$money = $charge['coin'];

            }else{//充金币
            	$charge=DI()->notorm->charge_rules->select("*")->where("id='{$kami['product_id']}'")->fetchOne();

            	$update_user  =DI()->notorm->users->where("id='{$uid}'")->update( array('coin' => new NotORM_Literal("coin + {$charge['coin']}") ) );
            	$this->add_coin_log($uid,$arr[1],$charge['coin'],'+','chongzhi',time());

            	$money = $charge['money'];
            }
            
        	$update_num  = DI()->notorm->kami->where("id='{$kami_detail['kami_id']}'")->update( array('res_num' => new NotORM_Literal("res_num - 1") ) );
            if($res  && $update_user && $update_num){ //DI()->config->get('dbs.servers.db_appapi.name')
            	
            	$this->setAgentProfit1($uid,$money);//分销分润

            	DI()->notorm->commit('db_appapi');
            	$rs['info'][0]=$this->getUserInfo($uid);
                return $rs;	
            }else{
            	DI()->notorm->rollback('db_appapi');
            	$rs['code'] = 1000;
				$rs['msg'] = '兑换失败，稍后再试';
				return $rs;
            }
                
		}
		

	

	  /**
     * qu支付支付回调
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function quzhifu_notify(){
       
        require_once "../../plugins/PaySdk.php";
        $sdki = new PaySdk($this->secretId, $this->sercretKey);
        if($_POST['callbacks'] =='CODE_SUCCESS'){
        	$ret = $sdki->notify($_POST);

	        if($ret){
	        	$no = $_POST['out_trade_no'];//获取订单号
	        	$chongzhi_type = strstr($no, 'VIP')?1:0; //判断是购买VIP还是购买金币
	            $orderInfo = DI()->notorm->users_charge->select('*')->where("orderno = '{$no}'")->fetchOne();
            	if(!$orderInfo){
            		file_put_contents("data/orderLog/".date('Y-m-d').".txt", '无此订单，订单号：'.$no.'/'.date('Y-m-d H:i:s').PHP_EOL, FILE_APPEND);
            		echo "fail";exit;
            	}
            	if($orderInfo['status']==1){
            		echo "success";exit;
            	}
            	if($_POST['amount'] != $orderInfo['money']){//测试 暂时相等
            		file_put_contents("data/orderLog/".date('Y-m-d').".txt", '订单金额与支付金额不匹配，订单号：'.$no.'/'.date('Y-m-d H:i:s').PHP_EOL, FILE_APPEND);
            		echo "fail";exit;
            	}
                
                
                //===============修改订单状态===========================//
                $users_charge = DI()->notorm->users_charge;
				$users_vip = DI()->notorm->users_vip;
				$userss = DI()->notorm->users;

                // $data['trade_no'] = $_POST['trade_no'];//平台订单号
                $data['status'] = 1;//成功
                $res  = $users_charge->where('orderno=?',$no)->update($data);
                $arr =explode('_', $no);
                if($chongzhi_type){//VIP充值

                	$charge=DI()->notorm->vip->select("*")->where("id=?",$arr[2])->fetchOne();
                	$user_vip=DI()->notorm->users_vip->select("*")->where("id=?",$orderInfo['uid'])->fetchOne();
                	if($user_vip){//之前购买过累加
                		if($user_vip['endtime']<time()){
                			$endtime = strtotime("+{$charge['length']} month",time());
							$update_user=$users_vip->where("uid=?",$orderInfo['uid'])->update(['endtime'=>$endtime]);
                		}else{
                			$endtime = strtotime("+{$charge['length']} month",$user_vip['endtime']);
							$update_user=$users_vip->where("uid=?",$orderInfo['uid'])->update(['endtime'=>$endtime]);
                		}
						
                	}else{
                		$endtime = strtotime("+{$charge['length']} month");
                		$update_user=$users_vip->where("uid=?",$orderInfo['uid'])->insert(['uid'=>$orderInfo['uid'],'addtime'=>time(),'endtime'=>$endtime]);
                	}

                }else{//充金币
                	$charge=DI()->notorm->charge_rules->select("*")->where("id=?",$arr[1])->fetchOne();

                	$update_user  =$userss->where("id='{$orderInfo['uid']}'")->update( array('coin' => new NotORM_Literal("coin + {$charge['coin']}") ) );
                	$this->add_coin_log($orderInfo['uid'],$arr[1],$charge['coin'],'+','chongzhi',time());
                }

                $this->setAgentProfit1($orderInfo['uid'],$_POST['total_amount']);//分销分润
                
                if(!$res){
                	file_put_contents('data/orderLog/'.date('Y-m-d').".txt", '订单状态更改失败，订单号：'.$no.'/'.date('Y-m-d H:i:s').PHP_EOL, FILE_APPEND);
            		echo "fail";exit;
                }

                if(!$update_user){
                	file_put_contents('data/orderLog/'.date('Y-m-d').".txt", '未到账，订单号：'.$no.'/'.date('Y-m-d H:i:s').PHP_EOL, FILE_APPEND);
            		echo "fail";exit;
                }

                echo 'success';exit;
	        }else{
	            file_put_contents('data/orderLog/'.date('Y-m-d').".txt","交易失败！签名验证错误，订单号：" . $no.'/'.date('Y-m-d H:i:s').PHP_EOL, FILE_APPEND);
	            echo "fail";exit;
	        }
        }else{
        	file_put_contents('data/orderLog/'.date('Y-m-d').".txt","交易失败！返回码错误，订单号：" . $no.'/'.date('Y-m-d H:i:s').PHP_EOL, FILE_APPEND);
            echo "fail";exit;
        }
        
    }

	public function alipay_notify(){
		$Chargedetail = D("users_charge");
		//读取后台配置信息
		$getConfigPri=$this->getConfigPri();	
		$getConfigPub=$this->getConfigPub();
		//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
		
        $aop = new AopClient;
        $aop->appId = '2016101900726399';             //支付宝AppId
        $aop->rsaPrivateKey = file_get_contents('aliprivatekey.txt');  //支付宝私钥
        $aop->alipayrsaPublicKey = file_get_contents('alipubkey.txt');;//支付宝公钥
        $flag = $aop->rsaCheckV1($_POST, NULL, "RSA2");

        if ($flag) {
            if ($_POST['trade_status'] == 'TRADE_SUCCESS') {//如果支付成功

            	$orderInfo = DI()->notorm->users_charge->select('*')->where('orderno='.$no)->fetchOne();
            	if(!$orderInfo){
            		file_put_contents("data/orderLog/".date('Y-m-d').".txt", '无此订单，订单号：'.$no.'/'.date('Y-m-d H:i:s').PHP_EOL, FILE_APPEND);
            		echo "fail";die;
            	}
            	
            	if($_POST['total_amount'] != $orderInfo['money']){
            		file_put_contents("data/orderLog/".date('Y-m-d').".txt", '订单金额与支付金额不匹配，订单号：'.$no.'/'.date('Y-m-d H:i:s').PHP_EOL, FILE_APPEND);
            		echo "fail";die;
            	}
                
                
                //===============修改订单状态===========================//
                $no = $_POST['out_trade_no'];//获取订单号
                $data['trade_no'] = $_POST['trade_no'];//平台订单号
                $data['status'] = 1;//成功
                $res  = DI()->notorm->users_charge->where('orderno='.$no)->update($data);
                
                if(!$res){
                	file_put_contents("data/orderLog/".date('Y-m-d').".txt", '订单状态更改失败，订单号：'.$no.'/'.date('Y-m-d H:i:s').PHP_EOL, FILE_APPEND);
            		echo "fail";die;
                }
                
            }
            echo "success";
            die;//  支付成功
        } else {
            echo "fail";
            die; //支付失败
        }


		if($verify_result) {//验证成功
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//请在这里加上商户的业务逻辑程序代
			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
			//获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
			//session("uid")."_".session("uid")."_".date("mdHis")."_".rand(999,9999); 
			//商户订单号
			$out_trade_no = $_POST['out_trade_no'];
			//支付宝交易号
			$trade_no = $_POST['trade_no'];
			//交易状态
			$trade_status = $_POST['trade_status'];
			//交易金额
			$total_fee = $_POST['total_fee'];

			if($trade_status == 'TRADE_FINISHED') {
				//判断该笔订单是否在商户网站中已经做过处理
				//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
				//如果有做过处理，不执行商户的业务程序
					
				//注意：
				//退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
				//请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的

				//调试用，写文本函数记录程序运行情况是否正常
				//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
		
			}else if ($trade_status == 'TRADE_SUCCESS') {
				//判断该笔订单是否在商户网站中已经做过处理
				//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
				//如果有做过处理，不执行商户的业务程序
					
				//注意：
				//付款完成后，支付宝系统发送该交易状态通知
				//请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的

				//调试用，写文本函数记录程序运行情况是否正常
				//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
	
				$orderinfo=$Chargedetail->where("orderno='{$out_trade_no}' and money='{$total_fee}' and status='0' and type='1'")->find();	

				if($orderinfo){
					/* 更新会员虚拟币 */
					$coin=$orderinfo['coin']+$orderinfo['coin_give'];
					M("users")->where("id='{$orderinfo['touid']}'")->setInc("coin",$coin);
					/* 更新 订单状态 */
					M("users_charge")->where("id='{$orderinfo['id']}'")->save(array("status"=>1,"trade_no"=>$trade_no));

					$this->logali("成功");	
					echo "success";		//请不要修改或删除
					exit;
				}else{
					$this->logali("orderno:".$out_trade_no.' 订单信息不存在');		
				}											
			}
			
		
			//更新会员余额
			
			echo "fail";		//请不要修改或删除
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	/* 打印log */
		//file_put_contents('./logali.txt',date('y-m-d h:i:s').'  msg:'.$msg."\r\n",FILE_APPEND);
		}	
		else {
			//验证失败
			echo "fail";
			//调试用，写文本函数记录程序运行情况是否正常
			//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
		}	
	}	
	//支付宝即时到帐  返回处理	
	
	
	/* 打印log */
	public function logali($msg){
		//file_put_contents('./logali.txt',date('y-m-d h:i:s').'  msg:'.$msg."\r\n",FILE_APPEND);
	}	
	//===========================
	
	
	public function getOrderStatus(){
		require_once "./wxpay/lib/WxPay.Api.php";
		require_once "./wxpay/lib/WxPay.Notify.php";
		require_once "./wxpay/pay/notify.php";
	   
	 	$orderid = $_GET['orderid'];
	 	$notify = new \PayNotifyCallBack();
		$wxpayStatus=$notify->Queryorder($orderid);
		 
		$order_info = explode("_",$orderid); 
		$uid = $order_info[0];
		$touid = $order_info[1];
		
		//获取该订单在数据库内的信息
		$Chargedetail = D("users_charge");
		$orderinfo=$Chargedetail->where("orderno='$orderid' and touid=$touid and uid=$uid")->find();
		
	
		if($orderinfo['status']==1){
				echo "订单已完成";
				exit;
		}
       
		//订单是否真正支付
		if($wxpayStatus['trade_state']=='SUCCESS'){
			
			if($wxpayStatus['out_trade_no']==$orderid && $orderinfo['status']==0){

				//该数据库状态
				$data['status']="1";
				$coin=$orderinfo['coin']+$orderinfo['coin_give'];
				$Chargedetail -> where("orderno='$orderid' and touid=$touid and uid=$uid")->save($data);

				$aaa=D("users")->execute('update cmf_users set coin=coin+'.($coin).' where id='.$orderinfo['touid']);
				echo 1;
			}else{
				echo "订单已完成";
			}

		}elseif($wxpayStatus['trade_state']=='NOTPAY'){
			echo 0 ; //未支付
		}else{
			echo -1;//未知错误
		} 
	}

	
}
