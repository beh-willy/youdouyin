<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<title><?php echo ($post_title); ?></title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta content="telephone=no" name="format-detection" />
<!-- Set render engine for 360 browser -->
<meta name="renderer" content="webkit">

<!-- No Baidu Siteapp-->
<meta http-equiv="Cache-Control" content="no-siteapp"/>
<link href="/themes/simplebootx/Public/css/page.css" rel="stylesheet">
<style type="text/css">
	p{
		color: #FFF;
		padding: 0 10px;
	}
</style>
</head>

<body class="body-white" style="background: #171717;color: #FFF;">
	<div class="container tc-main">	
	   <div class="page_content">
		     <?php echo ($post_content); ?>
		     
		 </div>
		
	</div>
	<!-- /container -->
</body>
</html>