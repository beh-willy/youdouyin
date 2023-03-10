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
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a href="javascript:;"><?php echo L('PORTAL_ADMINPOST_INDEX');?></a></li>
			<li><a href="<?php echo U('AdminPost/add',array('term'=>empty($term['term_id'])?'':$term['term_id']));?>" target="_self"><?php echo L('PORTAL_ADMINPOST_ADD');?></a></li>
		</ul>
		<form class="well form-search" method="post" action="<?php echo U('AdminPost/index');?>">
			ćç±»ïŒ 
			<select class="select_2" name="term">
				<option value='0'>ćšéš</option><?php echo ($taxonomys); ?>
			</select> &nbsp;&nbsp;
			æ¶éŽïŒ
			<input type="text" name="start_time" class="js-date" value="<?php echo ((isset($formget["start_time"]) && ($formget["start_time"] !== ""))?($formget["start_time"]):''); ?>" style="width: 80px;" autocomplete="off">-
			<input type="text" class="js-date" name="end_time" value="<?php echo ($formget["end_time"]); ?>" style="width: 80px;" autocomplete="off"> &nbsp; &nbsp;
			ćłéźć­ïŒ 
			<input type="text" name="keyword" style="width: 200px;" value="<?php echo ($formget["keyword"]); ?>" placeholder="èŻ·èŸć„ćłéźć­...">
			<input type="submit" class="btn btn-primary" value="æçŽą" />
		</form>
		<form class="js-ajax-form" action="" method="post">
			<div class="table-actions">
				<button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('AdminPost/listorders');?>"><?php echo L('SORT');?></button>
				<!-- <button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('AdminPost/check',array('check'=>1));?>" data-subcheck="true" style="display:none;">ćźĄæ ž</button>
				<button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('AdminPost/check',array('uncheck'=>1));?>" data-subcheck="true" style="display:none;">ćæ¶ćźĄæ ž</button> -->
				<button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('AdminPost/top',array('top'=>1));?>" data-subcheck="true" style="display:none;">çœźéĄ¶</button>
				<button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('AdminPost/top',array('untop'=>1));?>" data-subcheck="true" style="display:none;">ćæ¶çœźéĄ¶</button>
			<!-- 	<button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('AdminPost/recommend',array('recommend'=>1));?>" data-subcheck="true">æšè</button>
				<button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('AdminPost/recommend',array('unrecommend'=>1));?>" data-subcheck="true">ćæ¶æšè</button> -->
				<button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('AdminPost/delete');?>" data-subcheck="true" data-msg="äœ çĄźćźć é€ćïŒ"><?php echo L('DELETE');?></button>
				<button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('AdminPost/recommend',array('recommend'=>1));?>" data-subcheck="true">æšè</button>
				<button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('AdminPost/recommend',array('unrecommend'=>1));?>" data-subcheck="true">ćæ¶æšè</button>
				<button class="btn btn-primary btn-small js-articles-move" type="button" style="display:none;">æčéç§»ćš</button>
			</div>
			<table class="table table-hover table-bordered table-list">
				<thead>
					<tr>
						<th width="15"><label><input type="checkbox" class="js-check-all" data-direction="x" data-checklist="js-check-x"></label></th>
						<th width="50"><?php echo L('SORT');?></th>
						<th><?php echo L('TITLE');?></th>
						<th><?php echo L('CATEGORY');?></th>
						<th width="50" style="display:none;"><?php echo L('HITS');?></th>
						<th width="50" style="display:none;"><?php echo L('COMMENT_COUNT');?></th>
						<th width="50" style="display:none;"><?php echo L('KEYWORDS');?></th>
						<th width="50" style="display:none;"><?php echo L('SOURCE');?></th>
						<th width="50" style="display:none;"><?php echo L('ABSTRACT');?></th>
						<th width="50" style="display:none;"><?php echo L('THUMBNAIL');?></th>
						<th width="80"><?php echo L('AUTHOR');?></th>
						<th width="70"><?php echo L('PUBLISH_DATE');?></th>
						<!-- <th width="50"><?php echo L('STATUS');?></th> --> 
						<th width="70"><?php echo L('ACTIONS');?></th>
					</tr>
				</thead>
				<?php $status=array("1"=>"ć·ČćźĄæ ž","0"=>"æȘćźĄæ ž"); $top_status=array("1"=>"ć·ČçœźéĄ¶","0"=>"æȘçœźéĄ¶"); $recommend_status=array("1"=>"ć·Čæšè","0"=>"æȘæšè"); ?>
				<?php if(is_array($posts)): foreach($posts as $key=>$vo): ?><tr>
					<td><input type="checkbox" class="js-check" data-yid="js-check-y" data-xid="js-check-x" name="ids[]" value="<?php echo ($vo["tid"]); ?>" title="ID:<?php echo ($vo["tid"]); ?>"></td>
					<td><input name="listorders[<?php echo ($vo["tid"]); ?>]" class="input input-order" type="text" size="5" value="<?php echo ($vo["listorder"]); ?>" title="ID:<?php echo ($vo["tid"]); ?>"></td>
					<td  style="display:none;"<a href="<?php echo U('portal/article/index',array('id'=>$vo['tid']));?>" target="_blank"> <span><?php echo ($vo["post_title"]); ?></span></a></td>
					<td><a href="<?php echo U('portal/page/news',array('id'=>$vo['id']));?>" target="_blank"> <span><?php echo ($vo["post_title"]); ?></span></a></td>
					<td><?php echo ($terms[$vo['term_id']]); ?></td>
					<td style="display:none;"><?php echo ($vo["post_hits"]); ?></td>
					<td style="display:none;"><a href="javascript:open_iframe_dialog('<?php echo U('comment/commentadmin/index',array('post_id'=>$vo['id']));?>','èŻèźșćèĄš')"><?php echo ($vo["comment_count"]); ?></a></td>
					<td style="display:none;"><?php echo ($excerpt_keywords = empty($vo['post_keywords'])?"":'ć·ČćĄ«ć'); ?></td>
					<td style="display:none;"><?php echo ($excerpt_source = empty($vo['post_source'])?" ":'ć·ČćĄ«ć'); ?></td>
					<td style="display:none;"><?php echo ($excerpt_excerpt = empty($vo['post_excerpt'])?" ":'ć·ČćĄ«ć'); ?></td>
					<td style="display:none;">
						<?php $smeta=json_decode($vo['smeta'],true); ?>
						<?php if(!empty($smeta['thumb'])): ?><a href="<?php echo sp_get_asset_upload_path($smeta['thumb']);?>" target='_blank'>æ„ç</a><?php endif; ?>
					</td>
					<td><?php echo ($users[$vo['post_author']]['user_login']); ?></td>
					<td><?php echo ($vo["post_date"]); ?></td>
					<td style="display:none;"><?php echo ($status[$vo['post_status']]); ?><br><?php echo ($top_status[$vo['istop']]); ?><br><?php echo ($recommend_status[$vo['recommended']]); ?></td>
					<!-- <td><?php echo ($recommend_status[$vo['recommended']]); ?></td> -->
					<td>
						<a href="<?php echo U('AdminPost/edit',array('term'=>empty($term['term_id'])?'':$term['term_id'],'id'=>$vo['id']));?>"><?php echo L('EDIT');?></a> | 
						<a href="<?php echo U('AdminPost/delete',array('term'=>empty($term['term_id'])?'':$term['term_id'],'tid'=>$vo['tid']));?>" class="js-ajax-delete"><?php echo L('DELETE');?></a></td>
				</tr><?php endforeach; endif; ?>
				<tfoot>
					<tr>
						<th width="15"><label><input type="checkbox" class="js-check-all" data-direction="x" data-checklist="js-check-x"></label></th>
						<th width="50"><?php echo L('SORT');?></th>
						<th><?php echo L('TITLE');?></th>
						<th><?php echo L('CATEGORY');?></th>
						<th width="50" style="display:none;"><?php echo L('HITS');?></th>
						<th width="50" style="display:none;"><?php echo L('COMMENT_COUNT');?></th>
						<th width="50" style="display:none;"><?php echo L('KEYWORDS');?></th>
						<th width="50" style="display:none;"><?php echo L('SOURCE');?></th>
						<th width="50" style="display:none;"><?php echo L('ABSTRACT');?></th>
						<th width="50" style="display:none;"><?php echo L('THUMBNAIL');?></th>
						<th width="80"><?php echo L('AUTHOR');?></th>
						<th width="70"><?php echo L('PUBLISH_DATE');?></th>
						<!-- <th width="50"><?php echo L('STATUS');?></th>  -->
						<th width="70"><?php echo L('ACTIONS');?></th>
					</tr>
				</tfoot>
			</table>
			<div class="table-actions">
				<button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('AdminPost/listorders');?>"><?php echo L('SORT');?></button>
				<!-- <button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('AdminPost/check',array('check'=>1));?>" data-subcheck="true" style="display:none;">ćźĄæ ž</button>
				<button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('AdminPost/check',array('uncheck'=>1));?>" data-subcheck="true" style="display:none;">ćæ¶ćźĄæ ž</button>
				<button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('AdminPost/top',array('top'=>1));?>" data-subcheck="true" style="display:none;">çœźéĄ¶</button>
				<button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('AdminPost/top',array('untop'=>1));?>" data-subcheck="true" style="display:none;">ćæ¶çœźéĄ¶</button>
				<button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('AdminPost/recommend',array('recommend'=>1));?>" data-subcheck="true">æšè</button>
				<button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('AdminPost/recommend',array('unrecommend'=>1));?>" data-subcheck="true">ćæ¶æšè</button> -->
				<button class="btn btn-primary btn-small js-ajax-submit" type="submit" data-action="<?php echo U('AdminPost/delete');?>" data-subcheck="true" data-msg="äœ çĄźćźć é€ćïŒ"><?php echo L('DELETE');?></button>
				<button class="btn btn-primary btn-small js-articles-move" type="button" style="display:none;">æčéç§»ćš</button>
			</div>
			<div class="pagination"><?php echo ($Page); ?></div>
		</form>
	</div>
	<script src="/public/js/common.js"></script>
	<script>
		function refersh_window() {
			var refersh_time = getCookie('refersh_time');
			if (refersh_time == 1) {
				window.location = "<?php echo U('AdminPost/index',$formget);?>";
			}
		}
		setInterval(function() {
			refersh_window();
		}, 2000);
		$(function() {
			setCookie("refersh_time", 0);
			Wind.use('ajaxForm', 'artDialog', 'iframeTools', function() {
				//æčéç§»ćš
				$('.js-articles-move').click(function(e) {
					var str = 0;
					var id = tag = '';
					$("input[name='ids[]']").each(function() {
						if ($(this).attr('checked')) {
							str = 1;
							id += tag + $(this).val();
							tag = ',';
						}
					});
					if (str == 0) {
						art.dialog.through({
							id : 'error',
							icon : 'error',
							content : 'æšæČĄæćŸéäżĄæŻïŒæ æłèżèĄæäœïŒ',
							cancelVal : 'ćłé­',
							cancel : true
						});
						return false;
					}
					var $this = $(this);
					art.dialog.open("/index.php?g=portal&m=AdminPost&a=move&ids="+ id, {
						title : "æčéç§»ćš",
						width : "80%"
					});
				});
			});
		});
	</script>
</body>
</html>