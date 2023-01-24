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
			<li class="active"><a href="<?php echo U('storage/index');?>"><?php echo L('ADMIN_STORAGE_INDEX');?></a></li>
		</ul>
		<form method="post" class="form-horizontal js-ajax-form" action="<?php echo U('storage/setting_post');?>">
			<?php $support_storages=array("Local"=>L('DEFAULT'),"Qiniu"=>L('QINIU')); ?>
			<select name="type" onchange="func()" id="mySelect">
				<?php if(is_array($support_storages)): foreach($support_storages as $key=>$vo): $type_selected=$type==$key?"selected":""; ?>
				<option value="<?php echo ($key); ?>"<?php echo ($type_selected); ?>><?php echo ($vo); ?></option><?php endforeach; endif; ?>
			</select>
			<div style="margin-top: 10px;height:382px" >
				<div id="storage" style="display:none">
				<ul class="nav nav-tabs">
					<li class="active"><a><?php echo L('QINIU');?></a></li>
				</ul>

				<fieldset>
					<div class="control-group">
						<label class="control-label">AccessKey</label>
						<div class="controls">
							<input type="text" class="input mr5" name="Qiniu[accessKey]" value="<?php echo ($Qiniu["accessKey"]); ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">SecretKey</label>
						<div class="controls">
							<input type="text" class="input mr5" name="Qiniu[secretKey]" value="<?php echo ($Qiniu["secretKey"]); ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label"><?php echo L('DOMAIN');?></label>
						<div class="controls">
							<input type="text" class="input mr5" name="Qiniu[domain]" value="<?php echo ($Qiniu["domain"]); ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label"><?php echo L('BUCKET');?></label>
						<div class="controls">
							<input type="text" class="input mr5" name="Qiniu[bucket]" value="<?php echo ($Qiniu["bucket"]); ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label"><?php echo L('GET_ACCESS_KEY');?></label>
						<div class="controls">
							<a href="https://portal.qiniu.com/signup?code=3lfihpz361o42" target="_blank"><?php echo L('GET_IT_NOW');?></a>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label"><?php echo L('QINIU_PROMOTION_CODE');?></label>
						<div class="controls">
							<a href="http://www.thinkcmf.com/topic/topic/index/id/103.html" target="_blank">507670e8</a>
						</div>
					</div>
				</fieldset>
				</div>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary js-ajax-submit"><?php echo L('SAVE');?></button>
			</div>
		</form>
	</div>
	<script src="/public/js/common.js"></script>
	<script src="/public/js/jquery.js"></script>
	<script>
		function func(){
			var vs = $('select option:selected').val();
			if(vs=="Qiniu")
			{
				$("#storage").css('display','block');
			}else
			{
				$("#storage").css('display','none');
			}
		};
	  $(function(){
			func();
		})
	</script> 

</body>
</html>