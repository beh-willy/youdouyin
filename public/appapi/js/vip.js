$(function(){
	
	$(".buy_tab ul li").on("click",function(){
		$(this).siblings().removeClass("on");
		$(this).addClass("on");
		$(".buy_tab_body").hide().eq($(this).index()).show();
		
		$(".tab ul li").removeClass("on").eq($(this).index()).addClass("on");
		$(".vip-privilege").hide().eq($(this).index()).show();
	})
	
	$(".tab ul li").on("click",function(){
		$(this).siblings().removeClass("on");
		$(this).addClass("on");
		$(".vip-privilege").hide().eq($(this).index()).show();
		
		$(".buy_tab ul li").removeClass("on").eq($(this).index()).addClass("on"); 
		$(".buy_tab_body").hide().eq($(this).index()).show();
	})
	
	$(".buy_tab_body ul li").on("click",function(){
		$(this).siblings().removeClass("on");
		$(this).addClass("on");
	})
	
	$(".buy").on("click",function(){
		layer.open({
			type: 1,
			offset: 'b',
			title: false,
			closeBtn: 0,
			anim:2,
			area: '100%',
			skin: 'layui-layer-nobg', //没有背景色
			shadeClose: true,
			content: $('.buy_body')
		});
	})
	var isbuy=1;
	$(".submit").on("click",function(){
		if(!isbuy){
			return !1;
		}
		if(!uid || !token ){
			layer.msg("信息错误");
			return !1;
		}
		var vipid=$(".buy_tab_body:visible li.on").data("id");
		var viptype=$(".buy_tab_body:visible li.on").data("type");
		
		if(type!='' && viptype > type ){
			layer.confirm('购买将覆盖你当前拥有的普通vip，是否继续？', {
				title:false,
				btn: ['确定','取消'] //按钮
			}, function(){
				buyVip(vipid);
			}, function(){
				
			})
		}else{
			buyVip(vipid);
		}
		
		
		
	})
	
	function buyVip(vipid){
		layer.confirm('确认购买？', {
			title:false,
			btn: ['确定','取消'] //按钮
		}, function(){
			isbuy=0;
			$.ajax({
				url:'/index.php?g=appapi&m=vip&a=buy',
				data:{uid:uid,token:token,vipid:vipid},
				type:'POST',
				dataType:'json',
				success:function(data){
					isbuy=1;
					if(data.code==0){
						$(".vip-time").html(data.info.endtime).show();
						// $('#endtime').text(data.info.endtime);
						layer.msg(data.msg,{},function(){
							layer.closeAll();
						});
						return !1;
					}else{
						layer.msg(data.msg);
						return !1;
					}
				},
				error:function(){
					isbuy=1;
					layer.msg("购买失败");
					return !1;
				}
				
			})
		}, function(){

		});

		
	}
})