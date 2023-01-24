<?php

class Model_Pay extends Model_Common {


	protected $config;


	public function __construct(){

  		$this->config = array_merge($this->getConfigPri(),$this->getConfigPub());
   	}
	
   	//发起支付 传给前端
	public  function getPayData($uid,$changeid,$paytype,$money,$productid) {
        $time = time();
        $order = array();
        $config = $this->config;
        $order['type'] = $paytype == 'alipay'?1:2;

        if($productid ==2){//vip支付
            $charge=DI()->notorm->vip->select("*")->where("id=?",$changeid)->fetchOne();
            $order['money'] =number_format($charge['coin'],2) ;//支付金额
            $order['coin'] = 0;
            $order['coin_give'] = 0;
            $order['uid'] = $uid;
            $order['touid'] = $uid;
            $order['trade_no'] = '';
            $order['addtime'] = $time;
            $order['orderno'] = 'VIP_'.$uid."_".$changeid."_".date("mdHis")."_".rand(00000,99999);

           
            $order_insert = DI()->notorm->users_charge->insert($order);
        }else{//购买金币支付
            $charge=DI()->notorm->charge_rules->select("*")->where("id=?",$changeid)->fetchOne();
            $order['money'] =number_format($charge['money'],2) ;//支付金额
            $order['coin'] = $charge['coin'];//金币数量
            $order['coin_give'] = $charge['give'];//赠送金币
            $order['uid'] = $uid;
            $order['touid'] = $uid;
            $order['trade_no'] = '';
            $order['addtime'] = $time;
            $order['orderno'] = $uid."_".$changeid."_".date("mdHis")."_".rand(00000,99999);


            $order_insert = DI()->notorm->users_charge->insert($order);
        }
		if(!$charge || !$order_insert){
            $result['code']=1001;
            $result['msg']='';
            return $result;
        }
	
            $this->setcaches($uid."_".$productid,$order_insert['id'],60);//不能频繁支付

            $pay_notifyurl = $config['site_url']. '/?service=Pay.quzhifu_notify';//服务端返回地址
            $pay_callbackurl = $config['site_url'].'/home/Payment';//页面跳转返回地址

            $pay_orderid = $order['orderno'];    //订单号
            $pay_amount = $order['money'];    //交易金额

            //$pay_amount = 0.03;//测试用
            require_once "../../plugins/PaySdk.php";
            $sdk = new PaySdk($this->config['third_mchid'], $this->config['third_key']);
            $ret = $sdk->submit($pay_orderid,$pay_amount,$this->config['third_channl'],$pay_notifyurl,$pay_callbackurl,'91联盟官方'.$charge['name']);
       
            if($ret['code'] == 200){
                $result['code']=0;
                $result['msg']=$ret['url'];
               
            }else{
                $result['code']=1000;
                $result['msg']=$ret['msg'];
               
            }
            return $result;
       

		if($paytype == 'alipay'){
			$res = $this->aliApiparam($data,$productid);
		} else{
			$res = $this->wxApiparam($data,$productid);
		}

			return $res;
	}


	protected function aliApiparam($data,$productid=1)
    {

		$config = $this->config;
		require_once "../../alipay/aop/AopClient.php";
        //require_once "../../alipay/aop/request/AlipayTradeAppPayRequest.php";
        require_once "../../alipay/aop/request/AlipayTradeWapPayRequest.php";
        

        $url = $config['site_url']. '/?service=Pay.alipay_notify';

        /**
         * 调用支付宝接口。
         */
        $aop = new AopClient();
        $time = time();//时间
       // $aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $aop->gatewayUrl = "https://openapi.alipaydev.com/gateway.do";//沙箱环境
        	

        $aop->appId = '2016101900726399';             //支付宝AppId
        $aop->rsaPrivateKey = file_get_contents('aliprivatekey.txt');  //应用私钥
        $aop->format = "json";
        $aop->charset = "UTF-8";
        $aop->signType = "RSA2";
        $aop->apiVersion = '1.0';
        $aop->timestamp =$time;
        $aop->alipayrsaPublicKey = file_get_contents('alipubkey.txt');//支付宝公钥

        //$request = new AlipayTradeAppPayRequest();
        $request = new AlipayTradeWapPayRequest();
        
        $arr['subject'] = '测试产品-购买金币';                     //商品名称
        $arr['out_trade_no'] = $data['orderid'];           //商品订单号
        $arr['timeout_express'] = '30m';   //该笔订单允许的最晚付款时间，逾期将关闭交易
                   
        $arr['total_amount'] = $data['money'];           //订单总金额，单位为元，精确到小数点后两位
        $arr['product_code'] = 'QUICK_MSECURITY_PAY';   //销售产品码，商家和支付宝签约的产品码

        $json = json_encode($arr);
        $request->setNotifyUrl($url);
        $request->setBizContent($json);

      $response = $aop->sdkExecute($request);
    
		if(!empty($response)){
			try{
	            $order=array(
					'touid' =>$data['uid'],
					'uid'=>$data['uid'],
					'money' => $data['money'],
					'coin' =>$data['coin'],
					'coin_give' =>$data['give'],
					'trade_no'=>'',
					'orderno'=>$data['orderid'],
					'status'=>0,		
					'addtime'=>$time,
					'type'=>2,
				);

	            DI()->notorm->users_charge->insert($order);
	        } catch (Exception $e){   
	            return 1000;
	        }
		
		} else {
			return false;
		} 
        return $response;
    }

	//微信支付
    protected function wxApiparam($data)
    {
    	$config = $this->config;
    	require_once "../../wxpay/lib/WxPay.Api.php"; 
		require_once "../../wxpay/pay/WxPay.NativePay.php";
        
		$url = $config['site_url']. '/?service=Pay.wxNotify';
        $time = time();//时间


		$appid = '02561eb41';//微信开放平台appID
		$attach = '支付测试';
		$body = 'APP支付测试';
		$mch_id = '1514';//微信支付商户号 MCHID
		$key = '88122';//微信支付密钥 APIKEY
		$nonce_str = $this->randomStr(20);//32位随机字符串（字母大写）
		$notify_url = $url;//回调通知地址
		$out_trade_no = $data['orderid'];//订单号
		$spbill_create_ip = $this->getUserIp();//客户IP
		$total_fee = $data['money']*100;
		$trade_type = 'MWEB';
	

        // 生成签名
        $sign = $this->makeSign($data, $key);

        // 请求API
        //$url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';//下单地址
        $url = 'https://api.mch.weixin.qq.com/sandboxnew/pay/unifiedorder';//沙箱测试环境地址
        $result = $this->postXmlCurl($this->toXml($data), $url);//11
        $prepay = $this->fromXml($result);//prepay_id 预支付交易会话标识

        // 请求失败
       
        
		if($prepay['return_code'] == 'SUCCESS' && $prepay['result_code'] == 'SUCCESS'){
			try{
	            $order=array(
					'touid' =>$data['uid'],
					'uid'=>$data['uid'],
					'money' => $data['money'],
					'coin' =>$data['coin'],
					'coin_give' =>$data['give'],
					'trade_no'=>'',
					'orderno'=>$data['orderid'],
					'status'=>0,		
					'addtime'=>$time,
					'type'=>1,
				);	
	            DI()->notorm->users_charge->insert($order);
	        } catch (Exception $e){   
	            return 1000;
	        }
		
		} else {
			return false;
		} 
        // 生成 nonce_str 供前端使用
        $paySign = $this->makePaySign($nonce_str, $prepay['prepay_id'], $time, $key);//11

        return [
            'prepay_id' => $prepay['prepay_id'],
            'nonceStr' => $nonce_str,
            'timeStamp' => (string)$time,
            'paySign' => $paySign
        ];

    }



 /**
     * 生成指定长度的随机字符串(包含大写英文字母, 小写英文字母, 数字)
     * @param $length int 需要生成的字符串的长度
     * @return string 包含 大小写英文字母 和 数字 的随机字符串
     */
    public function randomStr($length)
    {
        //生成一个包含 大写英文字母, 小写英文字母, 数字 的数组
        $arr = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
        $str = '';
        $arr_len = count($arr);
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $arr_len - 1);
            $str .= $arr[$rand];
        }
        return $str;
    }

      /**
     * 生成paySign
     * @param $nonceStr
     * @param $prepay_id
     * @param $timeStamp
     * @return string
     */
    private function makePaySign($nonceStr, $prepay_id, $timeStamp, $wx_api_key)
    {
        $data = [
            'appId' => '02561eb41',
            'nonceStr' => $nonceStr,
            'package' => 'prepay_id=' . $prepay_id,
            'signType' => 'MD5',
            'timeStamp' => $timeStamp,
        ];

        //签名步骤一：按字典序排序参数
        ksort($data);

        $string = $this->toUrlParams($data);

        //签名步骤二：在string后加入KEY
        $string = $string . '&key=' . $wx_api_key;

        //签名步骤三：MD5加密
        $string = md5($string);

        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);

        return $result;
    }

    /**
     * 生成签名
     * @param $values
     * @return string 本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    private function makeSign($values, $wx_api_key)
    {
        //签名步骤一：按字典序排序参数
        ksort($values);
        $string = $this->toUrlParams($values);
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=" . $wx_api_key;
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    /**
     * 将xml转为array
     * @param $xml
     * @return mixed
     */
    private function fromXml($xml)
    {
        // 禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    /**
     * 以post方式提交xml到对应的接口url
     * @param $xml
     * @param $url
     * @param int $second
     * @return mixed
     */
    private function postXmlCurl($xml, $url, $second = 30)
    {
        $ch = curl_init();
        // 设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);//严格校验
        // 设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        // 要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        // 运行curl
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }



    /**
     * 格式化参数格式化成url参数
     * @param $values
     * @return string
     */
    private function toUrlParams($values)
    {
        $buff = '';
        foreach ($values as $k => $v) {
            if ($k != 'sign' && $v != '' && !is_array($v)) {
                $buff .= $k . '=' . $v . '&';
            }
        }
        return trim($buff, '&');
    }

    /**
     * 输出xml字符
     * @param $values
     * @return bool|string
     */
    private function toXml($values)
    {
        if (!is_array($values)
            || count($values) <= 0
        ) {
            return false;
        }

        $xml = "<xml>";
        foreach ($values as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

	
}
