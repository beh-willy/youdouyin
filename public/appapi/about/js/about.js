$(function(){
	var scrW=$(window).width();
	var scrH=$(window).height();

	var iconAreaH=scrH*100/667;
	$(".icon_area").height(iconAreaH);
	console.log(iconAreaH);
	$(".icon_area img").width(iconAreaH*0.6).height(iconAreaH*0.6);


});