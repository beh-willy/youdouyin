<?php
/**
 * 家族
 */
namespace Appapi\Controller;
use Common\Controller\HomebaseController;
class FamilyController extends HomebaseController {
	/* 家族驻地 */
	function home()
	{
		$uid=I('uid');
		$token=I('token');
		$reset=I('reset');
	
		if(checkToken($uid,$token)==700){
			$this->assign("reason",'您的登陆状态失效，请重新登陆！');
			$this->display(':error');
			exit;
		} 
		
		$this->assign("uid",$uid);
		$this->assign("token",$token);
		
		if($reset==1){
			$this->display('home_user'); 
			exit;
		}
		$type=0;
		$Family=M("family");
		
		$familyinfo=$Family->where("uid=".$uid)->find();
		if($familyinfo){
			$this->assign("familyinfo",$familyinfo);
			
			if($familyinfo['state']==0){
				$this->display("apply_wait"); 
				exit;
			}else if($familyinfo['state']==1){
				if($familyinfo['istip']==1){
					$Family->where("uid=".$uid)->setField("istip",0);
					$this->display("apply_no"); 
				}else{
                    $this->display('home_user'); 
                }
				exit;
			}else if($familyinfo['state']==2){
				if($familyinfo['istip']==1){
					$Family->where("uid=".$uid)->setField("istip",0);
					$this->display("apply_ok"); 
					exit; 
				}
				$type=1;
			}else if($familyinfo['state']==3){
				/* 家族解散 */
				$Family->where("uid=".$uid)->delete();
				$this->display("sign_no2"); 
				exit; 
			}
		}
		
		$user_family=M("users_family");
		$userfam=$user_family->where("uid={$uid}")->find();
		if($userfam)
		{
			$familyinfo=$Family->where("id={$userfam['familyid']}")->find();
			$this->assign("familyinfo",$familyinfo);
			$this->assign("userfam",$userfam);
			if($userfam['state']==0){
				$this->display("attended_wait"); 
				exit;
			}else if($userfam['state']==1){
				if($userfam['istip']==1){
                    $this->display("attended_no"); 
                }else{
                    $this->display('home_user'); 
                }
				exit;
			}else if($userfam['state']==2){
				if($userfam['istip']==1){
					$user_family->where("uid=".$uid)->setField("istip",0);
					$this->display("attended_ok"); 
					exit;
				}
				if($userfam['signout_istip']==1  ){
					$user_family->where("uid=".$uid)->setField("signout_istip",0);
					$this->display("sign_no"); 
					exit;
				}
			}else if($userfam['state']==3){
				if($userfam['signout_istip']==1  ){
					/* 解除签约通过 */
					$user_family->where("uid=".$uid)->delete();
					$this->display("sign_ok"); 
					exit;
				}else if($userfam['signout_istip']==2 ){
					/* 家族解散 */
					$user_family->where("uid=".$uid)->delete();
					$this->display("sign_no2"); 
					exit;
				}else if($userfam['signout_istip']==3 ){
					/* 踢出 */
					$user_family->where("uid=".$uid)->delete();
					$this->display("sign_no3"); 
					exit;
				}
			}
			$divide_family=$familyinfo['divide_family'];
			$Users=M("users");
			$userinfo=$Users->field("divide_family")->where("id={$uid}")->find();
			if($userinfo['divide_family']!='-1'){
				$divide_family=$userinfo['divide_family'];
			}
			
			
		}
		
		if($familyinfo){
			
			$familyinfo['userinfo']=getUserInfo($familyinfo['uid']);
			$this->assign("familyinfo",$familyinfo);
			$this->assign("type",$type);
			$this->assign("divide_family",$divide_family);
			$this->display(); 
			exit;
		}

		$this->display("home_user"); 

	}
	/* 设置家族默认分成 */
	public function setdivide()
	{
		$familyid=I('familyid');
		$uid=I('uid');
		$token=I('token');
		$divide=I('divide');
		$rs=array('code'=>0,'info'=>array(),'msg'=>'');

		if(checkToken($uid,$token)==700){
			$rs['code']=700;
			$rs['msg']='您的登陆状态失效，请重新登陆！';
			echo json_encode($rs);
			exit;
		} 
		$Family=M('family');
		$isexist=$Family->where("id={$familyid} and uid={$uid}")->find();
		if(!$isexist){

			$rs['code']=1001;
			$rs['msg']='你不是该家族长，无权操作';
			echo json_encode($rs);
			exit;
		}

		$data=array(
			'divide_family'=>checkNull($divide),
		);

		$family=$Family->where("id={$familyid}")->save($data);

		if($family!==false)
		{
			$rs['msg']='操作成功';
			echo json_encode($rs);
			exit;
		}
		else
		{
			$rs['code']=1002;
			$rs['msg']='操作失败';
			echo json_encode($rs);
			exit;
		}
	} 	
	/* 家族简介 */
	function setdes(){
		$uid=I('uid');
		$token=I('token');
		$familyid=I('familyid');
	
		if(checkToken($uid,$token)==700){
			$this->assign("reason",'您的登陆状态失效，请重新登陆！');
			$this->display(':error');
			exit;
		} 
		$type=0;
		$Family=M('family');
		$familyinfo=$Family->where("id={$familyid}")->find();
		if(!$familyinfo){
			$this->assign("reason",'家族不存在');
			$this->display(':error');
			exit;
		}
		
		if($familyinfo['uid']==$uid){
			$type=1;
		}
		
		$this->assign("uid",$uid);
		$this->assign("token",$token);
		$this->assign("type",$type);
		$this->assign("familyinfo",$familyinfo);
		$this->display();  
		
	}
	/* 设置家族简介 */
	function setdes_post()
	{
		$familyid=I('familyid');
		$uid=I('uid');
		$token=I('token');
		$des=I('des');
		$rs=array('code'=>0,'info'=>array(),'msg'=>'');

		if(checkToken($uid,$token)==700){
			$rs['code']=700;
			$rs['msg']='您的登陆状态失效，请重新登陆！';
			echo json_encode($rs);
			exit;
		} 
		$Family=M('family');
		$isexist=$Family->where("id={$familyid} and uid={$uid}")->find();
		if(!$isexist){

			$rs['code']=1001;
			$rs['msg']='你不是该家族长，无权操作';
			echo json_encode($rs);
			exit;
		}

		$data=array(
			'briefing'=>checkNull($des),
		);

		$family=$Family->where("id={$familyid}")->save($data);

		if($family!==false)
		{
			$rs['msg']='修改成功';
			echo json_encode($rs);
			exit;
		}
		else
		{
			$rs['code']=1002;
			$rs['msg']='修改失败';
			echo json_encode($rs);
			exit;
		}
	} 	
	/* 家族申请页面 */
	function apply(){
		$uid=I('uid');
		$token=I('token');
	
		if(checkToken($uid,$token)==700){
			$this->assign("reason",'您的登陆状态失效，请重新登陆！');
			$this->display(':error');
			exit;
		} 
		
		$this->assign("uid",$uid);
		$this->assign("token",$token);
		$this->display();  
	}
	//name 家族名称 申请人ID apply_side身份证反面 apply_pos身份证正面
	//apply_big 家族大图 apply_map家族小图 briefing家族简介 fullname真实姓名
	/* 家族申请提交 */
	public function add()
	{
		$name=$_REQUEST['name'];
		$uid=$_REQUEST['uid'];
		$apply_side=$_REQUEST['apply_side'];
		$apply_pos=$_REQUEST['apply_pos'];
		$apply_map=$_REQUEST['apply_map'];
		$briefing=$_REQUEST['briefing'];
		$carded=$_REQUEST['carded'];
		$fullname=$_REQUEST['fullname'];
		$token=$_REQUEST['token'];
		
		if(checkToken($uid,$token)==700){
			echo '{"state":"0","token":"'.$token.'","uid":"'.$uid.'","msg":"您的登陆状态失效，请重新登陆！"}';
			exit;
		} 
		$data=array(
			'uid'=>$uid,
			'name'=>checkNull($name),
			'badge'=>$apply_map,
			'apply_pos'=>$apply_pos,
			'apply_side'=>$apply_side,
			'briefing'=>checkNull($briefing),
			'carded'=>$carded,
			'fullname'=>checkNull($fullname),
			'addtime'=>time(),
			'state'=>'0',
			'reason'=>'',
		);
		$user_family=M('family')->where("uid=".$uid)->find();
		if($user_family)
		{
			if($user_family['state']==0){
				echo '{"state":"0","token":"'.$token.'","uid":"'.$uid.'","msg":"您提交的家族申请正在审核中..."}';
				exit;
			}
			
			$family=M('family')->where("id=".$user_family['id'])->save($data);
			
		}
		else
		{
			$family=M('family')->add($data);
		}
		
		$fam=M("users_family")->where("uid=".$uid)->find();
		if($fam)
		{
			M("users_family")->where("uid=".$uid)->delete();
		}
		echo '{"state":"1","name":"'.$name.'","uid":"'.$uid.'","token":"'.$token.'"}';
		exit;
	}
		
	function upload()
	{
		$savepath='family/';
		$config=array(
			'replace' => true,
			'rootPath' => './'.C("UPLOADPATH"),
			'savePath' => $savepath,
			'maxSize' => 0,//500K
			'saveName'   =>  array('uniqid',''),
			'exts'       =>    array('jpg', 'png', 'jpeg'),
			'autoSub'    =>    false,
		);
		$upload = new \Think\Upload($config);
		$info=$upload->upload();

		//开始上传
		if ($info) 
			{
				//上传成功
				//写入附件数据库信息
				$first=array_shift($info);
				if(!empty($first['url']))
				{
					$url=$first['url'];
				}
				else
				{
					$url=C("TMPL_PARSE_STRING.__UPLOAD__").$savepath.$first['savename'];
				}
			
				 echo json_encode(array("ret"=>200,'data'=>array("url"=>$url),'msg'=>$saveName));
		} 
		else 
		{
			echo json_encode(array("ret"=>0,'file'=>'','msg'=>$upload->getError()));
		}	
		exit;

	}
	
	/* 撤销申请 */
	function revoke(){
		$uid=I("uid");
		$token=I('token');
		$rs=array('code'=>0,'info'=>array(),'msg'=>'撤销成功');
		
		if(checkToken($uid,$token)==700){
			$rs['code']=700;
			$rs['msg']='您的登陆状态失效，请重新登陆！';
			echo json_encode($rs);
			exit;
		} 
		
		$Family=M("family");
		$familyinfo=$Family->where("uid={$uid}")->find();
		if($familyinfo){
			if($familyinfo['state']==1){
				//$rs['code']=1001;
				//$rs['msg']='家族申请已审核通过，不能撤销';
				//echo json_encode($rs);
				//exit;
			}else if($familyinfo['state']==2){
				$rs['code']=1002;
				$rs['msg']='家族申请已审核通过，不能撤销';
				echo json_encode($rs);
				exit;
			}
		}
		$result=$Family->where("uid={$uid}")->delete();
		$rs['info']['uid']=$uid;
		$rs['info']['token']=$token;
		echo json_encode($rs);
		exit;
		
	}
	/* 家族中心 */
	public function index2()
	{
		$uid=I("uid");
		$token=I('token');
		
		$this->assign("uid",$uid);
		$this->assign("token",$token);
		
		$user_family=M("users_family");
		
		$userfam=$user_family->where("uid={$uid}")->find();
			
		$list=M('family')->where("disable=0 and state=2")->order("rand()")->limit(0,20)->select();
		foreach($list as $k=>$v){
				$count=$user_family->where("familyid=".$v['id']." and state=2")->count();
				$list[$k]['count']=$count;
				$isstatus=-1;
				if($userfam['familyid']==$v['id']){
					$isstatus=$userfam['state'];
				}
				$list[$k]['isstatus']=$isstatus;
		}
		$this->assign('list', $list);
		$this->display();  
	}
	
	public function attended_reload()
	{
		$rs=array('code'=>0,'info'=>array(),'msg'=>'');
		
		$uid=I("uid");
		$token=I('token');
		$key=I('key');
		
		$map=array();
		$user_family=M("users_family");
		
		$userfam=$user_family->where("uid={$uid}")->find();
			
		$list=M('family')->where("disable=0 and state=2")->order("rand()")->limit(0,20)->select();
		foreach($list as $k=>$v){
				$count=$user_family->where("familyid=".$v['id']." and state=2")->count();
				$list[$k]['count']=$count;
				$isstatus=-1;
				if($userfam['familyid']==$v['id']){
					$isstatus=$userfam['state'];
				}
				$list[$k]['isstatus']=$isstatus;
		}
		$rs['info']=$list;
		echo json_encode($rs);
		exit;
	}
	
	public function attended_search()
	{
		$uid=I("uid");
		$key=I('key');
		$rs=array('code'=>0,'info'=>array(),'msg'=>'');
		if($key==''){
			$rs['code']=1001;
			$rs['msg']='请输入签约家族ID';
			echo json_encode($rs);
			exit;
		}
		$map=array();
		$map['disable']=0;
		$map['state']=2;
		$map['id|name']=array('like',"%{$key}%");
		$Family=M("family");
		$user_family=M("users_family");
		
		$userfam=$user_family->where("uid={$uid}")->find();
			
		$list=$Family->where($map)->select();
		foreach($list as $k=>$v){
				$count=$user_family->where("familyid={$v['id']} and state=2")->count();
				$list[$k]['count']=$count;
				$isstatus=-1;
				if($userfam['familyid']==$v['id']){
					$isstatus=$userfam['state'];
				}
				$list[$k]['isstatus']=$isstatus;
		}
		$rs['info']=$list;
		echo json_encode($rs);
		exit;
	}
	/* 家族详情 */
	function detail(){
		$uid=I("uid");
		$token=I('token');
		$familyid=I('familyid');
		
		$this->assign("uid",$uid);
		$this->assign("token",$token);
		
		$User_family=M("users_family");
		
		$userfam=$User_family->where("uid={$uid}")->find();
		$Users=M('users');
		$Family=M('family');
		$familyinfo=$Family->where("disable=0 and state=2 and id={$familyid}")->find();
		
		$familyinfo['userinfo']=$Users->field("id,user_nicename,avatar")->where("id={$familyinfo['uid']}")->find();
		$familyinfo['count']=$User_family->where("familyid={$familyid} and state=2")->count();
		
		$isstatus=-1;
		if($userfam['familyid']==$familyinfo['id']){
			$isstatus=$userfam['state'];
		}
		$familyinfo['isstatus']=$isstatus;
		
		$list=$User_family->where("familyid={$familyid} and state=2")->order("addtime desc")->limit(0,50)->select();
		foreach($list as $k=>$v){
			$userinfo=$Users->field("id,user_nicename,avatar")->where("id={$v['uid']}")->find();
			$userinfo['fansnum']=getFansnums($v['uid']);
			$list[$k]['userinfo']=$userinfo;
		}

		$this->assign('familyinfo', $familyinfo);
		$this->assign('list', $list);
		$this->display();  
	}
	
	function detail_more(){
		$familyid=I('familyid');
		$p=I('page');
		$pnums=50;
		$start=($p-1)*$pnums;

		$list=$User_family->where("familyid={$familyid} and state=2")->order("addtime desc")->limit($start,$pnums)->select();
		foreach($list as $k=>$v){
			$userinfo=$Users->field("id,user_nicename,avatar")->where("id={$v['uid']}")->find();
			$userinfo['fansnum']=getFansnums($v['uid']);
			$list[$k]['userinfo']=$userinfo;
		}
		
		$nums=count($list);
		if($nums<$pnums){
			$isscroll=0;
		}else{
			$isscroll=1;
		}

		$result=array(
			'data'=>$list,
			'nums'=>$nums,
			'isscroll'=>$isscroll,
		);
	 
		echo json_encode($result);
		exit;		
		
	}
	/* 签约家族 */
	function detail_sign(){
		$uid=I("uid");
		$token=I('token');
		$familyid=I('familyid');
		
		$this->assign("uid",$uid);
		$this->assign("token",$token);
		
		$User_family=M("users_family");
		
		$userfam=$User_family->where("uid={$uid}")->find();
		$Users=M('users');
		$Family=M('family');
		$familyinfo=$Family->where("disable=0 and state=2 and id={$familyid}")->find();
		

		$isstatus=-1;
		if($userfam['familyid']==$familyinfo['id']){
			$isstatus=$userfam['state'];
		}
		$familyinfo['isstatus']=$isstatus;
		

		$this->assign('familyinfo', $familyinfo);
		$this->display();  
	}
	/* 申请签约 */
	public function attended_add()
	{
		$uid=I("uid");
		$token=I("token");

		$rs=array('code'=>0,'info'=>array(),'msg'=>'');

		if(checkToken($uid,$token)==700){
			$rs['code']=700;
			$rs['msg']='您的登陆状态失效，请重新登陆！';
			echo json_encode($rs);
			exit;
		} 
		$familyid=I("familyid");

		$Family=M('family');
		$user_family=M("users_family");
		$fam=$Family->where("uid={$uid} ")->find();
		if($fam)
		{	
			if($fam['state']==0){
				$rs['code']=1001;
				$rs['msg']='你已经拥有一个家族在申请中';
				echo json_encode($rs);
				exit;
			}else if($fam['state']==2){
				$rs['code']=1002;
				$rs['msg']='你已经拥有一个家族';
				echo json_encode($rs);
				exit;
			}
			$Family->where("uid={$uid} ")->delete();
			$user_family->where("familyid={$fam['id']} ")->delete();
		} 
		
		
		$userfam=$user_family->where("uid={$uid}")->find();
		$data=array(
			'uid'=>$uid,
			'familyid'=>$familyid,
			'addtime'=>time(),
			'reason'=>'',
			'state'=>'0',
			'signout'=>'0'
		);
		if($userfam)
		{
			
			if($userfam['state']=="2")
			{
				$rs['code']=1003;
				$rs['msg']='你已经加入家族';
				echo json_encode($rs);
				exit;
			}

			$time=time()-(60*60*24*10);
			if($userfam['state']=="0" && $userfam['addtime'] > $time)
			{
				$rs['code']=1004;
				$rs['msg']='您加入家族的申请还在审核中，请耐心等待';
				echo json_encode($rs);
				exit;
			}

			$family=$user_family->where('id='.$userfam['id'])->save($data);
		}
		else
		{
			$family=$user_family->add($data);
		}
		
		M("users")->where("id={$uid}")->setField("divide_family",'-1');
		
		$rs['msg']='申请加入家族提交成功';
		echo json_encode($rs);
		exit;
		
	}
	/* 撤销申请签约 */
	function attended_revoke(){
		$uid=I("uid");
		$token=I('token');
		$rs=array('code'=>0,'info'=>array(),'msg'=>'撤销成功');
		
		if(checkToken($uid,$token)==700){
			$rs['code']=700;
			$rs['msg']='您的登陆状态失效，请重新登陆！';
			echo json_encode($rs);
			exit;
		} 
		$User_family=M("users_family");

		$familyinfo=$User_family->where("uid={$uid}")->find();
		if($familyinfo){
			if($familyinfo['state']==1){
				//$rs['code']=1001;
				//$rs['msg']='家族申请已审核通过，不能撤销';
				//echo json_encode($rs);
				//exit;
			}else if($familyinfo['state']==2){
				$rs['code']=1002;
				$rs['msg']='加入家族申请已审核通过，不能撤销';
				echo json_encode($rs);
				exit;
			}
		}
		$result=$User_family->where("uid={$uid}")->delete();

		echo json_encode($rs);
		exit;
		
	}
	/* 签约审核 */
	public function examine()
	{
		$uid=I('uid');
		$token=I('token');
	
		if(checkToken($uid,$token)==700){
			$this->assign("reason",'您的登陆状态失效，请重新登陆！');
			$this->display(':error');
			exit;
		} 
		$this->assign("uid",$uid);
		$this->assign("token",$token);
		
		$familyid=I('familyid');
		$family=M('family')->where("id={$familyid} and uid={$uid}")->find();
		if(!$family){
			$this->assign("reason",'你不是该家族会长，无权操作');
			$this->display(':error');
			exit;
		}
		$users=M('users');
		$list=M("users_family")->where("familyid={$family['id']} and state=0")->select();
		foreach($list as $k=>$v)
		{
			$userinfo=$users->field("id,user_nicename,avatar")->where("id={$v['uid']}")->find();
			$userinfo['fansnum']=getFansnums($v['uid']);
			$list[$k]['userinfo']=$userinfo;
		}
		$this->assign("list", $list);
		$this->assign('family', $family);
		$this->assign('familyid', $familyid);
		$this->display();  
	}
	/* 审核处理 */
	public function examine_edit()
	{
		$familyid=I('familyid');
		$uid=I('uid');
		$type=I('type');
		$touid=I('touid');
		$pass=I('pass');
		$token=I('token');
		$rs=array('code'=>0,'info'=>array(),'msg'=>'');
		if(checkToken($uid,$token)==700){
			$rs['code']=700;
			$rs['msg']='您的登陆状态失效，请重新登陆！';
			echo json_encode($rs);
			exit;
		}  
		$Family=M('family');
		$isexist=$Family->where("id={$familyid} and uid={$uid}")->find();
		if(!$isexist){
			$rs['code']=1001;
			$rs['msg']='你不是家族长，无权操作';
			echo json_encode($rs);
			exit;
		}
		
		
		$user_family=M("users_family");
		$isexist2=$user_family->where('familyid='.$familyid.' and uid='.$touid)->find();
		if(!$isexist2){
			$rs['code']=1003;
			$rs['msg']='该会员不是该家族下的成员，无权操作';
			echo json_encode($rs);
			exit;
		}
		$data=array(
			'state'=>$type,
			'istip'=>'1',
			'reason'=>checkNull($pass)
		);
		
		$result=$user_family->where("id={$isexist2['id']}")->save($data);
		if($result!==false)
		{
			$rs['msg']='操作成功';
			echo json_encode($rs);
			exit;
		}
		else
		{
			$rs['code']=1002;
			$rs['msg']='操作失败';
			echo json_encode($rs);
			exit;
		}
	}
	/* 家族成员 */
	public function member()
	{
		$familyid=I('familyid');
		$uid=I('uid');
		$token=I('token');
	
		if(checkToken($uid,$token)==700){
			$this->assign("reason",'您的登陆状态失效，请重新登陆！');
			$this->display(':error');
			exit;
		} 
		
		$this->assign("uid",$uid);
		$this->assign("token",$token);
		$type="0";
		$familyinfo=M("family")->where("id=".$familyid)->find();
		if($familyinfo['uid']==$uid)
		{
			$type="1";
		}

		$user_family=M("users_family");
		$Users=M('users');
		$list=$user_family->where("familyid={$familyid} and state=2")->select();
		foreach($list as $k=>$v)
		{
			$userinfo=$Users->field("id,user_nicename,avatar,divide_family")->where("id={$v['uid']}")->find();
			if($userinfo['divide_family']=='-1'){
				$userinfo['divide_family']=$familyinfo['divide_family'];
			}
			$userinfo['fansnum']=getFansnums($v['uid']);
			$list[$k]['userinfo']=$userinfo;
		}
		$this->assign('list', $list);
		$this->assign('type', $type);
		$this->assign('familyid', $familyid);
		$this->display();  
	}
	/* 成员独立抽成设置 */
	public function member_setdivide()
	{
		$familyid=I('familyid');
		$uid=I('uid');
		$token=I('token');
		$divide=I('divide');
		$touid=I('touid');
		$rs=array('code'=>0,'info'=>array(),'msg'=>'');

		if(checkToken($uid,$token)==700){
			$rs['code']=700;
			$rs['msg']='您的登陆状态失效，请重新登陆！';
			echo json_encode($rs);
			exit;
		} 
		$Family=M('family');
		$isexist=$Family->where("id={$familyid} and uid={$uid}")->find();
		if(!$isexist){
			$rs['code']=1001;
			$rs['msg']='你不是家族长，无权操作';
			echo json_encode($rs);
			exit;
		}
		$User_family=M("users_family");
		$isexist2=$User_family->where("familyid={$familyid} and uid={$touid} and state=2")->find();
		if(!$isexist2){

			$rs['code']=1003;
			$rs['msg']='该会员不是该家族下的成员，无权操作';
			echo json_encode($rs);
			exit;
		}

		$data=array(
			'divide_family'=>checkNull($divide),
		);
		$User=M("users");
		$result=$User->where("id={$touid}")->save($data);

		if($result!==false)
		{
			$rs['msg']='操作成功';
			echo json_encode($rs);
			exit;
		}
		else
		{
			$rs['code']=1002;
			$rs['msg']='操作失败';
			echo json_encode($rs);
			exit;
		}
	} 	
	/* 成员踢出 */
	public function member_del()
	{
		$familyid=I('familyid');
		$uid=I('uid');
		$touid=I('touid');
		$token=I('token');
		$reason=checkNull(I('reason'));
		$rs=array('code'=>0,'info'=>array(),'msg'=>'');
		if(checkToken($uid,$token)==700){
			$rs['code']=700;
			$rs['msg']='您的登陆状态失效，请重新登陆！';
			echo json_encode($rs);
			exit;
		}  
		
		$Family=M('family');
		$isexist=$Family->where("id={$familyid} and uid={$uid}")->find();
		if(!$isexist){
			$rs['code']=1001;
			$rs['msg']='你不是家族长，无权操作';
			echo json_encode($rs);
			exit;
		}
		$User_family=M("users_family");
		$isexist2=$User_family->where("familyid={$familyid} and uid={$touid} and state=2")->find();
		if(!$isexist2){
			$rs['code']=1003;
			$rs['msg']='该会员不是该家族下的成员，无权操作';
			echo json_encode($rs);
			exit;
		}
		
		$data=array(
			'state'=>3,
			'signout'=>3,
			'signout_istip'=>3,
			'signout_reason'=>$reason,
		);
		
		$result=$User_family->where('familyid='.$familyid.' and uid='.$touid)->save($data);
		if($result!==false)
		{
			$rs['msg']='操作成功';
			echo json_encode($rs);
			exit;
		}
		else
		{
			$rs['code']=1002;
			$rs['msg']='操作失败';
			echo json_encode($rs);
			exit;
		}
	}
	/* 解约申请管理 */
	public function signout()
	{
		$uid=I('uid');
		$token=I('token');
	
		if(checkToken($uid,$token)==700){
			$this->assign("reason",'您的登陆状态失效，请重新登陆！');
			$this->display(':error');
			exit;
		} 
		
		$this->assign("uid",$uid);
		$this->assign("token",$token);
		
		$familyid=I('familyid');
		$family=M('family')->where("id={$familyid} and uid={$uid}")->find();
		if(!$family){
			$this->assign("reason",'你不是该家族会长，无权操作');
			$this->display(':error');
			exit;
		}

		$users=M('users');
		$list=M("users_family")->where("familyid={$familyid} and signout=1")->select();
		foreach($list as $k=>$v)
		{
			$userinfo=getUserInfo($v['uid']);
			$userinfo['fansnum']=getFansnums($v['uid']);
			$list[$k]['userinfo']=$userinfo;
		}
		$this->assign("list", $list);
		$this->assign('familyid', $familyid);
		$this->display();  
	}
	
	/* 解约申请操作 */
	public function signout_post()
	{
		$familyid=I('familyid');
		$uid=I('uid');
		$touid=I('touid');
		$token=I('token');
		$type=I('type');
		$reason=checkNull(I('reason'));
		$rs=array('code'=>0,'info'=>array(),'msg'=>'');
		if(checkToken($uid,$token)==700){
			$rs['code']=700;
			$rs['msg']='您的登陆状态失效，请重新登陆！';
			echo json_encode($rs);
			exit;
		}  
		
		$Family=M('family');
		$isexist=$Family->where("id={$familyid} and uid={$uid}")->find();
		if(!$isexist){
			$rs['code']=1001;
			$rs['msg']='你不是家族长，无权操作';
			echo json_encode($rs);
			exit;
		}
		$User_family=M("users_family");
		$isexist2=$User_family->where("familyid={$familyid} and uid={$touid} and state=2")->find();
		if(!$isexist2){
			$rs['code']=1003;
			$rs['msg']='该会员不是该家族下的成员，无权操作';
			echo json_encode($rs);
			exit;
		}
		
		if($type==1){
			$data=array(
				'state'=>3,
				'signout'=>2,
				'signout_istip'=>1,
				'signout_reason'=>$reason,
			);
			$result=$User_family->where('familyid='.$familyid.' and uid='.$touid)->save($data);
		}else{
			$data=array(
				'signout'=>0,
				'signout_istip'=>1,
				'signout_reason'=>$reason,
			);
			$result=$User_family->where('familyid='.$familyid.' and uid='.$touid)->save( $data );
		}

		
		if($result!==false)
		{
			$rs['msg']='操作成功';
			echo json_encode($rs);
			exit;
		}
		else
		{
			$rs['code']=1002;
			$rs['msg']='操作失败';
			echo json_encode($rs);
			exit;
		}
	}
	/* 解除签约 */
	function relieve(){
		$uid=I('uid');
		$token=I('token');
		
		$this->assign("uid",$uid);
		$this->assign("token",$token);
		
		$this->display();  
	}
	/* 解除提交 */
	public function retreat()
	{
		$uid=I("uid");
		$token=I('token');
	
		$rs=array('code'=>0,'info'=>array(),'msg'=>'');
		if(checkToken($uid,$token)==700){
			$rs['code']=700;
			$rs['msg']='您的登陆状态失效，请重新登陆！';
			echo json_encode($rs);
			exit;
		}  
		
		$data=array(
			'signout'=>'1'
		);
		$result=M("users_family")->where("uid=".$uid)->save($data);
		if($result!==false)
		{
			$rs['msg']='申请成功，请等待家族长审核';
			echo json_encode($rs);
			exit;
		}
		else
		{
			$rs['code']=1002;
			$rs['msg']='操作失败';
			echo json_encode($rs);
			exit;
		}
	}
	/* 家族收益 */
	public function profit()
	{
		$uid=I('uid');
		$token=I('token');
	
		if(checkToken($uid,$token)==700){
			$this->assign("reason",'您的登陆状态失效，请重新登陆！');
			$this->display(':error');
			exit;
		} 
		$familyid=I("familyid");
		$Family=M('family');
		$isexist=$Family->where("id={$familyid} and uid={$uid}")->find();

		$User_family=M("users_family");
		$isexist2=$User_family->where("familyid={$familyid} and uid={$uid} and state=2")->find();
		if(!$isexist && !$isexist2){
			$this->assign("reason",'您不是该家族成员，无权操作');
			$this->display(':error');
			exit;
		}

		
		$this->assign("uid",$uid);
		$this->assign("token",$token);
		
		
		$Family_profit=M('family_profit');
		$users=M("users");

		$list=$Family_profit->field("time,uid,sum(profit) as profitzong,sum(profit_anthor) as anthor_totoal")->where("familyid={$familyid}")->group("uid,time")->order("time desc")->limit("0,50")->select();
		foreach($list as $k=>$v)
		{
			$list[$k]['userinfo']=getUserInfo($v['uid']);
			$list[$k]['profitzong']=NumberFormat($v['profitzong']);
			$list[$k]['anthor_totoal']=NumberFormat($v['anthor_totoal']);
		}

		$this->assign('list', $list);
		$this->assign('familyid', $familyid);

		$this->display();
	}
	
	public function profit_more()
	{
		$uid=I('uid');
		$token=I('token');
		
		$result=array(
			'data'=>array(),
			'nums'=>0,
			'isscroll'=>0,
		);
		
		if(checkToken($uid,$token)==700){
			echo json_encode($result);
			exit;
		} 
		$familyid=I("familyid");
		$Family=M('family');
		$isexist=$Family->where("id={$familyid} and uid={$uid}")->find();

		$User_family=M("users_family");
		$isexist2=$User_family->where("familyid={$familyid} and uid={$uid} and state=2")->find();
		if(!$isexist && !$isexist2){
			echo json_encode($result);
			exit;
		}
		
		$p=I('page');
		$pnums=50;
		$start=($p-1)*$pnums;
		
		$Family_profit=M('family_profit');
		$users=M("users");

		$list=$Family_profit->field("time,uid,sum(profit) as profitzong,sum(profit_anthor) as anthor_totoal")->where("familyid={$familyid}")->group("uid,time")->order("time desc")->limit($start,$pnums)->select();
		foreach($list as $k=>$v)
		{
			$usreinfo=getUserInfo($v['uid']);
			$list[$k]['usreinfo']=$usreinfo;
			$list[$k]['profitzong']=NumberFormat($v['profitzong']);
			$list[$k]['anthor_totoal']=NumberFormat($v['anthor_totoal']);
		}

		$nums=count($list);
		if($nums<$pnums){
			$isscroll=0;
		}else{
			$isscroll=1;
		}
		
		$result=array(
			'data'=>$list,
			'nums'=>$nums,
			'isscroll'=>$isscroll,
		);

		echo json_encode($result);
		exit;
	}
	/* 主播数据 */
	public function long()
	{
		$uid=I('uid');
		$token=I('token');
	
		if(checkToken($uid,$token)==700){
			$this->assign("reason",'您的登陆状态失效，请重新登陆！');
			$this->display(':error');
			exit;
		} 
		
		$this->assign("uid",$uid);
		$this->assign("token",$token);
		
		$familyid=I("familyid");
		$Family=M('family');
		$isexist=$Family->where("id={$familyid} and uid={$uid}")->find();

		$User_family=M("users_family");
		$isexist2=$User_family->where("familyid={$familyid} and uid={$uid} and state=2")->find();
		if(!$isexist && !$isexist2){
			$this->assign("reason",'您不是该家族成员，无权操作');
			$this->display(':error');
			exit;
		}
		
		$list=M('users_liverecord l')
			->field("l.uid,l.time,sum(l.endtime-l.starttime) as total")
			->join("left join __USERS_FAMILY__ f on l.uid=f.uid")
			->where("l.starttime > f.addtime and f.familyid={$familyid}")
			->group('uid,time')
			->limit(0,50)
			->select();
		foreach($list as $k=>$v){
			$list[$k]['userinfo']=getUserInfo($v['uid']);
			$list[$k]['total']=datelong($v['total']);
		}


		$this->assign('list', $list);
		$this->assign('familyid', $familyid);
		
		$this->display();
	}

	public function long_more()
	{
		$uid=I('uid');
		$token=I('token');
		
		$result=array(
			'data'=>array(),
			'nums'=>0,
			'isscroll'=>0,
		);
	
		if(checkToken($uid,$token)==700){
			echo json_encode($result);
			exit;
		} 
		
		$familyid=I("familyid");
		$Family=M('family');
		$isexist=$Family->where("id={$familyid} and uid={$uid}")->find();

		$User_family=M("users_family");
		$isexist2=$User_family->where("familyid={$familyid} and uid={$uid} and state=2")->find();
		if(!$isexist && !$isexist2){
			echo json_encode($result);
			exit;
		}
		
		$p=I('page');
		$pnums=50;
		$start=($p-1)*$pnums;
		
		$list=M('users_liverecord l')
			->field("l.uid,l.time,sum(l.endtime-l.starttime) as total")
			->join("left join __USERS_FAMILY__ f on l.uid=f.uid")
			->where("l.starttime > f.addtime and f.familyid={$familyid}")
			->group('uid,time')
			->limit($start,$pnums)
			->select();
		foreach($list as $k=>$v){
			$list[$k]['userinfo']=getUserInfo($v['uid']);
			$list[$k]['total']=datelong($v['total']);
		}


		$nums=count($list);
		if($nums<$pnums){
			$isscroll=0;
		}else{
			$isscroll=1;
		}
		
		$result=array(
			'data'=>$list,
			'nums'=>$nums,
			'isscroll'=>$isscroll,
		);

		echo json_encode($result);
		exit;
	}

}