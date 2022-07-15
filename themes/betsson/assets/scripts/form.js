/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var form = {};

form.fileUploadSelected = function () {
	if(jQuery('.section.form').length){
		jQuery(document).on('change', ':file', function() {
			var input = jQuery(this),
				numFiles = input.get(0).files ? input.get(0).files.length : 1,
				label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
			input.trigger('fileselect', [numFiles, label]);
		});

		jQuery(':file').on('fileselect', function(event, numFiles, label) {
			jQuery(this).closest('div.form-group').find('div.selected-file').html(label);
		});
	}
};	

( function( $ ) {
	form.fileUploadSelected();
} )( jQuery );
