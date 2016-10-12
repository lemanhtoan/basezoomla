
$('select').styler();

$("#switcher-open").click(function(){
	if ( $("#styleswitcher").hasClass('control-close') ) {
		$("#styleswitcher").animate( { left: 0 } );
		$("#styleswitcher").removeClass('control-close');
	} else {
		$("#styleswitcher").animate( { left: -202 } );
		$("#styleswitcher").addClass('control-close');
	}
	return false;
});
$(document).ready(function () {
    $('#color-schemes li a').click(function () {
          $('#color-schemes li').removeClass('active-style');
        $(this).parent().addClass('active-style');
           return true;
    });
 });
$(document).ready(function () {
$('#patterns li a').click(function () {
	  $('#patterns li').removeClass('active-style');
	$(this).parent().addClass('active-style');
	   return true;
});
});
$(document).ready(function(){
	$("#patterns>li:first-child>a").click(function(){
		$("body").removeClass().addClass("background-pattern1");
	});
	$("#patterns>li:first-child+li>a").click(function(){
		$("body").removeClass().addClass("background-pattern2");
	});
	$("#patterns>li:first-child+li+li>a").click(function(){
		$("body").removeClass().addClass("background-pattern3");
	});
	$("#patterns>li:first-child+li+li+li>a").click(function(){
		$("body").removeClass().addClass("background-pattern4");
	});
	$("#patterns>li:first-child+li+li+li+li>a").click(function(){
		$("body").removeClass().addClass("background-pattern5");
	});
	$("#patterns>li:first-child+li+li+li+li+li>a").click(function(){
		$("body").removeClass().addClass("background-pattern6");
	});
});

/** Styleswitcher **/

jQuery('#color-schemes a').click(function(e){
	e.preventDefault();
	jQuery(this).parent().find('a').removeClass('active');
	jQuery(this).addClass('active');
	var name=jQuery(this).attr('name');
	if(name=='brown'){
		jQuery('#styler_switch').attr('href','');
	}else{
		jQuery('#styler_switch').attr('href','css/'+name+'.css');
	}
});

jQuery('#layout_select[name=layout]').change(function() {
	var current = jQuery(this).find('option:selected').val();
	
	if(current == 'Boxed') {
		$("#switch_layout").attr("href","css/layout.css");
	} else {
		$("#switch_layout").attr("href","css/layout_wide.css");
	}

});

jQuery('.main_layout[name=layout]').change(function() {
	
	window.location.replace('index_full_width.html');

});
jQuery('.main_layout_full_width[name=layout]').change(function() {
	
	window.location.replace('index.html');

});
