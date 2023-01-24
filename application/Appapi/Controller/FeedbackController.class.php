<?php
/**
 * 我要反馈
 */
namespace Appapi\Controller;
use Common\Controller\HomebaseController;
use QCloud\Cos\Api;
use QCloud\Cos\Auth;
class FeedbackController extends HomebaseController {

	function index(){       
        
        $uid=checkNull(I('uid'));
        $token=checkNull(I('token'));
        $version=checkNull(I('version'));
        $model=checkNull(I('model'));
        
        if(checkToken($uid,$token)==700){
			$this->assign("reason",'您的登陆状态失效，请重新登陆！');
			$this->display(':error');
			exit;
		} 
        
        $time=time();
        
        $this->assign("uid",$uid);
        $this->assign("token",$token);
        $this->assign("version",$version);
        $this->assign("model",$model);
        $this->assign("time",$time);
		$this->display();
	    
	}
    
	public function upload(){
        

        //获取后台上传配置
		$configpri=getConfigPri();

		if($configpri['cloudtype']==1){  //七牛云存储

			$app='feedback';

        	$savepath=$app.'/'.date('Ymd').'/';
        	//上传处理类
            $config=array(
        		'rootPath' => './'.C("UPLOADPATH"),
        		'savePath' => $savepath,
        		'maxSize' => 100*1048576, //100M
        		'saveName'   =>    array('uniqid',''),
        		'exts'       =>    array('jpg','png','gif','jpeg'),
        		'autoSub'    =>    false,
            );

            $config_qiniu = array(

				'accessKey' => $configpri['qiniu_accesskey'], //这里填七牛AK
				'secretKey' => $configpri['qiniu_secretkey'], //这里填七牛SK
				'domain' => $configpri['qiniu_domain'],//这里是域名
				'bucket' => $configpri['qiniu_bucket']//这里是七牛中的“空间”
			);

			$upload = new \Think\Upload($config,'Qiniu',$config_qiniu);

			$info = $upload->upload();


			if ($info) {
                //上传成功
                //写入附件数据库信息
                $first=array_shift($info);
                if(!empty($first['url'])){
                	$url=$first['url'];
                	
                }else{
                	$url=C("TMPL_PARSE_STRING.__UPLOAD__").$savepath.$first['savename'];
                	
                }

                echo json_encode(array("ret"=>200,'data'=>array("url"=>$url),'msg'=>''));              

            } else {

                echo json_encode(array("ret"=>0,'data'=>array(),'msg'=>$upload->getError()));
            }

		}else if($configpri['cloudtype']==2){ //腾讯云存储

			/* 腾讯云 */
			require(SITE_PATH.'api/public/txcloud/include.php');
			//bucketname
			$bucket = $configpri['txcloud_bucket'];

			$src = $_FILES["image"]["tmp_name"];
			
			//var_dump("src".$src);

			//cosfolderpath
			$folder = '/'.$configpri['tximgfolder'];
			//cospath
			$dst = $folder.'/'.$_FILES["image"]["name"];
			//config your information
			$config = array(
				'app_id' => $configpri['txcloud_appid'],
				'secret_id' => $configpri['txcloud_secret_id'],
				'secret_key' => $configpri['txcloud_secret_key'],
				'region' => $configpri['txcloud_region'],   // bucket所属地域：华北 'tj' 华东 'sh' 华南 'gz'
				'timeout' => 60
			);

			date_default_timezone_set('PRC');
			$cosApi = new 	Api($config);

			$ret = $cosApi->upload($bucket, $src, $dst);

			

			if($ret['code']!=0){
				echo json_encode(array("ret"=>0,'data'=>array(),'msg'=>'上传失败'));
			}

			$url = $ret['data']['source_url'];

			echo json_encode(array("ret"=>200,'data'=>array("url"=>$url),'msg'=>''));

		}

        exit;
	}	
    
	public function save(){
        
        $rs=array('code'=>0,'msg'=>'提交成功','info'=>array());
        
		$uid=checkNull(I('uid'));
		$token=checkNull(I('token'));
        
        if(checkToken($uid,$token)==700){
            $rs['code']=1001;
            $rs['msg']='您的登陆状态失效，请重新登陆！';
			echo json_encode($rs);
			exit;
		} 
        
        $data['uid']=checkNull(I('uid'));
		$data['version']=checkNull(I('version'));
		$data['model']=checkNull(I('model'));
		$data['content']=checkNull(I('content'));
		$data['thumb']=checkNull(I('thumb'));
		$data['addtime']=time();
        $data['contact_msg']=checkNull(I('contactMsg'));

        if($data['content']==''){
            $rs['code']=1002;
            $rs['msg']='请输入反馈内容';
			echo json_encode($rs);
			exit;
        }

		$result=M("feedback")->add($data);
		if($result){
            echo json_encode($rs);
			exit;
		}else{
            $rs['code']=1002;
            $rs['msg']='提交失败,请重试';
            echo json_encode($rs);
			exit;
		}
	
	}

}