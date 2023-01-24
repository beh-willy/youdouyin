<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="telephone=no" name="format-detection">
    <title>关于<?php echo ($sitename); ?></title>
    <link rel="stylesheet" type="text/css" href="/public/appapi/about/css/about.css?t=<?php echo ($time); ?>">

</head>
<body>
    <div class="icon_area">
        <img src="/icon.png">
    </div>
    <div class="edition">版本号 <?php echo ($version); ?></div>
    <ul class="artice_list">
        <?php if(is_array($articleList)): foreach($articleList as $key=>$vo): ?><a href="/index.php?g=portal&m=page&a=news&id=<?php echo ($vo['id']); ?>">
                <li><?php echo ($vo['post_title']); ?></li>
            </a><?php endforeach; endif; ?>
        <a href="/index.php?g=portal&m=page&a=questions">
            <li><?php echo ($name); ?></li>
        </a>
        <a href="/index.php?g=Appapi&m=service&a=index">
            <li>联系客服</li>
        </a>
        <a href="/index.php?g=Appapi&m=feedback&a=index&uid=<?php echo ($uid); ?>&token=<?php echo ($token); ?>">
            <li>我要反馈</li>
        </a>

    </ul>
<script type="text/javascript" src="/public/js/jquery.js"></script>
<script type="text/javascript" src="/public/appapi/about/js/about.js?t=<?php echo ($time); ?>"></script>
<script type="text/javascript" src="/public/layer/layer.js"></script>
</body>
</html>