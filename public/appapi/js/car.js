$(function(){
	var isbuy=1;
	$(".buy").on("click",function(){
		if(!isbuy){
			return !1;
		}
		if(!uid || !token){
			layer.msg("信息错误");
			return !1;
		}
		var carid=$(this).data("id");
		var name=$(this).data("name");
		
		layer.confirm('确认要购买'+name+'？', {
			title:false,
			btn: ['确定','取消'] //按钮
		}, function(){
			isbuy=0;
			$.ajax({
				url:'/index.php?g=appapi&m=car&a=buy',
				data:{uid:uid,token:token,carid:carid},
				type:'POST',
				dataType:'json',
				success:function(data){
					isbuy=1;
					if(data.code==0){
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
		
	})
	
	$(".set").on("click",function(){
		if(!isbuy){
			return !1;
		}
		if(!uid || !token){
			layer.msg("信息错误");
			return !1;
		}
		var _this=$(this);
		var carid=_this.data("carid");
		var status=_this.data("status");
		
		isbuy=0;
		$.ajax({
			url:'/index.php?g=appapi&m=car&a=set',
			data:{uid:uid,token:token,carid:carid,status:status},
			type:'POST',
			dataType:'json',
			success:function(data){
				isbuy=1;
				if(data.code==0){
					if(status==0){
						$(".set").html("使用");
						$(".set").data("status",'0');
						
						_this.html("卸载");
						_this.data("status",'1');
					}else{
						_this.html("使用");
						_this.data("status",'0');
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