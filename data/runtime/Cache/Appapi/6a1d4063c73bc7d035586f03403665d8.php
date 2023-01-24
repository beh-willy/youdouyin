<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="telephone=no" name="format-detection">
    <title><?php echo ($info['title']); ?></title>
    <style type="text/css">
    	body{
    		background: #110D24;
    		color: #FFF;
    	}
    </style>  
</head>
<body>

   <?php echo ($info['content']); ?>
</body>
</html>