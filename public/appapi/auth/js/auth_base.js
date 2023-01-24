$(function(){

	var scrW=$(window).width();
	var scrH=$(window).height();

	var liW=$(".auth_list li").width();

	var liH=$(".auth_list li").height();
	var titleW=$(".auth_list_title").outerWidth();

	$(".auth_list_right").width(liW-titleW-liW*0.05).height(liH);

});