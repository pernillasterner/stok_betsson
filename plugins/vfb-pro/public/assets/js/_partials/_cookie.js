jQuery(document).ready(function($) {
	if ( $.fn.phoenix ) {
		$( '.vfbp-form :input:not(".vfb-file-input")' ).phoenix();
	}
});
