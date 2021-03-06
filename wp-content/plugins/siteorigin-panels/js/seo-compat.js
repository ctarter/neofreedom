/* global jQuery, YoastSEO, _, panelsOptions */

jQuery(function($){


	var SiteOriginSeoCompat = function() {

		if ( typeof YoastSEO !== 'undefined' ) {
			YoastSEO.app.registerPlugin( 'SiteOriginSeoCompat', { status: 'ready' } );
			YoastSEO.app.registerModification( 'content', this.contentModification, 'SiteOriginSeoCompat', 5 );
		}

		if ( typeof rankMathEditor !== 'undefined' ) {
			wp.hooks.addFilter( 'rank_math_content', 'SiteOriginSeoCompat', this.contentModification );
		}

	};

	SiteOriginSeoCompat.prototype.contentModification = function( data ) {
		if ( typeof window.soPanelsBuilderView !== 'undefined' ) {

			var whitelist = [
				'p', 'a', 'img', 'caption', 'br',
				'blockquote', 'cite',
				'em', 'strong', 'i', 'b',
				'q',
				'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
				'ul', 'ol', 'li',
				'table', 'tr', 'th', 'td'
			].join( ',' );

			var extractContent = function( data ) {
				var $data = $( data );

				if( $data.find( '.so-panel.widget' ).length === 0 ) {
					// Skip this for empty pages
					return data;
				}

				// Remove style tags created by Widgets Bundle
				$data.find( 'style' ).remove();

				$data.find( "*") .not( whitelist ).each( function() {
					var content = $( this ).contents();
					$( this ).replaceWith( content );
				} );

				return $data.html();
			};

			if ( ! Array.isArray( window.soPanelsBuilderView ) ) {
				data = extractContent( window.soPanelsBuilderView.contentPreview );
			} else {
				var $this = this;
				data = null;
				window.soPanelsBuilderView.forEach( function( panel ) {
					data += extractContent( panel.contentPreview );
				} );
			}
		}

		return data;
	};

	if ( typeof rankMathEditor !== 'undefined' ) {
		new SiteOriginSeoCompat();
	} else {
		$( window ).on(
			'YoastSEO:ready',
			function () {
				new SiteOriginSeoCompat();
			}
		);
	}
});
