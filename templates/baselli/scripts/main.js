document.onreadystatechange = function () {
    if (document.readyState == "complete") {
        jQuery(function($) {
            // slide-home
            var a = $(window).width();
            var b = $(window).height() - $('header').height() - $('footer').height();
            $(".home-slide").height(b);
            $('#loading-status').remove();
            $('#slider-wrapper').show();
            $("#slider-wrapper").carouFredSel({
                responsive: true,
                width: a,
                scroll      : {
                    fx          : "crossfade"
                },
                auto: {
                    timeoutDuration: 10000,
                    duration: 2000,
                    play: true,
                },
                items       : {
                    visible     : 1,
                    width: a,
                    height: b,
                },
                swipe       : {
                    onTouch         : true ,
                    onMouse         : true,
                },
                pagination: '.pagination-slide',
            });
        });
    }
}

jQuery(function($){
    var a = $(window).width();
    var b = $(window).height() - $('header').height() - $('footer').height();
    $(".home-slide").height(b);
    $('#l-categories-page').css({height: b});
    //function process validate form edit profile
    $(document).ready(function() {
        language_html = $('#language').html();
        $(window).load(function() {
            // Load collection page
            $('#loading-statuss').remove();
            $('#l-categories-page').css({height: 'auto'});

            $('.flexslider').flexslider({
                animation: "slide",
                slideshowSpeed: 5000,
                start: function(slider){
                  $('body').removeClass('loading');
                },
                directionNav: false,
                slideshow: true,
                manualControls: '.fl-control-nav a'
              });
            if($(window).width() < 1260) {
                $('#account-menu').show();
                $('#language-list').show().html('<a href="javascript:;" title="Languages">' + LANGUAGE_LABEL + '</a>' + language_html + '<i class="fa fa-angle-right"></i>');
                $('#language-list .current-language').remove();
                $('#language-list ul').removeClass('language').addClass('sub-menu');
            } else {
                $('#account-menu').hide();
                $('#language-list').html('').hide();
            }
        });
        $('#account-menu, #language-list').click(function() {
            $(this).children('ul').stop().slideToggle(300);
            $(this).children('.fa').toggleClass('fa-angle-right fa-angle-down');
        });
        $('#btl-input-name').removeClass('notvalid_name').addClass('valid_name');      
        $('#btl-input-phone').removeClass('notvalid_phone').addClass('valid_phone');      
        $('#btl-input-street').removeClass('notvalid_street').addClass('valid_street');     
        $('#btl-input-city').removeClass('notvalid_city').addClass('valid_city');    
        $('#btl-input-zipcode').removeClass('notvalid_zipcode').addClass('valid_zipcode');
        $('#btl-input-zipcode').removeClass('notvalid_zipcode_number').addClass('valid_zipcode_number');  
        $('#btl-input-street-delevery').removeClass('notvalid_street_delevery').addClass('valid_street_delevery');      
        $('#btl-input-city-delevery').removeClass('notvalid_city_delevery').addClass('valid_city_delevery');     
        $('#btl-input-zipcode-delevery').removeClass('notvalid_zipcode_delevery').addClass('valid_zipcode_delevery');   
        $('#btl-input-zipcode-delevery').removeClass('notvalid_zipcode_number_delevery').addClass('valid_zipcode_number_delevery');      
        $('#btl-input-password2').removeClass('notvalid_password_same').addClass('valid_password_same');      
        $('#btl-input-name').change('input', function() {
            var input=$(this);
            var is_name=input.val();            
            if(is_name){ input.removeClass("notvalid_name").addClass("valid_name");
            } else{ input.removeClass("valid_name").addClass("notvalid_name");}
        });        
        $('#btl-input-phone').change('input', function() {
            var input=$(this);
            var is_name=input.val();            
            if(is_name){ input.removeClass("notvalid_phone").addClass("valid_phone");
            } else{input.removeClass("valid_phone").addClass("notvalid_phone");}
        });
        $('#btl-input-street').change('input', function() {
            var input=$(this);
            var is_name=input.val();            
            if(is_name){ input.removeClass("notvalid_street").addClass("valid_street");
            } else{ input.removeClass("valid_street").addClass("notvalid_street");}
        });        
        $('#btl-input-city').change('input', function() {
            var input=$(this);
            var is_name=input.val();            
            if(is_name){ input.removeClass("notvalid_city").addClass("valid_city");
            } else{ input.removeClass("valid_city").addClass("notvalid_city");}
        });        
        $('#btl-input-zipcode').change('input', function() {
            var input=$(this);
            var is_name=input.val();    
            var numberRegExp = /^\d*$/;     
            if(is_name){ input.removeClass("notvalid_zipcode").addClass("valid_zipcode");
            }  else{  input.removeClass("valid_zipcode").addClass("notvalid_zipcode");}
            if(numberRegExp.test(is_name)){ input.removeClass("notvalid_zipcode_number").addClass("valid_zipcode_number");
            }  else{  input.removeClass("valid_zipcode_number").addClass("notvalid_zipcode_number");}
        });        
        $('#btl-input-street-delevery').change('input', function() {
            var input=$(this);
            var is_name=input.val();            
            if(is_name){ input.removeClass("notvalid_street_delevery").addClass("valid_street_delevery");
            }  else{  input.removeClass("valid_street_delevery").addClass("notvalid_street_delevery");}
        });        
        $('#btl-input-city-delevery').change('input', function() {
            var input=$(this);
            var is_name=input.val();            
            if(is_name){ input.removeClass("notvalid_city_delevery").addClass("valid_city_delevery");
            } else{ input.removeClass("valid_city_delevery").addClass("notvalid_city_delevery");}
        });        
        $('#btl-input-zipcode-delevery').change('input', function() {
            var input=$(this);
            var is_name=input.val();      
            var numberRegExp = /^\d*$/;          
            if(is_name){ input.removeClass("notvalid_zipcode_delevery").addClass("valid_zipcode_delevery");
            } else{ input.removeClass("valid_zipcode_delevery").addClass("notvalid_zipcode_delevery");}
            if(numberRegExp.test(is_name)){ input.removeClass("notvalid_zipcode_number_delevery").addClass("valid_zipcode_number_delevery");
            }  else{  input.removeClass("valid_zipcode_number_delevery").addClass("notvalid_zipcode_number_delevery");}
        });        
        $('#btl-input-password2').change('input', function() {
            var input=$(this);
            var is_name=input.val();
            var pass1 = $("#btl-input-password1").val();          
            if( (is_name) && (is_name == pass1) ){ input.removeClass("notvalid_password_same").addClass("valid_password_same");
            } else{ input.removeClass("valid_password_same").addClass("notvalid_password_same"); }
        });                  
        
        $("#profile-button button").click(function(event){ 
            var error_free=true;
            var ename=$("#btl-input-name");
            //name
            var vname =ename.hasClass("valid_name"); 
            if (!vname){
                $('#val_name').removeClass("error").addClass("error_show");          
                error_free=false;
            } else{ $('#val_name').removeClass("error_show").addClass("error"); }
            //phone
            var ephone=$("#btl-input-phone");
            var vphone =ephone.hasClass("valid_phone"); 
            if (!vphone){
                $('#val_phone').removeClass("error").addClass("error_show");                            
                error_free=false;
            } else{ $('#val_phone').removeClass("error_show").addClass("error");}            
            //street
            var ename=$("#btl-input-street");
            var vname =ename.hasClass("valid_street"); 
            if (!vname){
                $('#val_street').removeClass("error").addClass("error_show"); 
                error_free=false;
            } else{ $('#val_street').removeClass("error_show").addClass("error");}
            //city
            var ephone=$("#btl-input-city");
            var vphone =ephone.hasClass("valid_city"); 
            if (!vphone){
                $('#val_city').removeClass("error").addClass("error_show");                                
                error_free=false;
            }  else{ $('#val_city').removeClass("error_show").addClass("error");}            
            //zipcode
            var ephone=$("#btl-input-zipcode");
            var vphone =ephone.hasClass("valid_zipcode"); 
            if (!vphone){
                $('#val_zipcode').removeClass("error").addClass("error_show");                            
                error_free=false;
            } else{ $('#val_zipcode').removeClass("error_show").addClass("error");}  
            var vzipcode =ephone.hasClass("valid_zipcode_number"); 
            if (!vzipcode){
                $('#val_zipcode_number').removeClass("error").addClass("error_show");                            
                error_free=false;
            } else{ $('#val_zipcode_number').removeClass("error_show").addClass("error");}  
             
            //street copy
            var ename=$("#btl-input-street-delevery");
            var vname =ename.hasClass("valid_street_delevery"); 
            if (!vname){
                $('#val_street_delevery').addClass("error_show"); 
                $('#val_street_delevery').removeClass("error");                
                error_free=false;
            }
            else{
                $('#val_street_delevery').addClass("error");
                $('#val_street_delevery').removeClass("error_show");                 
            }
            //city copy
            var ephone=$("#btl-input-city-delevery");
            var vphone =ephone.hasClass("valid_city_delevery"); 
            if (!vphone){
                $('#val_city_delevery').removeClass("error").addClass("error_show");                               
                error_free=false;
            } else{  $('#val_city_delevery').removeClass("error_show").addClass("error"); }            
            //zipcode copy
            var ephone=$("#btl-input-zipcode-delevery");
            var vphone =ephone.hasClass("valid_zipcode_delevery"); 
            if (!vphone){
                $('#val_zipcode_delevery').removeClass("error").addClass("error_show"); 
                error_free=false;
            }  else{ $('#val_zipcode_delevery').removeClass("error_show").addClass("error"); } 
             var vzipcode =ephone.hasClass("valid_zipcode_number_delevery"); 
            if (!vzipcode){
                $('#val_zipcode_number_delevery').removeClass("error").addClass("error_show");                            
                error_free=false;
            } else{ $('#val_zipcode_number_delevery').removeClass("error_show").addClass("error");}  
                       
            //password same
            var ephone=$("#btl-input-password2");
            var vphone =ephone.hasClass("valid_password_same"); 
            if (!vphone){
                $('#val_password_same').removeClass("error").addClass("error_show"); 
                error_free=false;
            }  else{  $('#val_password_same').removeClass("error_show").addClass("error");}            
            
            if (!error_free){
                event.preventDefault(); 
            }
        });        
        
        //validate form forgot password - step email
        $('#jform_email').removeClass('valid_email').addClass('notvalid_email');
        $('#jform_email').removeClass('notvalid_email_format').addClass('valid_email_format');   
        $('#jform_token').removeClass('valid_token').addClass('notvalid_token');
        $('#jform_email').change('input', function() {
            var input=$(this);
            var is_name=input.val();  
            var emailRegExp = /^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.([a-zA-Z]){2,4})$/;         
            if(is_name){ input.removeClass("notvalid_email").addClass("valid_email");
            } else{ input.removeClass("valid_email").addClass("notvalid_email");}
            
            if(emailRegExp.test(is_name)){ input.removeClass("notvalid_email_format").addClass("valid_email_format");
            }  else{  input.removeClass("valid_email_format").addClass("notvalid_email_format");}   
        });     
        $('#jform_token').change('input', function() {
            var input=$(this);
            var is_name=input.val();     
            if(is_name){ input.removeClass("notvalid_token").addClass("valid_token");
            } else{ input.removeClass("valid_token").addClass("notvalid_token");}
        });  
          
        $("#reset_step_email button").click(function(e){  
            var isStop=true;
            var email = $("#jform_email");
            var valueEmail =email.hasClass("valid_email"); 
            
            if (!valueEmail){
                $('#val_email').removeClass("error").addClass("error_show");          
                isStop=false;
            } else{ $('#val_email').removeClass("error_show").addClass("error"); } 

            var email_format =email.hasClass("valid_email_format"); 
            if (!email_format){
                $('#val_email_format').removeClass("error").addClass("error_show");                            
                isStop=false;
            } else{ $('#val_email_format').removeClass("error_show").addClass("error");}  
            
            var code = $("#jform_token");
            var valueToken =code.hasClass("valid_token"); 
            
            if (!valueToken){
                $('#val_code').removeClass("error").addClass("error_show");          
                isStop=false;
            } else{ $('#val_code').removeClass("error_show").addClass("error"); } 

 
            if (!isStop){
                e.preventDefault(); 
            } 
        });
        
        //validate form forgot password - step password
        $('#jform_password1').removeClass('valid_pass1').addClass('notvalid_pass1');
 
        $('#jform_password2').removeClass('valid_pass2').addClass('notvalid_pass2');
        
        $('#jform_password2').removeClass('notvalid_pass_same').addClass('valid_pass_same');
        
        $('#jform_password1').change('input', function() {
            var input=$(this);
            var is_name=input.val();          
            if(is_name){ input.removeClass("notvalid_pass1").addClass("valid_pass1");
            } else{ input.removeClass("valid_pass1").addClass("notvalid_pass1");}
        });   
          
        $('#jform_password2').change('input', function() {
            var input=$(this);
            var is_name=input.val();     
            if(is_name){ input.removeClass("notvalid_pass2").addClass("valid_pass2");
            } else{ input.removeClass("valid_pass2").addClass("notvalid_pass2");}
            
            var pass1 = $("#jform_password1").val();          
            if( (is_name) && (is_name == pass1) ){ input.removeClass("notvalid_pass_same").addClass("valid_pass_same");
            } else{ input.removeClass("valid_pass_same").addClass("notvalid_pass_same"); }
            
        });  
          
        $("#reset_step_password button").click(function(e){  
            var isStop=true;
            var email = $("#jform_password1");
            var valueEmail =email.hasClass("valid_pass1");             
            if (!valueEmail){
                $('#val_pass1').removeClass("error").addClass("error_show");          
                isStop=false;
            } else{ $('#val_pass1').removeClass("error_show").addClass("error"); } 

            var code = $("#jform_password2");
            var valueToken =code.hasClass("valid_pass2"); 
            
            if (!valueToken){
                $('#val_pass2').removeClass("error").addClass("error_show");          
                isStop=false;
            } else{ $('#val_pass2').removeClass("error_show").addClass("error"); } 
            
            var valuePassSame =code.hasClass("valid_pass_same"); 
            
            if (!valuePassSame){
                $('#val_pass_similar').removeClass("error").addClass("error_show");          
                isStop=false;
            } else{ $('#val_pass_similar').removeClass("error_show").addClass("error"); } 

 
            if (!isStop){
                e.preventDefault(); 
            } else {
                //alert("Your password change completed, thanks you.");
                setTimeout(function() {window.location.reload();},7000);
            }
        });       
    });

	// function show language
	$('#language').on("click", function(){
        $(this).children('.language').slideToggle(300);
    });

	// function slide sub-menu
    if($('.parent').attr('id') != 'account-menu' || $('.parent').attr('id') != 'language-list') {
        $('.parent').append("<i class='fa fa-angle-right'></i>");
    }

	if($(window).width() >= 1260){

    $('#account-menu i:last-child').remove();
    //$('.collect-menu').append("<i class='fa fa-angle-right'></i>");
		$("#main-menu li").hover(function() {
	        $(this).children('.sub-menu').stop().slideDown("fast");
	    },
	    function() {
	        $(this).children('.sub-menu').stop().slideUp("fast");
	    });
	} else {
		$('#main-menu li').unbind('mouseenter mouseleave');
	}	

    // Resize function
	$( window ).resize(function(){
		var a = $(window).width();
		var b = $(window).height() - $('header').height() - $('footer').height();
		$(".home-slide").height(b);
		$("#slider-wrapper").carouFredSel({
			responsive: true,
			width: a,
			scroll		: {
				fx			: "crossfade"
			},
			auto: {
				timeoutDuration: 10000,
				duration: 2000,
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
        
		$('.collection-views .button').each(function(){
			var btnTop = -($(this).outerHeight())/2;
			var btnLeft = -($(this).outerWidth())/2;
			$(this).css({'margin-top': btnTop,'margin-left': btnLeft });
		});

		//$('.large-img img').one('load', function(){ 
	        $('.large-img').height($('.large-img img').height());
	   //});
		if($(window).width() >= 1260){
			$("#main-menu li").hover(function() {
		        $(this).children('.sub-menu').stop().slideDown("fast");
		    },
		    function() {
		        $(this).children('.sub-menu').stop().slideUp("fast");
		    });
            $('#account-menu').hide();
            $('#language-list').html('').hide();
		} else {

			$('#main-menu li').unbind('mouseenter mouseleave');

            // Mobile my account, language menu
            $('#account-menu').show();
            $('#language-list').show().html('<a href="javascript:;" title="Languages">' + LANGUAGE_LABEL + '</a>' + language_html + '<i class="fa fa-angle-right"></i>');
            $('#language-list .current-language').remove();
            $('#language-list ul').removeClass('language').addClass('sub-menu');
        }
        
		var height = $(window).height();
	    var width = $(window).width(); 
        //console.log(width + ' ' + height);
        if (width <= 320) { 
            $('.landscape-320').show();
            $('.landscape-360').hide();
            $('.landscape-768').hide();
            $('.landscape-800').hide();
            $('.landscape-980').hide();
            $('.landscape-1280').hide();
            $('.landscape-1920').hide();
            $('.landscape-1024-768').hide();
            $('.landscape-1280-800').hide();
            $('.landscape-1680').hide();
        } else
        if (width <= 360) { 
            $('.landscape-320').hide();
            $('.landscape-360').show();
            $('.landscape-768').hide();
            $('.landscape-800').hide();
            $('.landscape-980').hide();
            $('.landscape-1280').hide();
            $('.landscape-1920').hide();
            $('.landscape-1024-768').hide();
            $('.landscape-1280-800').hide();
            $('.landscape-1680').hide();
        } else
        if (width <= 768) {             
            if (height <= 360){ // too <=320
                $('.landscape-320').hide();
                $('.landscape-360').hide();
                $('.landscape-768').hide();
                $('.landscape-800').hide();
                $('.landscape-980').hide();
                $('.landscape-1280').show();
                $('.landscape-1920').hide();
                $('.landscape-1024-768').hide();
                $('.landscape-1280-800').hide();
                $('.landscape-1680').hide();
            }
            else {
                $('.landscape-320').hide();
                $('.landscape-360').hide();
                $('.landscape-768').show();
                $('.landscape-800').hide();
                $('.landscape-980').hide();
                $('.landscape-1280').hide();
                $('.landscape-1920').hide();
                $('.landscape-1024-768').hide();
                $('.landscape-1280-800').hide();
                $('.landscape-1680').hide();
            }
            
        } else
        if (width <= 800) { 
            $('.landscape-320').hide();
            $('.landscape-360').hide();
            $('.landscape-768').hide();
            $('.landscape-800').show();
            $('.landscape-980').hide();
            $('.landscape-1280').hide();
            $('.landscape-1920').hide();
            $('.landscape-1024-768').hide();
            $('.landscape-1280-800').hide();
            $('.landscape-1680').hide();
        } else
        if (width <= 980) { 
            $('.landscape-320').hide();
            $('.landscape-360').hide();
            $('.landscape-768').hide();
            $('.landscape-800').hide();
            $('.landscape-980').show();
            $('.landscape-1280').hide();
            $('.landscape-1920').hide();
            $('.landscape-1024-768').hide();
            $('.landscape-1280-800').hide();
            $('.landscape-1680').hide();
        } else
        if (width <= 1280) { 
            if (height <= 600) {
                $('.landscape-320').hide();
                $('.landscape-360').hide();
                $('.landscape-768').hide();
                $('.landscape-800').hide();
                $('.landscape-980').hide();
                $('.landscape-1280').show();
                $('.landscape-1920').hide();
                
                $('.landscape-1024-768').hide();
                $('.landscape-1280-800').hide();
                $('.landscape-1680').hide();
            } else if (height <= 768) {
                $('.landscape-320').hide();
                $('.landscape-360').hide();
                $('.landscape-768').hide();
                $('.landscape-800').hide();
                $('.landscape-980').hide();
                $('.landscape-1280').hide();
                $('.landscape-1920').hide();
                
                $('.landscape-1024-768').show();
                $('.landscape-1280-800').hide();
                $('.landscape-1680').hide();
            } else if (height <= 800) {
                $('.landscape-320').hide();
                $('.landscape-360').hide();
                $('.landscape-768').hide();
                $('.landscape-800').hide();
                $('.landscape-980').hide();
                $('.landscape-1280').hide();
                $('.landscape-1920').hide();  
                
                $('.landscape-1024-768').hide();              
                $('.landscape-1280-800').show();
                $('.landscape-1680').hide();
                
            } else if (height <= 980) {
                $('.landscape-320').hide();
                $('.landscape-360').hide();
                $('.landscape-768').hide();
                $('.landscape-800').hide();
                $('.landscape-980').hide();
                $('.landscape-1280').hide();
                $('.landscape-1920').hide();  
                                
                $('.landscape-1024-768').hide();              
                $('.landscape-1280-800').show();
                $('.landscape-1680').hide();
                
            } else {
                $('.landscape-320').hide();
                $('.landscape-360').hide();
                $('.landscape-768').hide();
                $('.landscape-800').hide();
                $('.landscape-980').hide();
                $('.landscape-1280').show();
                $('.landscape-1920').hide();
                $('.landscape-1024-768').hide();
                $('.landscape-1280-800').hide();
                $('.landscape-1680').hide();
            }
            
        } else
        if (width <= 1680) {
            $('.landscape-320').hide();
            $('.landscape-360').hide();
            $('.landscape-768').hide();
            $('.landscape-800').hide();
            $('.landscape-980').hide();
            $('.landscape-1280').hide();
            $('.landscape-1920').hide();
            $('.landscape-1024-768').hide();
            $('.landscape-1280-800').hide();
            $('.landscape-1680').show();
        } else
        {
            $('.landscape-320').hide();
            $('.landscape-360').hide();
            $('.landscape-768').hide();
            $('.landscape-800').hide();
            $('.landscape-980').hide();
            $('.landscape-1280').hide();
            $('.landscape-1920').show();
            $('.landscape-1024-768').hide();
            $('.landscape-1280-800').hide();
            $('.landscape-1680').hide();
        }

	});
    
    //function slider category
    function listCategorySlider(){
        // slide category product
        var slide_effect = '';
        var current_product_page = '';
        var current_product_page = $('#current_page').val();
        if(current_product_page == 'category_page' || current_product_page == '') {
            slide_effect = 'crossfade';
        } else if(current_product_page == 'product_page') {
            slide_effect = 'scroll';
        }

        var carousel = $(".main-slide");
		$(".main-slide").carouFredSel({
			responsive: true,
			height: 'auto',
			scroll		: {
				fx			: slide_effect,
                pauseOnHover    : true
			},
			auto: {
				timeoutDuration: 4000,
				duration: 1000,
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
            onCreate: function () {
                $(window).on('resize', function () {
                  carousel.parent().add(carousel).height(carousel.children().first().height());
                }).trigger('resize');
              }
		});
    }
    $(window).load(function(){ //alert("df");
        listCategorySlider();
    });  
        

    $('.parent .fa').click(function(){
        $(this).prev('.sub-menu').stop().slideToggle(300);
        $(this).toggleClass('fa-angle-right fa-angle-down');
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
    var slide_effect = '';
    var current_product_page = '';
    var current_product_page = $('#current_page').val();
    if(current_product_page == 'category_page' || current_product_page == '') {
        slide_effect = 'crossfade';
    } else if(current_product_page == 'product_page') {
        slide_effect = 'scroll';
    }
	// slide category product
	//$('.main-slide img').on('load', function(){
		$(".main-slide").carouFredSel({
			responsive: true,
			height: 'auto',
			scroll		: {
				fx			: slide_effect
			},
			auto: {
				timeoutDuration: 4000,
				duration: 1000,
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
    	$('.read-more').html(readmore);
    	$('.read-more.collap').html(collapse);
	});

	// device width 
    var height = $(window).height();
    var width = $(window).width();
    if (width <= 320) {
        $('.landscape-320').show();
        $('.landscape-360').hide();
        $('.landscape-768').hide();
        $('.landscape-800').hide();
        $('.landscape-980').hide();
        $('.landscape-1280').hide();
        $('.landscape-1920').hide();
        $('.landscape-1024-768').hide();
        $('.landscape-1280-800').hide();
        $('.landscape-1680').hide();
    } else
    if (width <= 360) {
        $('.landscape-320').hide();
        $('.landscape-360').show();
        $('.landscape-768').hide();
        $('.landscape-800').hide();
        $('.landscape-980').hide();
        $('.landscape-1280').hide();
        $('.landscape-1920').hide();
        $('.landscape-1024-768').hide();
        $('.landscape-1280-800').hide();
        $('.landscape-1680').hide();
    } else
    if (width <= 768) {
        if (height <= 360){ // too <=320
            $('.landscape-320').hide();
            $('.landscape-360').hide();
            $('.landscape-768').hide();
            $('.landscape-800').hide();
            $('.landscape-980').hide();
            $('.landscape-1280').show();
            $('.landscape-1920').hide();
            $('.landscape-1024-768').hide();
            $('.landscape-1280-800').hide();
            $('.landscape-1680').hide();
        }
        else {
            $('.landscape-320').hide();
            $('.landscape-360').hide();
            $('.landscape-768').show();
            $('.landscape-800').hide();
            $('.landscape-980').hide();
            $('.landscape-1280').hide();
            $('.landscape-1920').hide();
            $('.landscape-1024-768').hide();
            $('.landscape-1280-800').hide();
            $('.landscape-1680').hide();
        }

    } else
    if (width <= 800) {
        $('.landscape-320').hide();
        $('.landscape-360').hide();
        $('.landscape-768').hide();
        $('.landscape-800').show();
        $('.landscape-980').hide();
        $('.landscape-1280').hide();
        $('.landscape-1920').hide();
        $('.landscape-1024-768').hide();
        $('.landscape-1280-800').hide();
        $('.landscape-1680').hide();
    } else
    if (width <= 980) {
        $('.landscape-320').hide();
        $('.landscape-360').hide();
        $('.landscape-768').hide();
        $('.landscape-800').hide();
        $('.landscape-980').show();
        $('.landscape-1280').hide();
        $('.landscape-1920').hide();
        $('.landscape-1024-768').hide();
        $('.landscape-1280-800').hide();
        $('.landscape-1680').hide();
    } else
    if (width <= 1280) {
        if (height <= 600) {
            $('.landscape-320').hide();
            $('.landscape-360').hide();
            $('.landscape-768').hide();
            $('.landscape-800').hide();
            $('.landscape-980').hide();
            $('.landscape-1280').show();
            $('.landscape-1920').hide();

            $('.landscape-1024-768').hide();
            $('.landscape-1280-800').hide();
            $('.landscape-1680').hide();
        } else if (height <= 768) {
            $('.landscape-320').hide();
            $('.landscape-360').hide();
            $('.landscape-768').hide();
            $('.landscape-800').hide();
            $('.landscape-980').hide();
            $('.landscape-1280').hide();
            $('.landscape-1920').hide();

            $('.landscape-1024-768').show();
            $('.landscape-1280-800').hide();
            $('.landscape-1680').hide();
        } else if (height <= 800) {
            $('.landscape-320').hide();
            $('.landscape-360').hide();
            $('.landscape-768').hide();
            $('.landscape-800').hide();
            $('.landscape-980').hide();
            $('.landscape-1280').hide();
            $('.landscape-1920').hide();

            $('.landscape-1024-768').hide();
            $('.landscape-1280-800').show();
            $('.landscape-1680').hide();

        } else if (height <= 980) {
            $('.landscape-320').hide();
            $('.landscape-360').hide();
            $('.landscape-768').hide();
            $('.landscape-800').hide();
            $('.landscape-980').hide();
            $('.landscape-1280').hide();
            $('.landscape-1920').hide();

            $('.landscape-1024-768').hide();
            $('.landscape-1280-800').show();
            $('.landscape-1680').hide();

        } else {
            $('.landscape-320').hide();
            $('.landscape-360').hide();
            $('.landscape-768').hide();
            $('.landscape-800').hide();
            $('.landscape-980').hide();
            $('.landscape-1280').show();
            $('.landscape-1920').hide();
            $('.landscape-1024-768').hide();
            $('.landscape-1280-800').hide();
            $('.landscape-1680').hide();
        }

    } else
    if (width <= 1680) {
        $('.landscape-320').hide();
        $('.landscape-360').hide();
        $('.landscape-768').hide();
        $('.landscape-800').hide();
        $('.landscape-980').hide();
        $('.landscape-1280').hide();
        $('.landscape-1920').hide();
        $('.landscape-1024-768').hide();
        $('.landscape-1280-800').hide();
        $('.landscape-1680').show();
    } else
    {
        $('.landscape-320').hide();
        $('.landscape-360').hide();
        $('.landscape-768').hide();
        $('.landscape-800').hide();
        $('.landscape-980').hide();
        $('.landscape-1280').hide();
        $('.landscape-1920').show();
        $('.landscape-1024-768').hide();
        $('.landscape-1280-800').hide();
        $('.landscape-1680').hide();
    }

	$.preloadImages = function() {
	  for (var i = 0; i < arguments.length; i++) {
	    $("<img />").attr("src", arguments[i]);
	  }
	}
	 
	$('.thumb-img a').each(function(){
		var urlImg = $(this).attr('data-img');
		$.preloadImages(urlImg);
	});

	// process validate email field
    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    // subscribe process register into db
    $(document).ready(function () {
        $(".btn_subscribe").on("click", function () {
            var email = $('.email').val();
            var current_lang = $('#current-lang').val();
            if ($.trim(email).length == 0) {
                alert(not_fill_email);
                return false;
            }
            if(isEmail(email))
            {
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: "../components/com_ajax/subscribe.php?email="+email , 
                    data: {email: email, current_lang: current_lang},
                    cache: false,
                    success: function (response) {
                        console.log(response);
                        if (response['error_code'] == 0) {
                            var success_popup = '<div id="hide-popup"></div>' +
                                                '<div id="popup-wrapper">' +
                                                    '<div id="show-popup" class="modalDialog" style="margin-top: 20px;">' +
                                                        '<a href="#close" title="Close" class="modalCloseImg simplemodal-close" id="close"></a>' +
                                                        '<form action="" method="post">' +
                                                            '<h3>' + THANK_YOU + '</h3>' +
                                                            '<p>' + success_email + '</p>' +
                                                            '<div id="reset_step_email" class="reset_step_email" style="width: 100%; padding: 3px;">' +
                                                                '<button type="submit" id="btn-email" style="width: 195px; font-size: 17px; display: block; padding: 2px 0; text-align: center; margin: 10px auto 0">Close</button>' +
                                                            '</div>' +
                                                        '</form>' +
                                                    '</div>' +
                                                '</div>';
                            $('#content > .container').append(success_popup);
                            $('html, body').animate({'scrollTop' : 0}, 1000);
                        } else {
                            alert(email_exist);
                        }
                        //location.reload();
                        return false;
                    },
                });
            } else {
                alert(invalid_email);
                return false;
            }
            return false;
        });
    });	

	
	//register process
	$(document).ready(function() {
		//add phone
	    $("#add").click(function() {
	        var intId = $("#addPhone").length + 1;
	        var fieldWrapper = $("<div id=\"field" + intId + "\"/>");
	        var fName = $("<input type=\"text\" placeholder=\"" + PHONE_NUMBER + "\" name=\"jform[phone1]" + "\"   id=\"jform[phone1]" + "\"  />");
	        fieldWrapper.append(fName);
	        $("#addPhone").append(fieldWrapper);
	        $("#add").hide();
            $('#jform[phone1]').focus();
            
	    });

	    //default init checkbox copy delevery disable
	    $("#btl-checkbox-copy-delevery").attr("disabled", true);
        
        //check if exist value -> enable check
        var zipcode_deli = $('#btl-input-zipcode-delevery').val();
    	var city_deli = $('#btl-input-city-delevery').val();
    	var address_deli = $('#btl-input-street-delevery').val();
        
        var zipcode = $('#btl-input-zipcode').val();
    	var city = $('#btl-input-city').val();
    	var address = $('#btl-input-street').val();
        if( (zipcode_deli == zipcode) && (city_deli == city) && (address_deli == address) && (zipcode!="") && (city !="") && (address!="") ){ 
            $("#btl-checkbox-copy-delevery").prop('checked', true);
        }
            
            
        //console.log($('#btl-input-country').has('option').length);
    	//check full or not full fields enable checkbox copy 
        if(zipcode_deli !="" || city_deli !="" || address_deli !=""){
        	$("#btl-checkbox-copy-delevery").removeAttr("disabled");
        }
            

	    $('#btl-input-city').blur(function() {
	    	var zipcode = $('#btl-input-zipcode').val();
	    	var city = $('#btl-input-city').val();
	    	var address = $('#btl-input-street').val();
            //console.log($('#btl-input-country').has('option').length);
	    	//check full or not full fields enable checkbox copy 
	        if(address !="" && city !="" && zipcode !=""){
	        	$("#btl-checkbox-copy-delevery").removeAttr("disabled");
	        }
	    });
        
        $('#btl-input-zipcode').blur(function() {
	    	var zipcode = $('#btl-input-zipcode').val();
	    	var city = $('#btl-input-city').val();
	    	var address = $('#btl-input-street').val();
	    	//check full or not full fields enable checkbox copy 
	        if(address !="" && city !="" && zipcode !=""){
	        	$("#btl-checkbox-copy-delevery").removeAttr("disabled");
	        }
	    });
        
        $('#btl-input-street').blur(function() {
	    	var zipcode = $('#btl-input-zipcode').val();
	    	var city = $('#btl-input-city').val();
	    	var address = $('#btl-input-street').val();
	    	//check full or not full fields enable checkbox copy 
	        if(address !="" && city !="" && zipcode !=""){
	        	$("#btl-checkbox-copy-delevery").removeAttr("disabled");
	        }
	    });       
                
	    //copy data checkbox  
		$('#btl-checkbox-copy-delevery').change(function() {
	        if($(this).is(":checked")) {	   
		        var zipcode = $('#btl-input-zipcode').val();
		    	var city = $('#btl-input-city').val();
		    	var address = $('#btl-input-street').val();         		            
	            //data copy
	            $("#btl-input-street-delevery").val(address);
	            $("#btl-input-city-delevery").val(city);
	            $("#btl-input-zipcode-delevery").val(zipcode);

                var country = $('#btl-input-country option:selected').val(); 

                $("#btl-input-country_delevery option").each(function () { 

                    if ($(this).val() == country) { 
                        $(this).attr("selected", "selected");
                        return;
                    }
                });                
	        } else {
	        	$("#btl-input-street-delevery").val("");
	            $("#btl-input-city-delevery").val("");
	            $("#btl-input-zipcode-delevery").val("");
	        }       
	    });
        
        //remove image profile
        $('#delete').click(function(){
           //alert("Delete image"); 
           $('#mg-profile').remove();
           $("#img-profile").attr("src","../../../images/user/noavatar.png");           
        });

	});
    
    function isInt(n) {
       return n % 1 === 0;
    }

	// Plus or minus product in cart
	$('.quantity-minus').click(function(e)
	{
		e.stopImmediatePropagation();
		var element = $('.quantity-input');
		var current_qty = parseInt( $(element).val());
		var product_price = $('#h-product-price').val();
		if ( current_qty > 1 ) current_qty -= 1;
		else if(current_qty == 1) current_qty = 1;
		else current_qty = 1;
        
        var $cal="";
        if (isInt(product_price)) {
            $cal = Math.round(parseFloat(product_price) * current_qty * 100) / 100 + '.00';
        } else {
            $cal = Math.round(parseFloat(product_price) * current_qty * 100) / 100 + '0';
        }
        
		$('#v-product-price span').text($cal);
		$(element).val(current_qty);
	});
	$('.quantity-plus').click(function(e)
	{
		e.stopImmediatePropagation();
		var product_price = $('#h-product-price').val(); 
		var element = $('.quantity-input');
		var current_qty = parseInt($(element).val()); //console.log(current_qty);
		current_qty += 1;
        
        var $cal="";
        if (isInt(product_price)) {
            $cal = Math.round(parseFloat(product_price) * current_qty * 100) / 100 + '.00';
        } else {
            $cal = Math.round(parseFloat(product_price) * current_qty * 100) / 100 + '0';
        }
        
        $('#v-product-price span').text($cal);
		$(element).val(current_qty);
	});     
 })

jQuery(document).ready(function(){
    jQuery(document).on('click', '.cart_icon, .cart_icon_items, .cart_icon_active', function (){
        jQuery('.cart_dropdown').slideToggle(200);
        setTimeout(function(){ 
            if ( jQuery('.cart_dropdown').is(":visible") ) 
            {
                jQuery('.cart_icon_items').addClass('cart_icon_items_active');
                jQuery('.modViewCartContainer a#cart').removeClass('cart_icon').addClass('cart_icon_active');
            }
            else {
                jQuery('.cart_icon_items').removeClass('cart_icon_items_active');   
                jQuery('.modViewCartContainer a#cart').removeClass('cart_icon_active').addClass('cart_icon');
            }   
        }, 210);

        
    });
    jQuery(document).on('click', '.view_cart_but', function() {
        window.location.href = jQuery(this).attr('data-url');
    });
});