<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------

namespace Appapi\Controller;
use Common\Controller\HomebaseController; 
use QCloud\Cos\Api;
use QCloud\Cos\Auth;

class TestController extends HomebaseController {
	

	function index() 
	{
		/* $changeid = "1";//$_POST['changeid']; */
		$changeid = intval(I('changeid'));
		$uid = I('uid');
		
		$this->assign("uid",$uid);
		$this->assign("changeid",$changeid);
    	$this->display();
    }
	//对象存储
	public function fileupload(){
		file_put_contents('./load.txt',date('y-m-d h:i:s').'提交参数信息 src:'.json_encode($_FILES)."\r\n",FILE_APPEND);
	/* 	if ($_FILES["file"]["error"] > 0)
		  {
		  echo "Error: " . $_FILES["file"]["error"] . "<br />";
		  }
		else
		  {
		  echo "Upload: " . $_FILES["file"]["name"] . "<br />";
		  echo "Type: " . $_FILES["file"]["type"] . "<br />";
		  echo "Path: " . $_FILES["file"]["path"] . "<br />";
		  echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
		  echo "Stored in: " . $_FILES["file"]["tmp_name"];
		  } */
		
		require(SITE_PATH.'api/public/txcloud/include.php');
		/* require(SITE_PATH.'api/public/txcloud/src/qcloud/cos/Api.php'); */

		
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
		$cosApi = new 	Api($config);

		// Create folder in bucket.
/* 		$ret = $cosApi->createFolder($bucket, $folder);
		var_dump($ret); */

		// Upload file into bucket.
		$ret = $cosApi->upload($bucket, $src, $dst);
	//获取签名
		$auth = new Auth($config['app_id'], $config['secret_id'], $config['secret_key']);
		$signature = $auth->createNonreusableSignature($bucket, $dst);
		var_dump($signature);
	}
	
	public function uploadfile(){
		// 配置项 start
		$appid = '';
		$bucket_name = '';
		$dir_name = '';
		$secretID = '';
		$secretKey = '';
		// 配置项 end

		// 需要存储的资源url, 这里用百度logo来做演示
		$pic_url = 'http://www.baidu.com/img/logo.gif';
		// 获取文件名
		$filename = end(explode('/', $pic_url));
		// 构造上传url
		$upload_url = "web.file.myqcloud.com/files/v1/$appid/$bucket_name/$dir_name/$filename";
		// 设置过期时间
		$exp = time() + 3600;
		// 构造鉴权key
		$sign = "a=$appid&b=$bucket_name&k=$secretID&e=$exp&t=" . time() . '&r=' . rand() . "&f=/$appid/$bucket_name/$dir_name/$filename";
		$sign = base64_encode(hash_hmac('SHA1', $sign, $secretKey, true) . $sign);
		// 构造post数据
		$post_data = [
			'op' => 'upload',
			'filecontent' => file_get_contents($pic_url),  // baidu logo
		];
		// 设置post的headers, 加入鉴权key
		$header = [
			'Content-Type: multipart/form-data',
			'Authorization: ' . $sign,
		];
		// post
		$ch = curl_init($upload_url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		$res = curl_exec($ch);
		curl_close($ch);
		$res = json_decode($res, true);
		if (isset($res['data']['access_url'])) {
			// 成功, 输出文件url
			echo $res['data']['access_url'];
		} else {
			// 失败
			echo $res;
		}
	}
	
	
	
	 public function upload() {
        import('ORG.Net.UploadFile');
        $upload = new UploadFile();// 实例化上传类
        $upload->maxSize  = 10485760 ;// 设置附件上传大小
        $upload->allowExts  = array('mp3');// 设置附件上传类型
        $upload->savePath =  './Uploads/'.Date('Ym').'/';// 设置附件上传目录
        $upload->saveRule= time(); //文件名
        if(!$upload->upload()) {// 上传错误提示错误信息
            $this->error($upload->getErrorMsg());
        }else{
            $info = $upload->getUploadFileInfo();
             $this->cos_upload($info); //上传成功了,我们上传到cos
        }
    }
        //上传到cos 这里我写好了目录建立代码咯,不要认为我发的代码跟sdk一样,啥都没写 想用的直接拿过去就可以用了
    public function cos_upload($info) {
        $srcPath=$info[0]['savepath'].$info[0]['savename']; 
        $hash=$info[0]['hash']; //稀哈值
        
        $bucketName = "aosika"; //Bucket名称
        $dar=Date('Ym'); //以年月为目录
        //查询目录 如果无目录则创建目录
        $path = "/$dar/";
        $ispath=Cosapi::statFolder($bucketName, $path);
        if($ispath['code']!='0'){
            Cosapi::createFolder($bucketName, $path);//没有目录我就先创建
        }
        $dstPath = $path.time().".mp3"; //cos存储的路径,包括文件名与后缀,后缀自己想办法定义你上传的格式,我这只能上传mp3,所以我直接写上去了
        $arr = Cosapi::upload($srcPath,$bucketName,$dstPath);
        if($arr['code']=='0'){//如果上传成功了
            $name = preg_replace('/.mp3/','',$_FILES['file']['name']);
            $data['name']=$name; //音乐名称
                $data['host']='http://data.mp3.flash127.com'; //存储主机 附件在哪个主机上
            $data['url'] =$arr['data']['resource_path']; //cos存储路径
            $add=M('Music')->add($data);
            if($add){
                unlink($srcPath);//上传成功了,我把本地文件删除,当然你也可以保留
                 $this->success('上传成功！', U('play/'.($add)) );
            }
        }else{
            
            exit('上传失败,'.$arr['message']);
            //这里自己定义操作,如果cos上传失败,你本地还有,你可以直接放入数据库,或者以后再同步也可以,
        }
    }
	
	
}