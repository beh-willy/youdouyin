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
<style>
input{
  width:500px;
}
.form-horizontal textarea{
 width:500px;
}
.nav-tabs>.current>a{
    color: #95a5a6;
    cursor: default;
    background-color: #fff;
    border: 1px solid #ddd;
    border-bottom-color: transparent;
}
.nav li
{
	cursor:pointer
}
.nav li:hover
{
	cursor:pointer
}
.hide{
	display:none;
}
</style>


	<div class="wrap js-check-wrap">
		<!-- <ul class="nav nav-tabs">
			<li class="active"><a href="<?php echo U('Config/index');?>">èźŸçœź</a></li>
			<li><a href="<?php echo U('Config/lists');?>">çźĄç</a></li>
			<li><a href="<?php echo U('Config/add');?>">æ·»ć </a></li>
		</ul> -->
		<ul class="nav nav-tabs js-tabs-nav">
			<li><a>çœç«äżĄæŻ</a></li>
			<li><a>ç»ćœćŒćł</a></li>
			<li><a>APPçæŹçźĄç</a></li>
			<li><a>ćäș«èźŸçœź</a></li>
			<!-- <li><a>PCæšæ”èźŸçœź</a></li> -->
			<li style="display:none;"><a>çŽæ­çźĄç</a></li>
		</ul>
		
		<form method="post" class="form-horizontal js-ajax-form" action="<?php echo U('Config/set_post');?>">
			<input type="hidden" name="post['id']" value="1">
			<div class="js-tabs-content">
				<!-- çœç«äżĄæŻ -->
				<div>
					<fieldset>
						<div class="control-group">
							<label class="control-label">çœç«ç»Žæ€</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[maintain_switch]" <?php if(($config['maintain_switch']) == "0"): ?>checked="checked"<?php endif; ?>>ćłé­</label>
								<label class="radio inline"><input type="radio" value="1" name="post[maintain_switch]" <?php if(($config['maintain_switch']) == "1"): ?>checked="checked"<?php endif; ?>>ćŒćŻ</label>
								<label class="checkbox inline">çœç«ç»Žæ€ćŒćŻćïŒæ æłćŒćŻçŽæ­ïŒèżć„çŽæ­éŽ</label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">ç»Žæ€æç€ș</label>
							<div class="controls">				
								<textarea name="post[maintain_tips]"><?php echo ($config['maintain_tips']); ?></textarea>ç»Žæ€æç€șäżĄæŻïŒ200ć­ä»„ćïŒ
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">çœç«æ éą</label>
							<div class="controls">				
								<input type="text" name="post[sitename]" value="<?php echo ($config['sitename']); ?>">çœç«æ éą
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">ćŻŒèȘæéźćç§°</label>
							<div class="controls">				
								<input type="text" name="post[sitebarname]" value="<?php echo ($config['sitebarname']); ?>">ćŻŒèȘæéźćç§°ïŒä»„|ććŒ
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">çœç«ćć</label>
							<div class="controls">				
								<input type="text" name="post[site]" value="<?php echo ($config['site']); ?>"> çœç«ććïŒhttp:// ćŒć€Ž  ć°ŸéšäžćžŠ /
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">æ„ćŁćć</label>
							<div class="controls">				
								<input type="text" name="post[site_url]" value="<?php echo ($config['site_url']); ?>"> æ„ćŁèźżéźćć
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">çæäżĄæŻ</label>
							<div class="controls">				
								<textarea name="post[copyright]"><?php echo ($config['copyright']); ?></textarea>çæäżĄæŻïŒ200ć­ä»„ćïŒ
							</div>
						</div>

						<div class="control-group">
							<label class="control-label">ćčżćéŽéæ°</label>
							<div class="controls">
								<input type="text" name="post[ad_space]" value="<?php echo ($config['ad_space']); ?>"> ćčżćéŽéæ°
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">éćžćç§°</label>
							<div class="controls">				
								<input type="text" name="post[name_coin]" value="<?php echo ($config['name_coin']); ?>">è§éąçčè”ćŻćæąèæéćžćç§°
							</div>
						</div>
						<!-- <div class="control-group">
							<label class="control-label">éćžćç§°</label>
							<div class="controls">				
								<input type="text" name="post[name_votes]" value="<?php echo ($config['name_votes']); ?>">è§éąçčè”ćŻćæąèæç„šćç§°
							</div>
						</div> -->
						<div class="control-group">
							<label class="control-label">ćźąæQQ</label>
							<div class="controls">				
								<input type="text" name="post[qq]" value="<?php echo ($config['qq']); ?>">ćźæčćźąæQQ
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">ćźąæwx</label>
							<div class="controls">				
								<input type="text" name="post[wxin]" value="<?php echo ($config['wxin']); ?>">ćźæčćźąæćŸźäżĄ
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">ćźąæç”èŻ</label>
							<div class="controls">				
								<input type="text" name="post[mobile]" value="<?php echo ($config['mobile']); ?>">ćźæčćźąæç”èŻ
							</div>
						</div>
						<!-- <div class="control-group">
							<label class="control-label">éćäžéȘæç€ș</label>
							<div class="controls">				
									<input type="text" name="post[enter_tip_level]" value="<?php echo ($config['enter_tip_level']); ?>"> çšæ·ç­çș§ć€§äșèŻ„ćŒæ¶ïŒèżć„æżéŽæéćäžéȘææ
							</div>
						</div> -->
					</fieldset>
				</div>
								<!-- ç»ćœćŒćł -->
				<div>
					<fieldset>
						<div class="control-group">
							<label class="control-label">ç»ćœæčćŒ</label>
							<div class="controls">		
                                <?php $qq='qq'; $wx='wx'; $sina='sina'; $facebook='facebook'; $twitter='twitter'; ?>
								<label class="checkbox inline"><input type="checkbox" value="qq" name="post[login_type][]" <?php if(in_array(($qq), is_array($config['login_type'])?$config['login_type']:explode(',',$config['login_type']))): ?>checked="checked"<?php endif; ?>>QQ</label>
								<label class="checkbox inline"><input type="checkbox" value="wx" name="post[login_type][]" <?php if(in_array(($wx), is_array($config['login_type'])?$config['login_type']:explode(',',$config['login_type']))): ?>checked="checked"<?php endif; ?>>ćŸźäżĄ</label>
							<!-- 	<label class="checkbox inline"><input type="checkbox" value="sina" name="post[login_type][]" <?php if(in_array(($sina), is_array($config['login_type'])?$config['login_type']:explode(',',$config['login_type']))): ?>checked="checked"<?php endif; ?>>æ°æ”ȘćŸźć</label> -->
								<label class="checkbox inline"><input type="checkbox" value="facebook" name="post[login_type][]" <?php if(in_array(($facebook), is_array($config['login_type'])?$config['login_type']:explode(',',$config['login_type']))): ?>checked="checked"<?php endif; ?>>FaceBook</label>
								<label class="checkbox inline"><input type="checkbox" value="twitter" name="post[login_type][]" <?php if(in_array(($twitter), is_array($config['login_type'])?$config['login_type']:explode(',',$config['login_type']))): ?>checked="checked"<?php endif; ?>>Twitter</label>
								<label class="checkbox inline">ç»ćœæčćŒ</label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">ćäș«æčćŒ</label>
							<div class="controls">		
                                <?php $share_qq='qq'; $share_qzone='qzone'; $share_wx='wx'; $share_wchat='wchat'; $share_sina='sina'; $share_facebook='facebook'; $share_twitter='twitter'; ?>
								<label class="checkbox inline"><input type="checkbox" value="wx" name="post[share_type][]" <?php if(in_array(($share_wx), is_array($config['share_type'])?$config['share_type']:explode(',',$config['share_type']))): ?>checked="checked"<?php endif; ?>>ćŸźäżĄ</label>
								<label class="checkbox inline"><input type="checkbox" value="wchat" name="post[share_type][]" <?php if(in_array(($share_wchat), is_array($config['share_type'])?$config['share_type']:explode(',',$config['share_type']))): ?>checked="checked"<?php endif; ?>>ćŸźäżĄæćć</label>
								<label class="checkbox inline"><input type="checkbox" value="qzone" name="post[share_type][]" <?php if(in_array(($share_qzone), is_array($config['share_type'])?$config['share_type']:explode(',',$config['share_type']))): ?>checked="checked"<?php endif; ?>>QQç©șéŽ</label>
								<label class="checkbox inline"><input type="checkbox" value="qq" name="post[share_type][]" <?php if(in_array(($share_qq), is_array($config['share_type'])?$config['share_type']:explode(',',$config['share_type']))): ?>checked="checked"<?php endif; ?>>QQ</label>
							<!-- 	<label class="checkbox inline"><input type="checkbox" value="sina" name="post[share_type][]" <?php if(in_array(($share_sina), is_array($config['share_type'])?$config['share_type']:explode(',',$config['share_type']))): ?>checked="checked"<?php endif; ?>>æ°æ”ȘćŸźć</label> -->
								<label class="checkbox inline"><input type="checkbox" value="twitter" name="post[share_type][]" <?php if(in_array(($share_twitter), is_array($config['share_type'])?$config['share_type']:explode(',',$config['share_type']))): ?>checked="checked"<?php endif; ?>>Twitter</label>
								<label class="checkbox inline"><input type="checkbox" value="facebook" name="post[share_type][]" <?php if(in_array(($share_facebook), is_array($config['share_type'])?$config['share_type']:explode(',',$config['share_type']))): ?>checked="checked"<?php endif; ?>>FaceBook</label>
								<label class="checkbox inline">ćäș«æčćŒ</label>
							</div>
						</div>
					</fieldset>
				</div>
				<!-- APPçæŹçźĄç -->
				<div>
					<fieldset>
						<div class="control-group">
							<label class="control-label">APKçæŹć·</label>
							<div class="controls">				
								<input type="text" name="post[apk_ver]" value="<?php echo ($config['apk_ver']); ?>"> ćźćAPPææ°ççæŹć·ïŒèŻ·ćżéæäżźæč
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">APKäžèœœéŸæ„</label>
							<div class="controls">				
								<input type="text" name="post[apk_url]" value="<?php echo ($config['apk_url']); ?>"> ćźćææ°çAPKäžèœœéŸæ„
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">APkæŽæ°èŻŽæ</label>
							<div class="controls">				
								<textarea name="post[apk_des]"><?php echo ($config['apk_des']); ?></textarea>APkæŽæ°èŻŽæïŒ200ć­ä»„ćïŒ
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">IPAçæŹć·</label>
							<div class="controls">				
								<input type="text" name="post[ipa_ver]" value="<?php echo ($config['ipa_ver']); ?>"> IOS APPææ°ççæŹć·ïŒèŻ·ćżéæäżźæč
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">IPAäžæ¶çæŹć·</label>
							<div class="controls">				
								<input type="text" name="post[ios_shelves]" value="<?php echo ($config['ios_shelves']); ?>"> IOSäžæ¶ćźĄæ žäž­çæŹççæŹć·(çšäșäžæ¶æéŽéèäžæ¶çæŹéšććèœ,äžèŠćIPAçæŹć·çžć)
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">IPAäžèœœéŸæ„</label>
							<div class="controls">				
								<input type="text" name="post[ipa_url]" value="<?php echo ($config['ipa_url']); ?>"> IOSææ°çIPAäžèœœéŸæ„
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">IPAæŽæ°èŻŽæ</label>
							<div class="controls">				
								<textarea name="post[ipa_des]"><?php echo ($config['ipa_des']); ?></textarea>IPAæŽæ°èŻŽæïŒ200ć­ä»„ćïŒ
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label">ćŸç</label>
							<div class="controls">
								<div>
									<input type="hidden" name="post[qr_url]" id="thumb2" value="<?php echo ($config['qr_url']); ?>">
									<a href="javascript:void(0);" onclick="flashupload('thumb_images', 'éä»¶äžäŒ ','thumb2',thumb_images,'1,jpg|jpeg|gif|png|bmp,1,,,1','','','');return false;">
										<?php if($config['qr_url'] != ''): ?><img src="<?php echo ($config['qr_url']); ?>" id="thumb2_preview" width="135" style="cursor: hand" />
										<?php else: ?>
												<img src="/admin/themes/simplebootx/Public/assets/images/default-thumbnail.png" id="thumb2_preview" width="135" style="cursor: hand" /><?php endif; ?>
									</a>
									<input type="button" class="btn btn-small" onclick="$('#thumb2_preview').attr('src','/admin/themes/simplebootx/Public/assets/images/default-thumbnail.png');$('#thumb2').val('');return false;" value="ćæ¶ćŸç">
								</div>
								<span class="form-required"></span>
							</div>
						</div>
					</fieldset>
				</div>
				<!-- ćäș«èźŸçœź -->
				<div>
					<fieldset>
						<div class="control-group" style="display:none;">
							<label class="control-label">ćŸźäżĄæšćčżćć</label>
							<div class="controls">				
								<input type="text" name="post[wx_siteurl]" value="<?php echo ($config['wx_siteurl']); ?>"> http:// ćŒć€Ž ćæ°ćŒäžșçšæ·ID
							</div>
						</div>
						<div class="control-group" style="display:none;">
							<label class="control-label">ćäș«æ éą</label>
							<div class="controls">				
								<input type="text" name="post[share_title]" value="<?php echo ($config['share_title']); ?>"> ćäș«æ éą
							</div>
						</div>
						<div class="control-group" style="display:none;">
							<label class="control-label">ćäș«èŻæŻ</label>
							<div class="controls">				
								<input type="text" name="post[share_des]" value="<?php echo ($config['share_des']); ?>"> ćäș«èŻæŻ
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">AndroidAPPäžèœœéŸæ„</label>
							<div class="controls">				
								<input type="text" name="post[app_android]" value="<?php echo ($config['app_android']); ?>"> ćäș«çšAndroid APP äžèœœéŸæ„
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">IOSAPPäžèœœéŸæ„</label>
							<div class="controls">				
								<input type="text" name="post[app_ios]" value="<?php echo ($config['app_ios']); ?>"> ćäș«çšIOS APP äžèœœéŸæ„
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label">ćäș«ćéĄ”æ éą</label>
							<div class="controls">				
								<input type="text" name="post[video_share_title]" value="<?php echo ($config['video_share_title']); ?>"> ćäș«ćéĄ”æ éą
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">ćäș«ćéĄ”èŻŽæ</label>
							<div class="controls">				
								<script type="text/plain" id="container" name="post[video_share_explain]"><?php echo ($config['video_share_explain']); ?></script> ćäș«ćéĄ”èŻŽæ
							</div>
						</div>
						<textarea id="content1" name="post[video_share_explain]" style="display: none;"></textarea>
						<div class="control-group">
							<label class="control-label">ćäș«éŸæ„èŻæŻ</label>
							<div class="controls">				
								<input type="text" name="post[video_share_des]" value="<?php echo ($config['video_share_des']); ?>"> ćäș«éŸæ„èŻæŻ
							</div>
						</div>
						
					</fieldset>
				</div>
				<!-- PCæšæ”èźŸçœź -->
				<!-- <div>
					<fieldset>
						<div class="control-group">
							<label class="control-label">æšæ”ćèŸšçćźœćșŠ</label>
							<div class="controls">				
								<input type="text" name="post[live_width]" value="<?php echo ($config['live_width']); ?>">PCäž»æ­ç«ŻflashćèŸšè·ŻćźœćșŠ
							</div>
						</div><div class="control-group">
							<label class="control-label">æšæ”ćèŸšçé«ćșŠ</label>
							<div class="controls">				
								<input type="text" name="post[live_height]" value="<?php echo ($config['live_height']); ?>">PCäž»æ­ç«ŻflashćèŸšè·Żé«ćșŠ
							</div>
						</div><div class="control-group">
							<label class="control-label">ćłéźćž§</label>
							<div class="controls">				
								<input type="text" name="post[keyframe]" value="<?php echo ($config['keyframe']); ?>">PCäž»æ­ç«Żflashćłéźćž§ïŒæšè15-30ïŒ
							</div>
						</div><div class="control-group">
							<label class="control-label">fpsćž§æ°</label>
							<div class="controls">				
								<input type="text" name="post[fps]" value="<?php echo ($config['fps']); ?>">PCäž»æ­ç«Żflash FPSćž§æ°ïŒæšè30ïŒ
							</div>
						</div><div class="control-group">
							<label class="control-label">ćèŽš</label>
							<div class="controls">				
								<input type="text" name="post[quality]" value="<?php echo ($config['quality']); ?>">PCäž»æ­ç«Żflash ç»éąćèŽšïŒæšè90-100ïŒ
							</div>
						</div>
						
					</fieldset>
				</div> -->
				<!-- çŽæ­çźĄç -->
				<div style="display:none;">
					<fieldset>
						<div class="control-group">
							<label class="control-label">æżéŽç±»ć</label>
							<div class="controls">		
                                <?php $type_0='0;æźéæżéŽ'; $type_1='1;ćŻç æżéŽ'; $type_2='2;éšç„šæżéŽ'; $type_3='3;èźĄæ¶æżéŽ'; ?>
								<label class="checkbox inline hide"><input type="checkbox" value="0;æźéæżéŽ" name="post[live_type][]" <?php if(in_array(($type_0), is_array($config['live_type'])?$config['live_type']:explode(',',$config['live_type']))): ?>checked="checked"<?php endif; ?> readonly>æźéæżéŽ</label>
								<label class="checkbox inline"><input type="checkbox" value="1;ćŻç æżéŽ" name="post[live_type][]" <?php if(in_array(($type_1), is_array($config['live_type'])?$config['live_type']:explode(',',$config['live_type']))): ?>checked="checked"<?php endif; ?>>ćŻç æżéŽ</label>
								<label class="checkbox inline"><input type="checkbox" value="2;éšç„šæżéŽ" name="post[live_type][]" <?php if(in_array(($type_2), is_array($config['live_type'])?$config['live_type']:explode(',',$config['live_type']))): ?>checked="checked"<?php endif; ?>>éšç„šæżéŽ</label>
								<label class="checkbox inline"><input type="checkbox" value="3;èźĄæ¶æżéŽ" name="post[live_type][]" <?php if(in_array(($type_3), is_array($config['live_type'])?$config['live_type']:explode(',',$config['live_type']))): ?>checked="checked"<?php endif; ?>>èźĄæ¶æżéŽ</label>
								<label class="checkbox inline">æżéŽç±»ć</label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">èźĄæ¶çŽæ­æ¶èŽč</label>
							<div class="controls">				
									<input type="text" name="post[live_time_coin]" value="<?php echo ($config['live_time_coin']); ?>" > èźĄæ¶çŽæ­æ¶èŽčïŒä»·æ ŒæąŻćșŠçš , ććČ
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
	<script type="text/javascript" src="/public/js/content_addtop.js"></script>
	<script type="text/javascript" src="/public/js/ueditor/ueditor.config.js"></script>
	<script type="text/javascript" src="/public/js/ueditor/ueditor.all.min.js"></script>
	 <!-- ćźäŸćçŒèŸćš -->
    <script type="text/javascript">
        var ue = UE.getEditor('container');

        ue.addListener("blur",function(){
		  var ue = UE.getEditor('container');
		     console.log(111);
		  var desc = document.getElementById("content1");
		  desc.value=ue.getContent();
		  })
    </script>
</body>
</html>