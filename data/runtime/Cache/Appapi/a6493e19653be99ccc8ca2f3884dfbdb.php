<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="telephone=no" name="format-detection">
    <title>分享</title>
    <link rel="stylesheet" type="text/css" href="/public/appapi/css/share_login.css?t=<?php echo ($time); ?>">

</head>
<body>
    <div class="box">

        <div class="title">注册登录后体验更多精彩瞬间</div>
        
        <div class="phone_mix">
            <span>
                您的邀请码
            </span>
        </div>
        <div class="code_mix">
            <span>
                <input class="code" id="code" value="<?php echo ($code); ?>" readonly="readonly">
                <!-- <div class="getcode" data-code='<?php echo ($code); ?>' onclick="copyinput(this,'code')">复制</div> -->
            </span>
           
        </div>
        <div class="rsg" id='downloadButton'>下载注册领取<?php echo ($invite_tacket); echo ($name_coin); ?>*****</div>
    </div>
    <script type="text/javascript">
        // var uid = '<?php echo ($uid); ?>';
    </script>
    <script src="/public/js/jquery.js"></script>
    <script src="/public/layer/layer.js"></script>
    <!-- <script src="/public/appapi/js/share_login.js"></script> -->
    <script type="text/javascript">
        // $(function(){
        //     $(".rsg").click(function(){
        //         location.href='/index.php?g=Portal&m=Index&a=scanqr';
        //     });

        // });
    </script>

    <!-- 以下为openinstall集成代码，建议在html文档中尽量靠前放置，加快初始化过程 -->
<!-- 强烈建议直接引用下面的cdn加速链接，以得到最及时的更新，我们将持续跟踪各种主流浏览器的变化，提供最好的服务；不推荐将此js文件下载到自己的服务器-->
<script type="text/javascript" charset="UTF-8" src="//res.cdn.openinstall.io/openinstall.js"></script>
<script type="text/javascript">
    //openinstall初始化时将与openinstall服务器交互，应尽可能早的调用
    /*web页面向app传递的json数据(json string/js Object)，应用被拉起或是首次安装时，通过相应的android/ios api可以获取此数据*/
    var data = OpenInstall.parseUrlParams();//openinstall.js中提供的工具函数，解析url中的所有查询参数
    new OpenInstall({
        /*appKey必选参数，openinstall平台为每个应用分配的ID*/
        appKey : "hu6b2r",
        /*可选参数，自定义android平台的apk下载文件名；个别andriod浏览器下载时，中文文件名显示乱码，请慎用中文文件名！*/
        //apkFileName : 'com.fm.openinstalldemo-v2.2.0.apk',
        /*可选参数，是否优先考虑拉起app，以牺牲下载体验为代价*/
        //preferWakeup:true,
        /*自定义遮罩的html*/
        //mask:function(){
        //  return "<div id='openinstall_shadow' style='position:fixed;left:0;top:0;background:rgba(0,255,0,0.5);filter:alpha(opacity=50);width:100%;height:100%;z-index:10000;'></div>"
        //},
        /*openinstall初始化完成的回调函数，可选*/
        onready : function() {
            var m = this, button = document.getElementById("downloadButton");
            button.style.visibility = "visible";

            /*在app已安装的情况尝试拉起app*/
            m.schemeWakeup();
            /*用户点击某个按钮时(假定按钮id为downloadButton)，安装app*/
            button.onclick = function() {
                m.wakeupOrInstall();
                return false;
            }
        }
    }, data);

</script>
    
</body>
</html>