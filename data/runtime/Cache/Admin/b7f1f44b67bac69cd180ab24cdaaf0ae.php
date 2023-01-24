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
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li><a href="<?php echo U('Category/index');?>">标签分类</a></li>
			<li class="active"><a href="#">分类添加</a></li>
	    </ul>
		<form class="form-horizontal js-ajax-form" action="<?php echo U('Category/add_post');?>" method="post">
			<div class="tabbable">
		        <div class="tab-content">
		          <div class="tab-pane active" id="A">
						<fieldset>
							
							<div class="control-group">
								<label class="control-label">分类名称</label>
								<div class="controls">
									<input type="text" name="name" value=""><span class="form-required">*</span>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label">分类归属</label>
								<div class="controls">
									<select name="belongto">
										<option value="0">视频分类</option>
										<option value="1">社区分类</option>
									</select><span class="form-required">*</span>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">分类描述</label>
								<div class="controls">
									<textarea name="description" rows="5" cols="57"></textarea>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label">栏目封面</label>
								<div class="controls">
									<div >
										<input type="hidden" name="thumb" id="thumb" value="">
										<a href="javascript:void(0);" onclick="flashupload('thumb_images', '附件上传','thumb',thumb_images,'1,jpg|jpeg|gif|png|bmp,1,,,1','','','');return false;">
											<img src="/admin/themes/simplebootx/Public/assets/images/default-thumbnail.png" id="thumb_preview" width="135" style="cursor: hand" />
										</a>
										<input type="button" class="btn btn-small" onclick="$('#thumb_preview').attr('src','/admin/themes/simplebootx/Public/assets/images/default-thumbnail.png');$('#thumb').val('');return false;" value="取消图片">
									</div>
									<span class="form-required"></span>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label">是否</label>
					
								<div class="controls">
								<label class="radio inline" ><input type="radio" name="isreview" value="0" checked  />否</label>
								<label class="radio inline" ><input type="radio" name="isreview" value="1" >是</label>
							</div>
					
				</div>
						</fieldset>
		          </div>


		        </div>
		    </div>
		    <div class="form-actions">
		     	<button class="btn btn-primary js-ajax-submit"type="submit"><?php echo L('ADD');?></button>
		      	<a class="btn" href="<?php echo U('AdminTerm/index');?>"><?php echo L('BACK');?></a>
		    </div>
		</form>
	</div>
	<script type="text/javascript" src="/public/js/common.js"></script>
	<script type="text/javascript" src="/public/js/content_addtop.js"></script>
</body>
</html>