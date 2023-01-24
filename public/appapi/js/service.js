$(function(){
	var h=$(".confirmArea").height();

	$(".confirmMobile").css('line-height',(h/2)+'px').css('height',(h/2)+'px');

	var w=$(".confirmArea").width();
	console.log(w);
	$(".confirmBtnArea p").width(w/2);
	$(".confirmBtnArea p").css('line-height',(h/2-1)+'px').css('height',(h/2-1)+'px');


	$(".confirm_cancel").click(function(){
		$(".confirmArea").hide();
		$(".shadow").hide();
	});

	$(".confirm_confirm").click(function(){
		window.location.href='tel:'+mobile;
	});
});