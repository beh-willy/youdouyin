<?php


class PaySdk
{
    private $mch_id;
    private $key;
    private $url = "http://api.ruixinec.com/index";

    public function __construct($mch_id, $key)
    {
        $this->mch_id = $mch_id;
        $this->key = $key;
    }

    public function notify($data)
    {
        if (isset($data['sign'])) {

            if ($data['sign'] === $this->getSign($data)) {
                return $data;
            }
        }
        return false;
    }

    public function query($trade_no)
    {
        $params = [
            'id' => $this->mch_id,
            'trade_no' => $trade_no,
            'sign_type'=>'MD5'
        ];
        $params['sign'] = $this->getSign($params);

        return $this->request($this->url . '/getorder', $params);
    }

    public function submit($trade_no, $money, $type, $notify_url,$return_url ='',$name = '')
    {
        $params = [
            'appid' => $this->mch_id,
            'out_trade_no' => $trade_no,
            'amount' => $money,
            'pay_type' => $type,
            'callback_url' => $notify_url,
            'success_url'=>$return_url,
            'error_url'=>$return_url,
            'out_uid'=>$name,
            'version'=>'v1.0'
        ];

        $params['sign'] = $this->getSign($params);
       
        return $this->request($this->url . '/unifiedorder?format=json', $params);
    }

    private static function arr2str($param)
    {
        $str = "";
        foreach ($param as $k => $v) {
            $str .= "&{$k}=" . urlencode($v);
        }
        return trim($str, '&');
    }

    private function getSign($data)
    {
    	unset($data['sign']);
    	$data = array_filter($data);
        ksort($data);
        $string_a = http_build_query($data);
        $string_a = urldecode($string_a);
        $string_sign_temp = $string_a . "&key=" . $this->key;
        $sign = md5($string_sign_temp);
        $result = strtoupper($sign);
        return $result;
    }

    private function request($url, $params = [])
    {
        list($body, $err) = $this->getCurl($url, $params);
        // print_r($body);
        // exit;
        if ($body) {
            if ($ret = json_decode($body, true)) {
            
                return $ret;
            } else {
                return [false, '解析JSON数据失败'];
            }
        } else {
            return [false, $err];
        }
    }


    private function getCurl($url, $post = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if ($post) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $ret = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
       
        if ($err) {
            return [false, $err];
        } else {
            return [$ret, null];
        }
    }

}