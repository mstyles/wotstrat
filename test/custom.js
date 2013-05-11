/*************************** Menu ***************************/


function mainmenu(){
jQuery("#nav li a").removeAttr("title");
jQuery("#nav a").removeAttr("title");
jQuery("#nav li ul li:first-child").addClass("nav-first");
jQuery("#nav li ul li:last-child").addClass("nav-last");
jQuery("#nav ul ").css({display: "none"}); // Opera Fix
jQuery("#nav li").hover(function(){
		jQuery(this).find('ul:first').css({visibility: "visible",display: "none"}).show(400);
		},function(){
		jQuery(this).find('ul:first').css({visibility: "hidden"});
		});
}
 
jQuery(document).ready(function(){					
	mainmenu();
});


/*************************** Hover Effect ***************************/


jQuery(document).ready(function(){

	jQuery('#st1, #st2, #st3, #st4, #st5, #st6, #st7, #st8, #st9, #st10').css({'opacity':'0.9'});
	jQuery('#kwick-1, #kwick-2, #kwick-3, #kwick-4, #kwick-5, #kwick-6, #kwick-7, #kwick-8, #kwick-9, #kwick-10').hover(
		function() {
			jQuery(this).find('#st1, #st2, #st3, #st4, #st5, #st6, #st7, #st8, #st9, #st10').stop().fadeTo(500, 0);
		},
		function() {
			jQuery(this).find('#st1, #st2, #st3, #st4, #st5, #st6, #st7, #st8, #st9, #st10').stop().fadeTo(500, 0.9);
		}			
	)

	jQuery('.gallery-image-details').css({'opacity':'0'});
	jQuery('.gallery-image, .post-image').hover(
		function() {
			jQuery(this).find('.gallery-image-details').stop().fadeTo(500, 0.9);
		},
		function() {
			jQuery(this).find('.gallery-image-details').stop().fadeTo(500, 0);
		}			
	)
	
});


/*************************** Image Preloader ***************************/


jQuery(function () {
	jQuery('.preload').hide();//hide all the images on the page
});

var i = 0;//initialize
var int=0;//Internet Explorer Fix
jQuery(window).bind("load", function() {//The load event will only fire if the entire page or document is fully loaded
	var int = setInterval("doThis(i)",500);//500 is the fade in speed in milliseconds
});

function doThis() {
	var images = jQuery('.preload').length;//count the number of images on the page
	if (i >= images) {// Loop the images
		clearInterval(int);//When it reaches the last image the loop ends
	}
	jQuery('.preload:hidden').eq(0).fadeIn(500);//fades in the hidden images one by one
	i++;//add 1 to the count
}


/*************************** Tool Tips ***************************/


jQuery(document).ready(function() { 
    jQuery("#social-icons img[title]").tooltip({
        tip: '.tooltip', 
        effect: 'slide', 
        position: "bottom left",         
        offset: [15, 95] 
    });
});


/*************************** Lightbox ***************************/


jQuery(document).ready(function(){
	jQuery("a[rel^='prettyPhoto']").prettyPhoto({
		theme: 'facebook'
	});
});


/*************************** Tabs ***************************/


jQuery(document).ready(function(){
	// We can use this object to reference the panels container
	var panelContainer = jQuery('div#panels');
	// Create a DIV for the tabs and insert it before the panel container
	jQuery('<div id="tabs"></div>').insertBefore(panelContainer);
	
	// Find panel names and create nav
	// -- Loop through each panel
	panelContainer.find('div.panel').each(function(n){
		// For each panel, create a tab
		jQuery('div#tabs').append('<a class="tab" href="#' + (n+1) + '">' + jQuery(this).attr('title') + '</a>');
	});
	
	// Determine which tab should show first based on the URL hash
	var panelLocation = location.hash.slice(1);
	if(panelLocation){
		var panelNum = panelLocation;
	}else{
		var panelNum = '1';
	}
	// Hide all panels
	panelContainer.find('div.panel').hide();
	// Display the initial panel
	panelContainer.find('div.panel:nth-child(' + panelNum + ')').fadeIn('slow');
	// Change the class of the current tab
	jQuery('div#tabs').find('a.tab:nth-child(' + panelNum + ')').removeClass().addClass('tab-active');
	
	// What happens when a tab is clicked
	// -- Loop through each tab
	jQuery('div#tabs').find('a').each(function(n){
		// For each tab, add a 'click' action
		jQuery(this).click(function(){
			// Hide all panels
			panelContainer.find('div.panel').hide();
			// Find the required panel and display it
			panelContainer.find('div.panel:nth-child(' + (n+1) + ')').fadeIn('slow');
			// Give all tabs the 'tab' class
			jQuery(this).parent().find('a').removeClass().addClass('tab');
			// Give the clicked tab the 'tab-active' class
			jQuery(this).removeClass().addClass('tab-active');
		});
	});
});


/*************************** Contact Form ***************************/


jQuery(document).ready(function(){
	
	jQuery('#contactform').submit(function(){
	
		var action = jQuery(this).attr('action');
		
		jQuery("#message").slideUp(750,function() {
		jQuery('#message').hide();
		
 		jQuery('#submit')
			.after('<div class="loader"> </div>')
			.attr('disabled','disabled');
		
		jQuery.post(action, { 
			name: jQuery('#name').val(),
			email: jQuery('#email').val(),
			subject: jQuery('#subject').val(),
			comment_box: jQuery('#comment_box').val(),
			verify: jQuery('#verify').val()
		},
			function(data){
				document.getElementById('message').innerHTML = data;
				jQuery('#message').slideDown('slow');
				jQuery('#contactform div.loader').fadeOut('slow',function(){jQuery(this).remove()});
				jQuery('#contactform #submit').attr('disabled',''); 
				if(data.match('success') != null) jQuery('#contactform').slideUp('slow');
				
			}
		);
		
		});
		
		return false; 
	
	});
	
});