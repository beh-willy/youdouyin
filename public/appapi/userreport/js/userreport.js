$(function(){

	

	/*点击下一步*/

	$(".report_list_right input").click(function(){
		
		$(".reportarea input").attr("disabled", false);
		
		
	});

	$(".reportarea input").click(function(){
		var val=$('input:radio[name="classify"]:checked').val();
		
		if(!val){
			layer.msg("请选择举报理由");
			return;
		}
		$(".classify_area").hide();
		$(".report_con").show();
	});

	/*点击提交审核*/

	var is_submit=0;

	$(".autharea input").click(function(){

		if(is_submit==1){
			return;
		}

		

		var realname=$("#realname").val();
		var phone=$("#phone").val();
		var cardno=$("#cardno").val();
		//var reg_realName=/^(?=.*\d.*\b)/;
		var reg_realName=/^[\u4e00-\u9fa5]+$/;
		//var reg_phone=/^(\d{5}|\d{6}|\d{7}|\d{8}|\d{9}|\d{10}|\d{11}|\d{12}|\d{13}|\d{14}|\d{15}|\d{16}|\d{17}|\d{18}|\d{19}|\d{20}|\d{21})$/;
		var reg_phone = /^1[3|4|5|6|7|8|9][0-9]{9}$/; //验证规则
		var reg_identity=/^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/;

		if(reg_realName.test(realname)==false){
			layer.msg("请填写正确的姓名");
			return;
		}

		if(reg_phone.test(phone)==false){
			layer.msg("请填写正确的手机号码");
			return;
		}

		if(reg_identity.test(cardno)==false){
			layer.msg("请填写正确的身份证号");
			return;
		}

		is_submit=1;

		$.ajax({
			url: '/index.php?g=Appapi&m=Auth&a=auth_save',
			type: 'POST',
			dataType: 'json',
			data: {uid:uid,realname: realname,phone:phone,cardno:cardno},
			success:function(data){

				var code=data.code;
				if(code!=0){
					layer.msg(data.msg);
					return;
				}else{
					//layer.msg("认证成功");
					layer.msg('认证成功', {time:1000},function(){
						location.href="/index.php?g=Appapi&m=Auth&a=success&uid="+uid;
					});


				}
			},
			error:function(e){
				console.log(e);
			}
		});
		
		

	});
});