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
</head>
<body>
	<div class="wrap">
		   
		<fieldset>
			
			<div class="controls">
				<div>
					<div class="playerzmblbkjP" id="playerzmblbkjP" style="width:450px;height:600px;">
					</div>
				</div>
				<span class="form-required"></span>
			</div>

		</fieldset>
			
		
	</div>
	<script src="/public/js/common.js"></script>
	<script type="text/javascript" src="/public/js/content_addtop.js"></script>
	<script type="text/javascript" src="/public/playback/ckplayer.js" charset="utf-8"></script>
	<script type="text/javascript">
	$(function(){
			var flashvars={
				f:"<?php echo ($info['href']); ?>",//è§éąć°ćrtmp://testlive.anbig.com/5showcam/1737_1487723653
				a:'',//è°çšæ¶çćæ°ïŒćȘæćœs>0çæ¶ćææ
				s:'0',//è°çšæčćŒïŒ0=æźéæčæłïŒf=è§éąć°ćïŒïŒ1=çœććœąćŒ,2=xmlćœąćŒïŒ3=swfćœąćŒ(s>0æ¶f=çœćïŒéćaæ„ćźæćŻčć°ćçç»èŁ)
				c:'0',//æŻćŠèŻ»ćææŹéçœź,0äžæŻïŒ1æŻ
				t:'10|10',//è§éąćŒć§ćæ­æŸswf/ćŸçæ¶çæ¶éŽïŒć€äžȘçšç«çșżéćŒ
				y:'',//èżéæŻäœżçšçœććœąćŒè°çšćčżćć°ćæ¶äœżçšïŒćææŻèŠèźŸçœźlçćŒäžșç©ș
				z:'',//çŒćČćčżćïŒćȘèœæŸäžäžȘïŒswfæ ŒćŒ
				e:'8',//è§éąç»æćçćšäœïŒ0æŻè°çšjsćœæ°ïŒ1æŻćŸȘçŻæ­æŸïŒ2æŻæćæ­æŸćč¶äžäžè°çšćčżćïŒ3æŻè°çšè§éąæšèćèĄšçæä»¶ïŒ4æŻæžé€è§éąæ”ćč¶è°çšjsćèœć1ć·źäžć€ïŒ5æŻæćæ­æŸćč¶äžè°çšæććčżć
				v:'100',//é»èź€éłéïŒ0-100äčéŽ
				p:'0',//è§éąé»èź€0æŻæćïŒ1æŻæ­æŸïŒ2æŻäžć èœœè§éą
				h:'0',	//æ­æŸhttpè§éąæ”æ¶éçšäœç§æćšæčæłïŒ=0äžäœżçšä»»ææćšïŒ=1æŻäœżçšæćłéźćž§ïŒ=2æŻææ¶éŽçčïŒ=3æŻèȘćšć€æ­æä»äč(ćŠæè§éąæ ŒćŒæŻ.mp4ć°±æćłéźćž§ïŒ.flvć°±æćłéźæ¶éŽ)ïŒ=4äčæŻèȘćšć€æ­(ćȘèŠćć«ć­çŹŠmp4ć°±æmp4æ„ïŒćȘèŠćć«ć­çŹŠflvć°±æflvæ„)
				k:'32|63',//æç€șçčæ¶éŽïŒćŠ 30|60éŒ æ ç»èżèżćșŠæ 30ç§ïŒ60ç§äŒæç€șnæćźççžćșçæć­
				n:'èżæŻæç€șçčçćèœïŒćŠæäžéèŠć é€kćnçćŒ|æç€șçčæ”èŻ60ç§',//æç€șçčæć­ïŒè·kéćäœżçšïŒćŠ æç€șçč1|æç€șçč2
				wh:'',//ćźœé«æŻïŒćŻä»„èȘć·±ćźäčè§éąçćźœé«æćźœé«æŻćŠïŒwh:'4:3',æwh:'1080:720'
				lv:'0',//æŻćŠæŻçŽæ­æ”ïŒ=1ćéćźèżćșŠæ 
				loaded:'loadedHandler',//ćœæ­æŸćšć èœœćźæććéèŻ„jsćœæ°loaded
				//è°çšæ­æŸćšçææćæ°ćèĄšç»æ
				//ä»„äžäžșèȘćźäčçæ­æŸćšćæ°çšæ„ćšæä»¶éćŒçšç
				my_title:"<?php echo ($info['title']); ?>",
				my_url:encodeURIComponent(window.location.href)//æŹéĄ”éąć°ć
				//è°çšèȘćźäčæ­æŸćšćæ°ç»æ
			};
			var params={bgcolor:'#FFF',allowFullScreen:true,allowScriptAccess:'always'};									//èżéćźäčæ­æŸćšçć¶ćźćæ°ćŠèæŻèČïŒè·flashvarsäž­çbäžćïŒïŒæŻćŠæŻæćšć±ïŒæŻćŠæŻæäș€äș
			//var video=['http://img.ksbbs.com/asset/Mon_1605/0ec8cc80112a2d6.mp4->video/mp4'];
			var video=['<?php echo ($info['href']); ?>'];
			CKobject.embed('public/playback/ckplayer.swf','playerzmblbkjP','ckplayer_playerzmblbkjP','100%','100%',false,flashvars,video,params);
	})
</script>

</body>
</html>