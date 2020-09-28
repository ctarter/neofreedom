jQuery.noConflict();
var customizerLoaded = false;
/** Fire up jQuery - let's dance! */
jQuery(document).ready(function($) {
	
	//Get customizer settings:
	//console.log(_wpCustomizeSettings);
	//console.log(_wpCustomizeWidgetsSettings);
		
	$('#footlinks').appendTo('#customize-controls');
	
	/*SETTINGS*/
	$('.optim_settings').on('click',function() {
		$(this).addClass('opactive');
		$('#optimizer_settings').animate({"left":"-280px"});
	 });
	 $('.optim_settings_close').on('click',function() {
		$('.optim_settings').removeClass('opactive');
		$('#optimizer_settings').animate({"left":"-830px"});
    });
	
	$('.optim_presets').on('click',function() {
		$(this).addClass('opactive');
		$('#preset_options').fadeIn();
	 });
	 $('.preset_close').on('click',function() {
		$('.optim_presets').removeClass('opactive');
		$('#preset_options').fadeOut();
    });
	
	$(".customize-controls-close span:contains('Cancel')").parent().addClass("switch_cancel");
	 
	 /*SETTINGS Options Toggle*/
    $('.setting_option h4').on('click',function(){
      if(!$(this).parent().hasClass('setting_toggle')){
         $(this).parent().addClass('setting_toggle');
         $(this).next('.settings_toggle_inner').slideDown(200);
      }else{
         $(this).parent().removeClass('setting_toggle');
         $(this).next('.settings_toggle_inner').slideUp(200);
      }
	 });
	 
	 /*EXPAND*/
    $('.optim_expand').on('click', function(){ 
      if(!$(this).hasClass('opactive')){
         $(this).addClass('opactive');
         $('body').addClass('optimizer_expand');
         $('#customize-controls').animate({"width":"420px"});
         $('#optimizer_settings').animate({"width":"360px"});
      }else{
         $(this).removeClass('opactive');
         $('body').removeClass('optimizer_expand');
         $('#customize-controls').animate({"width":"330px"});
         $('#optimizer_settings').animate({"width":"270px"});
      }
   });
   
	 $('#save').before('<a class="upgrade_btn" href="https://optimizerwp.com/optimizer-pro/" target="_blank"><i class="fa fa-diamond"></i> '+objectL10n.upgrade+'</a>');



wp.customize.previewer.bind('ready', function() {
   if(!customizerLoaded){
      /*MOVE Frontpage Widget Section before footer widget are*/
      wp.customize.section( 'sidebar-widgets-front_sidebar' ).panel( 'front_panel' );
      wp.customize.section( 'sidebar-widgets-front_sidebar' ).priority( 11 );
      wp.customize.section( 'sidebar-widgets-sidebar' ).priority( 3 );
      wp.customize.section( 'sidebar-widgets-foot_sidebar' ).panel( 'footer_panel' );
      wp.customize.section( 'sidebar-widgets-foot_sidebar' ).priority( 1 );
      if(!jQuery('#customize-theme-controls #accordion-section-nav').length && jQuery('#customize-theme-controls #accordion-panel-nav_menus').length){ 
         wp.customize.panel( 'nav_menus' ).priority( 1 ); 
      }
      if(jQuery('#customize-theme-controls #accordion-section-nav').length){
         wp.customize.section( 'nav' ).priority( 1 ); 
      }
      wp.customize.panel( 'widgets' ).priority( 2 );
      
      /*TOOLTIP*/
      jQuery('.customize-control-description').each(function() {
         jQuery(this).hide();
         var tipcontent = jQuery(this).text();
         jQuery(this).parent().find('.customize-control-title').first().append('<i class="fa fa-question-circle customize-tooltip"><span class="optim_tooltip">'+tipcontent+'<dl class="tipbottom" /></span></i>');
      });
         $('.customize-tooltip').hoverIntent(function(){ 
            var x = jQuery(this).position();  jQuery(this).find('span').css({"left":-x.left - 8}); jQuery(this).find('dl').css({"left": x.left + 8}); 
               jQuery(this).addClass('tipactive');
               jQuery(this).find('span').stop().fadeIn(300);
         },function(){
               jQuery(this).removeClass('tipactive');
            jQuery(this).find('span').fadeOut(300);
         });
         
         $('ul.accordion-section-content').each(function(index, element) {
                  $(this).find('.customize-control .optim_tooltip').addClass('first_tooltip').prepend('<dl class="tipbottom" />');
         });

      //Footer Tooltip
      jQuery('#footlinks a').each(function(index, element) {
         var footip = jQuery(this).attr('title');
         jQuery(this).append('<span class="footer_tooltip">'+footip+'<dl class="tipbottom" /></span>');
         jQuery(this).removeAttr('title');
      });
      jQuery('#customize-footer-actions .devices button').each(function(index, element) {
         var responsivetip = jQuery(this).find('.screen-reader-text').text();
         jQuery(this).append('<span class="footer_tooltip">'+responsivetip+'<dl class="tipbottom" /></span>');
      });

      jQuery('.button.change-theme').append('<span class="footer_tooltip">'+jQuery(this).attr('title')+'<dl class="tipbottom" /></span>');
      customizerLoaded = true;
   }
});

	//Section Description Tooltip
	setTimeout(function(){
		jQuery('.customize-section-description-container').each(function(index, element) {
			jQuery(this).find('.customize-section-description').before('<i class="fa fa-question section-desc-toggle"></i>');
      });

      jQuery('.section-desc-toggle').on('click',function(){ 
         console.log('.section-desc-toggle');
         if(jQuery(this).hasClass('fa-question')){
            jQuery(this).removeClass('fa-question').addClass('fa-times');
            jQuery(this).parent().find('.customize-section-description').slideDown(300);
         }else{
            jQuery(this).addClass('fa-question').removeClass('fa-times');
            jQuery(this).parent().find('.customize-section-description').slideUp(300);
         }
      });
   }, 1000);		
	
	//QUICKIE
	$('.wp-full-overlay-sidebar').prepend('<div class="quickie"><i class="optimizer_logo">O</i></div>');
	
	$('.wp-full-overlay-sidebar .quickie').after('<div class="quickie_text"><span class="logotxt"></span></div>');
	$('.quickie, .quickie_text, .logotxt').hover(function(){ 
			jQuery('.wp-full-overlay').addClass('quickiehover');
	},function(){
			jQuery('.wp-full-overlay').removeClass('quickiehover');
	});
	
	
	
	//Wordpress 4.7 FIXES------------------------
		if(objectL10n.wp4_7 == 'wp4_7'){
			jQuery('body').addClass('wp4_7');
		}
		
		//Wordpress 4.7 Section toggle
		jQuery(document).on("click", ".wp4_7 #customize-theme-controls .control-section .accordion-section-title", function(e) {
			$('.accordion-section').removeClass('sec_open'); 
			
			if( $(this).parent().has('.open')){
				setTimeout(function(){ $('.control-section.open').parent().addClass('sec_open'); }, 50);
			}else{
				setTimeout(function(){ $('.control-section.open').parent().removeClass('sec_open');  }, 50);  
			}
			
		});
		//Wordpress 4.7 Widget Focus 
		wp.customize.previewer.bind( 'focus-widget-control', function(param){
			wp.customize.control.each( function ( control ) {  if(control.expanded) control.collapse();  });
			
			jQuery('.wp4_7 .accordion-section').removeClass('sec_open'); 
			setTimeout(function () { jQuery('.wp4_7 .control-section.open').parent().addClass('sec_open');  }, 100);
			
			var thewidgetid = param.replace( /^\D+/g, ''); 
			var thewidgetname = param.split("-")[0];
			console.log(param);
			wp.customize.control( 'widget_'+thewidgetname+'['+thewidgetid+']' ).focus();
			
		} );
		
	//Wordpress 4.7 - Group All Controls in sections
	$('.customize-pane-child:not(.control-section-nav_menu )').each(function(index, element) {
		var ariaid = $(this).attr('id');
        $(this).insertAfter('li.control-subsection[aria-owns="'+ariaid+'"] h3');
    });
	/*Custom Sections Added by Plugins*/
	$('.accordion-section-content').not('.control-section-nav_menu, #sub-accordion-section-colors').each(function(index, element) {
		if(! $(this).parent().parent().hasClass("control-panel-content")) {
			if($(this).has('.customize-control')){
				$(this).addClass('custom_section');
			}
		}
    });
	//-------------------------------------------



	//Logo 
	$('.optimizer_logo').click(function(){
		$('.quickie i').removeClass('activeq');
		$('.wp-full-overlay').removeClass('quickiehover subsection-open');
		wp.customize.panel.each( function ( panel ) {  panel.collapse();});
		wp.customize.section.each( function ( section ) {  section.collapse();});
	});
	
	
	//REMOVE NOW CUSTOMIZING THEME INFO
	$('#customize-info').remove();
	

});

/*REFACTOR CONTROLS*/
jQuery(window).on('load', function(){

	//Move Switch theme button to footer
	jQuery('.change-theme').prependTo('#footlinks');
	jQuery('.change-theme').attr('title',objectL10n.switchtheme).html('<i class="fa fa-random"></i>');
	jQuery('.button.change-theme').append('<span class="footer_tooltip">'+jQuery('.button.change-theme').attr('title')+'<dl class="tipbottom" /></span>');
	

	//===QUCIKIES===
	//ASSIGN QUICKIE ICONS
	jQuery('#accordion-panel-basic_panel').attr('data-qicon', 'fa-sliders');  jQuery('#accordion-panel-header_panel').attr('data-qicon', 'fa-credit-card');
	jQuery('#accordion-panel-front_panel').attr('data-qicon', 'fa-desktop');  jQuery('#accordion-panel-footer_panel').attr('data-qicon', 'fa-copyright');
	jQuery('#accordion-panel-singlepages_panel').attr('data-qicon', 'fa-indent');  jQuery('#accordion-panel-misc_panel').attr('data-qicon', 'fa-cogs');
	jQuery('#accordion-panel-nav_menus').attr('data-qicon', 'fa-bars');  jQuery('#accordion-panel-widgets').attr('data-qicon', 'fa-codepen'); 
	jQuery('#accordion-panel-help_panel').attr('data-qicon', 'fa-life-saver');
	
	//INITIATE QUCIKIES
	jQuery('li.control-panel').each(function(index, element) {
      var rawtitle = jQuery(this).find('h3.accordion-section-title').contents().get(0).nodeValue;
		var quickieidraw = jQuery(this).attr('id');
      var quickieid = quickieidraw.replace("accordion-panel-", "");
      console.log(rawtitle, quickieidraw);
		if(jQuery(this).attr('data-qicon')){   var qicon = jQuery(this).attr('data-qicon');  }else{  var qicon ='fa-cog';  }
		jQuery('.quickie').append('<i class="fa '+qicon+' quickie_'+quickieid+'"><dl>'+rawtitle+ '</dl></i>');
		
		jQuery('.quickie_'+quickieid).click(function(){  
			jQuery('.quickie i, .quickie_text dl').removeClass('activeq'); jQuery(this).addClass('activeq'); wp.customize.panel( quickieid ).focus(); 	
			jQuery('.wp-full-overlay').removeClass('quickiehover subsection-open'); 
		});
		
		jQuery('#'+quickieidraw).find('h3').click(function(){ 
			jQuery('.quickie i, .quickie_text dl').removeClass('activeq'); jQuery('.quickie_'+quickieid).addClass('activeq');
		});
		
    });
	

		jQuery('.quickie i, .quickie_text dl').click(function(){ 
			wp.customize.section.each( function ( section ) {section.collapse();}); 
		});
		
		jQuery('.accordion-section.control-subsection h3').on('click',function() {
			if(jQuery('.wp-full-overlay').find('.accordion-section.control-subsection.open').length != 0){
				jQuery( '.wp-full-overlay').removeClass('subsection-open').addClass('subsection-open');
			}else{
				jQuery( '.wp-full-overlay').removeClass('subsection-open');
			}
		});
		
		//before WORDPRESS 4.3 Menus Section
		if(jQuery('#customize-theme-controls #accordion-section-nav').length){
			jQuery('#accordion-section-nav').attr('data-qicon', 'fa-bars'); 
			jQuery('#accordion-section-nav').each(function(index, element) {
				var rawtitle = jQuery(this).find('h3.accordion-section-title').contents().get(0).nodeValue;
				var quickieidraw = jQuery(this).attr('id');
				var quickieid = quickieidraw.replace("accordion-section-", "");
				var qicon = jQuery(this).attr('data-qicon');
				jQuery('.quickie_misc_panel').after('<i class="fa '+qicon+' quickie_'+quickieid+'"><dl>'+rawtitle+ '</dl></i>');
				
				jQuery('.quickie_'+quickieid).click(function(){  
					jQuery('.quickie i, .quickie_text dl').removeClass('activeq'); jQuery(this).addClass('activeq'); wp.customize.section( quickieid ).focus(); 
					jQuery('.wp-full-overlay').removeClass('quickiehover subsection-open'); 
				});
				
				jQuery('#'+quickieidraw).find('h3').click(function(){ 
					jQuery('.quickie i, .quickie_text dl').removeClass('activeq'); jQuery('.quickie_'+quickieid).addClass('activeq');
				});
				
			});
		}
		//Hide Customizer Navigation control icon if the navigation control itself is not present
		if(!jQuery('#customize-theme-controls #accordion-section-nav').length){
			jQuery('.quickie_nav').hide();
		}

		
		/*MINI Controls*/
		jQuery('.mini_control').each(function(index, element) {
            jQuery(this).closest('li.customize-control').addClass('has_mini_control');
        });
		
		/*FONT CONTROL NAMES*/
		jQuery('#customize-control-logo_font_family').before('<h4 class="font_controlheader">'+objectL10n.sitettfont+'</h4>');
		jQuery('#customize-control-ptitle_font_family').before('<h4 class="font_controlheader no_border">'+objectL10n.menufont+'</h4>');
		jQuery('#customize-control-content_font_family').before('<h4 class="font_controlheader content_border">'+objectL10n.logofont+'</h4>');
		

		/*LOGO CONTROL TAB*/
		jQuery('#customize-control-logo_image_id').hide('');
		jQuery('#customize-control-blogname, #customize-control-blogdescription, #accordion-section-headlogo_section .font_controlheader, #customize-control-logo_font_family, #customize-control-logo_font_subsets, #customize-control-logo_font_size, #customize-control-logo_color_id').addClass('activelogoption');
		
		jQuery('#customize-control-blogname').addClass('activelogoption').before('<ul class="logo_control_tabs"><li class="txtlogo activlogo"><a>Text</a></li><li class="imglogo"><a>'+objectL10n.image+'</a></li></ul>');
		
	jQuery('.logo_control_tabs li.txtlogo a').click(function(){ 
		jQuery('.logo_control_tabs li').removeClass('activlogo');	jQuery(this).parent().addClass('activlogo');
		jQuery('#customize-control-blogname, #customize-control-blogdescription, #accordion-section-headlogo_section .font_controlheader, #customize-control-logo_font_family, #customize-control-logo_font_subsets, #customize-control-logo_font_size, #customize-control-logo_color_id').addClass('activelogoption').show();
		jQuery('#customize-control-logo_image_id').removeClass('activelogoption');
	});
	
	jQuery('.logo_control_tabs li.imglogo a').click(function(){ 
		jQuery('.logo_control_tabs li').removeClass('activlogo');	jQuery(this).parent().addClass('activlogo');
		jQuery('#customize-control-logo_image_id').addClass('activelogoption');
		jQuery('#customize-control-blogname, #customize-control-blogdescription, #accordion-section-headlogo_section .font_controlheader, #customize-control-logo_font_family, #customize-control-logo_font_subsets, #customize-control-logo_font_size, #customize-control-logo_color_id').removeClass('activelogoption').hide();
	});
		

		//CTA Buttons
		jQuery('#customize-control-static_cta1_text').before('<h4 class="control_cta1_title">'+objectL10n.button1+'</h4>');
		jQuery('#customize-control-static_cta2_text').before('<h4 class="control_cta2_title">'+objectL10n.button2+'</h4>');
	
		var cta1controls = jQuery('#customize-control-static_cta1_text, #customize-control-static_cta1_link, #customize-control-static_cta1_txt_style, #customize-control-static_cta1_bg_color, #customize-control-static_cta1_txt_color');
		var cta2controls = jQuery('#customize-control-static_cta2_text, #customize-control-static_cta2_link, #customize-control-static_cta2_txt_style, #customize-control-static_cta2_bg_color, #customize-control-static_cta2_txt_color');
		
		cta1controls.addClass('hidectas');
		jQuery('.control_cta1_title').on('click',function() {  
         if(!jQuery(cta1controls).hasClass('showctas')){
            cta1controls.removeClass('hidectas').addClass('showctas');  
         }else{
            cta1controls.addClass('hidectas').removeClass('showctas');  
         } 
      });
      cta2controls.addClass('hidectas');
      jQuery('.control_cta2_title').on('click',function() {  
         if(!jQuery(cta2controls).hasClass('showctas')){
            cta2controls.removeClass('hidectas').addClass('showctas');  
         }else{
            cta2controls.addClass('hidectas').removeClass('showctas');  
         } 
      });
      
		/*SLIDER CONTROL TAB*/
		jQuery('#customize-control-static_image_id, #customize-control-static_gallery, #customize-control-static_video_id, #customize-control-slide_ytbid, #customize-control-static_vid_loop, #customize-control-static_vid_mute').hide('');
		
		jQuery('#customize-control-static_image_id').addClass('activebgoption').before('<ul class="slider_control_tabs"><li class="imgbg activbg"><a>'+objectL10n.image+'</a></li><li class="slideshowbg"><a>'+objectL10n.slideshow+'</a></li><li class="vdobg"><a>'+objectL10n.video+'</a></li></ul>');
		
	jQuery('.slider_control_tabs li.imgbg a').click(function(){ 
		jQuery('.slider_control_tabs li').removeClass('activbg');	jQuery(this).parent().addClass('activbg');
		jQuery('#customize-control-static_gallery, #customize-control-static_video_id, #customize-control-slide_ytbid, #customize-control-static_vid_loop, #customize-control-static_vid_mute').removeClass('activebgoption');
      jQuery('#customize-control-static_image_id').addClass('activebgoption');
      jQuery('.sliderPRO_message').hide();
   });
   
   jQuery('#customize-control-static_image_id').after('<div class="sliderPRO_message" style="display:none">Upgrade to Access this Option</div>');
	
	jQuery('.slider_control_tabs li.slideshowbg a').click(function(){ 
		jQuery('.slider_control_tabs li').removeClass('activbg');	jQuery(this).parent().addClass('activbg');
      jQuery('#customize-control-static_image_id').attr('style', '').hide();
      jQuery('.sliderPRO_message').show();
		jQuery('#customize-control-static_image_id, #customize-control-static_video_id, #customize-control-slide_ytbid, #customize-control-static_vid_loop, #customize-control-static_vid_mute').removeClass('activebgoption');
		jQuery('#customize-control-static_gallery').addClass('activebgoption');
	});
	
	jQuery('.slider_control_tabs li.vdobg a').click(function(){ 
		jQuery('.slider_control_tabs li').removeClass('activbg');	jQuery(this).parent().addClass('activbg');
		jQuery('#customize-control-static_image_id').attr('style', '').hide();
      jQuery('#customize-control-static_gallery, #customize-control-static_image_id').removeClass('activebgoption');
      jQuery('.sliderPRO_message').show();
		jQuery('#customize-control-static_video_id, #customize-control-slide_ytbid, #customize-control-static_vid_loop, #customize-control-static_vid_mute').addClass('activebgoption');
	});

	//Slider Dropdown Select
	var staticontrols = jQuery('.slider_control_tabs, #customize-control-static_image_id, #customize-control-static_img_text_id, #customize-control-slider_txt_color, .control_cta1_title, .control_cta2_title, #customize-control-static_textbox_width, #customize-control-static_textbox_bottom, #customize-control-disable_slider_parallax');
	
	var staticontrols2 = jQuery('#customize-control-static_gallery, #customize-control-static_video_id, #customize-control-slide_ytbid, #customize-control-static_vid_loop, #customize-control-static_vid_mute,li#customize-control-static_cta1_text, li#customize-control-static_cta1_link, li#customize-control-static_cta1_txt_style, li#customize-control-static_cta1_bg_color, li#customize-control-static_cta1_txt_color, li#customize-control-static_cta2_text, li#customize-control-static_cta2_link, li#customize-control-static_cta2_txt_style,li#customize-control-static_cta2_bg_color, li#customize-control-static_cta2_txt_color, #customize-control-slider_content_align');
	
	var nivoaccordion = jQuery('#customize-control-nivo_accord_slider, #customize-control-slider_txt_hide, #customize-control-slidefont_size_id, #customize-control-n_slide_time_id, #customize-control-slide_height');
	
	var currentslider = jQuery('#customize-control-slider_type_id select option:selected').val();
	
	if(currentslider == 'accordion' || currentslider == 'nivo' || currentslider == 'noslider'){  
		staticontrols.addClass('hideslider'); staticontrols2.addClass('hideslider');  
	}
	if(currentslider == 'static' || currentslider == 'noslider'){  nivoaccordion.addClass('hideslider');  }
	
	if(currentslider == 'accordion' || currentslider == 'nivo' || currentslider == 'noslider'){  
		jQuery('#customize-control-static_image_id').addClass('hidestatimgc'); 
	}


		
	jQuery('#customize-control-slider_type_id select').on('change', function(){ 
		if(jQuery(this).find('option:selected').val() == 'static'){
			jQuery('#customize-control-static_image_id').removeClass('hideslider hidestatimgc');
			nivoaccordion.addClass('hideslider');
			staticontrols.removeClass('hideslider');
		}
		if(jQuery(this).find('option:selected').val() == 'accordion' || jQuery(this).find('option:selected').val() == 'nivo'){
			jQuery('#customize-control-static_image_id').attr('style', 'display:none!important;');
			staticontrols.addClass('hideslider');
			staticontrols2.addClass('hideslider').removeClass('activebgoption');
			nivoaccordion.removeClass('hideslider');
		}
		if(jQuery(this).find('option:selected').val() == 'noslider'){
			jQuery('#customize-control-static_image_id').attr('style', 'display:none!important;')
			nivoaccordion.addClass('hideslider');
			staticontrols.addClass('hideslider');
			staticontrols2.addClass('hideslider');
		}
	});
	
	jQuery('.slider_control_tabs').prepend('<span class="stattitle">'+objectL10n.statictitle+'</span>');
	jQuery('#customize-control-nivo_accord_slider').prepend('<span class="nivotitle">'+objectL10n.statictitle+'</span>');
	


	//Refresh Icons beside Controls that are not postMessage
	jQuery( "span.customize-control-title:contains('*')" ).addClass('control-refresh');
	jQuery('.control-refresh').each(function(index, element) {
        jQuery(this).html(jQuery(this).html().replace(/\*/g, ''));
    });
	jQuery('.control-refresh').append('<i class="fa fa-refresh" />');


		//REPLACE DUMMY CONTENT BUTTON FUNCTIONALITY
		wp.customize.previewer.bind( 'focus-frontsidebar', function(){
			jQuery('.wp-full-overlay').addClass('subsection-open');
			wp.customize.section( 'sidebar-widgets-front_sidebar' ).focus();
			jQuery('html, body').animate({scrollTop: jQuery('#customize-control-sidebars_widgets-front_sidebar').offset().top-100}, 150);
			jQuery('#customize-control-sidebars_widgets-front_sidebar .add-new-widget').removeClass('flashaddbutton').addClass('flashaddbutton');
			setTimeout(function () {  jQuery('#customize-control-sidebars_widgets-front_sidebar .add-new-widget').removeClass('flashaddbutton');  }, 500);
		});


	
	/*FRONTPAGE EDIT BUTTON*/				
	jQuery('.frontpage_edit_btn').click(function(){ 		
		jQuery('.quickie i, .quickie_text dl').removeClass('activeq'); jQuery('.quickie_widgets').addClass('activeq');		
		wp.customize.section( 'sidebar-widgets-front_sidebar' ).focus();		
	});		
	//Edit Widget Button For Other Pages
	wp.customize.previewer.bind( 'focus-current-sidebar', function(param){
			jQuery('.wp-full-overlay').addClass('subsection-open in-sub-panel section-open');
			console.log('Edit Button Clicked!');
			wp.customize.section( 'sidebar-widgets-'+param ).focus();
			wp.customize.control.each( function ( control ) {  if(control.expanded) control.collapse();  });
			jQuery('html, body').animate({scrollTop: jQuery('#customize-control-sidebars_widgets-front_sidebar').offset().top-100}, 150);
			jQuery('#customize-control-sidebars_widgets-'+param+' .add-new-widget').removeClass('flashaddbutton').addClass('flashaddbutton');
			setTimeout(function () {  jQuery('#customize-control-sidebars_widgets-'+param+' .add-new-widget').removeClass('flashaddbutton');  }, 500);
	} );
	
	
	/*PRO Message Highlight*/
	jQuery('.customize-control-info').html(function(_, html) {
	   return html.replace(/(PRO)/g, '<i class="pro_tag">$1</i>');
	});

});



/*CONVERSION PROCESS*/
jQuery(window).on('load', function(){

		var isconverted = wp.customize.instance('optimizer[converted]').get();
		if(isconverted == ''){
			wp.customize.instance('optimizer[converted]').set('1');
			jQuery('.conversion_message').prependTo('body.wp-customizer').fadeIn();
		}
});


jQuery(window).on('load', function(){
		jQuery('#accordion-section-sidebar-widgets-front_sidebar .add-new-widget').attr('id','limitbtn');
		jQuery('.add-new-widget, .widget-tpl').click(function(event) {
			if (event.target.id === "limitbtn") {
				jQuery( '#widgets-left').addClass('frontsidebarlimit');
			} else {
				jQuery( '#widgets-left').removeClass('frontsidebarlimit');
			}
		});

		var widgetcount = jQuery('#accordion-section-sidebar-widgets-front_sidebar .customize-control-widget_form').length;
		jQuery('#accordion-section-sidebar-widgets-front_sidebar .button-secondary.add-new-widget').before('<dl class="widgetblock" />');
		jQuery('#accordion-section-sidebar-widgets-front_sidebar .button-secondary.add-new-widget').html(objectL10n.addawidget+' '+widgetcount+'/4');
		jQuery('#accordion-section-sidebar-widgets-front_sidebar .button-secondary.add-new-widget').attr('data-widget-count',widgetcount);
		jQuery('#accordion-section-sidebar-widgets-front_sidebar dl').attr('data-dl-count',widgetcount);
		//console.log(widgetcount);
			
	jQuery(document).on('widget-added widget-updated', function(){
		// Add Limit Count in the Add Widget button
		var widgetcount = jQuery('#accordion-section-sidebar-widgets-front_sidebar .customize-control-widget_form').length;
		jQuery('#accordion-section-sidebar-widgets-front_sidebar .button-secondary.add-new-widget').html(objectL10n.addawidget+' '+widgetcount+'/4');
		jQuery('#accordion-section-sidebar-widgets-front_sidebar .button-secondary.add-new-widget').attr('data-widget-count',widgetcount);
		jQuery('#accordion-section-sidebar-widgets-front_sidebar dl').attr('data-dl-count',widgetcount);
		console.log(widgetcount);
	});
	
	//UPDATE COUNT ON WIDGET REMOVE 
	jQuery(document).on("click", "#accordion-section-sidebar-widgets-front_sidebar .widget-control-remove", function(e) {
		var widgetcount = jQuery('#accordion-section-sidebar-widgets-front_sidebar .customize-control-widget_form').length -1;
		jQuery('#accordion-section-sidebar-widgets-front_sidebar .button-secondary.add-new-widget').html(objectL10n.addawidget+' '+widgetcount+'/4');
		jQuery('#accordion-section-sidebar-widgets-front_sidebar .button-secondary.add-new-widget').attr('data-widget-count',widgetcount);
		jQuery('#accordion-section-sidebar-widgets-front_sidebar dl').attr('data-dl-count',widgetcount);
	});
	
	
	//Widgets List Modification
		jQuery('#available-widgets-list').prepend('<ul class="optimizer_widget_list"><li class="currnt_widgets"><a>'+objectL10n.optimwidgt+'</a></li><li><a>'+objectL10n.othrimwidgt+'</a></li></ul>');
		jQuery('.optimizer_widget_list li').eq(1).click(function() {
			jQuery( '.optimizer_widget_list li').removeClass('currnt_widgets');
			jQuery( this ).addClass('currnt_widgets');
			jQuery( '#available-widgets').addClass('active-otherwidget');
		});
		
		jQuery('.optimizer_widget_list li').eq(0).click(function() {
			jQuery( '.optimizer_widget_list li').removeClass('currnt_widgets');
			jQuery( this ).addClass('currnt_widgets');
			jQuery( '#available-widgets').removeClass('active-otherwidget');
		});
		
		//Replace the "Widget"
		jQuery('#available-widgets-list .widget-tpl').each(function() {
			jQuery(this).prepend('<i class="fa fa-info"></i>');
		});
		
		//Wrap All Optimizer Widgets to fix the description popup 
		jQuery('#available-widgets [id*="widget-tpl-optimizer_"], #available-widgets [id*="widget-tpl-ast_"], #available-widgets [id*="widget-tpl-thn_"]').wrapAll('<div class="the_optim_widgets"></div>');
		jQuery('#available-widgets-list > .widget-tpl').wrapAll('<div class="the_other_widgets"></div>');
		
		jQuery('#available-widgets-list .widget-tpl .fa-info').hoverIntent(function(){ 
			jQuery(this).parent().find('.widget-description').fadeIn(200);
		},function(){
			jQuery(this).parent().find('.widget-description').fadeOut(200);
		});
		
		
		//Append Blank PRO widgets
		function optimizer_dummy_widget(widgettitle, widgetid){
			return '<div id="widget-tpl-optimizer_front_'+widgetid+'-1" class="widget-tpl pro_widgets"><div id="widget-3_optimizer_front_'+widgetid+'-__i__" class="widget"><div class="widget-top"><div class="widget-title"><h4>'+widgettitle+'<span class="in-widget-title"></span></h4></div></div><div class="widget-description">'+objectL10n.prowidget+'</div></div></div>';	
		}
		jQuery('#available-widgets-list').append(optimizer_dummy_widget('Clients Logo Widget','clients'));
		jQuery('#available-widgets-list').append(optimizer_dummy_widget('Contact Widget','contact'));
		jQuery('#available-widgets-list').append(optimizer_dummy_widget('CTA Widget','cta'));
		jQuery('#available-widgets-list').append(optimizer_dummy_widget('Slider Widget','slider'));
		jQuery('#available-widgets-list').append(optimizer_dummy_widget('Testimonial Widget','testimonial'));
		jQuery('#available-widgets-list').append(optimizer_dummy_widget('Video Widget','video'));
		jQuery('#available-widgets-list').append(optimizer_dummy_widget('Portfolio Widget','portfolio'));
		jQuery('#available-widgets-list').append(optimizer_dummy_widget('Biography Widget','biography'));
		jQuery('#available-widgets-list').append(optimizer_dummy_widget('Countdown Widget','countdown'));
		jQuery('#available-widgets-list').append(optimizer_dummy_widget('Flickr Widget','flickr'));
		jQuery('#available-widgets-list').append(optimizer_dummy_widget('Social Bookmark Widget','social'));
});


jQuery( document ).on('load ready', function() {

    /* === Checkbox Multiple Control === */

    jQuery( '.customize-control-multicheck input[type="checkbox"]' ).on(
        'change',
        function() {

            checkbox_values = jQuery( this ).parents( '.customize-control' ).find( 'input[type="checkbox"]:checked' ).map(
                function() {
                    return this.value;
                }
            ).get().join( ',' );

            jQuery( this ).parents( '.customize-control' ).find( 'input[type="hidden"]' ).val( checkbox_values ).trigger( 'change' );
        }
    );
	/* === RADIO Image Control === */
	
    // Use buttonset() for radio images.
    jQuery( '.customize-control-radio-image .buttonset' ).buttonset();

    // Handles setting the new value in the customizer.
    jQuery( '.customize-control-radio-image input:radio' ).change(
        function() {

            // Get the name of the setting.
            var setting = jQuery( this ).attr( 'data-customize-setting-link' );

            // Get the value of the currently-checked radio input.
            var image = jQuery( this ).val();

            // Set the new value.
            wp.customize( setting, function( obj ) {

                obj.set( image );
            } );
        }
    );

} ); // jQuery( document ).on('load ready)


jQuery(document).ready(function($) {
	"use strict";
	
	$(document).on("click", ".Switch.On", function(e) {
		$(this).removeClass('On').addClass('Off');
	});
	
	$(document).on("click", ".Switch.Off", function(e) {
		$(this).removeClass('Off').addClass('On');
	});

});

/*GENERATE EXPORT*/
jQuery(document).ready(function($) {
	jQuery( '#generatexport' ).on( "click", function(e) {
		e.preventDefault();
		var value = jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data:{
				action: 'optimizer_get_options'
				}
			})
			 .fail(function(r,status,jqXHR) {
				 console.log('failed');
			 })
			 .done(function(result,status,jqXHR) {
				//console.log('success');
				//console.log(result);
				jQuery('#opt_current_options').html(result);
				  function SaveAsFile(t,f,m) {
						try {
							var b = new Blob([t],{type:m});
							saveAs(b, f);
						} catch (e) {
							window.open("data:"+m+"," + encodeURIComponent(t), '_blank','');
						}
					}
			
					SaveAsFile(result,"themeoptions.json","text/plain");
			 });
	});
});


/*OPTIMIZER THEME TOUR*/
jQuery( document ).on('load ready', function() {
	wp.customize.previewer.bind( 'start-tour', function(){
		if(!Cookies.get('optimizertour')){
			jQuery('#optimizerTour, .tour_backdrop').fadeIn();
		}
	} );
	
	//Social Link fields
	jQuery('#customize-control-facebook_field_id').prepend('<div class="social_fields_heading">'+objectL10n.socialinks+'</div>');
	
	//Append Previwe window inner shadow
	jQuery('#customize-preview').prepend('<div id="tour_innerglow"><span class="innerglow glow1"></span><span class="innerglow glow2"></span><span class="innerglow glow3"></span><span class="innerglow glow4"></span></div>');
	
	//Tour Function
	jQuery('.tournext').on('click', function() {
		if(jQuery(this).parent().next().is("li")){
			jQuery(this).parent().hide();
			jQuery(this).parent().next().show();
			var elmid = jQuery(this).parent().next().data('id');
			if(jQuery(this).parent().next().data('preview') == 'true'){}
			jQuery('.tourhighlight').removeClass('tourhighlight');
			jQuery("#customize-preview iframe").contents().find('.tourhighlight').removeClass('tourhighlight'); 
			jQuery('#'+elmid).addClass('tourhighlight');
			if(elmid == 'frontsidebar' || elmid == 'customizer_topbar'){ 
				//console.log('Preview True');
				jQuery("#customize-preview iframe").contents().find('#'+elmid).addClass('tourhighlight'); 
			}
		}
	} );
	
	jQuery('.tourprev').on('click', function() {
		if(jQuery(this).parent().prev().is("li")){
			jQuery(this).parent().hide();
			jQuery(this).parent().prev().show();
			var elmid = jQuery(this).parent().prev().data('id');
			jQuery('.tourhighlight').removeClass('tourhighlight');
			jQuery("#customize-preview iframe").contents().find('.tourhighlight').removeClass('tourhighlight'); 
			jQuery('#'+elmid).addClass('tourhighlight');
			if(elmid == 'frontsidebar' || elmid == 'customizer_topbar'){ 
				console.log('Preview True');
				jQuery("#customize-preview iframe").contents().find('#'+elmid).addClass('tourhighlight'); 
			}
		}
	} );

	jQuery('.tourend, .tourclose').on('click', function() {
		jQuery('#optimizerTour, .tour_backdrop').fadeOut();
		Cookies.set('optimizertour', 1, { expires: 365, path: '/'});
	} );
	
	jQuery('#tour_btn').on('click', function() {
		jQuery('#optimizerTour, .tour_backdrop').fadeIn();
		jQuery('.tourclose').show();
		jQuery('#optimizerTour>li').hide();
		jQuery('#optimizerTour li').eq(0).show();
		jQuery('#optimizer_settings').animate({"left":"-831px"});
		jQuery('.opactive').removeClass('opactive');
		wp.customize.panel.each( function ( panel ) {
			panel.collapse();
		});
	} );
	

function isPastDate(value) {
    return new Date() > new Date(value);
}

var promodate = isPastDate('2017-05-31');

if(promodate == false){
	
	jQuery('.upgrade_btn').append('<i class="theme_notification">1</i>');
	jQuery('.upgrade_btn').after('<div class="chrismas_promo"><p><b>MAY SALE:</b></br>Use coupon code "MAY17" to get 15% off on all purchase. Offer Valid till 31st May, 2017.</p></div>');
	jQuery('.upgrade_btn').hover(function(){ 
			jQuery('.chrismas_promo').fadeIn();
	},function(){
			jQuery('.chrismas_promo').fadeOut();
	});
	
}

	
} );

jQuery(window).on('load', function(){	
	
	//Tutorial Video
	jQuery('#customize-control-help-tuts .description').click(function() {
		jQuery('.basic_guide, .guide_backdrop').removeClass('vid_maximize vid_minimize vid_minimized').fadeIn();
	});	
	
	//Video Guides
	jQuery('#customize-control-help-createbus .description').click(function() {
		jQuery('.business_guide, .guide_backdrop').removeClass('vid_maximize vid_minimize vid_minimized').fadeIn();
	});	
	
	//Faq Tabs
	jQuery('.faq_tab_controls h3').click(function() {
		jQuery('.faq_tab_controls h3, .faq_tabs').removeClass('faq_active faq_tab_active'); jQuery(this).addClass('faq_active');  jQuery('.'+jQuery(this).attr('data-tab')).addClass('faq_tab_active');
	});
	
	jQuery('#customize-control-help-faq .description a').click(function() {	jQuery('#faq_tab, .guide_backdrop').fadeIn(); });
	jQuery('.faq_title_bar i').click(function() {	jQuery('#faq_tab, .guide_backdrop').fadeOut(); });
	
	
} );

//Custom Code Full Width
jQuery( document ).on('load ready', function() {
   var codeTypes = [{id:'css', title: 'CSS'}, {id:'js', title: 'Javascript'}];
   codeTypes.forEach(function(code){
      jQuery('#customize-control-custom-'+code.id).prepend('<a id="optimizer_'+code.id+'_full_button" title="Expand"><i class="fa-expand" /></a>');
      jQuery('#optimizer_'+code.id+'_full_button').on('click', function(){
         var currentCode = jQuery('#_customize-input-custom-'+code.id).val();
         jQuery('.wp-full-overlay-sidebar').prepend('<div id="optimizer_custom_'+code.id+'_full"><h3>Custom '+code.title+'</h3><div><textarea value="'+currentCode+'">'+currentCode+'</textarea><a onclick="updateCustomCode(\''+code.id+'\')">Update</a></div></div>');
      });
   })
});
function updateCustomCode(type){
   var currentCode = jQuery('#_customize-input-custom-'+type).val();
   var updatedCode = jQuery('#optimizer_custom_'+type+'_full textarea').val();
   if(currentCode !== updatedCode){
      jQuery('#_customize-input-custom-'+type).val(updatedCode).trigger('change');
      jQuery('#optimizer_custom_'+type+'_full').remove();
   }else{
      jQuery('#optimizer_custom_'+type+'_full').remove();
   }
}

////////////
	

/**
 * jQuery Unveil
 * A very lightweight jQuery plugin to lazy load images
 * http://luis-almeida.github.com/unveil
 *
 * Licensed under the MIT license.
 * Copyright 2013 Luís Almeida
 * https://github.com/luis-almeida
 */

(function(e){e.fn.unveil=function(t,n){function f(){var t=u.filter(function(){var t=e(this);if(t.is(":hidden"))return;var n=r.scrollTop(),s=n+r.height(),o=t.offset().top,u=o+t.height();return u>=n-i&&o<=s+i});a=t.trigger("unveil");u=u.not(a)}var r=e(window),i=t||0,s=window.devicePixelRatio>1,o=s?"data-src-retina":"data-src",u=this,a;this.one("unveil",function(){var e=this.getAttribute(o);e=e||this.getAttribute("data-src");if(e){this.setAttribute("src",e);if(typeof n==="function")n.call(this)}});r.on("scroll.unveil resize.unveil lookup.unveil",f);f();return this}})(window.jQuery||window.Zepto);

/*! 
 * FileSaver.js
 * https://github.com/eligrey/FileSaver.js/
 * Released under the MIT license
 */
(function(a,b){if("function"==typeof define&&define.amd)define([],b);else if("undefined"!=typeof exports)b();else{b(),a.FileSaver={exports:{}}.exports}})(this,function(){"use strict";function b(a,b){return"undefined"==typeof b?b={autoBom:!1}:"object"!=typeof b&&(console.warn("Deprecated: Expected third argument to be a object"),b={autoBom:!b}),b.autoBom&&/^\s*(?:text\/\S*|application\/xml|\S*\/\S*\+xml)\s*;.*charset\s*=\s*utf-8/i.test(a.type)?new Blob(["\uFEFF",a],{type:a.type}):a}function c(a,b,c){var d=new XMLHttpRequest;d.open("GET",a),d.responseType="blob",d.onload=function(){g(d.response,b,c)},d.onerror=function(){console.error("could not download file")},d.send()}function d(a){var b=new XMLHttpRequest;b.open("HEAD",a,!1);try{b.send()}catch(a){}return 200<=b.status&&299>=b.status}function e(a){try{a.dispatchEvent(new MouseEvent("click"))}catch(c){var b=document.createEvent("MouseEvents");b.initMouseEvent("click",!0,!0,window,0,0,0,80,20,!1,!1,!1,!1,0,null),a.dispatchEvent(b)}}var f="object"==typeof window&&window.window===window?window:"object"==typeof self&&self.self===self?self:"object"==typeof global&&global.global===global?global:void 0,a=/Macintosh/.test(navigator.userAgent)&&/AppleWebKit/.test(navigator.userAgent)&&!/Safari/.test(navigator.userAgent),g=f.saveAs||("object"!=typeof window||window!==f?function(){}:"download"in HTMLAnchorElement.prototype&&!a?function(b,g,h){var i=f.URL||f.webkitURL,j=document.createElement("a");g=g||b.name||"download",j.download=g,j.rel="noopener","string"==typeof b?(j.href=b,j.origin===location.origin?e(j):d(j.href)?c(b,g,h):e(j,j.target="_blank")):(j.href=i.createObjectURL(b),setTimeout(function(){i.revokeObjectURL(j.href)},4E4),setTimeout(function(){e(j)},0))}:"msSaveOrOpenBlob"in navigator?function(f,g,h){if(g=g||f.name||"download","string"!=typeof f)navigator.msSaveOrOpenBlob(b(f,h),g);else if(d(f))c(f,g,h);else{var i=document.createElement("a");i.href=f,i.target="_blank",setTimeout(function(){e(i)})}}:function(b,d,e,g){if(g=g||open("","_blank"),g&&(g.document.title=g.document.body.innerText="downloading..."),"string"==typeof b)return c(b,d,e);var h="application/octet-stream"===b.type,i=/constructor/i.test(f.HTMLElement)||f.safari,j=/CriOS\/[\d]+/.test(navigator.userAgent);if((j||h&&i||a)&&"undefined"!=typeof FileReader){var k=new FileReader;k.onloadend=function(){var a=k.result;a=j?a:a.replace(/^data:[^;]*;/,"data:attachment/file;"),g?g.location.href=a:location=a,g=null},k.readAsDataURL(b)}else{var l=f.URL||f.webkitURL,m=l.createObjectURL(b);g?g.location=m:location.href=m,g=null,setTimeout(function(){l.revokeObjectURL(m)},4E4)}});f.saveAs=g.saveAs=g,"undefined"!=typeof module&&(module.exports=g)});

/*! js-cookie v3.0.0-rc.0 | MIT */
!function(e,t){"object"==typeof exports&&"undefined"!=typeof module?module.exports=t():"function"==typeof define&&define.amd?define(t):(e=e||self,function(){var r=e.Cookies,n=e.Cookies=t();n.noConflict=function(){return e.Cookies=r,n}}())}(this,function(){"use strict";function e(e){for(var t=1;t<arguments.length;t++){var r=arguments[t];for(var n in r)e[n]=r[n]}return e}var t={read:function(e){return e.replace(/%3B/g,";")},write:function(e){return e.replace(/;/g,"%3B")}};return function r(n,i){function o(r,o,u){if("undefined"!=typeof document){"number"==typeof(u=e({},i,u)).expires&&(u.expires=new Date(Date.now()+864e5*u.expires)),u.expires&&(u.expires=u.expires.toUTCString()),r=t.write(r).replace(/=/g,"%3D"),o=n.write(String(o),r);var c="";for(var f in u)u[f]&&(c+="; "+f,!0!==u[f]&&(c+="="+u[f].split(";")[0]));return document.cookie=r+"="+o+c}}return Object.create({set:o,get:function(e){if("undefined"!=typeof document&&(!arguments.length||e)){for(var r=document.cookie?document.cookie.split("; "):[],i={},o=0;o<r.length;o++){var u=r[o].split("="),c=u.slice(1).join("="),f=t.read(u[0]).replace(/%3D/g,"=");if(i[f]=n.read(c,f),e===f)break}return e?i[e]:i}},remove:function(t,r){o(t,"",e({},r,{expires:-1}))},withAttributes:function(t){return r(this.converter,e({},this.attributes,t))},withConverter:function(t){return r(e({},this.converter,t),this.attributes)}},{attributes:{value:Object.freeze(i)},converter:{value:Object.freeze(n)}})}(t,{path:"/"})});