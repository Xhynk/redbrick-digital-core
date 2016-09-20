jQuery(document).ready(function($){
	var autoplay	= $('.rbd-review-slider').attr('data-attr-slider-autoplay');
		speed		= $('.rbd-review-slider').attr('data-attr-slider-speed');
		delay		= $('.rbd-review-slider').attr('data-attr-slider-delay');
		$unslider	= $('.review-slider-container').unslider(
		{
			autoplay:	autoplay,
			speed:		speed,
			delay:		delay,
			infinite:	true
		}
	);

	$unslider.each(function(){
		$(this).on('mouseover', function() {
			$(this).unslider('stop');
			$('.unslider-progress-bar-fill').animate({ width: '0%' }, 0);
		}).on('mouseout', function() {
			$(this).unslider('start');
			$('.unslider-progress-bar-fill').animate({ width: '100%' }, 10000);
		});
		$('.unslider-progress-bar-fill').animate({ width: '100%' }, 10000);
	});
});
