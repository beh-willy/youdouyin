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
.table img{
	max-width:100px;
	max-height:100px;
}
.textArea textarea{
	width:90%;padding:3%;height:80%;margin:0 auto;margin-top:30px;
	margin-left: 2%;
}
.textArea_btn{
	text-align: right;
	margin-top: 30px;
}
.textArea_btn input{
	margin-right: 30px;
}
</style>
</head>
<body>
	<div class="wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a >ćć­ćèĄš</a></li>
			<!-- <li><a href="<?php echo U('Community/add');?>">ćć­æ·»ć </a></li> -->
		</ul>
		
		<form class="well form-search" method="post" action="<?php echo U('Community/passindex');?>">
			æćșïŒ
			<select class="select_2" name="ordertype">
				<option value="">é»èź€</option>
				<option value="1" <?php if($formget["ordertype"] == '1'): ?>selected<?php endif; ?> >èŻèźșæ°æćș</option>
				<option value="2" <?php if($formget["ordertype"] == '2'): ?>selected<?php endif; ?> >çčè”æ°æćș</option>			
				<option value="3" <?php if($formget["ordertype"] == '3'): ?>selected<?php endif; ?> >ćäș«æ°æćș</option>			
			</select>
			
			
			ćłéźć­ïŒ 
			<input type="text" name="keyword" style="width: 200px;" value="<?php echo ($formget["keyword"]); ?>" placeholder="èŻ·èŸć„ćć­IDăäŒćID">
			<input type="text" name="keyword1" style="width: 200px;" value="<?php echo ($formget["keyword1"]); ?>" placeholder="èŻ·èŸć„ćć­æ éą">
			<input type="text" name="keyword2" style="width: 200px;" value="<?php echo ($formget["keyword2"]); ?>" placeholder="èŻ·èŸć„çšæ·ćç§°">
			<input type="submit" class="btn btn-primary" value="æçŽą">
		</form>		
		
		<form method="post" class="js-ajax-form">
			<table class="table table-hover table-bordered">
				<thead>
					<tr>
						<th align="center">ID</th>
						<th>äŒćæ”ç§°ïŒIDïŒ</th>
						<th style="max-width: 300px;">æ éą</th>
						<th>ćŸç</th>
						<th>çčè”æ°</th>
						<th>èŻèźșæ°</th>
						<th>æ”è§æ°</th>
						<!-- <th>ćŹæŽćä»·</th>
						<th>ć·ČćŹæŽééą</th> -->
						<th>ćć­ç¶æ</th>
						<th>äžäžæ¶ç¶æ</th>
						<th>ććžæ¶éŽ</th>
						<th align="center"><?php echo L('ACTIONS');?></th>
					</tr>
				</thead>
				<tbody>
					<?php $isdel=array("0"=>"äžæ¶","1"=>"äžæ¶");$status=array("0"=>"ćŸćźĄæ ž","1"=>"éèż","2"=>"äžéèż"); ?>
					<?php if(is_array($lists)): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
						<td align="center"><?php echo ($vo["id"]); ?></td>
						<td><?php echo ($vo['userinfo']['user_nicename']); ?> (<?php echo ($vo['uid']); ?>)</td>
						<td style="max-width: 300px;"><?php echo ($vo['title']); ?></td>
						<td>
						<?php if($vo['videolink'] != '' ): ?><img src="<?php echo explode(',', $vo['videolink'])[0]; ?>" />
    							<?php else: ?> 
    							<?php if(is_array($vo['imgs'])): $k = 0; $__LIST__ = $vo['imgs'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($k % 2 );++$k;?><img src="<?php echo ($sub); ?>" />
						        <?php if($k ==4){ ?>
						        	</br>
						    	<?php } endforeach; endif; else: echo "" ;endif; endif; ?>
							
						</td>
						<td><?php echo ($vo['likes']); ?></td>
						<td><?php echo ($vo['comments']); ?></td>
						<td><?php echo ($vo['views']); ?></td>
						<!-- <td><?php echo ($vo['urge_money']); ?></td>
						<td><?php echo ($vo['hasurgemoney']); ?></td> -->
						<td><?php echo ($status[$vo['status']]); ?></td>
						<td><?php echo ($isdel[$vo['isdel']]); ?></td>
						<td><?php echo (date("Y-m-d H:i:s",$vo["addtime"])); ?></td>
						<td align="center">
							<?php if($vo['videolink'] != '' ): ?><a href="javascript:void(0)" onclick="videoListen(<?php echo ($vo['id']); ?>)" >è§ç</a>
								|<?php endif; ?>
							

							<!--<a href="<?php echo U('Community/edit',array('id'=>$vo['id'],'from'=>'passindex'));?>" >çŒèŸ</a> -->
							 <?php if($vo['isdel'] == '0'): ?><a href="javascript:void (0)" onclick="xiajia(<?php echo ($vo['id']); ?>)" >äžæ¶</a><?php endif; ?>
							 |
							<a href="javascript:void (0)" onclick="commentlists(<?php echo ($vo['id']); ?>)" >èŻèźșćèĄš</a></if>
							 |
							 <a href="javascript:void (0)" onclick="del(<?php echo ($vo['id']); ?>)" >ć é€</a></if>
							<!-- <a href="<?php echo U('Community/del',array('id'=>$vo['id']));?>" class="js-ajax-dialog-btn" data-msg="æšçĄźćźèŠć é€ćïŒ">ć é€</a> -->
						</td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
				</tbody>
			</table>
			<div class="pagination"><?php echo ($page); ?></div>
		</form>
	</div>
	<script src="/public/js/common.js"></script>
	<script src="/public/layer/layer.js"></script>
	<script type="text/javascript">


		var xiajia_status=0;
		var del_status=0;

		function xiajia(id){
			var p=<?php echo ($p); ?>;

			layer.open({
			  type: 1,
			  title:"æŻćŠçĄźćźć°èŻ„ćć­äžæ¶",
			  skin: 'layui-layer-rim', //ć äžèŸčæĄ
			  area: ['30%', '30%'], //ćźœé«
			  content: '<div class="textArea"><textarea id="xiajia_reason" maxlength="50" placeholder="èŻ·èŸć„äžæ¶ćć ,æć€50ć­" /> </div><div class="textArea_btn" ><input type="button" id="xiajia" value="äžæ¶" onclick="xiajia_submit('+id+','+p+')" /><input type="button" id="cancel" onclick="layer.closeAll();" value="ćæ¶" /></div>'
			});
		}

		function xiajia_submit(id,p){

			var reason=$("#xiajia_reason").val();
			if(xiajia_status==1){
				return;
			}
			xiajia_status=1;
			$.ajax({
				url: '/index.php?g=Admin&m=Community&a=setXiajia',
				type: 'POST',
				dataType: 'json',
				data: {id:id,reason: reason},
				success:function(data){
					var code=data.code;
					if(code!=0){
						layer.msg(data.msg);
						return;
					}
					xiajia_status=0;
					//èźŸçœźæéźäžćŻçš
					$("#xiajia").attr("disabled",true);
					$("#cancel").attr("disabled",true);
					layer.msg("äžæ¶æć",{icon: 1,time:1000},function(){
						layer.closeAll();
						location.href='/index.php?g=Admin&m=Community&a=index&p='+p;
					});
				},
				error:function(e){
					$("#xiajia").attr("disabled",false);
					$("#cancel").attr("disabled",false);
					console.log(e);
				}
			});
			
			
		}

		function del(id){
			var p=<?php echo ($p); ?>;

			layer.open({
			  type: 1,
			  title:"æŻćŠçĄźćźć°èŻ„ćć­ć é€",
			  skin: 'layui-layer-rim', //ć äžèŸčæĄ
			  area: ['30%', '30%'], //ćźœé«
			  content: '<div class="textArea"><textarea id="del_reason" maxlength="50" placeholder="èŻ·èŸć„ć é€ćć ,æć€50ć­" /> </div><div class="textArea_btn" ><input type="button" id="delete" value="ć é€" onclick="del_submit('+id+','+p+')" /><input type="button" id="cancel" onclick="layer.closeAll();" value="ćæ¶" /></div>'
			});
		}

		function del_submit(id,p){

			var reason=$("#del_reason").val();

			if(del_status==1){
				return;
			}

			del_status=1;

			$.ajax({
				url: '/index.php?g=Admin&m=Community&a=del',
				type: 'POST',
				dataType: 'json',
				data: {id:id,reason: reason},
				success:function(data){
					var code=data.code;
					if(code!=0){
						layer.msg(data.msg);
						return;
					}

					del_status=0;
					//èźŸçœźæéźäžćŻçš
					$("#delete").attr("disabled",true);
					$("#cancel").attr("disabled",true);

					layer.msg("ć é€æć",{icon: 1,time:1000},function(){
						layer.closeAll();
						location.href='/index.php?g=Admin&m=Community&a=passindex&p='+p;
					});
				},
				error:function(e){
					$("#delete").attr("disabled",false);
					$("#cancel").attr("disabled",false);

					console.log(e);
				}
			});
			
			
		}

		/*è·ććć­èŻèźșćèĄš*/
		function commentlists(communityid){
			layer.open({
				type: 2,
				title: 'ćć­èŻèźșćèĄš',
				shadeClose: true,
				shade: 0.8,
				area: ['60%', '90%'],
				content: '/index.php?g=Admin&m=Community&a=commentlists&communityid='+communityid 
			}); 
		}
	</script>

	<script type="text/javascript">
		function videoListen(id){
			layer.open({
			  type: 2,
			  title: 'è§çćć­',
			  shadeClose: true,
			  shade: 0.8,
			  area: ['500px', '750px'],
			  content: '/index.php?g=Admin&m=Community&a=community_listen&id='+id
			}); 
		}
	</script>
</body>
</html>