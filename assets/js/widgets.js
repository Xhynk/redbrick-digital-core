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
		}).on('mouseout', function() {
			$(this).unslider('start');
		});
	});
});
