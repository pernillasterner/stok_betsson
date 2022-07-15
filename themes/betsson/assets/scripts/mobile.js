var Mobile = {
	isMobile: function () {
		var result = false;

		( function ( a ) {
			result = /Android|webOS|iPhone|iPad|BlackBerry|Windows Phone|Opera Mini|IEMobile|Mobile/i.test( a );
		} )( navigator.userAgent || navigator.vendor || window.opera );

		return result;
	}
};