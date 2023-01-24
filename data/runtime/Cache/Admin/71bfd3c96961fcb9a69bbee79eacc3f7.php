<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<!-- Set render engine for 360 browser -->
	<meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- HTML5 shim for IE8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->

	<link href="/public/simpleboot/themes/<?php echo C('SP_ADMIN_STYLE');?>/theme.min.css" rel="stylesheet">
    <link href="/public/simpleboot/css/simplebootadmin.css" rel="stylesheet">
    <link href="/public/js/artDialog/skins/default.css" rel="stylesheet" />
    <link href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome.min.css"  rel="stylesheet" type="text/css">
    <style>
		.length_3{width: 180px;}
		form .input-order{margin-bottom: 0px;padding:3px;width:40px;}
		.table-actions{margin-top: 5px; margin-bottom: 5px;padding:0px;}
		.table-list{margin-bottom: 0px;}
	</style>
	<!--[if IE 7]>
	<link rel="stylesheet" href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome-ie7.min.css">
	<![endif]-->
<script type="text/javascript">
//全局变量
var GV = {
    DIMAUB: "/",
    JS_ROOT: "public/js/",
    TOKEN: ""
};
</script>
<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/public/js/jquery.js"></script>
    <script src="/public/js/wind.js"></script>
    <script src="/public/simpleboot/bootstrap/js/bootstrap.min.js"></script>
<?php if(APP_DEBUG): ?><style>
		#think_page_trace_open{
			z-index:9999;
		}
	</style><?php endif; ?>
</head>
<body>
	<div class="wrap">
		<ul class="nav nav-tabs">
			<li ><a href="<?php echo U('Community/index');?>">列表</a></li>
			<li class="active"><a >编辑</a></li>
		</ul>
		<form method="post" class="form-horizontal js-ajax-form" action="<?php echo U('Community/edit_post');?>">
		   <input type="hidden" name="id" value="<?php echo ($community['id']); ?>">
		   <input type="hidden" name="nopasstime" value="<?php echo ($community['nopass_time']); ?>">
			<fieldset>
				<div class="control-group">
					<label class="control-label">圈子状态</label>
					<?php if($community['status'] == '0'): ?><div class="controls">
							<label class="radio inline" ><input type="radio" name="status" value="0" checked  />待审核</label>
							<label class="radio inline" ><input type="radio" name="status" value="1" />通过</label>
							<label class="radio inline" ><input type="radio" name="status" value="2" >不通过</label>
						</div>
					<!--<?php else: ?>-->
					<!--	<?php if($community['status'] == '1'): ?>-->
					<!--		<label class="radio inline" >通过</label>-->
					<!--	<?php elseif($community['status'] == '2'): ?>-->
					<!--		<div class="controls">-->
					<!--			<label class="radio inline" ><input type="radio" name="status" value="1" />通过</label>-->
					<!--			<label class="radio inline" ><input type="radio" name="status" value="2" checked >不通过</label>-->
					<!--		</div>-->
					<!--<?php endif; ?> -->
					<!--	<div class="controls">-->
					<!--		<label class="radio inline" ><input type="radio" name="status" value="1" <?php if($community['status'] == '1'): ?>checked<?php endif; ?> />通过</label>-->
					<!--		<label class="radio inline" ><input type="radio" name="status" value="2" <?php if($community['status'] == '2'): ?>checked<?php endif; ?> >不通过</label>-->
					<!--	</div>--><?php endif; ?>
				</div>
				<!-- <div class="control-group">
					<label class="control-label">上下架状态</label>
					<div class="controls">
						<label class="radio inline" for="active_true"><input type="radio" name="isdel" value="0" <?php if($community['isdel'] == '0'): ?>checked<?php endif; ?> id="active_true" />上架</label>
						<label class="radio inline" for="active_false"><input type="radio" name="isdel" value="1" <?php if($community['isdel'] == '1'): ?>checked<?php endif; ?> id="active_false">下架</label>
					</div> -->
				</div>
				<div class="control-group">
					<label class="control-label">用户信息</label>
					<div class="controls">
						
						<input type="text"  value=" <?php echo ($community['userinfo']['user_nicename']); ?> (<?php echo ($community['uid']); ?>) " readonly>
						<span class="form-required">*</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">标题</label>
					<div class="controls">
						<input type="text" name="title"  value="<?php echo ($community['title']); ?>">
						<!--<span class="form-required">*</span>-->
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">内容</label>
					<div class="controls">
						<textarea name="content"><?php echo ($community['content']); ?></textarea>
						<!--<script type="text/plain" id="container" name="content"></script>-->
						<!--<span class="form-required">*</span>-->
					</div>
				</div>
				
				<?php if($community['imgs'] != '' ): ?><div class="control-group">
						<label class="control-label">图片</label>
						<div class="controls">
						
								
								<!--<img src="<?php echo ($community['thumb']); ?>" class="thumb2_preview" width="135" style="cursor: hand" />-->
								
								<?php if(is_array($community['imgs'])): $k = 0; $__LIST__ = $community['imgs'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($k % 2 );++$k;?><img src="<?php echo ($sub); ?>"  class="thumb2_preview" width="135" style="cursor: hand" />&nbsp;&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?>
							
						</div>
					
				<?php else: ?>
				<label class="control-label">视频</label>
					<div class="controls">
						<div class="playerzmblbkjP" id="playerzmblbkjP" style="width:500px;height:500px;">
					</div><?php endif; ?>
				</div>
				<div class="control-group">
					<label class="control-label">是否置顶</label>
					<div class="controls">
						<label class="radio inline" ><input type="radio" name="istop" value="0" checked  />否</label>
						<label class="radio inline" ><input type="radio" name="istop" value="1" >是</label>
					</div>
				</div>
			


			</fieldset>

			<div class="form-actions">
				<button type="submit" class="btn btn-primary js-ajax-submit"><?php echo L('EDIT');?>1</button>
				<?php if($from == 'index'): ?><a class="btn" href="<?php echo U('Community/index');?>"><?php echo L('BACK');?></a>
				<?php elseif($from == 'lower'): ?>
					<a class="btn" href="<?php echo U('Community/lowervideo');?>"><?php echo L('BACK');?></a>
				<?php elseif($from == 'nopassindex'): ?>
					<a class="btn" href="<?php echo U('Community/nopassindex');?>"><?php echo L('BACK');?></a>
				<?php elseif($from == 'passindex'): ?>
					<a class="btn" href="<?php echo U('Community/passindex');?>"><?php echo L('BACK');?></a><?php endif; ?>
			</div>
		</form>
	</div>
	<script src="/public/js/common.js"></script>

	<script type="text/javascript" src="/public/js/content_addtop.js"></script>
	<script type="text/javascript" src="/public/playback/ckplayer.js" charset="utf-8"></script>

		<script type="text/javascript" src="/public/js/ueditor/ueditor.config.js"></script>
	<script type="text/javascript" src="/public/js/ueditor/ueditor.all.min.js"></script>
	<!-- 实例化编辑器 -->
    <script type="text/javascript">
        var ue = UE.getEditor('container');

        ue.addListener("blur",function(){
		  var ue = UE.getEditor('container');
		     console.log(111);
		  var desc = document.getElementById("content1");
		  desc.value=ue.getContent();
		  })
    </script>
	<script type="text/javascript">
		$(function(){
			var flashvars={
				f:"<?php echo ($community['videolink']); ?>",//圈子地址rtmp://testlive.anbig.com/5showcam/1737_1487723653
				a:'',//调用时的参数，只有当s>0的时候有效
				s:'0',//调用方式，0=普通方法（f=圈子地址），1=网址形式,2=xml形式，3=swf形式(s>0时f=网址，配合a来完成对地址的组装)
				c:'0',//是否读取文本配置,0不是，1是
				t:'10|10',//圈子开始前播放swf/图片时的时间，多个用竖线隔开
				y:'',//这里是使用网址形式调用广告地址时使用，前提是要设置l的值为空
				z:'',//缓冲广告，只能放一个，swf格式
				e:'8',//圈子结束后的动作，0是调用js函数，1是循环播放，2是暂停播放并且不调用广告，3是调用圈子推荐列表的插件，4是清除圈子流并调用js功能和1差不多，5是暂停播放并且调用暂停广告
				v:'100',//默认音量，0-100之间
				p:'0',//圈子默认0是暂停，1是播放，2是不加载圈子
				h:'0',	//播放http圈子流时采用何种拖动方法，=0不使用任意拖动，=1是使用按关键帧，=2是按时间点，=3是自动判断按什么(如果圈子格式是.mp4就按关键帧，.flv就按关键时间)，=4也是自动判断(只要包含字符mp4就按mp4来，只要包含字符flv就按flv来)
				k:'32|63',//提示点时间，如 30|60鼠标经过进度栏30秒，60秒会提示n指定的相应的文字
				n:'这是提示点的功能，如果不需要删除k和n的值|提示点测试60秒',//提示点文字，跟k配合使用，如 提示点1|提示点2
				wh:'',//宽高比，可以自己定义圈子的宽高或宽高比如：wh:'4:3',或wh:'1080:720'
				lv:'0',//是否是直播流，=1则锁定进度栏
				loaded:'loadedHandler',//当播放器加载完成后发送该js函数loaded
				//调用播放器的所有参数列表结束
				//以下为自定义的播放器参数用来在插件里引用的
				my_title:"<?php echo ($info['title']); ?>",
				my_url:encodeURIComponent(window.location.href)//本页面地址
				//调用自定义播放器参数结束
			};
			var params={bgcolor:'#FFF',allowFullScreen:true,allowScriptAccess:'always'};									//这里定义播放器的其它参数如背景色（跟flashvars中的b不同），是否支持全屏，是否支持交互
			//var video=['http://img.ksbbs.com/asset/Mon_1605/0ec8cc80112a2d6.mp4->video/mp4'];
			var video=['<?php echo ($community['videolink']); ?>'];
			CKobject.embed('public/playback/ckplayer.swf','playerzmblbkjP','ckplayer_playerzmblbkjP','100%','100%',false,flashvars,video,params);
	})
</script>


<script type="text/javascript">
		$(function(){

			$("input[name='community_upload_type']").click(function(){
				var val=$("input[name='community_upload_type']:checked").val();
				console.log(val);
				if(val==0){
					$(".video_url_area").show();
					$(".upload_video_area").hide();
				}else{
					$(".video_url_area").hide();
					$(".upload_video_area").show();
					$("input[name='href']").val('');
				}
			});

			

		});
		
	function show_type(e){

			if($(e).val() == 1){
				$('#imgs').show();
				$('#Communitys').attr("style","display:none;");
				$('#Community_img').attr("style","display:none;");
				$(".Community_url_area").hide();
				$(".upload_Community_area").hide();
			}else{
				$('#Communitys').show();
				$(".Community_url_area").show();
				$('#Community_img').show();
				$('#imgs').attr("style","display:none;");
			}
		}


		$(function(){

				// 初始化直播一级分类
		    initCategory();

		    // 更改一级分类后的操作
		   /* $("select[name='cate']").change(function() {

		        var cateCode = $("select[name='cate']").val();

		        getSubCate(cateCode);

		    });*/



			$("input[name='Community_upload_type']").click(function(){
				var val=$("input[name='Community_upload_type']:checked").val();
				console.log(val);
				if(val==0){
					$(".Community_url_area").show();
					$(".upload_Community_area").hide();
				}else{
					$(".Community_url_area").hide();
					$(".upload_Community_area").show();
					$("input[name='href']").val('');
				}
			});

			$("input[name='owner']").on("click",function(){
				var val=$(this).val();
				if(val==1){ //所有者为用户
					$("#owner_uid").show();
				}else{
					$("#owner_uid").hide();
				}
			});

		});

function initCategory() {

    var firstCateCode;
    // ajax请求所有分类
    $.get("/api/public", {
        service : "Category.GetCategory",num:1

    }, function(data) {
        //console.log(data);
        // console.log(data);
        $.each(data.data.info, function(key, d) {
            $("select[name='cate']").append(
                "<option value='"+d.id+"'>" + d.name
                + "</option>");
        });

        // 获取第一个分类的code
        firstCateCode = data.data.info[0].id;
        // 根据第一个一级分类code获取对应二级分类列表
        //getSubCate(firstCateCode);
    }, 'json');

}
	</script>
</body>
</html>