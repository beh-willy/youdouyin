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
//ćšć±ćé
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
<style>
.home_info li em {
	float: left;
	width: 120px;
	font-style: normal;
}
li {
	list-style: none;
}
</style>
<link href="/public/simpleboot/css/admin.css"  rel="stylesheet" type="text/css">
</head>
<body>
	<div class="admin_index">
		<div class="layer">
			<div class="modular">
				<span class="title"><a>äŒćç»èźĄ</a></span>
				<span class="info"><a>ćšçșżäșșæ°ïŒ</a><a class="data"><?php echo ($online_num); ?>äșș</a></span>
			</div>
		<div class="modular">
				<span class="title"><a>äŒćç»èźĄ</a></span>
				<span class="info"><a>æłšćéïŒ</a><a class="data"><?php echo ($users_admin['register']); ?></a></span>
				<!--<span class="info"><a>äž»æ­æ°éïŒ</a><a class="data"><?php echo ($users_admin['auth']); ?></a></span>-->
				<!--<span class="info"><a href="<?php echo U('Liveing/index');?>">æ­ŁćšçŽæ­çäž»æ­ïŒ</a><a class="data"><?php echo ($users_admin['live']); ?></a></span>-->
				<!--<span class="info"></span>-->
			</div>
			<!-- <div class="modular">
				<span class="title"><a>ććŒæ°æź</a></span>
				<span class="info">
					<a>ä»æ„çșżäžććŒééąïŒ</a>
					<a class="data"><?php echo ($charge_admin['money_my']); ?></a>
					ć±<a class="data"><?php echo ($charge_admin['money_my_count']); ?></a>ć
				</span>
				<span class="info">
					<a>ä»æ„æćšććŒçčæ°ïŒ</a>
					<a class="data"><?php echo ($charge_admin['money_amdin']); ?></a>
					ć±<a class="data"><?php echo ($charge_admin['money_amdin_count']); ?></a>ć
				</span>
				<span class="info"></span>
				<span class="info"></span>
			</div>
			<div class="modular">
				<span class="title"><a>ććŒæ„æș</a></span>
				<span class="info"><a>æŻä»ćźïŒ</a><a class="data"><?php echo ($source['source_zfb']); ?></a></span>
				<span class="info"><a>ćŸźäżĄïŒ</a><a class="data"><?php echo ($source['source_wx']); ?></a></span>
				<span class="info"><a>èčææŻä»ïŒ</a><a class="data"><?php echo ($source['source_ios']); ?></a></span>
				<span class="info"></span>
			</div> -->
		</div>
		<div class="layer">
			<!-- <div class="modular">
				<span class="title"><a>äž»æ­ćźĄæ ž</a></span>
				<span class="info"><a href="<?php echo U('Userauth/index');?>">ćŸćźĄæ žçäž»æ­ïŒ</a><a class="data"><?php echo ($examine); ?></a></span>
				<span class="info"></span>
				<span class="info"></span>
				<span class="info"></span>
			</div>
			<div class="modular">
				<span class="title"><a>æç°æ°æź</a></span>
				<span class="info">
					<a>æç°ééąïŒ</a>
					<a class="data"><?php echo ($cashrecord['total']); ?></a>
				</span>
				<span class="info">
					<a>ć·Čæćæç°ééąïŒ</a>
					<a class="data"><?php echo ($cashrecord['success']); ?></a>
				</span>
				<span class="info">
					<a>ćŸć€çæç°äž»æ­ïŒ</a>
					<a class="data"><?php echo ($cashrecord['info']); ?></a>
				</span>
				<span class="info">
					<a>ćŸć€çæç°ééąïŒ</a>
					<a class="data"><?php echo ($cashrecord['fail']); ?></a>
				</span>
			</div>
			<div class="modular" style="height: 206px;">
				<span class="title"><a>ç­éšäž»æ­</a></span>
				 <?php if(is_array($hot)): $i = 0; $__LIST__ = $hot;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><span class="remen">
						<img src="<?php echo ($v['avatar']); ?>"/> <?php echo ($v['user_nicename']); ?>
					</span><?php endforeach; endif; else: echo "" ;endif; ?>
			</div> -->
		</div>
	</div>
	<script src="/public/js/common.js"></script>
</body>
</html>