'use strict';

$(document).ready(function() {

	var body = $('body');
	var banner = $('div.banner');
	var mainnav = $('nav.main');
	var dropdown = $('div.dropdown');

	mainnav.click(function() {
		if($(dropdown).css('display') === 'none') {
			$(dropdown).stop(true, true).slideDown('fast');
		} else {
			$(dropdown).stop(true, true).slideUp('fast');
		}
	});

	banner.mouseenter(function() {
		$(dropdown).slideUp('fast');
	});


	/*************************************/
	/** VIDEO RESPONSIVENESS **/
	/*************************************/
	// Find all YouTube videos
	var $allVideos = $("iframe[src^='https://www.youtube.com'],iframe[src^='https://player.vimeo.com']");

    // The element that is fluid width
    var $fluidEl = $(".video-holder");

	// Figure out and save aspect ratio for each video
	$allVideos.each(function() {
	  	$(this)
		    .data('aspectRatio', this.height / this.width)

		    // and remove the hard coded width/height
		    .removeAttr('height')
		    .removeAttr('width');

	});

	// When the window is resized
	$(window).resize(function() {

		var $newWidth = $fluidEl.width();

		// Resize all videos according to their own aspect ratio
		$allVideos.each(function() {

		var $el = $(this);
		$el
			.width($newWidth)
			.height($newWidth * $el.data('aspectRatio'));
		});

	// Kick off one resize to fix all videos on page load
	}).resize();


	/*************************************/
	/** ADD TO CART **/
	/*************************************/
	$('.add_to_cart_button').click(function() {
		$(this).fadeOut('fast');
		$('.cart-loading').fadeIn('fast');

	    setTimeout(function () {
			$.ajax({ url: '/wp-content/themes/forsee/ajax-add-to-cart.php',
	        	async: false,
				success: function(output) {
					$('.cart-contents').html(output);
					$('a.checkout-btn').fadeIn('fast');

					$('.cart-loading').fadeOut('fast');
				},
				error: function() {
					$('.cart-contents').html('fail');
					$('.cart-loading').fadeOut('fast');
				}
			});	
		}, 1000);
	});



	/*************************************/
	/** FREE TRIAL **/
	/*************************************/
	$('form.register p:last').after('<div class="submit-spinner"><img src="/wp-includes/images/spinner-2x.gif" /></div>');
	$('form.register').submit(function(e) {
		$('.submit-spinner').fadeIn('fast');
	});


	/*************************************/
	/** FAQ **/
	/*************************************/
	$('.faq-question').click(function() {
		var answer;
		$('.faq-answer').slideUp('fast');
		answer = $(this).parent().find('.faq-answer');
		if($(answer).css('display') === 'none') {
			$(answer).slideDown('fast');
		} else {
			$(answer).slideUp('fast');
		}
	});


	jQuery('#reg_email').attr('placeholder', 'This email will be used to send your details'); 

});