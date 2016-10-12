$(document).ready(function(){
	var $width = $(".container-filter img").width();
	var $height = $(".container-filter img").height();
	$(".container-filter").css({
		'width':'auto',
		'height':$height,
		'position':'relative',
		'z-index':'1'
	});
	$(".container-filter").append("<div></div>");
	$(".container-filter").find("div").css({
		'width':'100%',
		'height':'100%',
		'position':'absolute',
		'top':'0',
		'left':'0',
		'z-index':'2',
		'background':'url("images/switcher-squares.png") repeat'
	});
});