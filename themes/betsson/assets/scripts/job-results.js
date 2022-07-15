var JobResults = {};

( function ( $ ) {
	JobResults.init = function () {
		var hash = window.location.hash.substr( 1 ),
			jobsContainer = $( '#jobs-container' ),
			subscribeButton = $( '.js-subscribe-button' ),
			jobsCount = $( '.js-jobs-count' ),
			jobsForm = $( '.js-jobs-search-form' ),
			jobsShowMore = $( '#jobs-show-more' ),
			excludedIds = null,
			loader = $( '.loader' ),
			subscribeModal = $( '#subscribe-modal' );

		var loadJobs = function ( keyword, departments, locations, page ) {
			if ( jobsContainer.length === 0 ) {
				return;
			}

			if ( page === 1 ) {
				excludedIds = null;
			}

			var hasFilter = keyword || ( departments && departments.length ) || ( locations && locations.length );

			// Modify query parameters if there are filters
			if ( hasFilter ) {
				var data = {
					keyword: keyword,
					departments: departments,
					locations: locations
				};
			}

			$.ajax( {
				url: '/wp-admin/admin-ajax.php',
				data: {
					action: 'get_jobs',
					keyword: keyword,
					departments: departments,
					locations: locations,
					page: page,
					excluded_ids: excludedIds
				},
				method: 'get',
				dataType: 'json',
				beforeSend: function () {
					jobsShowMore.hide();
					loader.show();

					if ( page === 1 ) {
						jobsContainer.parents( '.table-container' ).hide();
					}
				},
				success: function ( result ) {
					if ( result.success ) {
						if ( page === 1 ) {
							jobsContainer.html( result.data.html );
							jobsContainer.parents( 'section' ).show();
							excludedIds = result.data.excluded_ids;

							if ( hasFilter ) {
								jobsCount.html( result.data.found_posts );
								jobsCount.parent().show();
							} else {
								jobsCount.parent().hide();
							}
						} else {
							jobsContainer.append( result.data.html );
						}

						if ( result.data.found_posts > 0 ) {
							subscribeButton.hide();
							jobsContainer.parents( '.table-container' ).show();
						} else {
							subscribeButton.show();
							jobsContainer.parents( '.table-container' ).hide();
						}

						if ( result.data.has_more ) {
							jobsShowMore.show();

							jobsShowMore.off( 'click' );
							jobsShowMore.click( function () {
								loadJobs( keyword, departments, locations, 2 );
							} );
						} else {
							jobsShowMore.hide();
						}
					}

					if ( hasFilter && page === 1 ) {
						$( function () {
							var jobsContainer = $( '.job-subscribe:visible' );
							Scroll.To( jobsContainer, jobsContainer.hasClass( 'visible-xs' ) ? 40 : 100 );
						} );
					}

					loader.hide();
				},
				error: function ( result ) {
					console.log( result );
				}
			} );
		};

		// Show data to form fields
		var showValues = function ( jobData ) {
			if ( jobData.hasOwnProperty( 'keyword' ) ) {
				jobsForm.find( '[name=keyword]' ).val( jobData.keyword );
				subscribeModal.find( '.js-modal-search' ).val( jobData.keyword );
			} else {
				jobsForm.find( '[name=keyword]' ).val( '' );
				subscribeModal.find( '.js-modal-search' ).val( '' );
			}

			if ( jobData.hasOwnProperty( 'departments' ) ) {
				var departmentNames = [];

				jobsForm.find( '[name="departments[]"] option' ).prop( 'selected', false );
				jobData.departments.forEach( function ( departmentId ) {
					var items = jobsForm.find( '[name="departments[]"] option[value=' + departmentId + ']' );
					items.prop( 'selected', true );

					departmentNames.push( $( items[0] ).text() );
				} );
				jobsForm.find( '[name="departments[]"]' ).trigger( 'change' );

				subscribeModal.find( '.js-modal-departments' ).val( departmentNames.join( ',' ) );
			} else {
				jobsForm.find( '[name="departments[]"] option' ).prop( 'selected', false );
				jobsForm.find( '[name="departments[]"]' ).trigger( 'change' );
				subscribeModal.find( '.js-modal-departments' ).val( '' );
			}

			if ( jobData.hasOwnProperty( 'locations' ) ) {
				var locationNames = [];

				jobsForm.find( '[name="locations[]"] option' ).prop( 'selected', false );
				jobData.locations.forEach( function ( locationId ) {
					var items = jobsForm.find( '[name="locations[]"] option[value=' + locationId + ']' );
					items.prop( 'selected', true );

					locationNames.push( $( items[0] ).text() );
				} );
				jobsForm.find( '[name="locations[]"]' ).trigger( 'change' );

				subscribeModal.find( '.js-modal-locations' ).val( locationNames.join( ',' ) );
			} else {
				jobsForm.find( '[name="locations[]"] option' ).prop( 'selected', false );
				jobsForm.find( '[name="locations[]"]' ).trigger( 'change' );
				subscribeModal.find( '.js-modal-locations' ).val( '' );
			}
		};

		var adjustSubscribeModal = function () {
			var instructions = $( '.vfb-fieldType-instructions' ),
				modalBody = $( '.modal-body' );

			if ( instructions.length === 0 ) {
				return;
			}

			if ( helperViewport.documentWidth().width < 767 ) {
				modalBody.removeAttr( 'style' );
			} else {
				modalBody.css( 'padding-bottom', instructions.height() + 40 + 'px' );
			}
		};

		// Check if search action comes from another page
		if ( hash ) {
			// Decrypt the hash
			hash = CryptoJS.AES.decrypt( hash, 'betsson' ).toString( CryptoJS.enc.Utf8 );

			// Get job data
			jobData = JSON.parse( hash );

			showValues( jobData );

			var keyword = jobData.hasOwnProperty( 'keyword' ) ? jobData.keyword : null,
				departments = jobData.hasOwnProperty( 'departments' ) ? jobData.departments : null,
				locations = jobData.hasOwnProperty( 'locations' ) ? jobData.locations : null;

			// Load initial jobs
			loadJobs( keyword, departments, locations, 1 );
		} else {
			// Load initial jobs
			loadJobs( null, null, null, 1 );
		}

		// Add markup to subscribe modal
		$( '.js-modal-checkmark' ).after( '<span class="checkmark"></span>' );

		// Subscribe modal adjustments
		window.addEventListener( 'resize', adjustSubscribeModal );
		adjustSubscribeModal();

		// Initialize subscribe button
		subscribeButton.click( function () {
			subscribeModal.modal( 'show' );

			setTimeout( function () {
				adjustSubscribeModal();
			}, 200 );
		} );

		// Submit subscribe form thru AJAX
		subscribeModal.find( 'form' ).submit( function ( e ) {
			var form = $( this );
			e.preventDefault();

			// Delay the checking to make this execute last
			setTimeout( function () {
				if ( form.find( '.vfb-has-error' ).length === 0 ) {
					$.post( '', form.serialize(), function () {
						form.hide();
						subscribeModal.find( '.js-modal-subscribe-success' ).show();
						subscribeModal.find( '.modal-dialog' ).addClass( 'is-success' );
						subscribeModal.find( '.modal-body' ).removeAttr( 'style' );
					} );
				}
			}, 200 );
		} );

		// On form submit, if there is a jobs container, load result via ajax	
		jobsForm.submit( function ( e ) {
			var keyword = null, departments = [], locations = [];
			var form = $( this );

			form.serializeArray().forEach( function ( item ) {
				if ( item.name === 'keyword' ) {
					keyword = item.value;
				} else if ( item.name === 'departments[]' ) {
					departments.push( item.value );
				} else if ( item.name === 'locations[]' ) {
					locations.push( item.value );
				}
			} );

			var hasFilter = keyword || departments.length || locations.length;

			if ( !hasFilter ) {
				return false;
			}

			var data = {
				keyword: keyword,
				departments: departments,
				locations: locations
			};

			showValues( data );

			// If jobs container is existing, load results via AJAX, else add search info to URL and redirect
			if ( jobsContainer.length > 0 ) {
				// Load initial jobs
				loadJobs( keyword, departments, locations, 1 );

				// Update URL hash
				hash = CryptoJS.AES.encrypt( JSON.stringify( data ), 'betsson' ).toString();
				window.location.hash = '#' + hash;
			} else {
				hash = CryptoJS.AES.encrypt( JSON.stringify( data ), 'betsson' ).toString();
				window.location.href = form.prop( 'action' ) + '#' + hash;
			}

			return false;
		} );

		// Jobs department filter
		$( '.js-job-department' ).click( function ( e ) {
			e.preventDefault();

			var element = $( this );
			var departmentId = element.data( 'id' );

			if ( !departmentId ) {
				return;
			}

			var data = { departments: [departmentId] };

			loadJobs( null, [departmentId], null, 1 );

			// Update URL hash
			var hash = CryptoJS.AES.encrypt( JSON.stringify( data ), 'betsson' ).toString();
			window.location.hash = '#' + hash;

			showValues( data );
		} );
	};

	$( JobResults.init );
} )( jQuery );