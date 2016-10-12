// Bootstrap accordion

$(".accordion").collapse();
$(function() {
    $('.accordion').on('show', function (e) {
         $(e.target).prev('.accordion-heading').find('.accordion-toggle').addClass('accordion-active');
    });
    $('.accordion').on('hide', function (e) {
        $(this).find('.accordion-toggle').not($(e.target)).removeClass('accordion-active');
    }); 
});

// Bootstrap Menu

$(document).ready(function(){
	$('.main-menu>li>a').append('<div class="no_active_devices_icon"></div><div class="active_devices_icon"></div>');
	$('.main-menu>li').each(function(){
		if(!$(this).children('ul').length){
			$(this).children('a').css('background-image','none');
			$(this).children('a').children('div').hide();
		}
	});
});

// button style

$(document).ready(function(){
	$('.join-button,.small_button_style_5,.medium_button_style_5,.large_button_style_5,.post_time dt,.recent-work a,.news-content a,.read-more-link,.indicator>div,.learn-more-link,.search-button,.list-type1>li>a,.list-type2>li>ul>li>a').append("<span class='button-element'></span>");
	$('.recent-work figure div').append('<i></i>');
	
	$('.portfolio_navigation>li>a.selected').delay(500).append('<span class="button-element"></span>');
});

if($("#layer-slider").length){
$(document).ready(function(){
        $('#layer-slider').layerSlider({
			autoStart:true,
			navPrevNext:true,
			hoverPrevNext:false,
			navButtons:true,
			skin: 'default',
			skinsPath:false,
			touchNav:true,
			thumbnailNavigation:'disabled',
			imgPreload:true,
			responsive:true
		});
 });
}
// first-letter

$(document).ready(function(){
		$('.first-letter').each(function(){

		var first = $(this).text().slice(0,1);
		var last = $(this).text().slice(1,$(this).text().length);

		$(this).html('<span class="first_letter_style_1">'+first+'</span>' + last);
		});	
		$('.first_letter_default').each(function(){

		var first = $(this).text().slice(0,1);
		var last = $(this).text().slice(1,$(this).text().length);

		$(this).html('<span class="first_letter_default_style">'+first+'</span>' + last);
		});	
		$('.first-letter-type2').each(function(){

		var first = $(this).text().slice(0,1);
		var last = $(this).text().slice(1,$(this).text().length);

		$(this).html('<span class="first_letter_style_2">'+first+'</span>' + last);
		});
		$('.first-letter-type3').each(function(){

		var first = $(this).text().slice(0,1);
		var last = $(this).text().slice(1,$(this).text().length);

		$(this).html('<span class="first_letter_style_3">'+first+'</span>' + last);
		});	
});

// ipad fix

if( 'devicePixelRatio' in window && window.devicePixelRatio == 2 ){
	$(".recent-work").click(function(){});
	$(".tweet_list>li").click(function(){});
	$('.bwWrapperPartners').find('img').css('visibility','hidden');
}
$(document).ready(function(){
	$('.box-2-column,.box-3-column,.box-4-column').on('mouseenter', function(){
		$(this).find('div.portfolio_description').css('top','0');
	});
	$('.box-2-column,.box-3-column,.box-4-column').on('mouseleave', function(){
		$(this).find('div.portfolio_description').removeAttr('style');
	});
});
$(document).ready(function(){
	$('.pricing_table_column').on('mouseenter', function(){
		$(this).addClass('hover_table_column');
	});
	$('.pricing_table_column').on('mouseleave', function(){
		$(this).removeClass('hover_table_column');
	});
});
$(document).ready(function(){
	$('.box_blog_wrap').on('mouseenter', function(){
		$(this).addClass('box_blog_wrap_hover');
	});
	$('.box_blog_wrap').on('mouseleave', function(){
		$(this).removeClass('box_blog_wrap_hover');
	});
});

//sidebar height fix

if($('.sidebar-page-content').length){
$(document).ready(function(){
	$('.sidebar-page-content>.container>.row,.single-project-content>.row').eqHeight(".content-part-sidebar-page, aside");
});
}
if($('.recent-work').length){
$(document).ready(function(){
	$('.recent-work-container').eqHeight(".recent-work");
});
}

if($("#carousel-featured-work").length){
$(window).load(function(){
	$('#carousel-featured-work').carouFredSel({
	responsive: true,
	width: "auto",
	auto: false,
	circular	: true,
	infinite	: false,
	prev : {
		button		: "#car_prev",
		key			: "left",
			},
	next : {
		button		: "#car_next",
		key			: "right",
				},
	swipe: {
		onMouse: true,
		onTouch: true
		},
	items: {
		width: 245,
		visible: {
			min: 1,
			max: 4
		}
	}
	});
});
}
if($("#carousel-recent-news").length){
$(window).load(function(){
	$('#carousel-recent-news').carouFredSel({
	responsive: true,
	width: 'auto',
	auto: false,
	circular	: true,
	infinite	: false,
	swipe: {
		onMouse: true,
		onTouch: true
		},
	items: {
		width: 245,
		visible: {
			min: 1,
			max: 1
		},
		height: 'auto'
	},
	pagination: "#carousel-news-nav"
	});
});
}
if($("#team-carousel").length){
$(window).load(function(){
	$('#team-carousel').carouFredSel({
	responsive: true,
	width: "auto",
	auto: false,
	circular	: true,
	infinite	: false,
	swipe: {
		onMouse: true,
		onTouch: true
		},
	items: {
		width: 250,
		visible: {
			min: 1,
			max: 4
		},
		height: 'auto'
	},
	pagination: "#carousel-team-nav"
	});
});
}

if($("#our_partners_carousel").length){
$(window).load(function(){
	$('#our_partners_carousel').carouFredSel({
	responsive: true,
	width: "auto",
	auto: false,
	circular	: true,
	infinite	: false,
	swipe: {
		onMouse: true,
		onTouch: true
		},
	items: {
		width:326,
		visible: {
			min: 1,
			max: 3
		}
	},
	pagination: "#our_partners_carousel_nav"
	});
});
}

$(document).ready(function(){
	$('.bwWrapper').BlackAndWhite({
		hoverEffect: false,
		responsive: true
	})
}); 

$(document).ready(function(){
	$('.bwWrapperPartners').BlackAndWhite({
		hoverEffect: true,
		responsive: true
	})
}); 

if($(".featured-work-description a").length){
$(document).ready(function(){
$('.featured-work-description a').not('.link_icon').addClass('fancy_not_link');
	$('.featured-work-description a.fancy_not_link').fancybox({
		transitionIn: 'elastic',
		transitionOut: 'elastic',
		speedIn: 600,
		speedOut: 300
	});
});
}
if($(".fancybox_blog_container a").length){
$(document).ready(function(){
	$('.fancybox_blog_container a').fancybox({
		transitionIn: 'elastic',
		transitionOut: 'elastic',
		speedIn: 600,
		speedOut: 300
	});
});
}
if($(".portfolio_description a").length){
$(document).ready(function(){
	$('.portfolio_description a').fancybox({
		transitionIn: 'elastic',
		transitionOut: 'elastic',
		speedIn: 600,
		speedOut: 300
	});
});
}

if($('a.video-button').length){
$(document).ready(function(){
    $("a.video-button").fancybox({
		 "width": 620, // or whatever
		 "height": 420,
		 "type": "iframe"
	});
});
}

if($("#layer-slider-fullwidth").length){
 $(document).ready(function(){
	$('#layer-slider-fullwidth').layerSlider({
		autoStart:true,
		touchNav: true,
		thumbnailNavigation:'disabled',
		imgPreload:true,
		responsive:false,
		skinsPath:false,
		responsiveUnder: '1000',
		navPrevNext: true,
		hoverPrevNext: false,
		sublayerContainer: '1000',
		navButtons: true
	});
});
}

// Scroll up

if($('.scrollup').length){
  $(window).scroll(function(){
		if ($(this).scrollTop() > 200) {
			$('.scrollup').fadeIn();
		} else {
			$('.scrollup').fadeOut();
		}
	}); 

	$('.scrollup').click(function(){
		$("html, body").animate({ scrollTop: 0 }, 800);
		return false;
	});
}
$(document).ready(function(){
	$('.main_menu_container').wrap('<div class="menu_effect"></div>');
	$('.slider-wrap').wrap('<div class="slider_effect"></div>');
	
  $(window).scroll(function(){
		if ($(this).scrollTop() > 100) {
			$('.main_menu_container').css({'position':'fixed','top':'0','background':'#fff','margin-top':'0','box-shadow':'0px 1px 1px 1px rgba(154,154,154,0.2)'});
			$('.slider-wrap').css({'position':'fixed','top':'53px','z-index':'0'});
		} else {
			$('.main_menu_container').removeAttr('style');
			$('.slider-wrap').removeAttr('style');
		}
	}); 
})

$(".btn").click(function(){
	if($(".btn").hasClass("btn-active")){
		$(this).removeClass("btn-active");
	}
	else{
		$(this).addClass("btn-active");
	}
});

// Colorpicker

$(document).ready(function(){
	$("#colorSelector").minicolors({
		swatchPosition:'right',
		change: function(hex){
			$(".month,.layer-description,.layer-description-full-width,#carousel-news-nav>a.selected,.join-button,.comment_info a div,.apply_button,.title-page-block,.accordion-active .accordion-icon,.process-icon-block,.third_icon_block,.search-button,.portfolio_navigation>li>a.selected,.small_button_style_1,.medium_button_style_1,.pagination_style_2 li.selected_pag a,.social_icons_type_2 li a,.first_letter_style_2,mark,.reset_button,.scrollup,.fancy_button,.ps-container .ps-scrollbar-x").css({"background-color": hex });
			$(".featured-work-description a, .portfolio_description a,.join-button,#carousel-team-nav a.selected,.indicator>div,.recent_work_icon_block,.small_button_style_3,#our_partners_carousel_nav a.selected,.large_button_style_1,.large_button_style_3,.medium_button_style_3,.large_button_style_4,.medium_button_style_4,.small_button_style_4,.large_button_style_5,.medium_button_style_5,.small_button_style_5,.pricing_table_column.active_table_column>footer>a,.post_time dt,.jp-play-bar,.jp-play,.jp-pause,.jp-volume-bar-value,.menu_button").css({"background": hex });
			$(".main-menu>li>ul li.current-item>a,.recent-work a,.news-content a,.tweet_text a,.author,first-letter:first-letter,.first_letter_style_1,#team-carousel>li p,.team-list>li p,.read-more-link,.learn-more-link,.first_letter_default_style,.pagination_style_1 .hidden_pages a,.active_table_column .content_column_part dl dt,.active_table_column .content_column_part dl dd,.active_table_column header h5,.first_letter_style_3,.link_demo,.container_404>p:first-child,#time_container dl dt,#time_container dl dd,.track_name dt,.comment_info a,.ls-bottom-slidebuttons a.ls-nav-active,.current_item_top_level>a").css({"color":hex});
			$(".main-menu ul,.accordion-active .accordion-icon-arrow,.search-button .button-element,.portfolio_navigation>li>a.selected .button-element,.post_time dt .button-element").css({'border-top-color':hex});
			$(".recent-work a,.featured-work-description,.news-content a,.join-button .button-element,.read-more-link,.learn-more-link,.portfolio_description,.large_button_style_5 .button-element,.medium_button_style_5 .button-element,.small_button_style_5 .button-element,.team-gallery-part ul.team-list > li > figure img").css({'border-bottom-color':hex});
			$(".recent-work a .button-element,.news-content a .button-element,.read-more-link .button-element,.indicator>div .button-element,.learn-more-link .button-element").css({'border-left-color':hex});
			$(".pagination_style_2 li.selected_pag a,.ls-bottom-slidebuttons a.ls-nav-active").css({'border-color':hex});
			$(".recent-work a,.news-content a,.tweet_text a,.read-more-link,.learn-more-link").mouseover(function(){
				$(this).css({'color':'#000'});
			});
			$(".recent-work a,.news-content a,.tweet_text a,.read-more-link,.learn-more-link").mouseout(function(){
				$(this).css({'color':hex});
			});
			$(".recent-work a,.news-content a,.read-more-link,.learn-more-link").mouseover(function(){
				$(this).css({'border-bottom-color':'#000'});
			});
			$(".recent-work a,.news-content a,.read-more-link,.learn-more-link").mouseout(function(){
				$(this).css({'border-bottom-color':hex});
			});
			$(".recent-work a,.news-content a,.read-more-link,.learn-more-link").mouseover(function(){
				$(this).find("span").css({'border-left-color':'#000'});
			});
			$(".recent-work a,.news-content a,.read-more-link,.learn-more-link").mouseout(function(){
				$(this).find("span").css({'border-left-color':hex});
			});
			$(".list-type1 > li > a").mouseover(function(){
				$(this).find("span").css({'border-left-color':hex});
			});
			$(".list-type1 > li > a").mouseout(function(){
				$(this).find("span").css({'border-left-color':'#626262'});
			});
			$('.main-menu ul li:not(li.current-item)').mouseover(function(){
				$(this).children('a').css({'color':hex});
			});
			$('.main-menu ul li:not(li.current-item)').mouseout(function(){
				$(this).children('a').css({'color':'#585858'});
			});
			$('.main-menu>li:not(.current_item_top_level)').mouseover(function(){
				$(this).children('a').css({'color':hex});
			});
			$('.main-menu>li:not(.current_item_top_level)').mouseout(function(){
				$(this).children('a').css({'color':'#000'})
			});
			$('.tweet_list>li').mouseover(function(){
				$(this).find('.twitter_logo').css({'background-color':hex});
			});
			$('.tweet_list>li').mouseout(function(){
				$(this).find('.twitter_logo').css({'background-color':'#e3e3e3'});
			});
			$('.social_icons_type_5 li a').mouseover(function(){
				$(this).css({'background-color':hex});
			});
			$('.social_icons_type_5 li a').mouseout(function(){
				$(this).css({'background-color':'#d7d7d7'});
			});
			$(".personal-social>li>a,.view-all-button,.personal-social>li>a,.social_icons_type_3 li a").mouseover(function(){
				$(this).css({'background-color':hex});
			});
			$(".personal-social>li>a,.view-all-button,.personal-social>li>a,.social_icons_type_3 li a").mouseout(function(){
				$(this).css({'background-color':'#cdcdcd'});
			});
			$("#carousel_process>li>figure").mouseover(function(){
				$(this).find('.process_2_icon').css({'background-color':hex});
			});
			$("#carousel_process>li>figure").mouseout(function(){
				$(this).find('.process_2_icon').css({'background-color':'#c7c7c7'});
			});
			$("#carousel_process>li>figure").mouseover(function(){
				$(this).find('.process_2_icon').children('i').css({'border-top-color':hex});
			});
			$("#carousel_process>li>figure").mouseout(function(){
				$(this).find('.process_2_icon').children('i').css({'border-top-color':'#c7c7c7'});
			});
			$("#latest_post_next,#latest_post_prev,.related-projects-container .carousel-fw-nav #car_prev,.related-projects-container .carousel-fw-nav #car_next,.blog_small_nav .blog_small_prev,.blog_small_nav .blog_small_next,#car_process_prev,#car_process_next").mouseover(function(){
				$(this).css({'background-color':hex});
			});
			$("#latest_post_next,#latest_post_prev,.related-projects-container .carousel-fw-nav #car_prev,.related-projects-container .carousel-fw-nav #car_next,.blog_small_nav .blog_small_prev,.blog_small_nav .blog_small_next,#car_process_prev,#car_process_next").mouseout(function(){
				$(this).css({'background-color':'#000'});
			});
			$(".list-type1 > li > a,.list-type2 > li > a,.list-type3 > li > a,.list-type4 > li > a,.post_links div a,.list-type4 > li > a").mouseover(function(){
				$(this).css({'color':hex});
				$(this).find('div.no_active_link_icon').css({'background':hex});
				$(this).find('div.active_link_icon').css({'background':hex});
			});
			$(".list-type1 > li > a,.list-type2 > li > a,.list-type3 > li > a,.list-type4 > li > a,.post_links div a").mouseout(function(){
				$(this).css({'color':'#616262'});
				$(this).find('div.no_active_link_icon').css({'background':'#616262'});
				$(this).find('div.active_link_icon').css({'background':'#616262'});;
			});
			$(".track_stat a").mouseover(function(){
				$(this).css({'color':hex});
			});
			$(".track_stat a").mouseout(function(){
				$(this).css({'color':'#878787'});
			});
			$(".list-type2>li>ul>li>a").mouseover(function(){
				$(this).find("span").css({'background':hex});
			});
			$(".list-type2>li>ul>li>a").mouseout(function(){
				$(this).find("span").css({'background':'#989898'});
			});
			$(".list-type2>li>ul>li>a").mouseover(function(){
				$(this).css({'color':hex});
			});
			$(".list-type2>li>ul>li>a").mouseout(function(){
				$(this).css({'color':'#989898'});
			});
			$(".comment_info a").mouseover(function(){
				$(this).css({'color':'#989898'});
			});
			$(".comment_info a").mouseout(function(){
				$(this).css({'color':hex});
			});
			$(".subscribers-list li a,.responsive_menu>ul.main-menu>li>a").mouseover(function(){
				$(this).css({'background':hex});
			});
			$(".subscribers-list li a,.responsive_menu>ul.main-menu>li>a").mouseout(function(){
				$(this).css({'background':'transparent'});
			});
			$(".pagination_style_2 li a").mouseover(function(){
				$(this).css({'background':hex});
				$(this).css({'border-color':hex});
			});
			$(".pagination_style_2 li a").mouseout(function(){
				$(this).css({'background':'transparent'});
				$(this).css({'border-color':'#DFDFDF'});
			});
			$(".recent-work").mouseover(function(){
				$(this).find('figure').find('div').css({'background':hex});
				$(this).find('figure').find('i').css({'border-top-color':hex});
			});
			$(".recent-work").mouseout(function(){
				$(this).find('figure').find('div').css({'background':'#c1c1c1'});
				$(this).find('figure').find('i').css({'border-top-color':'#c1c1c1'});
			});
			$(".box_blog_wrap").mouseover(function(){
				$(this).css({'border-color':hex});
			});
			$(".box_blog_wrap").mouseout(function(){
				$(this).css({'border-color':'#D3D3D3'});
			});
			$(".pricing_table_column:not(.active_table_column)").mouseover(function(){
				$(this).find("h5").css({'color':hex});
				$(this).find("dt").css({'color':hex});
			});
			$(".pricing_table_column:not(.active_table_column)").mouseout(function(){
				$(this).find("h5").css({'color':'#373737'});
				$(this).find("dt").css({'color':'#373737'});
			});
			$(".pricing_table_column:not(.active_table_column)").mouseover(function(){
				$(this).find("dd").css({'color':hex});
			});
			$(".pricing_table_column:not(.active_table_column)").mouseout(function(){
				$(this).find("dd").css({'color':'#989898'});
			});
			$(".pricing_table_column:not(.active_table_column)").mouseover(function(){
				$(this).find("a").css({'background':hex});
			});
			$(".pricing_table_column:not(.active_table_column)").mouseout(function(){
				$(this).find("a").css({'background':'#c0c0c0'});
			});
			$(window).resize(function(){
				$('.main-menu ul').css({
					'border-color':hex
				});
			});
		},
		DefaultValue: '#237dc8'
	});
});

// reset to default

$(document).ready(function(){
	var reset = $('.reset_button');
	var valueNull = $('#colorSelector');
	reset.click(function(){
		if(valueNull.val().length){
			window.location.reload();
		}
	});
});

$(document).ready(function(){
	
	var $defaultColor = $("#colorSelector");
	var $hoverButton = $(".apply_button");
	var $hoverButtonColor = $("#hoverButton");
	
	$hoverButton.click(function(){
		$(".join-button,.featured-work-description a,.fancy_button, .portfolio_description a,.small_button_style_3,.small_button_style_1,.medium_button_style_1,.large_button_style_1,.large_button_style_3,.medium_button_style_3,.large_button_style_4,.medium_button_style_4,.small_button_style_4,.large_button_style_5,.medium_button_style_5,.small_button_style_5").hover(function(){
			$(this).css({'background-color':$hoverButtonColor.val()});
			$(this).find('span').css({'border-bottom-color':$hoverButtonColor.val()})
		},function(){
			$(this).css({'background-color':$defaultColor.val()});
			$(this).find('span').css({'border-bottom-color':$defaultColor.val()})
		});
		$('.search-button,.portfolio_navigation>li>a.selected').hover(function(){
			$(this).css({'background-color':$hoverButtonColor.val()});
			$(this).find('span').css({'border-top-color':$hoverButtonColor.val()})
		},function(){
			$(this).css({'background-color':$defaultColor.val()});
			$(this).find('span').css({'border-top-color':$defaultColor.val()})
		});
	});
	
});

// youtube fix

$(document).ready(function(){
    $('iframe').each(function(){
          var url = $(this).attr("src");
          var char = "?";
          if(url.indexOf("?") != -1){
                  var char = "&";
           }
         
          $(this).attr("src",url+char+"wmode=transparent");
    });
});

if($("#carousel_process").length){
$(window).load(function(){
	$('#carousel_process').carouFredSel({
	responsive: true,
	width: "auto",
	auto: false,
	circular	: true,
	infinite	: false,
	prev : {
		button		: "#car_process_prev",
		key			: "left",
			},
	next : {
		button		: "#car_process_next",
		key			: "right",
				},
	swipe: {
		onMouse: true,
		onTouch: true
		},
	items: {
		width: 240,
		visible: {
			min: 1,
			max: 4
		},
		height: 'auto'
	}
	});
});
}

if($("#latest_post_carousel").length){
$(window).load(function(){
	$('#latest_post_carousel').carouFredSel({
	responsive: true,
	width: "auto",
	auto: false,
	circular	: true,
	infinite	: false,
	prev : {
		button		: "#latest_post_prev",
		key			: "left",
			},
	next : {
		button		: "#latest_post_next",
		key			: "right",
				},
	swipe: {
		onMouse: true,
		onTouch: true
		},
	items: {
		width: 219,
		height: 'auto',
		visible: {
			min: 1,
			max: 1
		}
	}
	});
});
}
if($("#portfolio_carousel").length){
$(window).load(function(){
	$('#portfolio_carousel').carouFredSel({
	responsive: true,
	width: "auto",
	auto: false,
	circular	: true,
	infinite	: false,
	prev : {
		button		: "#portfolio_prev",
		key			: "left",
			},
	next : {
		button		: "#portfolio_next",
		key			: "right",
				},
	swipe: {
		onMouse: true,
		onTouch: true
		},
	items: {
		width: 730,
		height: 'auto',
		visible: {
			min: 1,
			max: 1
		}
	}
});
});
}


if($("#post_carousel").length){
$(window).load(function(){
	$('#post_carousel').carouFredSel({
	responsive: true,
	width: "auto",
	auto: false,
	circular	: true,
	infinite	: false,
	prev : {
		button		: "#post_prev",
		key			: "left",
			},
	next : {
		button		: "#post_next",
		key			: "right",
				},
	swipe: {
		onMouse: true,
		onTouch: true
		},
	items: {
		width: 729,
		height: 'auto',
		visible: {
			min: 1,
			max: 1
		}
	}
});
});
}
if($("#carousel_v4").length){
$(window).load(function(){
	$('#carousel_v4').carouFredSel({
	responsive: true,
	width: "auto",
	auto: false,
	circular	: true,
	infinite	: false,
	prev : {
		button		: "#post_prev",
		key			: "left",
			},
	next : {
		button		: "#post_next",
		key			: "right",
				},
	swipe: {
		onMouse: true,
		onTouch: true
		},
	items: {
		width: 729,
		height: 'auto',
		visible: {
			min: 1,
			max: 1
		}
	}
});
});
}

if($(".blog_small_images_carousel").length){
$(window).load(function(){
	$('.blog_small_images_carousel').carouFredSel({
	responsive: true,
	width: "auto",
	auto: false,
	circular	: true,
	infinite	: false,
	prev        : {
        button      : function() {
            return $(this).parents(".blog_small_image_part,.box_blog_carousel_container").find(".blog_small_prev");
        }
    },
    next        : {
        button      : function() {
            return $(this).parents(".blog_small_image_part,.box_blog_carousel_container").find(".blog_small_next");
        }
    },
	swipe: {
		onMouse: true,
		onTouch: true
		},
	items: {
		width: 281,
		visible: {
			min: 1,
			max: 1
		}
	}
	});
});
}

// isotope

if($('.portfolio-content').length){
var $container = $('.portfolio-content');
$container.imagesLoaded(function() {
$container.isotope({
	itemSelector: '.box-2-column',
	layoutMode: 'fitRows'
});
});
 $(window).smartresize(function(){
	  $container.isotope({
		// update columnWidth to a percentage of container width
		masonry: { columnWidth: $container.width() / 5 }
	  });
	});
 }
 if($('.portfolio-content-3-column').length){
var $container = $('.portfolio-content-3-column');
$container.imagesLoaded(function() {
$container.isotope({
	itemSelector: '.box-3-column',
	layoutMode: 'fitRows'
});
});
 $(window).smartresize(function(){
	  $container.isotope({
		// update columnWidth to a percentage of container width
		masonry: { columnWidth: $container.width() / 5 }
	  });
	});
 }
  if($('.portfolio-content-4-column').length){
var $container = $('.portfolio-content-4-column');
$container.imagesLoaded(function() {
$container.isotope({
	itemSelector: '.box-4-column',
	layoutMode: 'fitRows'
});
});
 $(window).smartresize(function(){
	  $container.isotope({
		// update columnWidth to a percentage of container width
		masonry: { columnWidth: $container.width() / 5 }
	  });
	});
 }
 
 if($('.blog_container_isotope').length){
		var $container = $('.blog_container_isotope');
	$container.imagesLoaded(function() {
		$container.isotope({
			itemSelector: '.box_blog',
			masonry: {
				columnWidth: 1
			}
		});
	});
}
 
 // isotope menu
 
 if($('.portfolio_navigation a').length){
$('.portfolio_navigation a').click(function(){
  var selector = $(this).attr('data-filter');
	$container.isotope({ 
	filter: selector,
	animationOptions: {
	 duration: 750,
	 easing: 'linear',
	 queue: false,
   }
  });
  return false;
});
}
if($('.portfolio_navigation').length){
var $optionSets = $('.portfolio_navigation'),
	  $optionLinks = $optionSets.find('a');

  $optionLinks.click(function(){
	var $this = $(this);
	// don't proceed if already selected
	if ( $this.hasClass('selected') ) {
	  return false;
	}
	var $optionSet = $this.parents('.portfolio_navigation');
	$optionSet.find('.selected').removeClass('selected');
	$this.addClass('selected').append("<span class='button-element'></span>");
	// make option object dynamically, i.e. { filter: '.my-filter-class' }
	var options = {},
		key = $optionSet.attr('data-option-key'),
		value = $this.attr('data-option-value');
	// parse 'false' as false boolean
	value = value === 'false' ? false : value;
	options[ key ] = value;
	return false;
  });
}

// tabs

$(function () {
    var tabContainers = $('div#tabs > div');
    tabContainers.hide().filter(':first').show(500).fadeIn(700);
    $('div#tabs ul.tabs-nav a').click(function () {
        tabContainers.hide();
        tabContainers.filter(this.hash).fadeIn(1000);
        $('div#tabs ul.tabs-nav a').removeClass('tab_selected');
		$('div#tabs ul.tabs-nav a').find("div").remove();
        $(this).addClass('tab_selected');
		$(this).append('<div class="tabs-selected-element1"><div class="tabs-selected-element2"></div><div class="tabs-selected-element3"></div></div>');
        return false;
    }).filter(':first').click();
});


// allert messages

$(function(){
	
	var $blue = $('.alert_information_message');
	var $yellow = $('.alert_warning_message');
	var $green = $('.alert_succes_message');
	var $red = $('.alert_error_message');
	
	$blue.append("<a class='close_message'></a>");
	$yellow.append("<a class='close_message'></a>");
	$green.append("<a class='close_message'></a>");
	$red.append("<a class='close_message'></a>");
	
	$('.close_message').click(function(){
		$(this).parent().animate({opacity:0},1000,function(){
			$(this).slideUp(600,function(){
				$(this).remove();
			})
		});
	});
	$('.ie8 .close_message').click(function(){
		$(this).animate({opacity:0},1000,function(){
			$(this).slideUp(600,function(){
				$(this).remove();
			});
		});
	});
});

// list types

$(function(){

	var $list_link = $('.list-type4>li>a');
	
	$list_link.append("<div class='list_icon_container'><div class='active_link_icon'></div><div class='no_active_link_icon'></div></div>");

});

//tables 
$(function(){
	$('table tr:nth-child(2n)').addClass('table-light-row');
});

$(function(){
	$('.list_of_services div:nth-child(2n+1)').addClass('pricing-table-light-row');
});

// forms

$(function(){
	$('#uploadfile').click(function(){
		$('#upfile').trigger('click');
	});
});

if($("#time_container").length){
function clock() {
	var now = new Date();
	var outStrHours = (now.getHours() < 10) ? ("0" + now.getHours()) : (now.getHours());
	var outStrMinutes = (now.getMinutes() < 10) ? ("0" + now.getMinutes()) : (now.getMinutes());
	var outStrSeconds = (now.getSeconds() < 10) ? ("0" + now.getSeconds()) : (now.getSeconds());
	document.getElementById('time_hour').innerHTML=outStrHours;
	document.getElementById('time_minute').innerHTML=outStrMinutes;
	document.getElementById('time_second').innerHTML = outStrSeconds;
	setTimeout('clock()',1000);
}
clock();
}

// audio

$(document).ready(function(){
	$('.audio_controls li:first-child+li+li a').click(function(){
		if($(this).hasClass('audio_list')){
			$(this).removeClass('audio_list');
		}
		else{
			$(this).addClass('audio_list')
		}
	});
});

$(function(){
	$('.player_controls li a').on('click' , function(){
		$(this).find('img').toggleClass('m_top_0');
	});
});

$(function(){

	var $fstar = $('.popular_list>li:first-child>a');
	var $secstar = $('.popular_list>li:first-child+li>a');
	var $thrstar = $('.popular_list>li:first-child+li+li>a');
	var $frstar = $('.popular_list>li:first-child+li+li+li>a');
	var $fvstar = $('.popular_list>li:first-child+li+li+li+li>a');
	
	$fstar.click(function(){
		$(this).addClass("active_popular");
		$secstar.removeClass("active_popular");
		$thrstar.removeClass("active_popular");
		$frstar.removeClass("active_popular");
		$fvstar.removeClass("active_popular");
	});
	$secstar.click(function(){
		$secstar.addClass("active_popular");
		$thrstar.removeClass("active_popular");
		$frstar.removeClass("active_popular");
		$fvstar.removeClass("active_popular");
	});
	$thrstar.click(function(){
		$secstar.addClass("active_popular");
		$thrstar.addClass("active_popular");
		$frstar.removeClass("active_popular");
		$fvstar.removeClass("active_popular");
	});
	$frstar.click(function(){
		$secstar.addClass("active_popular");
		$thrstar.addClass("active_popular");
		$frstar.addClass("active_popular");
		$fvstar.removeClass("active_popular");
	});
	$fvstar.click(function(){
		$secstar.addClass("active_popular");
		$thrstar.addClass("active_popular");
		$frstar.addClass("active_popular");
		$fvstar.addClass("active_popular");
	});
});

// contact

(function() {

		if($('#contactform').length) {

			var $form = $('#contactform'),
			$loader = '<img src="images/form-preloader.gif" alt="Loading..." />';
			$form.children("fieldset").append('<div id="contact_form_responce">');
			var $response = $('#contact_form_responce');
			$response.append('<p></p>');

			$form.submit(function(e){
				
				$response.find('p').removeClass();
				$response.find('p').html($loader);
				
				var data = {
					action: "contact_form_request",
					values: $("#contactform").serialize()
				};

				//send data to server
				$.post("bat/contact-send.php", data, function(response) {

					response = $.parseJSON(response);
					
					$(".wrong-data").removeClass("wrong-data");
					$response.find('img').remove();

					if(response.is_errors){
						
						$response.find('p').removeClass().addClass("alert_error_message");
						$.each(response.info,function(input_name, input_label) {

							$("[name="+input_name+"]").addClass("wrong-data");
							$response.find('p').append(input_label+ '</br>');
						});

					} else {
						$response.find('p').removeClass().addClass('alert_succes_message');

						if(response.info == 'success'){
							$response.find('p').append('Your message has been successfully sent!');
								$response.delay(10000).hide(500,function(){
									$(this).removeClass().text("").fadeIn(500);
								});
								$form.find('input:not(input[type="submit"], button), textarea, select').val('').attr( 'checked', false );
						}

						if(response.info == 'server_fail'){
							$response.find('p').append('Server failed. Send later!');
						}
					}

					// Scroll to bottom of the form to show respond message
					var bottomPosition = $form.offset().top + $form.outerHeight() - $(window).height();

					if($(document).scrollTop() < bottomPosition) {
						$('html, body').animate({
							scrollTop : bottomPosition
						});
					}

					if(!$('#contact_form_responce').css('display') == 'block') {
						$response.show(450);
					}

				});

				e.preventDefault();

			});				

		}

	})();
	
$(document).ready(function() {
	if ($('#tweets').length) {
		$('#tweets').tweet({
		username: 'Creative_WS',
		count: 2,
		loading_text: 'loading twitter feed...',
		/* etc... */
	});
}
});

// google map 

if($('#gmap').length){
var map;
    $(document).ready(function(){
      map = new GMaps({
        el: '#gmap',
        lat: -12.043333,
        lng: -77.028333,
        zoomControl : true,
        zoomControlOpt: {
            style : 'SMALL',
            position: 'TOP_LEFT'
        },
        panControl : false,
        streetViewControl : false,
        mapTypeControl: false,
        overviewMapControl: false
      });
    });
}

/*!
  GMaps.js v0.4.4
  http://hpneo.github.com/gmaps/
 
  Copyright 2013, Gustavo Leon
  Released under the MIT License.
 */
 if($('#gmap').length){
 if(window.google==undefined&&window.google.maps==undefined){throw'Google Maps API is required. Please register the following JavaScript library http://maps.google.com/maps/api/js?sensor=true.'}
var extend_object=function(obj,new_obj){var name;if(obj===new_obj){return obj;}
for(name in new_obj){obj[name]=new_obj[name];}
return obj;};var replace_object=function(obj,replace){var name;if(obj===replace){return obj;}
for(name in replace){if(obj[name]!=undefined){obj[name]=replace[name];}}
return obj;};var array_map=function(array,callback){var original_callback_params=Array.prototype.slice.call(arguments,2),array_return=[],array_length=array.length,i;if(Array.prototype.map&&array.map===Array.prototype.map){array_return=Array.prototype.map.call(array,function(item){callback_params=original_callback_params;callback_params.splice(0,0,item);return callback.apply(this,callback_params);});}
else{for(i=0;i<array_length;i++){callback_params=original_callback_params;callback_params=callback_params.splice(0,0,array[i]);array_return.push(callback.apply(this,callback_params));}}
return array_return;};var array_flat=function(array){var new_array=[],i;for(i=0;i<array.length;i++){new_array=new_array.concat(array[i]);}
return new_array;};var coordsToLatLngs=function(coords,useGeoJSON){var first_coord=coords[0],second_coord=coords[1];if(useGeoJSON){first_coord=coords[1];second_coord=coords[0];}
return new google.maps.LatLng(first_coord,second_coord);};var arrayToLatLng=function(coords,useGeoJSON){var i;for(i=0;i<coords.length;i++){if(coords[i].length>0&&typeof(coords[i][0])!="number"){coords[i]=arrayToLatLng(coords[i],useGeoJSON);}
else{coords[i]=coordsToLatLngs(coords[i],useGeoJSON);}}
return coords;};var getElementById=function(id,context){var element,id=id.replace('#','');if('jQuery'in this&&context){element=$("#"+id,context)[0];}else{element=document.getElementById(id);};return element;};var findAbsolutePosition=function(obj){var curleft=0,curtop=0;if(obj.offsetParent){do{curleft+=obj.offsetLeft;curtop+=obj.offsetTop;}while(obj=obj.offsetParent);}
return[curleft,curtop];};var GMaps=(function(global){"use strict";var doc=document;var GMaps=function(options){options.zoom=options.zoom||15;options.mapType=options.mapType||'roadmap';var self=this,i,events_that_hide_context_menu=['bounds_changed','center_changed','click','dblclick','drag','dragend','dragstart','idle','maptypeid_changed','projection_changed','resize','tilesloaded','zoom_changed'],events_that_doesnt_hide_context_menu=['mousemove','mouseout','mouseover'],options_to_be_deleted=['el','lat','lng','mapType','width','height','markerClusterer','enableNewStyle'],container_id=options.el||options.div,markerClustererFunction=options.markerClusterer,mapType=google.maps.MapTypeId[options.mapType.toUpperCase()],map_center=new google.maps.LatLng(options.lat,options.lng),zoomControl=options.zoomControl||true,zoomControlOpt=options.zoomControlOpt||{style:'DEFAULT',position:'TOP_LEFT'},zoomControlStyle=zoomControlOpt.style||'DEFAULT',zoomControlPosition=zoomControlOpt.position||'TOP_LEFT',panControl=options.panControl||true,mapTypeControl=options.mapTypeControl||true,scaleControl=options.scaleControl||true,streetViewControl=options.streetViewControl||true,overviewMapControl=overviewMapControl||true,map_options={},map_base_options={zoom:this.zoom,center:map_center,mapTypeId:mapType},map_controls_options={panControl:panControl,zoomControl:zoomControl,zoomControlOptions:{style:google.maps.ZoomControlStyle[zoomControlStyle],position:google.maps.ControlPosition[zoomControlPosition]},mapTypeControl:mapTypeControl,scaleControl:scaleControl,streetViewControl:streetViewControl,overviewMapControl:overviewMapControl};if(typeof(options.el)==='string'||typeof(options.div)==='string'){this.el=getElementById(container_id,options.context);}else{this.el=container_id;}
if(typeof(this.el)==='undefined'||this.el===null){throw'No element defined.';}
window.context_menu=window.context_menu||{};window.context_menu[self.el.id]={};this.controls=[];this.overlays=[];this.layers=[];this.singleLayers={};this.markers=[];this.polylines=[];this.routes=[];this.polygons=[];this.infoWindow=null;this.overlay_el=null;this.zoom=options.zoom;this.registered_events={};this.el.style.width=options.width||this.el.scrollWidth||this.el.offsetWidth;this.el.style.height=options.height||this.el.scrollHeight||this.el.offsetHeight;google.maps.visualRefresh=options.enableNewStyle;for(i=0;i<options_to_be_deleted.length;i++){delete options[options_to_be_deleted[i]];}
if(options.disableDefaultUI!=true){map_base_options=extend_object(map_base_options,map_controls_options);}
map_options=extend_object(map_base_options,options);for(i=0;i<events_that_hide_context_menu.length;i++){delete map_options[events_that_hide_context_menu[i]];}
for(i=0;i<events_that_doesnt_hide_context_menu.length;i++){delete map_options[events_that_doesnt_hide_context_menu[i]];}
this.map=new google.maps.Map(this.el,map_options);if(markerClustererFunction){this.markerClusterer=markerClustererFunction.apply(this,[this.map]);}
var buildContextMenuHTML=function(control,e){var html='',options=window.context_menu[self.el.id][control];for(var i in options){if(options.hasOwnProperty(i)){var option=options[i];html+='<li><a id="'+control+'_'+i+'" href="#">'+option.title+'</a></li>';}}
if(!getElementById('gmaps_context_menu'))return;var context_menu_element=getElementById('gmaps_context_menu');context_menu_element.innerHTML=html;var context_menu_items=context_menu_element.getElementsByTagName('a'),context_menu_items_count=context_menu_items.length
i;for(i=0;i<context_menu_items_count;i++){var context_menu_item=context_menu_items[i];var assign_menu_item_action=function(ev){ev.preventDefault();options[this.id.replace(control+'_','')].action.apply(self,[e]);self.hideContextMenu();};google.maps.event.clearListeners(context_menu_item,'click');google.maps.event.addDomListenerOnce(context_menu_item,'click',assign_menu_item_action,false);}
var position=findAbsolutePosition.apply(this,[self.el]),left=position[0]+e.pixel.x-15,top=position[1]+e.pixel.y-15;context_menu_element.style.left=left+"px";context_menu_element.style.top=top+"px";context_menu_element.style.display='block';};this.buildContextMenu=function(control,e){if(control==='marker'){e.pixel={};var overlay=new google.maps.OverlayView();overlay.setMap(self.map);overlay.draw=function(){var projection=overlay.getProjection(),position=e.marker.getPosition();e.pixel=projection.fromLatLngToContainerPixel(position);buildContextMenuHTML(control,e);};}
else{buildContextMenuHTML(control,e);}};this.setContextMenu=function(options){window.context_menu[self.el.id][options.control]={};var i,ul=doc.createElement('ul');for(i in options.options){if(options.options.hasOwnProperty(i)){var option=options.options[i];window.context_menu[self.el.id][options.control][option.name]={title:option.title,action:option.action};}}
ul.id='gmaps_context_menu';ul.style.display='none';ul.style.position='absolute';ul.style.minWidth='100px';ul.style.background='white';ul.style.listStyle='none';ul.style.padding='8px';ul.style.boxShadow='2px 2px 6px #ccc';doc.body.appendChild(ul);var context_menu_element=getElementById('gmaps_context_menu')
google.maps.event.addDomListener(context_menu_element,'mouseout',function(ev){if(!ev.relatedTarget||!this.contains(ev.relatedTarget)){window.setTimeout(function(){context_menu_element.style.display='none';},400);}},false);};this.hideContextMenu=function(){var context_menu_element=getElementById('gmaps_context_menu');if(context_menu_element){context_menu_element.style.display='none';}};var setupListener=function(object,name){google.maps.event.addListener(object,name,function(e){if(e==undefined){e=this;}
options[name].apply(this,[e]);self.hideContextMenu();});};for(var ev=0;ev<events_that_hide_context_menu.length;ev++){var name=events_that_hide_context_menu[ev];if(name in options){setupListener(this.map,name);}}
for(var ev=0;ev<events_that_doesnt_hide_context_menu.length;ev++){var name=events_that_doesnt_hide_context_menu[ev];if(name in options){setupListener(this.map,name);}}
google.maps.event.addListener(this.map,'rightclick',function(e){if(options.rightclick){options.rightclick.apply(this,[e]);}
if(window.context_menu[self.el.id]['map']!=undefined){self.buildContextMenu('map',e);}});this.refresh=function(){google.maps.event.trigger(this.map,'resize');};this.fitZoom=function(){var latLngs=[],markers_length=this.markers.length,i;for(i=0;i<markers_length;i++){latLngs.push(this.markers[i].getPosition());}
this.fitLatLngBounds(latLngs);};this.fitLatLngBounds=function(latLngs){var total=latLngs.length;var bounds=new google.maps.LatLngBounds();for(var i=0;i<total;i++){bounds.extend(latLngs[i]);}
this.map.fitBounds(bounds);};this.setCenter=function(lat,lng,callback){this.map.panTo(new google.maps.LatLng(lat,lng));if(callback){callback();}};this.getElement=function(){return this.el;};this.zoomIn=function(value){value=value||1;this.zoom=this.map.getZoom()+value;this.map.setZoom(this.zoom);};this.zoomOut=function(value){value=value||1;this.zoom=this.map.getZoom()-value;this.map.setZoom(this.zoom);};var native_methods=[],method;for(method in this.map){if(typeof(this.map[method])=='function'&&!this[method]){native_methods.push(method);}}
for(i=0;i<native_methods.length;i++){(function(gmaps,scope,method_name){gmaps[method_name]=function(){return scope[method_name].apply(scope,arguments);};})(this,this.map,native_methods[i]);}};return GMaps;})(this);GMaps.prototype.createControl=function(options){var control=document.createElement('div');control.style.cursor='pointer';control.style.fontFamily='Arial, sans-serif';control.style.fontSize='13px';control.style.boxShadow='rgba(0, 0, 0, 0.398438) 0px 2px 4px';for(var option in options.style){control.style[option]=options.style[option];}
if(options.id){control.id=options.id;}
if(options.classes){control.className=options.classes;}
if(options.content){control.innerHTML=options.content;}
for(var ev in options.events){(function(object,name){google.maps.event.addDomListener(object,name,function(){options.events[name].apply(this,[this]);});})(control,ev);}
control.index=1;return control;};GMaps.prototype.addControl=function(options){var position=google.maps.ControlPosition[options.position.toUpperCase()];delete options.position;var control=this.createControl(options);this.controls.push(control);this.map.controls[position].push(control);return control;};GMaps.prototype.createMarker=function(options){if(options.lat==undefined&&options.lng==undefined&&options.position==undefined){throw'No latitude or longitude defined.';}
var self=this,details=options.details,fences=options.fences,outside=options.outside,base_options={position:new google.maps.LatLng(options.lat,options.lng),map:null};delete options.lat;delete options.lng;delete options.fences;delete options.outside;var marker_options=extend_object(base_options,options),marker=new google.maps.Marker(marker_options);marker.fences=fences;if(options.infoWindow){marker.infoWindow=new google.maps.InfoWindow(options.infoWindow);var info_window_events=['closeclick','content_changed','domready','position_changed','zindex_changed'];for(var ev=0;ev<info_window_events.length;ev++){(function(object,name){if(options.infoWindow[name]){google.maps.event.addListener(object,name,function(e){options.infoWindow[name].apply(this,[e]);});}})(marker.infoWindow,info_window_events[ev]);}}
var marker_events=['animation_changed','clickable_changed','cursor_changed','draggable_changed','flat_changed','icon_changed','position_changed','shadow_changed','shape_changed','title_changed','visible_changed','zindex_changed'];var marker_events_with_mouse=['dblclick','drag','dragend','dragstart','mousedown','mouseout','mouseover','mouseup'];for(var ev=0;ev<marker_events.length;ev++){(function(object,name){if(options[name]){google.maps.event.addListener(object,name,function(){options[name].apply(this,[this]);});}})(marker,marker_events[ev]);}
for(var ev=0;ev<marker_events_with_mouse.length;ev++){(function(map,object,name){if(options[name]){google.maps.event.addListener(object,name,function(me){if(!me.pixel){me.pixel=map.getProjection().fromLatLngToPoint(me.latLng)}
options[name].apply(this,[me]);});}})(this.map,marker,marker_events_with_mouse[ev]);}
google.maps.event.addListener(marker,'click',function(){this.details=details;if(options.click){options.click.apply(this,[this]);}
if(marker.infoWindow){self.hideInfoWindows();marker.infoWindow.open(self.map,marker);}});google.maps.event.addListener(marker,'rightclick',function(e){e.marker=this;if(options.rightclick){options.rightclick.apply(this,[e]);}
if(window.context_menu[self.el.id]['marker']!=undefined){self.buildContextMenu('marker',e);}});if(marker.fences){google.maps.event.addListener(marker,'dragend',function(){self.checkMarkerGeofence(marker,function(m,f){outside(m,f);});});}
return marker;};GMaps.prototype.addMarker=function(options){var marker;if(options.hasOwnProperty('gm_accessors_')){marker=options;}
else{if((options.hasOwnProperty('lat')&&options.hasOwnProperty('lng'))||options.position){marker=this.createMarker(options);}
else{throw'No latitude or longitude defined.';}}
marker.setMap(this.map);if(this.markerClusterer){this.markerClusterer.addMarker(marker);}
this.markers.push(marker);GMaps.fire('marker_added',marker,this);return marker;};GMaps.prototype.addMarkers=function(array){for(var i=0,marker;marker=array[i];i++){this.addMarker(marker);}
return this.markers;};GMaps.prototype.hideInfoWindows=function(){for(var i=0,marker;marker=this.markers[i];i++){if(marker.infoWindow){marker.infoWindow.close();}}};GMaps.prototype.removeMarker=function(marker){for(var i=0;i<this.markers.length;i++){if(this.markers[i]===marker){this.markers[i].setMap(null);this.markers.splice(i,1);GMaps.fire('marker_removed',marker,this);break;}}
return marker;};GMaps.prototype.removeMarkers=function(collection){var collection=(collection||this.markers);for(var i=0;i<this.markers.length;i++){if(this.markers[i]===collection[i]){this.markers[i].setMap(null);}}
var new_markers=[];for(var i=0;i<this.markers.length;i++){if(this.markers[i].getMap()!=null){new_markers.push(this.markers[i]);}}
this.markers=new_markers;};GMaps.prototype.drawOverlay=function(options){var overlay=new google.maps.OverlayView(),auto_show=true;overlay.setMap(this.map);if(options.auto_show!=null){auto_show=options.auto_show;}
overlay.onAdd=function(){var el=document.createElement('div');el.style.borderStyle="none";el.style.borderWidth="0px";el.style.position="absolute";el.style.zIndex=100;el.innerHTML=options.content;overlay.el=el;if(!options.layer){options.layer='overlayLayer';}
var panes=this.getPanes(),overlayLayer=panes[options.layer],stop_overlay_events=['contextmenu','DOMMouseScroll','dblclick','mousedown'];overlayLayer.appendChild(el);for(var ev=0;ev<stop_overlay_events.length;ev++){(function(object,name){google.maps.event.addDomListener(object,name,function(e){if(navigator.userAgent.toLowerCase().indexOf('msie')!=-1&&document.all){e.cancelBubble=true;e.returnValue=false;}
else{e.stopPropagation();}});})(el,stop_overlay_events[ev]);}
google.maps.event.trigger(this,'ready');};overlay.draw=function(){var projection=this.getProjection(),pixel=projection.fromLatLngToDivPixel(new google.maps.LatLng(options.lat,options.lng));options.horizontalOffset=options.horizontalOffset||0;options.verticalOffset=options.verticalOffset||0;var el=overlay.el,content=el.children[0],content_height=content.clientHeight,content_width=content.clientWidth;switch(options.verticalAlign){case'top':el.style.top=(pixel.y-content_height+options.verticalOffset)+'px';break;default:case'middle':el.style.top=(pixel.y-(content_height/2)+options.verticalOffset)+'px';break;case'bottom':el.style.top=(pixel.y+options.verticalOffset)+'px';break;}
switch(options.horizontalAlign){case'left':el.style.left=(pixel.x-content_width+options.horizontalOffset)+'px';break;default:case'center':el.style.left=(pixel.x-(content_width/2)+options.horizontalOffset)+'px';break;case'right':el.style.left=(pixel.x+options.horizontalOffset)+'px';break;}
el.style.display=auto_show?'block':'none';if(!auto_show){options.show.apply(this,[el]);}};overlay.onRemove=function(){var el=overlay.el;if(options.remove){options.remove.apply(this,[el]);}
else{overlay.el.parentNode.removeChild(overlay.el);overlay.el=null;}};this.overlays.push(overlay);return overlay;};GMaps.prototype.removeOverlay=function(overlay){for(var i=0;i<this.overlays.length;i++){if(this.overlays[i]===overlay){this.overlays[i].setMap(null);this.overlays.splice(i,1);break;}}};GMaps.prototype.removeOverlays=function(){for(var i=0,item;item=this.overlays[i];i++){item.setMap(null);}
this.overlays=[];};GMaps.prototype.drawPolyline=function(options){var path=[],points=options.path;if(points.length){if(points[0][0]===undefined){path=points;}
else{for(var i=0,latlng;latlng=points[i];i++){path.push(new google.maps.LatLng(latlng[0],latlng[1]));}}}
var polyline_options={map:this.map,path:path,strokeColor:options.strokeColor,strokeOpacity:options.strokeOpacity,strokeWeight:options.strokeWeight,geodesic:options.geodesic,clickable:true,editable:false,visible:true};if(options.hasOwnProperty("clickable")){polyline_options.clickable=options.clickable;}
if(options.hasOwnProperty("editable")){polyline_options.editable=options.editable;}
if(options.hasOwnProperty("icons")){polyline_options.icons=options.icons;}
if(options.hasOwnProperty("zIndex")){polyline_options.zIndex=options.zIndex;}
var polyline=new google.maps.Polyline(polyline_options);var polyline_events=['click','dblclick','mousedown','mousemove','mouseout','mouseover','mouseup','rightclick'];for(var ev=0;ev<polyline_events.length;ev++){(function(object,name){if(options[name]){google.maps.event.addListener(object,name,function(e){options[name].apply(this,[e]);});}})(polyline,polyline_events[ev]);}
this.polylines.push(polyline);GMaps.fire('polyline_added',polyline,this);return polyline;};GMaps.prototype.removePolyline=function(polyline){for(var i=0;i<this.polylines.length;i++){if(this.polylines[i]===polyline){this.polylines[i].setMap(null);this.polylines.splice(i,1);GMaps.fire('polyline_removed',polyline,this);break;}}};GMaps.prototype.removePolylines=function(){for(var i=0,item;item=this.polylines[i];i++){item.setMap(null);}
this.polylines=[];};GMaps.prototype.drawCircle=function(options){options=extend_object({map:this.map,center:new google.maps.LatLng(options.lat,options.lng)},options);delete options.lat;delete options.lng;var polygon=new google.maps.Circle(options),polygon_events=['click','dblclick','mousedown','mousemove','mouseout','mouseover','mouseup','rightclick'];for(var ev=0;ev<polygon_events.length;ev++){(function(object,name){if(options[name]){google.maps.event.addListener(object,name,function(e){options[name].apply(this,[e]);});}})(polygon,polygon_events[ev]);}
this.polygons.push(polygon);return polygon;};GMaps.prototype.drawRectangle=function(options){options=extend_object({map:this.map},options);var latLngBounds=new google.maps.LatLngBounds(new google.maps.LatLng(options.bounds[0][0],options.bounds[0][1]),new google.maps.LatLng(options.bounds[1][0],options.bounds[1][1]));options.bounds=latLngBounds;var polygon=new google.maps.Rectangle(options),polygon_events=['click','dblclick','mousedown','mousemove','mouseout','mouseover','mouseup','rightclick'];for(var ev=0;ev<polygon_events.length;ev++){(function(object,name){if(options[name]){google.maps.event.addListener(object,name,function(e){options[name].apply(this,[e]);});}})(polygon,polygon_events[ev]);}
this.polygons.push(polygon);return polygon;};GMaps.prototype.drawPolygon=function(options){var useGeoJSON=false;if(options.hasOwnProperty("useGeoJSON")){useGeoJSON=options.useGeoJSON;}
delete options.useGeoJSON;options=extend_object({map:this.map},options);if(useGeoJSON==false){options.paths=[options.paths.slice(0)];}
if(options.paths.length>0){if(options.paths[0].length>0){options.paths=array_flat(array_map(options.paths,arrayToLatLng,useGeoJSON));}}
var polygon=new google.maps.Polygon(options),polygon_events=['click','dblclick','mousedown','mousemove','mouseout','mouseover','mouseup','rightclick'];for(var ev=0;ev<polygon_events.length;ev++){(function(object,name){if(options[name]){google.maps.event.addListener(object,name,function(e){options[name].apply(this,[e]);});}})(polygon,polygon_events[ev]);}
this.polygons.push(polygon);GMaps.fire('polygon_added',polygon,this);return polygon;};GMaps.prototype.removePolygon=function(polygon){for(var i=0;i<this.polygons.length;i++){if(this.polygons[i]===polygon){this.polygons[i].setMap(null);this.polygons.splice(i,1);GMaps.fire('polygon_removed',polygon,this);break;}}};GMaps.prototype.removePolygons=function(){for(var i=0,item;item=this.polygons[i];i++){item.setMap(null);}
this.polygons=[];};GMaps.prototype.getFromFusionTables=function(options){var events=options.events;delete options.events;var fusion_tables_options=options,layer=new google.maps.FusionTablesLayer(fusion_tables_options);for(var ev in events){(function(object,name){google.maps.event.addListener(object,name,function(e){events[name].apply(this,[e]);});})(layer,ev);}
this.layers.push(layer);return layer;};GMaps.prototype.loadFromFusionTables=function(options){var layer=this.getFromFusionTables(options);layer.setMap(this.map);return layer;};GMaps.prototype.getFromKML=function(options){var url=options.url,events=options.events;delete options.url;delete options.events;var kml_options=options,layer=new google.maps.KmlLayer(url,kml_options);for(var ev in events){(function(object,name){google.maps.event.addListener(object,name,function(e){events[name].apply(this,[e]);});})(layer,ev);}
this.layers.push(layer);return layer;};GMaps.prototype.loadFromKML=function(options){var layer=this.getFromKML(options);layer.setMap(this.map);return layer;};GMaps.prototype.addLayer=function(layerName,options){options=options||{};var layer;switch(layerName){case'weather':this.singleLayers.weather=layer=new google.maps.weather.WeatherLayer();break;case'clouds':this.singleLayers.clouds=layer=new google.maps.weather.CloudLayer();break;case'traffic':this.singleLayers.traffic=layer=new google.maps.TrafficLayer();break;case'transit':this.singleLayers.transit=layer=new google.maps.TransitLayer();break;case'bicycling':this.singleLayers.bicycling=layer=new google.maps.BicyclingLayer();break;case'panoramio':this.singleLayers.panoramio=layer=new google.maps.panoramio.PanoramioLayer();layer.setTag(options.filter);delete options.filter;if(options.click){google.maps.event.addListener(layer,'click',function(event){options.click(event);delete options.click;});}
break;case'places':this.singleLayers.places=layer=new google.maps.places.PlacesService(this.map);if(options.search||options.nearbySearch){var placeSearchRequest={bounds:options.bounds||null,keyword:options.keyword||null,location:options.location||null,name:options.name||null,radius:options.radius||null,rankBy:options.rankBy||null,types:options.types||null};if(options.search){layer.search(placeSearchRequest,options.search);}
if(options.nearbySearch){layer.nearbySearch(placeSearchRequest,options.nearbySearch);}}
if(options.textSearch){var textSearchRequest={bounds:options.bounds||null,location:options.location||null,query:options.query||null,radius:options.radius||null};layer.textSearch(textSearchRequest,options.textSearch);}
break;}
if(layer!==undefined){if(typeof layer.setOptions=='function'){layer.setOptions(options);}
if(typeof layer.setMap=='function'){layer.setMap(this.map);}
return layer;}};GMaps.prototype.removeLayer=function(layer){if(typeof(layer)=="string"&&this.singleLayers[layer]!==undefined){this.singleLayers[layer].setMap(null);delete this.singleLayers[layer];}
else{for(var i=0;i<this.layers.length;i++){if(this.layers[i]===layer){this.layers[i].setMap(null);this.layers.splice(i,1);break;}}}};var travelMode,unitSystem;GMaps.prototype.getRoutes=function(options){switch(options.travelMode){case'bicycling':travelMode=google.maps.TravelMode.BICYCLING;break;case'transit':travelMode=google.maps.TravelMode.TRANSIT;break;case'driving':travelMode=google.maps.TravelMode.DRIVING;break;default:travelMode=google.maps.TravelMode.WALKING;break;}
if(options.unitSystem==='imperial'){unitSystem=google.maps.UnitSystem.IMPERIAL;}
else{unitSystem=google.maps.UnitSystem.METRIC;}
var base_options={avoidHighways:false,avoidTolls:false,optimizeWaypoints:false,waypoints:[]},request_options=extend_object(base_options,options);request_options.origin=/string/.test(typeof options.origin)?options.origin:new google.maps.LatLng(options.origin[0],options.origin[1]);request_options.destination=/string/.test(typeof options.destination)?options.destination:new google.maps.LatLng(options.destination[0],options.destination[1]);request_options.travelMode=travelMode;request_options.unitSystem=unitSystem;delete request_options.callback;var self=this,service=new google.maps.DirectionsService();service.route(request_options,function(result,status){if(status===google.maps.DirectionsStatus.OK){for(var r in result.routes){if(result.routes.hasOwnProperty(r)){self.routes.push(result.routes[r]);}}}
if(options.callback){options.callback(self.routes);}});};GMaps.prototype.removeRoutes=function(){this.routes=[];};GMaps.prototype.getElevations=function(options){options=extend_object({locations:[],path:false,samples:256},options);if(options.locations.length>0){if(options.locations[0].length>0){options.locations=array_flat(array_map([options.locations],arrayToLatLng,false));}}
var callback=options.callback;delete options.callback;var service=new google.maps.ElevationService();if(!options.path){delete options.path;delete options.samples;service.getElevationForLocations(options,function(result,status){if(callback&&typeof(callback)==="function"){callback(result,status);}});}else{var pathRequest={path:options.locations,samples:options.samples};service.getElevationAlongPath(pathRequest,function(result,status){if(callback&&typeof(callback)==="function"){callback(result,status);}});}};GMaps.prototype.cleanRoute=GMaps.prototype.removePolylines;GMaps.prototype.drawRoute=function(options){var self=this;this.getRoutes({origin:options.origin,destination:options.destination,travelMode:options.travelMode,waypoints:options.waypoints,unitSystem:options.unitSystem,callback:function(e){if(e.length>0){self.drawPolyline({path:e[e.length-1].overview_path,strokeColor:options.strokeColor,strokeOpacity:options.strokeOpacity,strokeWeight:options.strokeWeight});if(options.callback){options.callback(e[e.length-1]);}}}});};GMaps.prototype.travelRoute=function(options){if(options.origin&&options.destination){this.getRoutes({origin:options.origin,destination:options.destination,travelMode:options.travelMode,waypoints:options.waypoints,callback:function(e){if(e.length>0&&options.start){options.start(e[e.length-1]);}
if(e.length>0&&options.step){var route=e[e.length-1];if(route.legs.length>0){var steps=route.legs[0].steps;for(var i=0,step;step=steps[i];i++){step.step_number=i;options.step(step,(route.legs[0].steps.length-1));}}}
if(e.length>0&&options.end){options.end(e[e.length-1]);}}});}
else if(options.route){if(options.route.legs.length>0){var steps=options.route.legs[0].steps;for(var i=0,step;step=steps[i];i++){step.step_number=i;options.step(step);}}}};GMaps.prototype.drawSteppedRoute=function(options){var self=this;if(options.origin&&options.destination){this.getRoutes({origin:options.origin,destination:options.destination,travelMode:options.travelMode,waypoints:options.waypoints,callback:function(e){if(e.length>0&&options.start){options.start(e[e.length-1]);}
if(e.length>0&&options.step){var route=e[e.length-1];if(route.legs.length>0){var steps=route.legs[0].steps;for(var i=0,step;step=steps[i];i++){step.step_number=i;self.drawPolyline({path:step.path,strokeColor:options.strokeColor,strokeOpacity:options.strokeOpacity,strokeWeight:options.strokeWeight});options.step(step,(route.legs[0].steps.length-1));}}}
if(e.length>0&&options.end){options.end(e[e.length-1]);}}});}
else if(options.route){if(options.route.legs.length>0){var steps=options.route.legs[0].steps;for(var i=0,step;step=steps[i];i++){step.step_number=i;self.drawPolyline({path:step.path,strokeColor:options.strokeColor,strokeOpacity:options.strokeOpacity,strokeWeight:options.strokeWeight});options.step(step);}}}};GMaps.Route=function(options){this.origin=options.origin;this.destination=options.destination;this.waypoints=options.waypoints;this.map=options.map;this.route=options.route;this.step_count=0;this.steps=this.route.legs[0].steps;this.steps_length=this.steps.length;this.polyline=this.map.drawPolyline({path:new google.maps.MVCArray(),strokeColor:options.strokeColor,strokeOpacity:options.strokeOpacity,strokeWeight:options.strokeWeight}).getPath();};GMaps.Route.prototype.getRoute=function(options){var self=this;this.map.getRoutes({origin:this.origin,destination:this.destination,travelMode:options.travelMode,waypoints:this.waypoints||[],callback:function(){self.route=e[0];if(options.callback){options.callback.call(self);}}});};GMaps.Route.prototype.back=function(){if(this.step_count>0){this.step_count--;var path=this.route.legs[0].steps[this.step_count].path;for(var p in path){if(path.hasOwnProperty(p)){this.polyline.pop();}}}};GMaps.Route.prototype.forward=function(){if(this.step_count<this.steps_length){var path=this.route.legs[0].steps[this.step_count].path;for(var p in path){if(path.hasOwnProperty(p)){this.polyline.push(path[p]);}}
this.step_count++;}};GMaps.prototype.checkGeofence=function(lat,lng,fence){return fence.containsLatLng(new google.maps.LatLng(lat,lng));};GMaps.prototype.checkMarkerGeofence=function(marker,outside_callback){if(marker.fences){for(var i=0,fence;fence=marker.fences[i];i++){var pos=marker.getPosition();if(!this.checkGeofence(pos.lat(),pos.lng(),fence)){outside_callback(marker,fence);}}}};GMaps.prototype.toImage=function(options){var options=options||{},static_map_options={};static_map_options['size']=options['size']||[this.el.clientWidth,this.el.clientHeight];static_map_options['lat']=this.getCenter().lat();static_map_options['lng']=this.getCenter().lng();if(this.markers.length>0){static_map_options['markers']=[];for(var i=0;i<this.markers.length;i++){static_map_options['markers'].push({lat:this.markers[i].getPosition().lat(),lng:this.markers[i].getPosition().lng()});}}
if(this.polylines.length>0){var polyline=this.polylines[0];static_map_options['polyline']={};static_map_options['polyline']['path']=google.maps.geometry.encoding.encodePath(polyline.getPath());static_map_options['polyline']['strokeColor']=polyline.strokeColor
static_map_options['polyline']['strokeOpacity']=polyline.strokeOpacity
static_map_options['polyline']['strokeWeight']=polyline.strokeWeight}
return GMaps.staticMapURL(static_map_options);};GMaps.staticMapURL=function(options){var parameters=[],data,static_root='http://maps.googleapis.com/maps/api/staticmap';if(options.url){static_root=options.url;delete options.url;}
static_root+='?';var markers=options.markers;delete options.markers;if(!markers&&options.marker){markers=[options.marker];delete options.marker;}
var polyline=options.polyline;delete options.polyline;if(options.center){parameters.push('center='+options.center);delete options.center;}
else if(options.address){parameters.push('center='+options.address);delete options.address;}
else if(options.lat){parameters.push(['center=',options.lat,',',options.lng].join(''));delete options.lat;delete options.lng;}
else if(options.visible){var visible=encodeURI(options.visible.join('|'));parameters.push('visible='+visible);}
var size=options.size;if(size){if(size.join){size=size.join('x');}
delete options.size;}
else{size='630x300';}
parameters.push('size='+size);if(!options.zoom){options.zoom=15;}
var sensor=options.hasOwnProperty('sensor')?!!options.sensor:true;delete options.sensor;parameters.push('sensor='+sensor);for(var param in options){if(options.hasOwnProperty(param)){parameters.push(param+'='+options[param]);}}
if(markers){var marker,loc;for(var i=0;data=markers[i];i++){marker=[];if(data.size&&data.size!=='normal'){marker.push('size:'+data.size);}
else if(data.icon){marker.push('icon:'+encodeURI(data.icon));}
if(data.color){marker.push('color:'+data.color.replace('#','0x'));}
if(data.label){marker.push('label:'+data.label[0].toUpperCase());}
loc=(data.address?data.address:data.lat+','+data.lng);if(marker.length||i===0){marker.push(loc);marker=marker.join('|');parameters.push('markers='+encodeURI(marker));}
else{marker=parameters.pop()+encodeURI('|'+loc);parameters.push(marker);}}}
function parseColor(color,opacity){if(color[0]==='#'){color=color.replace('#','0x');if(opacity){opacity=parseFloat(opacity);opacity=Math.min(1,Math.max(opacity,0));if(opacity===0){return'0x00000000';}
opacity=(opacity*255).toString(16);if(opacity.length===1){opacity+=opacity;}
color=color.slice(0,8)+opacity;}}
return color;}
if(polyline){data=polyline;polyline=[];if(data.strokeWeight){polyline.push('weight:'+parseInt(data.strokeWeight,10));}
if(data.strokeColor){var color=parseColor(data.strokeColor,data.strokeOpacity);polyline.push('color:'+color);}
if(data.fillColor){var fillcolor=parseColor(data.fillColor,data.fillOpacity);polyline.push('fillcolor:'+fillcolor);}
var path=data.path;if(path.join){for(var j=0,pos;pos=path[j];j++){polyline.push(pos.join(','));}}
else{polyline.push('enc:'+path);}
polyline=polyline.join('|');parameters.push('path='+encodeURI(polyline));}
parameters=parameters.join('&');return static_root+parameters;};GMaps.prototype.addMapType=function(mapTypeId,options){if(options.hasOwnProperty("getTileUrl")&&typeof(options["getTileUrl"])=="function"){options.tileSize=options.tileSize||new google.maps.Size(256,256);var mapType=new google.maps.ImageMapType(options);this.map.mapTypes.set(mapTypeId,mapType);}
else{throw"'getTileUrl' function required.";}};GMaps.prototype.addOverlayMapType=function(options){if(options.hasOwnProperty("getTile")&&typeof(options["getTile"])=="function"){var overlayMapTypeIndex=options.index;delete options.index;this.map.overlayMapTypes.insertAt(overlayMapTypeIndex,options);}
else{throw"'getTile' function required.";}};GMaps.prototype.removeOverlayMapType=function(overlayMapTypeIndex){this.map.overlayMapTypes.removeAt(overlayMapTypeIndex);};GMaps.prototype.addStyle=function(options){var styledMapType=new google.maps.StyledMapType(options.styles,options.styledMapName);this.map.mapTypes.set(options.mapTypeId,styledMapType);};GMaps.prototype.setStyle=function(mapTypeId){this.map.setMapTypeId(mapTypeId);};GMaps.prototype.createPanorama=function(streetview_options){if(!streetview_options.hasOwnProperty('lat')||!streetview_options.hasOwnProperty('lng')){streetview_options.lat=this.getCenter().lat();streetview_options.lng=this.getCenter().lng();}
this.panorama=GMaps.createPanorama(streetview_options);this.map.setStreetView(this.panorama);return this.panorama;};GMaps.createPanorama=function(options){var el=getElementById(options.el,options.context);options.position=new google.maps.LatLng(options.lat,options.lng);delete options.el;delete options.context;delete options.lat;delete options.lng;var streetview_events=['closeclick','links_changed','pano_changed','position_changed','pov_changed','resize','visible_changed'],streetview_options=extend_object({visible:true},options);for(var i=0;i<streetview_events.length;i++){delete streetview_options[streetview_events[i]];}
var panorama=new google.maps.StreetViewPanorama(el,streetview_options);for(var i=0;i<streetview_events.length;i++){(function(object,name){if(options[name]){google.maps.event.addListener(object,name,function(){options[name].apply(this);});}})(panorama,streetview_events[i]);}
return panorama;};GMaps.prototype.on=function(event_name,handler){return GMaps.on(event_name,this,handler);};GMaps.prototype.off=function(event_name){GMaps.off(event_name,this);};GMaps.custom_events=['marker_added','marker_removed','polyline_added','polyline_removed','polygon_added','polygon_removed','geolocated','geolocation_failed'];GMaps.on=function(event_name,object,handler){if(GMaps.custom_events.indexOf(event_name)==-1){return google.maps.event.addListener(object,event_name,handler);}
else{var registered_event={handler:handler,eventName:event_name};object.registered_events[event_name]=object.registered_events[event_name]||[];object.registered_events[event_name].push(registered_event);return registered_event;}};GMaps.off=function(event_name,object){if(GMaps.custom_events.indexOf(event_name)==-1){google.maps.event.clearListeners(object,event_name);}
else{object.registered_events[event_name]=[];}};GMaps.fire=function(event_name,object,scope){if(GMaps.custom_events.indexOf(event_name)==-1){google.maps.event.trigger(object,event_name,Array.prototype.slice.apply(arguments).slice(2));}
else{if(event_name in scope.registered_events){var firing_events=scope.registered_events[event_name];for(var i=0;i<firing_events.length;i++){(function(handler,scope,object){handler.apply(scope,[object]);})(firing_events[i]['handler'],scope,object);}}}};GMaps.geolocate=function(options){var complete_callback=options.always||options.complete;if(navigator.geolocation){navigator.geolocation.getCurrentPosition(function(position){options.success(position);if(complete_callback){complete_callback();}},function(error){options.error(error);if(complete_callback){complete_callback();}},options.options);}
else{options.not_supported();if(complete_callback){complete_callback();}}};GMaps.geocode=function(options){this.geocoder=new google.maps.Geocoder();var callback=options.callback;if(options.hasOwnProperty('lat')&&options.hasOwnProperty('lng')){options.latLng=new google.maps.LatLng(options.lat,options.lng);}
delete options.lat;delete options.lng;delete options.callback;this.geocoder.geocode(options,function(results,status){callback(results,status);});};if(!google.maps.Polygon.prototype.getBounds){google.maps.Polygon.prototype.getBounds=function(latLng){var bounds=new google.maps.LatLngBounds();var paths=this.getPaths();var path;for(var p=0;p<paths.getLength();p++){path=paths.getAt(p);for(var i=0;i<path.getLength();i++){bounds.extend(path.getAt(i));}}
return bounds;};}
if(!google.maps.Polygon.prototype.containsLatLng){google.maps.Polygon.prototype.containsLatLng=function(latLng){var bounds=this.getBounds();if(bounds!==null&&!bounds.contains(latLng)){return false;}
var inPoly=false;var numPaths=this.getPaths().getLength();for(var p=0;p<numPaths;p++){var path=this.getPaths().getAt(p);var numPoints=path.getLength();var j=numPoints-1;for(var i=0;i<numPoints;i++){var vertex1=path.getAt(i);var vertex2=path.getAt(j);if(vertex1.lng()<latLng.lng()&&vertex2.lng()>=latLng.lng()||vertex2.lng()<latLng.lng()&&vertex1.lng()>=latLng.lng()){if(vertex1.lat()+(latLng.lng()-vertex1.lng())/(vertex2.lng()-vertex1.lng())*(vertex2.lat()-vertex1.lat())<latLng.lat()){inPoly=!inPoly;}}
j=i;}}
return inPoly;};}
google.maps.LatLngBounds.prototype.containsLatLng=function(latLng){return this.contains(latLng);};google.maps.Marker.prototype.setFences=function(fences){this.fences=fences;};google.maps.Marker.prototype.addFence=function(fence){this.fences.push(fence);};google.maps.Marker.prototype.getId=function(){return this['__gm_id'];};if(!Array.prototype.indexOf){Array.prototype.indexOf=function(searchElement){"use strict";if(this==null){throw new TypeError();}
var t=Object(this);var len=t.length>>>0;if(len===0){return-1;}
var n=0;if(arguments.length>1){n=Number(arguments[1]);if(n!=n){n=0;}else if(n!=0&&n!=Infinity&&n!=-Infinity){n=(n>0||-1)*Math.floor(Math.abs(n));}}
if(n>=len){return-1;}
var k=n>=0?n:Math.max(len-Math.abs(n),0);for(;k<len;k++){if(k in t&&t[k]===searchElement){return k;}}
return-1;}}
var is={ie:navigator.appName=='Microsoft Internet Explorer',java:navigator.javaEnabled(),ns:navigator.appName=='Netscape',ua:navigator.userAgent.toLowerCase(),version:parseFloat(navigator.appVersion.substr(21))||parseFloat(navigator.appVersion),win:navigator.platform=='Win32'}
is.mac=is.ua.indexOf('mac')>=0;if(is.ua.indexOf('opera')>=0){is.ie=is.ns=false;is.opera=true;}
if(is.ua.indexOf('gecko')>=0){is.ie=is.ns=false;is.gecko=true;}
}
// menu
$(document).ready(function(){
	
	var container = $('.main-menu');
	menu_button = $('.menu_button');
	
	function resizer(){
		if($(window).width() < 767){
			container.hide();
			container.find('ul').css({
				'visibility':'visible',
				'opacity':'1',
				'display':'none'
			});
			menu_button.removeClass('menu_button_active');
			menu_button.unbind('click').on('click', function(){
				$(this).toggleClass('menu_button_active');
				container.slideToggle(500);
			});
			
			container.find('li').each(function(){
				if($(this).children('ul').length){
					$(this).children('a').removeAttr('href').css('cursor','pointer');
				}
			});
			
			container.find('a').unbind('click').on('click',function(){
				$(this).parent('li').toggleClass('active_item_devices');
				$(this).parent('li').siblings().removeClass('active_item_devices');
				$(this).next('ul').slideToggle(500);
				$(this).parent().siblings().children('ul').slideUp(500);
			});
		}
		else if ($(window).width() > 767){
			container.find('a').off('click');
			container.removeAttr('style');
			container.find('ul').removeAttr('style');
		}
	}
	resizer();
	if((navigator.platform.indexOf("iPhone") != -1) || (navigator.platform.indexOf("iPad") != -1)){
		$(window).off('resize');
		$(window).on('orientationchange', resizer);

	} else {
		$(window).on('resize', resizer);
	}
});