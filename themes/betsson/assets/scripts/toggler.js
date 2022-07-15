
var toggler = {};

( function( $ ) {

	toggler.init = function () {

		$('.toggler').click(function(e) {
			e.preventDefault();

			var $this = $(this);

			if ($this.next().hasClass('show')) {
				$this.removeClass('active');
				$this.next().removeClass('show');
				$this.next().slideUp(350);
			} else {
				$this.addClass('active');
				$this.next().addClass('show');
				$this.next().slideDown(350);
			}
		});
    };

    $( document ).ready(function() {
        toggler.init();
    });

} )( jQuery );
