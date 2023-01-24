<?php
/**
 * 方言秀视频举报
 */
namespace Appapi\Controller;
use Common\Controller\HomebaseController;
class DialectreportController extends HomebaseController {

	function index(){       
		$uid=I('uid');
		$videoid=I('videoid');
		$type=I('type');//0：视频；1：方言秀
        
		$report=M("dialect_report");
		$reportinfo=$report->select();
		
		
		$this->assign("uid",$uid); 
		$this->assign("videoid",$videoid); 
		$this->assign("type",$type); 
		$this->assign("reportinfo",$reportinfo); 
    
		$this->display();
	}
    
    function reportSave(){
		$uid=I('uid');
		$videoid=checkNull(I('videoid'));
		$type=I('type');
		$content=checkNull(I('content'));
		$addtime=time();
        $touid=0;
        $data=array(
            "uid"=>$uid,
            "content"=>$content,
            "addtime"=>$addtime
        );
        if($type==1){//方言秀视频举报
            $dialectinfo=M("users_dialect")->where("id=".$videoid)->find();
            if($dialectinfo){
                $touid=$dialectinfo['uid'];
            }      
            $data["dialectid"]=$videoid;
            $data["touid"]=$touid;
			$result=M("users_dialectreport")->add($data);
        }else{
            $videoinfo=M("users_video")->where("id=".$videoid)->find();
            if($videoinfo){
                $touid=$videoinfo['uid'];
            }
            $data["videoid"]=$videoid;
            $data["touid"]=$touid;
			$result=M("users_video")->add($data);
        }
      
        

		
		if($result){
				echo json_encode(array("status"=>0,'msg'=>''));
		}else{
			 	echo json_encode(array("status"=>400,'errormsg'=>'提交失败'));
		}
	
	}	
}