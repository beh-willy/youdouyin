$(function(){
	
	var swiper = new Swiper('.swiper-container', {
        slidesPerView: 4,
        paginationClickable: true,
        spaceBetween: 0,
        freeMode: true
    });

	$(".swiper-container .swiper-wrapper .swiper-slide").on("click",function(){
		$(this).siblings().removeClass("on");
		$(this).addClass("on");
		$(".list").hide().eq($(this).index()).show();
	})
	

	
	$(".list .list_top a").on("click",function(){
		var _this=$(this);
		var length=_this.data("length");
		$.ajax({
			url:'/index.php?g=appapi&m=liang&a=replace',
			data:{length:length},
			type:'POST',
			dataType:'json',
			success:function(data){
				if(data.code==0){
					var list=data.info,nums=list.length,html='';
					for(var i=0;i<nums;i++){
						html+='<li>\
									<div class="thumb"></div>\
									<div class="info">\
										<p class="name">'+list[i]['name']+'</p>\
										<p class="coin">'+list[i]['coin']+name_coin+'</p>\
									</div>\
									<div class="buy" data-id="'+list[i]['id']+'" data-name="'+list[i]['name']+'" data-coin="'+list[i]['coin']+name_coin+'">\
										购买\
									</div>\
								</li>';
					}
					_this.parent().siblings("ul").html(html);
					return !1;
				}else{
					layer.msg(data.msg);
					return !1;
				}
			},
			error:function(){
				layer.msg("请求失败");
				return !1;
			}
			
		})
	})
	var isbuy=1;
	$(".list").on("click",'.buy',function(){
		if(!isbuy){
			return !1;
		}
		if(!uid || !token ){
			layer.msg("信息错误");
			return !1;
		}
		var _this=$(this);
		var liangid=_this.data("id");
		var name=_this.data("name");
		var coin=_this.data("coin");
		
		layer.confirm('购买靓号'+name+'需要消费'+coin+'<br>是否购买', {
			title:false,
			btn: ['确定','取消'] //按钮
		}, function(){
			isbuy=0;
			$.ajax({
				url:'/index.php?g=appapi&m=liang&a=buy',
				data:{uid:uid,token:token,liangid:liangid},
				type:'POST',
				dataType:'json',
				success:function(data){
					isbuy=1;
					if(data.code==0){
						layer.msg(data.msg,{},function(){
							layer.closeAll();
							_this.parent("li").remove();
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
		
	})
	
	$(".set").on("click",function(){
		if(!isbuy){
			return !1;
		}
		if(!uid || !token ){
			layer.msg("信息错误");
			return !1;
		}
		var _this=$(this);
		var liangid=_this.data("liangid");
		var state=_this.data("state");
		var goodnum=_this.data("goodnum");
		
		isbuy=0;
		$.ajax({
			url:'/index.php?g=appapi&m=liang&a=set',
			data:{uid:uid,token:token,liangid:liangid,state:state,goodnum:goodnum},
			type:'POST',
			dataType:'json',
			success:function(data){
				isbuy=1;
				if(data.code==0){
					if(state==0){
						$(".set").html("使用");
						$(".set").data("state",'0');
						
						_this.html("卸载");
						_this.data("state",'1');
					}else{
						_this.html("使用");
						_this.data("state",'0');
					}
					
					layer.msg(data.msg);
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
	})
})