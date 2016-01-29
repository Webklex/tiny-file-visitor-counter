(function( $ ) {
	'use strict';

	jsCounter.init({
		tagPrefix: 'js-counter-',
		backend: php_vars.backend,
		async: true,
		live: {
			enabled: php_vars.live,
			timeout: php_vars.timeout,
			resource: null
		}
	});

})( jQuery );