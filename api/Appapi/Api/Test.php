<?php

class Api_Test extends Api_Common {

	public function getRules() {
		return array(
			'jssend' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '会员ID'),
				'videoid' => array('name' => 'videoid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '会员ID'),
				'type' => array('name' => 'type', 'type' => 'int', 'desc' => '会员ID'),
                'token' => array('name' => 'token', 'desc' => '会员token'),
            ),
            'getRecommendVideos'=>array(
            	'uid' => array('name' => 'uid', 'type' => 'int',  'desc' => '用户ID'),
            	'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1, 'desc' => '页数'),
            ),
            'reset_free_count'=>array(
            	'key' => array('name' => 'key', 'require' => true,'desc' => '访问密钥')
            )
            
		);
	}
	
	/* 获取订单号 */
	public function upload(){

		file_put_contents('./load.txt',date('y-m-d h:i:s').'提交参数信息 src:'.json_encode($_FILES['file'])."\r\n",FILE_APPEND);
		require(API_ROOT.'/public/txcloud/include.php');

		//bucketname
		$bucket = 'aosika';
		//uploadlocalpath
		/* $src = $_FILES['file'];//'./hello.txt'; */
		$src = $_FILES["file"]["tmp_name"];//'./hello.txt';
		
		//cospath
		$dst = '/test1/'.$_FILES["file"]["name"];
	
		//cosfolderpath
		$folder = '/test1';
		//config your information
		$config = array(
			'app_id' => '1255500835',
			'secret_id' => 'AKIDbBcrfKT7EE3gBUQqjPxKWWJvPxPk3thI',
			'secret_key' => 'XvCLJ7j8NSN6f7QcfXZR7g2C9tRCm5pQ',
			'region' => 'sh',   // bucket所属地域：华北 'tj' 华东 'sh' 华南 'gz'
			'timeout' => 60
		);
		
		

		date_default_timezone_set('PRC');
		
		$cosApi = new 	\QCloud\Cos\Api($config);

		// Create folder in bucket.
/* 		$ret = $cosApi->createFolder($bucket, $folder);
		var_dump($ret); */

		// Upload file into bucket.
		$ret = $cosApi->upload($bucket, $src, $dst);
		
		var_dump($ret);
	
		$auth = new \QCloud\Cos\Auth($config['app_id'], $config['secret_id'], $config['secret_key']);
		$signature = $auth->createNonreusableSignature($bucket, $dst);
		var_dump($signature);
		exit;
		
	}

	public function jssend(){
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$domain = new Domain_Test();
		$info=$domain->jssend($this->uid,43,0);
		
		$rs['info']=$info;
		return $rs;
	}
	
	public function reset_free_count(){
		
		if (!class_exists('PhalApi_Tool')) {
    		require dirname(__FILE__) . '/../PhalApi/Tool.php';
		}
		$ip = PhalApi_Tool::getClientIp();
		
		
		if ($ip != "103.254.72.208") {
			echo '非本机访问';exit;
		}
		
		if ($this->key != 'jjj123456') {
			echo '访问错误';exit;
		}

		$configpri = $this->getConfigPri();
		$configpri['free_count'] = $configpri['free_count'] ?$configpri['free_count']:10;
		$res = DI()->notorm->users->update( array('free_count' => $configpri['free_count']) );
		if ($res) {
			echo 'success';exit;
		}
		echo 'false';exit;
	}

    
	public function getRecommendVideos(){
        $uid=$this->uid;
        $p=$this->p;
		$pnums=100;
		$start=($p-1)*$pnums;


		//获取私密配置里的评论权重和点赞权重
		$configPri=$this->getConfigPri();

		$comment_weight=$configPri['comment_weight'];
		$like_weight=$configPri['like_weight'];
		$share_weight=$configPri['share_weight'];

		$prefix= DI()->config->get('dbs.tables.__default__.prefix');

		
		$info=DI()->notorm->users_video->queryAll("select id,uid,comments,likes,shares,show_val,watch_ok,views,(ceil(comments * ".$comment_weight." + likes * ".$like_weight." + shares * ".$share_weight.") + show_val) * if(format(watch_ok/views,2) >1,'1',format(watch_ok/views,2)) as recomend from ".$prefix."users_video where isdel=0 and status=1 group by uid order by recomend desc,addtime desc limit ".$start.",".$pnums);
        
        
        $uids=array_column($info,'uid');
        
        file_put_contents('./test.txt',date('Y-m-d H:i:s').' 提交参数信息 uids:'.json_encode($uids)."\r\n",FILE_APPEND);
        //echo json_encode($uids);
        //echo '<br>';
        echo json_encode(array_count_values($uids));


        exit;
		if(!$info){
			return 1001;
		}



		return $info;
	}
    public function test(){
        $todayBeginDate=strtotime(date('Y-m-d 00:00:00'));//今天开始时间
		$todayEndDate=strtotime(date('Y-m-d 23:59:59'));//今天开始时间
        $ip=1685348840;
        $count3=DI()->notorm->praise_list2->where("ip=? and addtime > ? and addtime < ?",$ip,$todayBeginDate,$todayEndDate)->fetchAll();
        
        return $count3;
    }
    
    public function test2(){
        $ip=$_SERVER["HTTP_X_FORWARDED_FOR"];
        if(!$ip){
            $ip=$_SERVER["REMOTE_ADDR"];
        }
        echo $ip;
        echo '<br>';
        $ip= ip2long($ip) ; 
        echo $ip;

        exit;
        
    }
}
