jQuery(document).ready(function($) {
	var msgMinWords   = 'This value is too short. It should have %s words or more.',
		msgMaxWords   = 'This value is too long. It should have %s words or fewer.',
		msgWordsRange = 'This value length is invalid. It should be between %s and %s words long.';

	// If custom validation messages
	if ( window.vfbp_validation_custom ) {
		var messages = vfbp_validation_custom.vfbp_messages;

		msgMinWords   = messages.minwords;
		msgMaxWords   = messages.maxwords;
		msgWordsRange = messages.words;
	}

	// minwords, maxwords, words extra validators
	var countWords = function (string) {
	  return string
	      .replace( /(^\s*)|(\s*$)/gi, "" )
	      .replace( /[ ]{2,}/gi, " " )
	      .replace( /\n /, "\n" )
	      .split(' ').length;
	};

	window.Parsley.addValidator(
		'minwords',
		function (value, nbWords) {
			return countWords(value) >= nbWords;
		}, 32)
		.addMessage( 'en', 'minwords', msgMinWords );

	window.Parsley.addValidator(
		'maxwords',
		function (value, nbWords) {
			return countWords(value) <= nbWords;
		}, 32)
		.addMessage( 'en', 'maxwords', msgMaxWords );

	window.Parsley.addValidator(
		'words',
		function (value, arrayRange) {
			var length = countWords(value);
			return length >= arrayRange[0] && length <= arrayRange[1];
		}, 32)
		.addMessage( 'en', 'words', msgWordsRange );

	// gt, gte, lt, lte extra validators
	var parseRequirement = function (requirement) {
	  if ( isNaN( +requirement ) ) {
	    return parseFloat( $( requirement ).val() );
	  }
	  else {
	    return +requirement;
	  }
	};

	// Greater than validator
	window.Parsley.addValidator( 'gt', {
	  validateString: function ( value, requirement ) {
	    return parseFloat(value) > parseRequirement(requirement);
	  },
	  priority: 32
	});

	// Greater than or equal to validator
	window.Parsley.addValidator( 'gte', {
	  validateString: function ( value, requirement ) {
	    return parseFloat(value) >= parseRequirement(requirement);
	  },
	  priority: 32
	});

	// Less than validator
	window.Parsley.addValidator( 'lt', {
	  validateString: function ( value, requirement ) {
	    return parseFloat(value) < parseRequirement(requirement);
	  },
	  priority: 32
	});

	// Less than or equal to validator
	window.Parsley.addValidator( 'lte', {
	  validateString: function ( value, requirement ) {
	    return parseFloat(value) <= parseRequirement(requirement);
	  },
	  priority: 32
	});

	/*
	 * Add extras default validation messages
	 *
	 * These values are not available for translation unless
	 * using the Custom Validation messages under the Settings menu
	 */
	window.Parsley.addMessages( 'en', {
	  dateiso:    'This value should be a valid date (YYYY-MM-DD).',
	  minwords:   'This value is too short. It should have %s words or more.',
	  maxwords:   'This value is too long. It should have %s words or fewer.',
	  words:      'This value length is invalid. It should be between %s and %s words long.',
	  gt:         'This value should be greater.',
	  gte:        'This value should be greater or equal.',
	  lt:         'This value should be less.',
	  lte:        'This value should be less or equal.',
	  notequalto: 'This value should be different.'
	});
});
