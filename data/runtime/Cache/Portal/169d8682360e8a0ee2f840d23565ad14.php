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
<style type="text/css">
.pic-list li {
	margin-bottom: 5px;
}
</style>
</head>
<body>
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li><a href="<?php echo U('AdminPost/index');?>"><?php echo L('PORTAL_ADMINPOST_INDEX');?></a></li>
			<li class="active"><a href="<?php echo U('AdminPost/add',array('term'=>empty($term['term_id'])?'':$term['term_id']));?>" target="_self"><?php echo L('PORTAL_ADMINPOST_ADD');?></a></li>
		</ul>
		<form action="<?php echo U('AdminPost/add_post');?>" method="post" class="form-horizontal js-ajax-forms" enctype="multipart/form-data">
			<div class="row-fluid">
				<div class="span9">
					<table class="table table-bordered">
						<tr>
							<th width="80">ćç±»</th>
							<td>
								<select multiple="multiple" style="max-height: 100px;" name="term[]"><?php echo ($taxonomys); ?></select>
								<div style="display:none;">windowsïŒæäœ Ctrl æéźæ„éæ©ć€äžȘééĄč,MacïŒæäœ command æéźæ„éæ©ć€äžȘééĄč</div>
							</td>
						</tr>
						<tr>
							<th>æ éą</th>
							<td>
								<input type="text" style="width:400px;" name="post[post_title]" id="title" required value="" placeholder="èŻ·èŸć„æ éą"/>
								<span class="form-required">*</span>
							</td>
						</tr>
						<tr style="display:none;">
							<th>ćłéźèŻ</th>
							<td><input type="text" name="post[post_keywords]" id="keywords" value="" style="width: 400px" placeholder="èŻ·èŸć„ćłéźć­"> ć€ćłéźèŻäčéŽçšç©șæ Œæèè±æéć·éćŒ</td>
						</tr>
						<tr style="display:none;">
							<th>æç« æ„æș</th>
							<td><input type="text" name="post[post_source]" id="source" value="" style="width: 400px" placeholder="èŻ·èŸć„æç« æ„æș"></td>
						</tr>
						<tr style="display:none;">
							<th>æèŠ</th>
							<td>
								<textarea name="post[post_excerpt]" id="description" style="width: 98%; height: 50px;" placeholder="èŻ·ćĄ«ćæèŠ"></textarea>
							</td>
						</tr>
						<tr>
							<th>ććźč</th>
							<td>
								<script type="text/plain" id="content" name="post[post_content]"></script>
							</td>
						</tr>
						<tr style="display:none;"> 
							<th>çžććŸé</th>
							<td>
								<fieldset>
									<legend>ćŸçćèĄš</legend>
									<ul id="photos" class="pic-list unstyled"></ul>
								</fieldset>
								<a href="javascript:;" onclick="javascript:flashupload('albums_images', 'ćŸçäžäŒ ','photos',change_images,'10,gif|jpg|jpeg|png|bmp,0','','','')" class="btn btn-small">éæ©ćŸç</a>
							</td>
						</tr>
					</table>
				</div>
				<div class="span3" style="display:none;">
					<table class="table table-bordered">
						<tr>
							<th><b>çŒ©ç„ćŸ</b></th>
						</tr>
						<tr>
							<td>
								<div style="text-align: center;">
									<input type="hidden" name="smeta[thumb]" id="thumb" value="">
									<a href="javascript:void(0);" onclick="flashupload('thumb_images', 'éä»¶äžäŒ ','thumb',thumb_images,'1,jpg|jpeg|gif|png|bmp,1,,,1','','','');return false;">
										<img src="/admin/themes/simplebootx/Public/assets/images/default-thumbnail.png" id="thumb_preview" width="135" style="cursor: hand" />
									</a>
									<input type="button" class="btn btn-small" onclick="$('#thumb_preview').attr('src','/admin/themes/simplebootx/Public/assets/images/default-thumbnail.png');$('#thumb').val('');return false;" value="ćæ¶ćŸç">
								</div>
							</td>
						</tr>
						<tr>
							<th><b>ććžæ¶éŽ</b></th>
						</tr>
						<tr>
							<td><input type="text" name="post[post_modified]" value="<?php echo date('Y-m-d H:i:s',time());?>" class="js-datetime" style="width: 160px;"></td>
						</tr>
						<tr>
							<th><b>ç¶æ</b></th>
						</tr>
						<tr>
							<td>
								<label class="radio"><input type="radio" name="post[post_status]" value="1" checked>ćźĄæ žéèż</label>
								<label class="radio"><input type="radio" name="post[post_status]" value="0">ćŸćźĄæ ž</label>
							</td>
						</tr>
						<tr>
							<td>
								<label class="radio"><input type="radio" name="post[istop]" value="1">çœźéĄ¶</label>
								<label class="radio"><input type="radio" name="post[istop]" value="0" checked>æȘçœźéĄ¶</label>
							</td>
						</tr>
						<tr>
							<td>
								<label class="radio"><input type="radio" name="post[recommended]" value="1">æšè</label>
								<label class="radio"><input type="radio" name="post[recommended]" value="0" checked>æȘæšè</label>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="form-actions">
				<button class="btn btn-primary js-ajax-submit" type="submit">æäș€</button>
				<a class="btn" href="<?php echo U('AdminPost/index');?>">èżć</a>
			</div>
		</form>
	</div>
	<script type="text/javascript" src="/public/js/common.js"></script>
	<script type="text/javascript" src="/public/js/content_addtop.js"></script>
	<script type="text/javascript">
		//çŒèŸćšè·ŻćŸćźäč
		var editorURL = GV.DIMAUB;
	</script>
	<script type="text/javascript" src="/public/js/ueditor/ueditor.config.js"></script>
	<script type="text/javascript" src="/public/js/ueditor/ueditor.all.min.js"></script>
	<script type="text/javascript">
		$(function() {
			$(".js-ajax-close-btn").on('click', function(e) {
				e.preventDefault();
				Wind.use("artDialog", function() {
					art.dialog({
						id : "question",
						icon : "question",
						fixed : true,
						lock : true,
						background : "#CCCCCC",
						opacity : 0,
						content : "æšçĄźćźéèŠćłé­ćœćéĄ”éąćïŒ",
						ok : function() {
							setCookie("refersh_time", 1);
							window.close();
							return true;
						}
					});
				});
			});
			/////---------------------
			Wind.use('validate', 'ajaxForm', 'artDialog', function() {
				//javascript

				//çŒèŸćš
				editorcontent = new baidu.editor.ui.Editor();
				editorcontent.render('content');
				try {
					editorcontent.sync();
				} catch (err) {
				}
				//ćąć çŒèŸćšéȘèŻè§ć
				jQuery.validator.addMethod('editorcontent', function() {
					try {
						editorcontent.sync();
					} catch (err) {
					}
					return editorcontent.hasContents();
				});
				var form = $('form.js-ajax-forms');
				//ieć€çplaceholderæäș€éźéą
				if ($.browser.msie) {
					form.find('[placeholder]').each(function() {
						var input = $(this);
						if (input.val() == input.attr('placeholder')) {
							input.val('');
						}
					});
				}

				var formloading = false;
				//èĄšćéȘèŻćŒć§
				form.validate({
					//æŻćŠćšè·ćçŠçčæ¶éȘèŻ
					onfocusout : false,
					//æŻćŠćšæČć»éźçæ¶éȘèŻ
					onkeyup : false,
					//ćœéŒ æ æçș§æ¶éȘèŻ
					onclick : false,
					//éȘèŻéèŻŻ
					showErrors : function(errorMap, errorArr) {
						//errorMap {'name':'éèŻŻäżĄæŻ'}
						//errorArr [{'message':'éèŻŻäżĄæŻ',element:({})}]
						try {
							$(errorArr[0].element).focus();
							art.dialog({
								id : 'error',
								icon : 'error',
								lock : true,
								fixed : true,
								background : "#CCCCCC",
								opacity : 0,
								content : errorArr[0].message,
								cancelVal : 'çĄźćź',
								cancel : function() {
									$(errorArr[0].element).focus();
								}
							});
						} catch (err) {
						}
					},
					//éȘèŻè§ć
					rules : {
						'post[post_title]' : {
							required : 1
						},
						'post[post_content]' : {
							editorcontent : true
						}
					},
					//éȘèŻæȘéèżæç€șæ¶æŻ
					messages : {
						'post[post_title]' : {
							required : 'èŻ·èŸć„æ éą'
						},
						'post[post_content]' : {
							editorcontent : 'ććźčäžèœäžșç©ș'
						}
					},
					//ç»æȘéèżéȘèŻçćçŽ ć ææ,éȘçç­
					highlight : false,
					//æŻćŠćšè·ćçŠçčæ¶éȘèŻ
					onfocusout : false,
					//éȘèŻéèżïŒæäș€èĄšć
					submitHandler : function(forms) {
						if (formloading)
							return;
						$(forms).ajaxSubmit({
							url : form.attr('action'), //æéźäžæŻćŠèȘćźäčæäș€ć°ć(ć€æéźæć”)
							dataType : 'json',
							beforeSubmit : function(arr, $form, options) {
								formloading = true;
							},
							success : function(data, statusText, xhr, $form) {
								formloading = false;
								if (data.status) {
									setCookie("refersh_time", 1);
									//æ·»ć æć
									Wind.use("artDialog", function() {
										art.dialog({
											id : "succeed",
											icon : "succeed",
											fixed : true,
											lock : true,
											background : "#CCCCCC",
											opacity : 0,
											content : data.info,
											button : [ {
												name : 'ç»§ç»­æ·»ć ïŒ',
												callback : function() {
													reloadPage(window);
													return true;
												},
												focus : true
											}, {
												name : 'èżććèĄšéĄ”',
												callback : function() {
													location = "<?php echo U('AdminPost/index');?>";
													return true;
												}
											} ]
										});
									});
								} else {
									isalert(data.info);
								}
							}
						});
					}
				});
			});
			////-------------------------
		});
	</script>
</body>
</html>