jQuery(document).ready(function($){
	function hidePopup(){
		$('.rbd-re-popup-cloud').fadeOut().addClass('hidden');
	}

	function insertAtCaret(areaId,text) {
	    var txtarea = document.getElementById(areaId);
	    var scrollPos = txtarea.scrollTop;
	    var strPos = 0;
	    var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
	    	"ff" : (document.selection ? "ie" : false ) );
	    if (br == "ie") {
	    	txtarea.focus();
	    	var range = document.selection.createRange();
	    	range.moveStart ('character', -txtarea.value.length);
	    	strPos = range.text.length;
	    }
	    else if (br == "ff") strPos = txtarea.selectionStart;

	    var front = (txtarea.value).substring(0,strPos);
	    var back = (txtarea.value).substring(strPos,txtarea.value.length);
	    txtarea.value=front+text+back;
	    strPos = strPos + text.length;
	    if (br == "ie") {
	    	txtarea.focus();
	    	var range = document.selection.createRange();
	    	range.moveStart ('character', -txtarea.value.length);
	    	range.moveStart ('character', strPos);
	    	range.moveEnd ('character', 0);
	    	range.select();
	    }
	    else if (br == "ff") {
	    	txtarea.selectionStart = strPos;
	    	txtarea.selectionEnd = strPos;
	    	txtarea.focus();
	    }
	    txtarea.scrollTop = scrollPos;
	}

	$('.rbd-re-form').on( 'submit', function( event ) {
		event.preventDefault();

		var generated = $(this).serialize();
			console.log(generated);
			//Do stuff to "generated"
			generated = decodeURIComponent( generated );
			generated = '[rbd_review_engine '+ generated +'"]';
			generated = generated.replace(/\&/g, '" ').replace(/\=/g, '="').replace(/\+/g, ' ').replace(new RegExp('" ]', 'g'), '"]');

		var txtareaaffected = $('.wp-editor-area').attr("id");
		insertAtCaret(txtareaaffected, generated);
		tinyMCE.execCommand('mceInsertContent', false, generated);

		$('.rbd-re-popup-cloud').fadeOut().addClass('hidden');
		//console.log( $( this ).serialize() );
	});

	$('#insert-reviews-button').mousedown(function(){
		$('.rbd-re-popup-cloud').fadeIn().removeClass('hidden');
	});

	$(document).keyup(function(e) {
		if (e.keyCode == 27) {
			hidePopup();
		}
	});

	$('.rbd-core-ui #rbd-re-popup-close').click(function(){
		hidePopup();
	});

	$('.rbd-re-popup-cloud').click(function(e){
		if(e.target == this){
			hidePopup();
		}
	});

	var $r = jQuery('.rbd-core-ui-admin input[type="range"]');
	var $ruler = jQuery('<div class="rangeslider__ruler" />');

	// Initialize
	$r.each(function(){
		$(this).rangeslider({
			polyfill: false,
		});
	});

	$('.rbd-core-ui-admin .color-picker').wpColorPicker();
});

jQuery(document).on('widget-updated', function(e, widget){
	var $r = jQuery('.rbd-core-ui-admin input[type="range"]');
	var $ruler = jQuery('<div class="rangeslider__ruler" />');

	// Initialize
	$r.each(function(){
		jQuery(this).rangeslider({
			polyfill: false,
		});
	});

	jQuery('.rbd-core-ui-admin .color-picker').wpColorPicker();
});

jQuery(document).on('widget-added', function(e, widget){
	var $r = jQuery('.rbd-core-ui-admin input[type="range"]');
	var $ruler = jQuery('<div class="rangeslider__ruler" />');

	// Initialize
	$r.each(function(){
		jQuery(this).rangeslider({
			polyfill: false,
		});
	});

	jQuery('.rbd-core-ui-admin .color-picker').wpColorPicker();
	jQuery('.wp-color-result').hide();
	jQuery('.wp-picker-container .wp-picker-container .wp-color-result').show();
});
