    //上传
    function file_click(e){
			var n= e.attr("data-index");  //ipt-file1
			upload(n);
    }
    function upload(index) {
			$('#upload').empty();
			var input = '<input type="file" id="ipt-file1" name="image" accept="image/*" />';
			$('#upload').html(input);
			var iptt=document.getElementById(index);
			if(window.addEventListener) { // Mozilla, Netscape, Firefox
					iptt.addEventListener('change',function(){
						ajaxFileUpload(index);
						$(".shadd[data-select="+index+"]").show();
					},false);
			}else{
					iptt.attachEvent('onchange',function(){
						ajaxFileUpload(index);
						$(".shadd[data-select="+index+"]").show();
					});
			}
			$('#'+index).click();
    }
    function ajaxFileUpload(img) {
			$("."+img).css({"width":"0px"});
			$("."+img).animate({"width":"100%"},700,function(){
				var id= img;
				$.ajaxFileUpload
				(
					{
						url: '/index.php?g=Appapi&m=Feedback&a=upload',
						secureuri: false,
						fileElementId: id,
						data: { },
						dataType: 'html',
						success: function(data) {

                            console.log(data);
							data=data.replace(/<[^>]+>/g,"");
							var str=JSON.parse(data);
							if(str.ret==200){
								var sub=img.substr(8,1);
								$(".sf"+sub).attr("value",str.data.url);
								//$.alert("上传成功");
								$(".shadd[data-select="+img+"]").hide();
								$(".img-sfz[data-index="+img+"]").attr("src",str.data.url);
							}else{
                                layer.msg(str.msg);
								$(".shadd[data-select="+img+"]").hide();
							}
						},
						error: function(data) {
							console.log(data);
							layer.msg("上传失败");
							$(".shadd[data-select="+img+"]").hide();
						}
					}
				)
				return true;
			});
    }
    (function(){
        var issubmit=false;
        $(".feedback_bottom").click(function(){
            if(issubmit){
                return !1;
            }
            
            var thumb=$("#thumb").val();
            var content=$("#content").val();
            var contactMsg=$("#contactMsg").val();
            
            if(content==''){
                layer.msg("请填写反馈内容");
                return !1;
            }

            /*if(contactMsg==""){
                layer.msg("请填写联系方式");
                return !1;
            }*/

            /* if(hasEmoji(content)){
                layer.msg("不能含有表情");
                return !1;
            } */
            issubmit=true;
            $.ajax({
                url:'/index.php?g=Appapi&m=Feedback&a=save',
                type:'POST',
                data:{uid:uid,token:token,model:model,version:version,thumb:thumb,content:content,contactMsg:contactMsg},
                dataType:'json',
                success:function(data){
                    issubmit=false;
                    layer.msg(data.msg);
                    if(data.code==0){
                        $("#thumb").val('');
                        $("#content").val('');
                        $("#contactMsg").val('');
                        $("#input_nums").text("0");
                        $(".img-sfz").attr('src','/public/appapi/images/feedback/upload.png');
                    }
                    
                },
                error:function(data){
                    layer.msg("提交失败");
                    return !1;
                }
                
            })
            
        })
    })();




    $(function(){
    	var scrW=$(window).width();
    	var feedbackBodyW=$(".feedback_tel").width();
    	var feedbackTelNameW=$(".feedback_tel_name").width();
    	console.log(feedbackTelNameW);
    	$(".feedback_tel_con").width(feedbackBodyW-feedbackTelNameW);

        $("#content").on('blur keyup input',function(){  
            var text=$("#content").val();  
            var counter=text.length;  
            $("#input_nums").text(counter);  
        });  
    });