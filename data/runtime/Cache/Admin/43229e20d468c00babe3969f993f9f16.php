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
        <li class="active"><a href="<?php echo U('Configprivate/index');?>">èźŸçœź</a></li>
        <li><a href="<?php echo U('Configprivate/lists');?>">çźĄç</a></li>

        <li><a href="<?php echo U('Configprivate/add');?>">æ·»ć </a></li>
    </ul>
    <div class="form-actions">
        <span style="color:#ff0000">æç€șïŒæ°ć èźŸçœźèŻ·æžç©șäžçŒć­ïŒ</span>
    </div> -->
	<ul class="nav nav-tabs js-tabs-nav">
		<li><a>ćșæŹèźŸçœź</a></li>
		<li><a>ç»ćœéçœź</a></li>
		<li><a>æç°éçœź</a></li>
		<li><a>æšééçœź</a></li>
		<li><a>æŻä»çźĄç</a></li>
		<li><a>äșć­ćšèźŸçœź</a></li>
		<li><a>è§éąćæ°èźŸçœź</a></li>
		<li><a>ćæąæç°èœŹćéçœź</a></li>
		<li><a>ćééçœź</a></li>


	</ul>

	<form method="post" class="form-horizontal js-ajax-form" action="<?php echo U('Configprivate/set_post');?>">
		<input type="hidden" name="post['id']" value="1">

		<div class="js-tabs-content">
			<!-- ćșæŹéçœź -->
			<div>
				<fieldset>
					<div class="control-group">
						<label class="control-label">çŒć­ćŒćł</label>
						<div class="controls">
							<label class="radio inline"><input type="radio" value="0" name="post[cache_switch]" <?php if(($config['cache_switch']) == "0"): ?>checked="checked"<?php endif; ?>>ćłé­</label>
							<label class="radio inline"><input type="radio" value="1" name="post[cache_switch]" <?php if(($config['cache_switch']) == "1"): ?>checked="checked"<?php endif; ?>>ćŒćŻ</label>
							<label class="checkbox inline"></label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">çŒć­æ¶éŽ</label>
						<div class="controls">
							<input type="text" name="post[cache_time]" value="<?php echo ($config['cache_time']); ?>">çœç«æ°æźççŒć­æ¶éŽïŒç§ïŒ
						</div>
					</div>


					<div class="control-group">
                        <label class="control-label">æłšćć„ć±</label>
                        <div class="controls">
                            <input type="text" name="post[reg_reward]" value="<?php echo ($config['reg_reward']); ?>"> æ°çšæ·æłšćć„ć±ïŒæŽæ°ïŒ
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label">èź€èŻéć¶</label>
						<div class="controls">
							<label class="radio inline"><input type="radio" value="0" name="post[auth_islimit]" <?php if(($config['auth_islimit']) == "0"): ?>checked="checked"<?php endif; ?>>ćłé­</label>
							<label class="radio inline"><input type="radio" value="1" name="post[auth_islimit]" <?php if(($config['auth_islimit']) == "1"): ?>checked="checked"<?php endif; ?>>ćŒćŻ</label>
							<label class="checkbox inline">çšæ·æŻćŠéèŠèș«ä»œèź€èŻ</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">æȘćłæłšćéæ¶æŻæĄæ°ćŒćł</label>
						<div class="controls">
							<label class="radio inline"><input type="radio" value="0" name="post[private_letter_switch]" <?php if(($config['private_letter_switch']) == "0"): ?>checked="checked"<?php endif; ?>>ćłé­</label>
							<label class="radio inline"><input type="radio" value="1" name="post[private_letter_switch]" <?php if(($config['private_letter_switch']) == "1"): ?>checked="checked"<?php endif; ?>>ćŒćŻ</label>
							<label class="checkbox inline"></label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">æȘćłæłšćŻćéæ¶æŻæĄæ°</label>
						<div class="controls">
							<input type="text" name="post[private_letter_nums]" value="<?php echo ($config['private_letter_nums']); ?>"> æȘćłæłšçšæ·ćŻćéæ¶æŻæĄæ°ïŒæŽæ°ïŒ
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">è§éąćźĄæ žćŒćł</label>
						<div class="controls">
							<label class="radio inline"><input type="radio" value="0" name="post[video_audit_switch]" <?php if(($config['video_audit_switch']) == "0"): ?>checked="checked"<?php endif; ?>>ćłé­</label>
							<label class="radio inline"><input type="radio" value="1" name="post[video_audit_switch]" <?php if(($config['video_audit_switch']) == "1"): ?>checked="checked"<?php endif; ?>>ćŒćŻ</label>
							<label class="checkbox inline"></label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">ç€Ÿćș/ćć­ćźĄæ žćŒćł</label>
						<div class="controls">
							<label class="radio inline"><input type="radio" value="0" name="post[community_audit_switch]" <?php if(($config['community_audit_switch']) == "0"): ?>checked="checked"<?php endif; ?>>ćłé­</label>
							<label class="radio inline"><input type="radio" value="1" name="post[community_audit_switch]" <?php if(($config['community_audit_switch']) == "1"): ?>checked="checked"<?php endif; ?>>ćŒćŻ</label>
							<label class="checkbox inline"></label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">éèŻ·ä»äșșćæčćŻè·ćŸéćžæ°</label>
						<div class="controls">
							<input type="text" name="post[invite_tacket]" value="<?php echo ($config['invite_tacket']); ?>"> ïŒæŽæ°ïŒ
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">éèŻ·ä»äșșè” éè§çć€©æ°</label>
						<div class="controls">
							<input type="text" name="post[invite_add_day]" value="<?php echo ($config['invite_add_day']); ?>"> ïŒæŽæ°ïŒ
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">ć ćŻç§é„</label>
						<div class="controls">
							<input type="text" name="post[signature]" value="<?php echo ($config['signature']); ?>"> çšäșæäœćźćšæ§çéȘèŻ(ćĄ«ćæ°ć­ćć­æŻçç»ćïŒéżćșŠæäœ6äœ)
						</div>
					</div>

				</fieldset>
			</div>
			<!-- ç»ćœéçœź -->
			<div>
				<fieldset>
					<!-- <div class="control-group">
                        <label class="control-label">ç»ćœć„ć±ćŒćł</label>
                        <div class="controls">
                            <label class="radio inline"><input type="radio" value="0" name="post[bonus_switch]" <?php if(($config['bonus_switch']) == "0"): ?>checked="checked"<?php endif; ?>>ćłé­</label>
                            <label class="radio inline"><input type="radio" value="1" name="post[bonus_switch]" <?php if(($config['bonus_switch']) == "1"): ?>checked="checked"<?php endif; ?>>ćŒćŻ</label>
                            <label class="checkbox inline"></label>
                        </div>
                    </div> -->

					<div class="control-group">
						<label class="control-label">ćŸźäżĄćŹäŒćčłć°Appid</label>
						<div class="controls">
							<input type="text" name="post[login_wx_appid]" value="<?php echo ($config['login_wx_appid']); ?>"> ćŸźäżĄćŹäŒćčłć°Appid
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">ćŸźäżĄćŹäŒćčłć°AppSecret</label>
						<div class="controls">
							<input type="text" name="post[login_wx_appsecret]" value="<?php echo ($config['login_wx_appsecret']); ?>"> ćŸźäżĄćŹäŒćčłć°AppSecret
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">ç­äżĄćźæ°æźćŒćł</label>
						<div class="controls">
							<label class="radio inline"><input type="radio" value="0" name="post[sendcode_switch]" <?php if(($config['sendcode_switch']) == "0"): ?>checked="checked"<?php endif; ?>>ćłé­</label>
							<label class="radio inline"><input type="radio" value="1" name="post[sendcode_switch]" <?php if(($config['sendcode_switch']) == "1"): ?>checked="checked"<?php endif; ?>>ćŒćŻ</label>
							<label class="checkbox inline">ç­äżĄćźæ°æźćłé­ïŒéȘèŻç é»èź€123456</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">ç­äżĄćźćčłć°ćžć·</label>
						<div class="controls">
							<input type="text" name="post[ihuyi_account]" value="<?php echo ($config['ihuyi_account']); ?>"> ç­äżĄéȘèŻç 
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">ç­äżĄćźćčłć°ćŻç </label>
						<div class="controls">
							<input type="text" name="post[ihuyi_ps]" value="<?php echo ($config['ihuyi_ps']); ?>"> ç­äżĄéȘèŻç 
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">ç­äżĄéȘèŻç IPéć¶ćŒćł</label>
						<div class="controls">
							<label class="radio inline"><input type="radio" value="0" name="post[iplimit_switch]" <?php if(($config['iplimit_switch']) == "0"): ?>checked="checked"<?php endif; ?>>ćłé­</label>
							<label class="radio inline"><input type="radio" value="1" name="post[iplimit_switch]" <?php if(($config['iplimit_switch']) == "1"): ?>checked="checked"<?php endif; ?>>ćŒćŻ</label>
							<label class="checkbox inline">ç­äżĄéȘèŻç IPéć¶ćŒćł</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">ç­äżĄéȘèŻç IPéć¶æŹĄæ°</label>
						<div class="controls">
							<input type="text" name="post[iplimit_times]" value="<?php echo ($config['iplimit_times']); ?>"> ćäžIPæŻć€©ćŻä»„ćééȘèŻç çæć€§æŹĄæ°
						</div>
					</div>
				</fieldset>
			</div>

			<!-- æç°éçœź -->
			<div>
                <fieldset>
                    <div class="control-group">
                        <label class="control-label">æç°æŻäŸ</label>
                        <div class="controls">
                            <input type="text" name="post[cash_rate]" value="<?php echo ($config['cash_rate']); ?>"> æç°äžćäșșććžéèŠçç„šæ°
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">æç°æäœéąćșŠïŒćïŒ</label>
                        <div class="controls">
                            <input type="text" name="post[cash_min]" value="<?php echo ($config['cash_min']); ?>"> ćŻæç°çæć°éąćșŠïŒäœäșèŻ„éąćșŠæ æłäœç°
                        </div>
                    </div>
                </fieldset>
            </div>
			<!-- äžæčéçœź -->
			<div>
				<fieldset>
					<div class="control-group">
						<label class="control-label">æćæšéæšĄćŒ</label>
						<div class="controls">
							<label class="radio inline"><input type="radio" value="0" name="post[jpush_sandbox]" <?php if(($config['jpush_sandbox']) == "0"): ?>checked="checked"<?php endif; ?>>ćŒć</label>
							<label class="radio inline"><input type="radio" value="1" name="post[jpush_sandbox]" <?php if(($config['jpush_sandbox']) == "1"): ?>checked="checked"<?php endif; ?>>çäș§</label>
							<label class="checkbox inline">æćæšéæšĄćŒ</label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">æćæšéAPP_KEY</label>
						<div class="controls">
							<input type="text" name="post[jpush_key]" value="<?php echo ($config['jpush_key']); ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">æćæšémaster_secret</label>
						<div class="controls">
							<input type="text" name="post[jpush_secret]" value="<?php echo ($config['jpush_secret']); ?>">
						</div>
					</div>
				</fieldset>
			</div>
			<!-- æŻä»çźĄç -->
			<div>
                <fieldset>
                    <div class="control-group">
                        <label class="control-label">æŻä»ćźAPP</label>
                        <div class="controls">
                            <label class="radio inline"><input type="radio" value="0" name="post[aliapp_switch]" <?php if(($config['aliapp_switch']) == "0"): ?>checked="checked"<?php endif; ?>>ćłé­</label>
                            <label class="radio inline"><input type="radio" value="1" name="post[aliapp_switch]" <?php if(($config['aliapp_switch']) == "1"): ?>checked="checked"<?php endif; ?>>ćŒćŻ</label>
                            <label class="checkbox inline">æŻä»ćźAPPæŻä»æŻćŠćŒćŻ</label>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">ćäœèèș«ä»œID</label>
                        <div class="controls">
                            <input type="text" name="post[aliapp_partner]" value="<?php echo ($config['aliapp_partner']); ?>">æŻä»ćźćäœèèș«ä»œID
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">æŻä»ćźćžć·</label>
                        <div class="controls">
                            <input type="text" name="post[aliapp_seller_id]" value="<?php echo ($config['aliapp_seller_id']); ?>">æŻä»ćźç»ćœèŽŠć·
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">æŻä»ćźćźććŻé„</label>
                        <div class="controls">
                                <textarea name="post[aliapp_key_android]"><?php echo ($config['aliapp_key_android']); ?></textarea>æŻä»ćźćźććŻé„
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">æŻä»ćźèčæćŻé„</label>
                        <div class="controls">
                            <textarea name="post[aliapp_key_ios]"><?php echo ($config['aliapp_key_ios']); ?></textarea>æŻä»ćźèčæćŻé„
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">æŻä»ćźæ ĄéȘç </label>
                        <div class="controls">
                            <input type="text" name="post[aliapp_check]" value="<?php echo ($config['aliapp_check']); ?>"> æŻä»ćźæ ĄéȘç ïŒæ«ç æŻä»ïŒ
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">èčææŻä»æšĄćŒ</label>
                        <div class="controls">
                            <label class="radio inline"><input type="radio" value="0" name="post[ios_sandbox]" <?php if(($config['ios_sandbox']) == "0"): ?>checked="checked"<?php endif; ?>>æČç</label>
                            <label class="radio inline"><input type="radio" value="1" name="post[ios_sandbox]" <?php if(($config['ios_sandbox']) == "1"): ?>checked="checked"<?php endif; ?>>çäș§</label>
                            <label class="checkbox inline">èčææŻä»æšĄćŒ</label>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">æŻä»ćźPC</label>
                        <div class="controls">
                            <label class="radio inline"><input type="radio" value="0" name="post[aliapp_pc]" <?php if(($config['aliapp_pc']) == "0"): ?>checked="checked"<?php endif; ?>>ćłé­</label>
                            <label class="radio inline"><input type="radio" value="1" name="post[aliapp_pc]" <?php if(($config['aliapp_pc']) == "1"): ?>checked="checked"<?php endif; ?>>ćŒćŻ</label>
                            <label class="checkbox inline">æŻä»ćźPCæ«ç æŻä»æŻćŠćŒćŻ</label>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">ćŸźäżĄæŻä»PC</label>
                        <div class="controls">
                            <label class="radio inline"><input type="radio" value="0" name="post[wx_switch_pc]" <?php if(($config['wx_switch_pc']) == "0"): ?>checked="checked"<?php endif; ?>>ćłé­</label>
                            <label class="radio inline"><input type="radio" value="1" name="post[wx_switch_pc]" <?php if(($config['wx_switch_pc']) == "1"): ?>checked="checked"<?php endif; ?>>ćŒćŻ</label>
                            <label class="checkbox inline">ćŸźäżĄæŻä»PC æŻćŠćŒćŻ</label>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">ćŸźäżĄæŻä»</label>
                        <div class="controls">
                            <label class="radio inline"><input type="radio" value="0" name="post[wx_switch]" <?php if(($config['wx_switch']) == "0"): ?>checked="checked"<?php endif; ?>>ćłé­</label>
                            <label class="radio inline"><input type="radio" value="1" name="post[wx_switch]" <?php if(($config['wx_switch']) == "1"): ?>checked="checked"<?php endif; ?>>ćŒćŻ</label>
                            <label class="checkbox inline">ćŸźäżĄæŻä»ćŒćł</label>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">ćŒæŸćčłć°èŽŠć·AppID</label>
                        <div class="controls">
                            <input type="text" name="post[wx_appid]" value="<?php echo ($config['wx_appid']); ?>">ćŸźäżĄćŒæŸćčłć°èŽŠć·AppID
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">ćŸźäżĄćșçšappsecret</label>
                        <div class="controls">
                            <input type="text" name="post[wx_appsecret]" value="<?php echo ($config['wx_appsecret']); ?>">ćŸźäżĄćșçšappsecret
                        </div>
                    </div>
					<div class="control-group">
                        <label class="control-label">ćŸźäżĄćæ·ć·mchid</label>
                        <div class="controls">
                            <input type="text" name="post[wx_mchid]" value="<?php echo ($config['wx_mchid']); ?>">ćŸźäżĄćæ·ć·mchid
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">ćŸźäżĄćŻé„key</label>
                        <div class="controls">
                            <input type="text" name="post[wx_key]" value="<?php echo ($config['wx_key']); ?>">ćŸźäżĄćŻé„key
                        </div>
                    </div>
					<div class="control-group">
						<label class="control-label">äžæčæŻä»mchid</label>
						<div class="controls">
							<input type="text" name="post[third_mchid]" value="<?php echo ($config['third_mchid']); ?>">äžæčæŻä»ćæ·ć·mchid
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">äžæčæŻä»key</label>
						<div class="controls">
							<input type="text" name="post[third_key]" value="<?php echo ($config['third_key']); ?>">äžæčæŻä»ćŻé„key
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">äžæčæŻä»éé</label>
						<div class="controls">
							<input type="text" name="post[third_channl]" value="<?php echo ($config['third_channl']); ?>">äžæčæŻä»éé
						</div>
					</div>
                </fieldset>
            </div>


			<!-- èŸèźŻäșć­ćšèźŸçœź -->
			<div>
				<fieldset>
					<div class="control-group">
						<label class="control-label">éæ©ć­ćšæčćŒ</label>
						<div class="controls">

							<label class="radio inline"><input type="radio" value="1" name="post[cloudtype]" <?php if(($config['cloudtype']) == "1"): ?>checked="checked"<?php endif; ?>>äžçäșć­ćš</label>
							<label class="radio inline"><input type="radio" value="2" name="post[cloudtype]" <?php if(($config['cloudtype']) == "2"): ?>checked="checked"<?php endif; ?>>èŸèźŻäșć­ćš</label>
							<label class="radio inline"><input type="radio" value="3" name="post[cloudtype]" <?php if(($config['cloudtype']) == "3"): ?>checked="checked"<?php endif; ?>>æŹć°ć­ćš</label>
							<label class="checkbox inline"></label>
						</div>
					</div>




					<div class="control-group">
						<label class="control-label">äžçäșć­ćšaccessKey</label>
						<div class="controls">
							<input type="text" class="input mr5" name="post[qiniu_accesskey]" value="<?php echo ($config['qiniu_accesskey']); ?>">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">äžçäșć­ćšsecretKey</label>
						<div class="controls">
							<input type="text" class="input mr5" name="post[qiniu_secretkey]" value="<?php echo ($config['qiniu_secretkey']); ?>">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">äžçäșć­ćšbucket</label>
						<div class="controls">
							<input type="text" class="input mr5" name="post[qiniu_bucket]" value="<?php echo ($config['qiniu_bucket']); ?>">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">äžçäșć­ćšç©șéŽćć</label>
						<div class="controls">
							<input type="text" class="input mr5" name="post[qiniu_domain]" value="<?php echo ($config['qiniu_domain']); ?>">äžćžŠhttp://æhttps://ïŒäžèŠä»„/ç»ć°ŸïŒćŠqiniudemo.lingdu01.com.com
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">äžçäșć­ćšç©șéŽć°ć</label>
						<div class="controls">
							<input type="text" class="input mr5" name="post[qiniu_domain_url]" value="<?php echo ($config['qiniu_domain_url']); ?>"> ä»„http://æhttps://ćŒć€ŽïŒä»„/ç»ć°ŸïŒćŠhttp://qiniudemo.lingdu01.com.com/
						</div>
					</div>
					<!--
                                        <div class="control-group">
                                            <label class="control-label">äžäŒ ćć</label>
                                            <div class="controls">
                                                <input type="text" class="input mr5" name="post[qiniu_upload_url]" value="<?php echo ($config['qiniu_upload_url']); ?>"> äžçäžćć­ćšćșćäžäŒ ććäžäžæ ·ïŒæ čæźæšç©șéŽçć­ćšćșć,èźŸçœźäžćçććïŒ
                                                ćäžïŒhttp://up.qiniu.com,ććïŒhttp://up-z1.qiniu.com;ććïŒhttp://up-z2.qiniu.com;
                                                é»èź€äžșćć
                                            </div>
                                        </div>
                    -->
					<div class="control-group">
						<label class="control-label">èŸèźŻäșć­ćšappid</label>
						<div class="controls">
							<input type="text" class="input mr5" name="post[txcloud_appid]" value="<?php echo ($config['txcloud_appid']); ?>">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">èŸèźŻäșć­ćšsecret_id</label>
						<div class="controls">
							<input type="text" class="input mr5" name="post[txcloud_secret_id]" value="<?php echo ($config['txcloud_secret_id']); ?>">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">èŸèźŻäșć­ćšsecret_key</label>
						<div class="controls">
							<input type="text" class="input mr5" name="post[txcloud_secret_key]" value="<?php echo ($config['txcloud_secret_key']); ?>">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">èŸèźŻäșć­ćšregion</label>
						<div class="controls">
							<input type="text" class="input mr5" name="post[txcloud_region]" value="<?php echo ($config['txcloud_region']); ?>"> ćć tj ćäž sh ćć gz
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">èŸèźŻäșć­ćšbucket</label>
						<div class="controls">
							<input type="text" class="input mr5" name="post[txcloud_bucket]" value="<?php echo ($config['txcloud_bucket']); ?>">
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">èŸèźŻäșć­ćšćŸçć­æŸçźćœ</label>
						<div class="controls">
							<input type="text" class="input mr5" name="post[tximgfolder]" value="<?php echo ($config['tximgfolder']); ?>">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">èŸèźŻäșć­ćšè§éąć­æŸçźćœ</label>
						<div class="controls">
							<input type="text" class="input mr5" name="post[txvideofolder]" value="<?php echo ($config['txvideofolder']); ?>">
						</div>
					</div>
					<div class="control-group" style="display:none;">
						<label class="control-label">çšæ·ć€Žćć­æŸçźćœ</label>
						<div class="controls">
							<input type="text" class="input mr5" name="post[txuserimgfolder]" value="<?php echo ($config['txuserimgfolder']); ?>">
						</div>
					</div>
				</fieldset>
			</div>

			<!-- è§éąćæ°éçœź -->
			<div>
				<fieldset>
					<div class="control-group">
						<label class="control-label">èŻèźșæéćŒ</label>
						<div class="controls">
							<input type="text" name="post[comment_weight]" value="<?php echo ($config['comment_weight']); ?>"> çšäșè§éąæšè
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">çčè”æéćŒ</label>
						<div class="controls">
							<input type="text" name="post[like_weight]" value="<?php echo ($config['like_weight']); ?>"> çšäșè§éąæšè
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">ćäș«æéćŒ</label>
						<div class="controls">
							<input type="text" name="post[share_weight]" value="<?php echo ($config['share_weight']); ?>"> çšäșè§éąæšè
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">ćć§æććŒ</label>
						<div class="controls">
							<input type="text" name="post[show_val]" value="<?php echo ($config['show_val']); ?>" onkeyup="this.value=this.value.replace(/[^0-9-]+/,'');"> èŻ·ćĄ«ćæŽæ°ïŒçšäșè§éąæšè
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">æŻć°æ¶æŁé€æććŒ</label>
						<div class="controls">
							<input type="text" name="post[hour_minus_val]" value="<?php echo ($config['hour_minus_val']); ?>" onkeyup="this.value=this.value.replace(/[^0-9-]+/,'');"> èŻ·ćĄ«ćæŽæ°ïŒçšäșè§éąæšè
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">ćIPæŻć€©éæ¶çčè”æ°</label>
						<div class="controls">
							<input type="text" name="post[day_ip_limit]" value="<?php echo ($config['day_ip_limit']); ?>" onkeyup="this.value=this.value.replace(/[^0-9-]+/,'');"> èŻ·ćĄ«ćæŽæ°ïŒçšäșè§éąçčè”
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">æŻć€©ćèŽčè§çè§éąæŹĄæ°</label>
						<div class="controls">
							<input type="text" name="post[free_count]" value="<?php echo ($config['free_count']); ?>" onkeyup="this.value=this.value.replace(/[^0-9-]+/,'');"> èŻ·ćĄ«ćæŽæ°ïŒçšäșè§çè§éąæŹĄæ°
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">çšæ·äžäŒ è§éąć€§ć°éć¶</label>
						<div class="controls">
							<input type="text" name="post[set_video_size]" value="<?php echo ($config['set_video_size']); ?>" onkeyup="this.value=this.value.replace(/[^0-9-]+/,'');"> èŻ·ćĄ«ćæŽæ°ïŒćäœ(M)
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">çšæ·äžäŒ è§éąæ¶éżéć¶</label>
						<div class="controls">
							<input type="text" name="post[video_lenth_time]" value="<?php echo ($config['video_lenth_time']); ?>" onkeyup="this.value=this.value.replace(/[^0-9-]+/,'');"> èŻ·ćĄ«ćæŽæ°ïŒćäœ(ç§)
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">è§éąæé«ä»·æ Œéć¶</label>
						<div class="controls">
							<input type="text" name="post[set_max_price]" value="<?php echo ($config['set_max_price']); ?>" onkeyup="this.value=this.value.replace(/[^0-9-]+/,'');"> èŻ·ćĄ«ćæŽæ°ïŒçšäșè§éąä»·æ Œ
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">èȘćȘäœclient</label>
						<div class="controls">
							<input type="text" name="post[iiilab_client]" value="<?php echo ($config['iiilab_client']); ?>" > çšäșè·ćæ æ°Žć°è§éąéŸæ„(äžæčïŒhttp://www.iiilab.com)
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">èȘćȘäœkey</label>
						<div class="controls">
							<input type="text" name="post[iiilab_key]" value="<?php echo ($config['iiilab_key']); ?>" >çšäșè·ćæ æ°Žć°è§éąéŸæ„(äžæčïŒhttp://www.iiilab.com)
						</div>
					</div>
				</fieldset>
			</div>

			<!-- æç°éçœź -->
			<div>
				<fieldset>
					<div class="control-group">
						<label class="control-label">éćžæç°æŻäŸ</label>
						<div class="controls">
							<input type="text" name="post[ticket_percent]" value="<?php echo ($config['ticket_percent']); ?>"> 1ćäșșæ°ćžćŻćæąéćžæ°
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">éćžæç°æç»­èŽč</label>
						<div class="controls">
							<input type="text" name="post[service_charge]" value="<?php echo ($config['service_charge']); ?>"> ćäœïŒ%
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">ç°éæç°æç»­èŽč</label>
						<div class="controls">
							<input type="text" name="post[cash_withdraw]" value="<?php echo ($config['cash_withdraw']); ?>"> ćäœïŒ%
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">æäœćŻæç°ééą</label>
						<div class="controls">
							<input type="text" name="post[draw_min_cash]" value="<?php echo ($config['draw_min_cash']); ?>"> ćäœïŒć
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">ćçșąæäœćŻæç°ééą</label>
						<div class="controls">
							<input type="text" name="post[bonus_min_cash]" value="<?php echo ($config['bonus_min_cash']); ?>"> ćäœïŒć
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">çčè”ćæąéćžæŻäŸ</label>
						<div class="controls">
							<input type="text" name="post[praise_percent]" value="<?php echo ($config['praise_percent']); ?>"> 1éćžćŻćæąçčè”æ°
						</div>
					</div>

					<div class="control-group">
						<label class="control-label">ć„ć±éćž</label>
						<div class="controls">
							<input type="text" name="post[reward_tacket]" value="<?php echo ($config['reward_tacket']); ?>"> çšæ·ćźæŽçćźäžäžȘè§éąć„ć±éćžæ°(ćĄ«ćæŽæ°)
						</div>
					</div>


				</fieldset>
			</div>
			<!-- ćééçœź -->
			<div>
				<fieldset>
					<div class="control-group">
						<label class="control-label">ćéćŒćł</label>
						<div class="controls">
							<label class="radio inline"><input type="radio" value="0" name="post[agent_switch]" <?php if(($config['agent_switch']) == "0"): ?>checked="checked"<?php endif; ?>>ćłé­</label>
							<label class="radio inline"><input type="radio" value="1" name="post[agent_switch]" <?php if(($config['agent_switch']) == "1"): ?>checked="checked"<?php endif; ?>>ćŒćŻ</label>
							<label class="checkbox inline"></label>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">äžçș§äžç»©èœŹćç</label>
						<div class="controls">
							<input type="text" name="post[distribut1]" value="<?php echo ($config['distribut1']); ?>">ćäœïŒ%
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">äșçș§äžç»©èœŹćç</label>
						<div class="controls">
							<input type="text" name="post[distribut2]" value="<?php echo ($config['distribut2']); ?>">ćäœïŒ%
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">äžçș§äžç»©èœŹćç</label>
						<div class="controls">
							<input type="text" name="post[distribut3]" value="<?php echo ($config['distribut3']); ?>">ćäœïŒ%
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
</body>
</html>