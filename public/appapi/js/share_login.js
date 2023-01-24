$(function(){
	
	var count = 60;
	var countdown = 0;

	$(".getcode").on("click",function(){
		$(this).siblings().removeClass("on");
		$(this).addClass("on");
		var phone = $(".phone").val();
		// console.log(phone);
		if (phone == '') {
			layer.msg("手机号不能为空");
			return false;
		}
		var phoneReg = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
        if(!phoneReg.test(phone)) {
           layer.msg("请输入有效的手机号码");
            return false;
        }
		$.ajax({
			url:'/api/public/index.php?service=Login.getLoginCode&mobile='+phone,
			data:{},
			type:'GET',
			dataType:'json',
			success:function(data){
				// console.log(data);

				if(data.data.code==0 || data.data.code==667){

					countdown = setInterval(CountDown, 1000);

					layer.msg(data.data.msg,{},function(){
						layer.closeAll();
					});
					return !1;
				}else{
					layer.msg(data.data.msg);
					return !1;
				}
			},
			error:function(){
				
				layer.msg("发送失败");
				return !1;
			}

		})

	})

	$(".rsg").on("click",function(){

		var phone = $(".phone").val();
		var code = $(".code").val();

		if (phone == '') {
			layer.msg("手机号不能为空");
			return false;
		}
		if (code == '') {
			layer.msg("验证码不能为空");
			return false;
		}
		
		if (uid == '') {
			layer.msg("uid不能为空");
			return false;
		}

		var phoneReg = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
        if(!phoneReg.test(phone)) {
           layer.msg("请输入有效的手机号码");
            return false;
        }

		$.ajax({
			url:'/api/public/index.php?service=Login.shareRsg&mobile='+phone+'&code='+code+'&uid='+uid,
			data:{},
			type:'GET',
			dataType:'json',
			success:function(data){
				console.log(data);
				
				if(data.data.code==0){
					
					layer.msg("注册成功",{time:1000},function(){
						
						location.href = '/index.php?g=Portal&m=Index&a=scanqr';
					});
					
					return !1;
				}else{
					layer.msg(data.data.msg);
					return !1;
				}
			},
			error:function(){
				
				layer.msg("注册失败");
				return !1;
			}
			
		})
	})

	function CountDown() {
		console.log('55555');
		$(".getcode").attr("disabled", true);
		$(".getcode").text(""+count+"s");
		if (count == 0) {
			count = 60;
            $(".getcode").text("获取验证码").removeAttr("disabled");//将input变为可用
            clearInterval(countdown);

        }
        count--;
    }




})