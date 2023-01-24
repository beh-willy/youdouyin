<?php
/**
 * 会员举报
 */
namespace Appapi\Controller;
use Common\Controller\HomebaseController;
use QCloud\Cos\Api;
use QCloud\Cos\Auth;
class UserreportController extends HomebaseController {
	
	public function index(){
		$touid=I("touid");
		$uid=I('uid');
		$token=I("token");       
		if(checkToken($uid,$token)==700){
			$this->assign("reason",'您的登陆状态失效，请重新登陆！');
			$this->display(':error');
			exit;
		}

		//判断用户是否存在
		$touserinfo=M("users")->field("user_status,user_nicename,user_login")->where("id={$touid} and user_type=2")->find();

		if(!$touserinfo){
			$this->assign("reason",'举报用户不存在');
			$this->display(':error');
			exit;
		}

		//判断用户是否被拉黑
		if($touserinfo['user_status']==0){
			$this->assign("reason",'该用户已被禁用');
			$this->display(':error');
			exit;
		}

		//获取用户举报分类
		$classifies=M("users_report_classify")->order("orderno")->select();

		$this->assign("classifies",$classifies);

		$this->assign("uid",$uid);
		$this->assign("token",$token);
		$this->assign("touid",$touid);

		$time=time();
		$this->assign("time",$time);
		$this->display();
	    
	}


	public function upload(){
        

        //获取后台上传配置
		$configpri=getConfigPri();

		if($configpri['cloudtype']==1){  //七牛云存储

			$app='userreport';

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

			$src = $_FILES["image"]["tmp_name"]; //image对应reportcon.js里的input的name值
			
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

        $classify=checkNull(I('classify'));

        $data['uid']=checkNull(I('uid'));
		$data['touid']=checkNull(I('touid'));
		$data['content']=checkNull(I('content'));
		$data['thumb']=checkNull(I('thumb'));
		$data['addtime']=time();
       // $data['contact_msg']=checkNull(I('contactMsg'));


        if($data['uid']==$data['touid']){
        	$rs['code']=1001;
            $rs['msg']='自己不能举报自己';
			echo json_encode($rs);
			exit;
        }

        if($classify==''){
        	$rs['code']=1001;
            $rs['msg']='请选择举报类型';
			echo json_encode($rs);
			exit;
        }
        //判断举报类型是否存在
        $classify_info=M("users_report_classify")->where("id={$classify}")->find();

        if(!$classify_info){
        	$rs['code']=1001;
            $rs['msg']='举报类型不存在';
			echo json_encode($rs);
			exit;
        }

        //判断被举报用户是否存在，是否被禁用
        $touserinfo=M("users")->field("user_status,user_nicename,user_login")->where("id={$data['touid']} and user_type=2")->find();

		if(!$touserinfo){
			
			$rs['code']=1001;
            $rs['msg']='举报用户不存在';
			echo json_encode($rs);
			exit;
		}

		//判断用户是否被拉黑
		if($touserinfo['user_status']==0){
			$rs['code']=1001;
            $rs['msg']='该用户已被禁用';
			echo json_encode($rs);
			exit;
		}

        $data['classify']=$classify_info['title'];

        if($data['content']==''){
            $rs['code']=1002;
            $rs['msg']='请输入反馈内容';
			echo json_encode($rs);
			exit;
        }


		$result=M("users_report")->add($data);
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