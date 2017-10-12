(function( $ ) {
	'use strict';

	var changeSlide = function(index) {
		var scrollEl;
		$('.cw-slide').addClass('cw-hide');
		$('.cw-slide[data-index="' + index + '"]').removeClass('cw-hide');

		if( index == 0 ) {
			scrollEl = $('.cw-slide-overview');
		} else {
			scrollEl = $('.cw-content-slides');
		}

		$('html,body').animate({
			scrollTop: scrollEl.offset().top - 100
		}, 600 );
	}

	$('.cw-slide-nav').on('click', function(e) {
		var index, state;
		index = $(this).attr('data-open');
		state = { 'slide': index };
		history.pushState(state, '', '?cw-slide=' + index );

		if( index == 0 ) {
			$('.cw-begin-slideshow').removeClass('cw-hide');
			$('.cw-slide-overview').removeClass('cw-hide');
		}

		if( cwSlideshow.options.force_reload == true ) {
			window.location.href = window.location.href;
		} else {
			changeSlide(index);
		}

	});

	$('.cw-begin-slideshow').on('click', function(e) {
		var index, state;
		index = $(this).attr('data-open');
		state = { 'slide': index };
		history.pushState(state, '', '?cw-slide=' + index );

		$(this).addClass('cw-hide');

		$('.cw-slide-overview').addClass('cw-hide');

		if( cwSlideshow.options.force_reload == true ) {
			window.location.href = window.location.href;
		} else {
			changeSlide(index);
		}
	});

})( jQuery );
