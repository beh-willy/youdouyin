var type=1;
var isappend=0;
var isselect=0;
var page={
    '1':'1',
    '2':'1',
};

var isscroll={
    '1':true,
    '2':true,
};

var url={
    '1':'/index.php?g=Appapi&m=Bonus&a=getBonusIncome',
    '2':'/index.php?g=Appapi&m=Bonus&a=getBonusForward',
};




$(function(){


	getmore();
	var scrH=$(window).height();
	var tab_body_height= scrH - $(".tabList").outerHeight(true) -5;
	$(".scores_tab_body").height(tab_body_height);


	/*$(".tabList li").click(function(){
		
	});*/

    $(".tabList li").on('click touch',function(){
        if(!$(this).hasClass("on")){

            $(this).siblings().removeClass("on");
            $(this).addClass("on");
            type= $(this).data("type");
            var index=$(this).index();
            isappend=0;
            $(".scores_tab_bd").hide().find(".listarea").html("");
            $(".scores_tab_bd").eq(index).show();

            isscroll[type]=true;
            page[type]=1;
            getmore();
        }
    });


	/* 加载更多 */
    $(".scores_tab_body").scroll(function(){ 
        var srollPos = $(".scores_tab_body").scrollTop();    //滚动条距顶部距离(页面超出窗口的高度)
        var totalheight = parseFloat($(".scores_tab_body").height()) + parseFloat(srollPos);
        if($(".scores_tab_body").height()-50 <= totalheight){
            getmore()
        }  
    });


});



function getmore(){

    if(!isscroll[type]){
        return !1;
    }


    isscroll[type]=false;

    $.ajax({
        url:url[type],
        data:{'p':page[type],uid:uid,token:token},
        type:'post',
        dataType:'json',
        success:function(data){
            if(data.code==0){
                var html='';

                if(data.info.nums>0){
                    var nums=data.info.nums;
                    var list=data.info.list;

                    var i=0;
                    switch(type){
                        case 1:
                            isselect=1;
                            for(;i<nums;i++){
                                
                                html+='<li>\
									<p class="datetime">'+list[i]['addtime']+'</p>\
									<p class="type">平台分红</p>\
									<p class="money">'+list[i]['bonus']+'</p>\
									<div class="clearboth"></div>\
								</li>';


                            }

                            break;
                        case 2:
                            for(;i<nums;i++){
                                html+='<li>\
								<p class="datetime">'+list[i]['addtime']+'</p>\
								<p class="type">'+list[i]['type']+'</p>\
								<p class="money">'+list[i]['money']+'</p>\
								<p class="status">'+list[i]['status']+'</p>\
								<div class="clearboth"></div>\
							</li>';
                            }
                            break;
                        
                        
                    }   
                }
                
                if(isappend){
                    $(".scores_tab_bd"+type+" .listarea").append(html);
                }else{
                    if(html==''){
                        var tip='';
                        switch(type){
                            case 1:
                                tip= '暂无转入记录';
                                break;
                            case 2:
                                tip= '暂无转出记录';
                                break
                            
                        }
                        html='<div class="no_list"><i></i><div>'+tip+'</div></div>';
                    }
                    $(".scores_tab_bd"+type+" .listarea").html(html);
                }
                
                
                if(data.info.isscroll==1){
                    page[type]++;
                    isappend=1;
                    isscroll[type]=true;
                }
            }else{
                layer.msg(data.msg);
            }
            
            
        }
    })
}