<?php

/**
 * 极光推送
 */
namespace Admin\Controller;
use Common\Controller\AdminbaseController;
use JMessage\JMessage;
use JMessage\IM\Admin;
use JMessage\IM\Message;
use JMessage\IM\User;

class PushMessageController extends AdminbaseController {

	/*推送发送*/
	function add(){

		$this->display();
	}

	function add_post(){
		$rs=array("code"=>0,"msg"=>"","info"=>array());

		$title=I("title");
		$synopsis=I("synopsis");
		$msg_type=I("msg_type");
		$content=I("content");
		$url=I("url");

		if($title==""){
			$rs['code']=1001;
			$rs['msg']="请填写标题";
			echo json_encode($rs);
			exit;
		}

		if($synopsis==""){
			$rs['code']=1001;
			$rs['msg']="请填写简介";
			echo json_encode($rs);
			exit;
		}

		if($msg_type==2&&$url==""){
			$rs['code']=1002;
			$rs['msg']="请填写链接地址";
			echo json_encode($rs);
			exit;
		}

		$id=$_SESSION['ADMIN_ID'];
		$user=M("Users")->where("id='{$id}'")->find();

		$result=M("admin_push")->add(array("title"=>$title,"synopsis"=>$synopsis,"type"=>$msg_type,"content"=>htmlspecialchars_decode($content),"url"=>$url,"admin"=>$user['user_login'],"addtime"=>time(),"ip"=>$_SERVER['REMOTE_ADDR']));

		if($result!==false){
			$rs['info']['id']=$result;
			$rs['info']['count']=M("users")->where("user_type=2 and user_status=1")->count();

			echo json_encode($rs);
			exit;
		}else{
			$rs['code']=1002;
			$rs['msg']="推送失败";
			echo json_encode($rs);

		}


	}

	/*推送记录*/
	public function index(){

		$map=array();
			
		if($_REQUEST['keyword']!=''){
			$map['title']=array("like","%".$_REQUEST['keyword']."%");
			$_GET['keyword']=$_REQUEST['keyword'];
		}
		
    	$push=M("admin_push");
    	$count=$push->where($map)->count();
    	$page = $this->page($count, 20);
    	$lists = $push
			->where($map)
			->order("addtime desc")
			->limit($page->firstRow . ',' . $page->listRows)
			->select();

		//var_dump($push->getLastSql());
		
		//获取私密配置信息
		/*$config=getConfigPri();
		$timestamp=time();
		$random_str="022cd9fd995849b58b3ef0e943421ed9";
		$signature = md5("appkey={$config['jpush_key']}&timestamp={timestamp}&random_str={$random_str}&key={$config['jpush_secret']}");
*/		
		//获取当前用户总数
		$count=M("users")->where("user_type=2 and user_status=1")->count();
		
    	$this->assign('lists', $lists);
    	$this->assign('formget', $_GET);
    	$this->assign("page", $page->show('Admin'));
    	$this->assign("count", $count);
    	
    	/*$this->assign("jpush_key",$config['jpush_key']);
    	$this->assign("random_str",$random_str);
    	$this->assign("signature",$signature);
    	$this->assign("timestamp",$timestamp);
*/
    	$this->display();
	}

	/*将原来的信息重新获取一份新加入数据库*/
	public function push_add(){

		$res=array("code"=>0,"msg"=>"","info"=>array());
		$id=I("id");
		if($id==""){
			$res['code']=1001;
			$res['msg']="数据传入失败";
			echo json_encode($res);
			exit;
		}

		//判断id信息是否存在
		$info=M("admin_push")->where("id={$id}")->find();
		if(!$info){
			$res['code']=1001;
			$res['msg']="数据传入失败";
			echo json_encode($res);
			exit;
		}

		unset($info['id']);
		$info['addtime']=time();
		$result=M("admin_push")->add($info);
		
		if($result==false){
			$res['code']=1001;
			$res['msg']="写入数据失败";
			echo json_encode($res);
			exit;
		}

		//获取当前用户的总数
		/*$count=M("users")->where("user_type=2 and user_status=1")->count();
		$res['info']=$count;*/

		echo json_encode($res);

	}


	public function push(){
		
		$res=array("code"=>0,"msg"=>"","info"=>array());
		$id=I("msgid");
		$lastid=I("lastid");
		$num=I("num");

		if($id==""){
			$res['code']=1001;
			$res['msg']="数据传入失败";
			echo json_encode($res);
			exit;
		}

		//判断id信息是否存在
		$info=M("admin_push")->where("id={$id}")->find();
		if(!$info){
			$res['code']=1001;
			$res['msg']="数据传入失败";
			echo json_encode($res);
			exit;
		}


		//获取后台配置的极光推送app_key和master_secret
		$configPri=getConfigPri();
		$appKey = trim($configPri['jpush_key']);
		$masterSecret =  trim($configPri['jpush_secret']);

		if($appKey&&$masterSecret){


			//极光IM
			vendor('jmessage.autoload');//导入极光IM类库		

			$jm = new JMessage($appKey, $masterSecret);


			//注册管理员
			$admin = new Admin($jm);
			$regInfo = [
			    'username' => 'dsp_admin_1',
			    'password' => 'dsp_admin_1',
			    'nickname'=>'视频官方'
			];

			$response = $admin->register($regInfo);


			//var_dump($response['body']);
			if($response['body']==""||$response['body']['error']['code']==899001){ //新管理员注册成功或管理员已经存在

				//发布消息
				$message = new Message($jm);

				$user = new User($jm);

				$before=userSendBefore(); //获取极光用户账号前缀

				$from = [
				    'id'   => 'dsp_admin_1', //短视频系统规定视频官方必须是该账号（与APP保持一致）
				    'type' => 'admin'
				];

				$msg = [
				   'text' => $info['title']
				];

				$notification =[
					'notifiable'=>false  //是否在通知栏展示
				];

				//查找系统所有用户
				$userlists=M("users")->query("select id from __PREFIX__users where user_type=2 and user_status=1 and id>{$lastid} order by id asc limit {$num}");

				

				foreach ($userlists as $k => $v) {
					/*$target=[];
					$userinfo=$user->show($v['id']); //获取用户信息

					if($userinfo['body']['error']['code']==899002){  //极光用户不存在
						continue;
					}*/

					$target = [
					    'id'   => $before.$v['id'],
					    'type' => 'single'
					];

					$response = $message->sendText(1, $from, $target, $msg,$notification,[]);  //最后一个参数代表其他选项数组，主要是配置消息是否离线存储，默认为true

					$lastid=$v["id"];
				}

				/*$target = [
				    'id'   => '12220',
				    'type' => 'single'
				];

				$response = $message->sendText(1, $from, $target, $msg,$notification,[]);  //最后一个参数代表其他选项数组，主要是配置消息是否离线存储，默认为true*/
				
				//file_put_contents("a.txt", $lastid."时间：".date("Y-m-d:H:i:s",time()).PHP_EOL,FILE_APPEND);//换行追加
				
				$res['msg']="消息推送成功";
				$res['info']=$lastid;

			}else{
				$res['code']=1001;
				$res['msg']="消息推送失败";
			}

			echo json_encode($res);
			exit;
				
		}else{

			$res['code']=1001;
			$res['msg']="消息推送失败";
			echo json_encode($res);
			exit;
		}

		
	}

	public function del(){
		$id=I("id");
		if($id==""){
			$this->error("数据传入失败");
			exit;
		}

		$result=M("admin_push")->where("id={$id}")->delete();
		if($result!==false){
			$this->success("删除成功");
		}else{
			$this->error("删除失败");
		}
	}

	

}
