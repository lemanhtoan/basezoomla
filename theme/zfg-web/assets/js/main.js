jQuery(function($){

	// function show language
	$('#language').on("click", function(){
        $(this).children('.language').slideToggle(300);
    });

	// function slide sub-menu
	$('.parent').append("<i class='fa fa-angle-right'></i>");
	if($(window).width() > 600){
		$("#main-menu li").hover(function() {
	        $(this).children('.sub-menu').slideDown("fast");
	    },
	    function() {
	        $(this).children('.sub-menu').slideUp("fast");
	    });
	} else {
		$('.parent .fa').each(function(){
			$(this).click(function(){
				$(this).prev('.sub-menu').slideToggle(300);
				$(this).toggleClass('fa-angle-right fa-angle-down')
			});
		});
		$('#main-menu li').unbind('mouseenter mouseleave');
	}


	// slide-home
	var a = $(window).width();
	var b = $(window).height() - $('header').height() - $('footer').height();
	$(".home-slide").height(b);
	$(".home-slide").carouFredSel({
		responsive: true,
		width: a,
		scroll		: {
			fx			: "crossfade"
		},
		auto: {
			timeoutDuration: 3000,
			duration: 500,
			play: true,
		},
		items		: {
			visible		: 1,
			width: a,
			height: b,
		},
		swipe       : {
	        onTouch         : true ,
	        onMouse         : true,
	    },
		pagination: '.pagination-slide',
	});

	$( window ).resize(function(){
		var a = $(window).width();
		var b = $(window).height() - $('header').height() - $('footer').height();
		$(".home-slide").height(b);
		$(".home-slide").carouFredSel({
			responsive: true,
			width: a,
			scroll		: {
				fx			: "crossfade"
			},
			auto: {
				timeoutDuration: 3000,
				duration: 500,
				play: true,
			},
			items		: {
				visible		: 1,
				width: a,
				height: b,
			},
			swipe       : {
		        onTouch         : true ,
		        onMouse         : true,
		    },
			pagination: '.pagination-slide',
		});

		// slide category product
		$(".main-slide").carouFredSel({
			responsive: true,
			height: 'auto',
			scroll		: {
				fx			: "crossfade"
			},
			auto: {
				timeoutDuration: 3000,
				duration: 500,
				play: true,
			},
			prev: {
				key: 37,
			},
			next: {
				key: 39,
			},
			items		: {
				visible		: 1,
				height: 400,
			},
			swipe       : {
		        onTouch         : true ,
		        onMouse         : true,
		    },
			pagination: '.pagination-category-slide',
		});

		$('.collection-views .button').each(function(){
			var btnTop = -($(this).outerHeight())/2;
			var btnLeft = -($(this).outerWidth())/2;
			$(this).css({'margin-top': btnTop,'margin-left': btnLeft });
		});

		//$('.large-img img').one('load', function(){ 
	        $('.large-img').height($('.large-img img').height());
	   //});

		if($(window).width() > 600){
			$("#main-menu li").hover(function() {
		        $(this).children('.sub-menu').slideDown("fast");
		    },
		    function() {
		        $(this).children('.sub-menu').slideUp("fast");
		    });
		} else {
			$('.parent .fa').each(function(){
				$(this).click(function(){
					$(this).prev('.sub-menu').slideToggle(300);
					$(this).toggleClass('fa-angle-right fa-angle-down')
				});
			});
			$('#main-menu li').unbind('mouseenter mouseleave');
		}
		//focus center images slide
		// $('.home-slide img').each(function(){
		// 	var l = -($(this).width() - $(window).width())/2;
		// 	$('.home-slide img').css('left', l);
		// 	console.log($(this).width());
		// });
		
		var height = $(window).height();
	    var width = $(window).width(); 
	    if( width > height ) {
	        $('.landscape-img').show();
	        $('.portrait-img').hide();
	        $('.banner').removeClass('portrait').addClass('landscape');
	    }else{
	        $('.landscape-img').hide();
	        $('.portrait-img').show();
	        $('.banner').removeClass('landscape').addClass('portrait');
	    }

	});

	// align button collection page
	$('.collection-views .button').each(function(){
		var btnTop = -($(this).outerHeight())/2;
		var btnLeft = -($(this).outerWidth())/2;
		$(this).css({'margin-top': btnTop,'margin-left': btnLeft });
	})
	// toggle menu 
	$('#toggle-menu').click(function(){
		$('#main-menu').slideToggle(300);
	});

	// slide category product
	//$('.main-slide img').on('load', function(){
		$(".main-slide").carouFredSel({
			responsive: true,
			height: 'auto',
			scroll		: {
				fx			: "crossfade"
			},
			auto: {
				timeoutDuration: 3000,
				duration: 500,
				play: true,
			},
			prev: {
				key: 37,
			},
			next: {
				key: 39,
			},
			items		: {
				visible		: 1,
				height: 400,
			},
			swipe       : {
		        onTouch         : true ,
		        onMouse         : true,
		    },

			pagination: '.pagination-category-slide',
		});
	//})


	// views product images select
	$('.thumb-img a').click(function(){
		var urlImg = $(this).attr('data-img');
		var img = "<img class='lazy-image' src='" + urlImg + "'>";
		$('.large-img').html(img);
		$('.thumb-img a').removeClass('active');
		$(this).addClass('active');

	});

	var model = "[data-id='" + $('.models .active').attr('data-id') + "']";
	var title = $('.models .active').children('p').html();
	$('.breadcrumb .code').html(title);
	$('.thumb-img a').filter(model).css('display', 'inline-block');
	
	$('.models a').click(function(){
		$('.thumb-img a').hide();
		$('.models a').removeClass('active');
		var model = "[data-id='" + $(this).attr('data-id') + "']";
		var title = $(this).children('p').html();
		$('.breadcrumb .code').html(title);
		$('.thumb-img a').filter(model).css('display', 'inline-block');
		$('.thumb-img a').filter(model).eq(0).addClass('active');
		var urlImg = $('.thumb-img a').filter(model).eq(0).attr('data-img');
		var img = "<img class='lazy-image' src='" + urlImg + "'>";
		$('.large-img').html(img);
		$(this).addClass('active');

	});

	$('.thumb-img a').eq(0).addClass('active');

	$('.large-img img').one('load', function(){ 
        $('.large-img').height($('.large-img img').height());
    });

	// accordion in blog page
	$('.accordion-views .read-more').bind("click", function(e){
		e.preventDefault();
		$(this).prevAll('.teaser').slideToggle();
		$(this).prevAll('.article-body').slideToggle(300);
    	$(this).prev('.article-body').toggleClass('active');
    	$(this).toggleClass('collap');
    	$('.read-more').html('Read more');
    	$('.read-more.collap').html('Collapse');
	});

	// device width 
    var height = $(window).height();
    var width = $(window).width(); 
    if( width > height ) {
        $('.landscape-img').show();
        $('.portrait-img').hide();
        $('.banner').removeClass('portrait').addClass('landscape');
    }else{
        $('.landscape-img').hide();
        $('.portrait-img').show();
        $('.banner').removeClass('landscape').addClass('portrait');
    }

	//focus center images slide
	// $('.home-slide img').each(function(){
	// 	var l = -($(this).width() - $(window).width())/2;
	// 	$('.home-slide img').css('left', l);
	// });

	$.preloadImages = function() {
	  for (var i = 0; i < arguments.length; i++) {
	    $("<img />").attr("src", arguments[i]);
	  }
	}
	 
	$('.thumb-img a').each(function(){
		var urlImg = $(this).attr('data-img');
		$.preloadImages(urlImg);
	})

	$('.main-slide a:first-child img').load(function(){
		$('.caroufredsel_wrapper,.main-slide').height($('.main-slide a').height() + 40 );
	})
	
	
})
