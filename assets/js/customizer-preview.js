/**
 * Live Customizer preview for options using the postMessage transport.
 */
( function ( api ) {
	'use strict';

	var root = document.documentElement;

	function contrast( hex ) {
		hex = String( hex || '' ).replace( '#', '' );
		if ( 3 === hex.length ) {
			hex = hex.replace( /(.)/g, '$1$1' );
		}
		if ( 6 !== hex.length ) {
			return '#ffffff';
		}

		var rgb = [ 0, 2, 4 ].map( function ( i ) {
			var c = parseInt( hex.substr( i, 2 ), 16 ) / 255;
			return c <= 0.03928 ? c / 12.92 : Math.pow( ( c + 0.055 ) / 1.055, 2.4 );
		} );
		var l = 0.2126 * rgb[ 0 ] + 0.7152 * rgb[ 1 ] + 0.0722 * rgb[ 2 ];

		return 1.05 / ( l + 0.05 ) >= ( l + 0.05 ) / 0.0556 ? '#ffffff' : '#111111';
	}

	var colors = {
		primary_color: [ '--crrg-primary', '--crrg-on-primary' ],
		secondary_color: [ '--crrg-secondary', '--crrg-on-secondary' ],
		accent_color: [ '--crrg-accent', '--crrg-on-accent' ],
		body_bg_color: [ '--crrg-body-bg' ],
		surface_color: [ '--crrg-surface' ],
		text_color: [ '--crrg-text' ],
		heading_color: [ '--crrg-heading' ],
		link_color: [ '--crrg-link' ],
		link_hover_color: [ '--crrg-link-hover' ],
		topbar_bg_color: [ '--crrg-topbar-bg' ],
		topbar_text_color: [ '--crrg-topbar-text' ],
		header_bg_color: [ '--crrg-header-bg' ],
		header_text_color: [ '--crrg-header-text' ],
		hero_bg_color: [ '--crrg-hero-bg', '--crrg-on-hero-bg' ],
		banner_bg_color: [ '--crrg-banner-bg', '--crrg-on-banner' ],
		footer_bg_color: [ '--crrg-footer-bg' ],
		footer_text_color: [ '--crrg-footer-text' ],
		footer_heading_color: [ '--crrg-footer-heading' ],
		footer_bottom_bg_color: [ '--crrg-footer-bottom-bg' ],
		hero_overlay_color: [ '--crrg-hero-overlay', '--crrg-on-overlay' ]
	};

	// Accent text on a background: lighter orange on dark, link colour on light
	// (mirrors crrg_css_variables() in inc/customizer/css.php).
	var accents = {
		header_bg_color: [ '--crrg-header-accent', 'var(--crrg-link)' ],
		banner_bg_color: [ '--crrg-banner-accent', 'var(--crrg-link)' ],
		footer_bg_color: [ '--crrg-footer-accent', 'var(--crrg-link)' ],
		hero_bg_color: [ '--crrg-hero-accent', 'var(--crrg-secondary)' ]
	};
	var onDark = 'color-mix(in srgb, var(--crrg-accent) 78%, #fff)';

	function heroForeground() {
		var hasImage = !! document.querySelector( '.crrg-hero--has-image' );
		var source = hasImage ? 'hero_overlay_color' : 'hero_bg_color';
		var dark = '#ffffff' === contrast( api( source ).get() );

		root.style.setProperty( '--crrg-hero-fg', contrast( api( source ).get() ) );
		document.body.classList.toggle( 'hero-is-dark', dark );
		document.body.classList.toggle( 'hero-is-light', ! dark );
	}

	Object.keys( colors ).forEach( function ( setting ) {
		api( setting, function ( value ) {
			value.bind( function ( to ) {
				var dark = '#ffffff' === contrast( to );

				root.style.setProperty( colors[ setting ][ 0 ], to );
				if ( colors[ setting ][ 1 ] ) {
					root.style.setProperty( colors[ setting ][ 1 ], contrast( to ) );
				}
				if ( accents[ setting ] ) {
					root.style.setProperty( accents[ setting ][ 0 ], dark ? onDark : accents[ setting ][ 1 ] );
				}
				if ( 'header_bg_color' === setting ) {
					document.body.classList.toggle( 'header-is-dark', dark );
					document.body.classList.toggle( 'header-is-light', ! dark );
				}
				if ( 'hero_bg_color' === setting || 'hero_overlay_color' === setting ) {
					heroForeground();
				}
			} );
		} );
	} );

	var sizes = {
		base_font_size: [ '--crrg-font-size', 'px' ],
		container_width: [ '--crrg-container', 'px' ],
		readable_width: [ '--crrg-readable', 'px' ],
		sidebar_width: [ '--crrg-sidebar', 'px' ],
		border_radius: [ '--crrg-radius', 'px' ],
		logo_max_height: [ '--crrg-logo-height', 'px' ],
		heading_weight: [ '--crrg-heading-weight', '' ]
	};

	Object.keys( sizes ).forEach( function ( setting ) {
		api( setting, function ( value ) {
			value.bind( function ( to ) {
				root.style.setProperty( sizes[ setting ][ 0 ], to + sizes[ setting ][ 1 ] );
			} );
		} );
	} );

	api( 'site_gutter', function ( value ) {
		value.bind( function ( to ) {
			var px = Math.max( 0, parseInt( to, 10 ) || 0 );
			root.style.setProperty( '--crrg-gutter-wide', px + 'px' );
			root.style.setProperty( '--crrg-gutter-mobile', Math.min( 20, px ) + 'px' );
		} );
	} );

	api( 'hero_overlay_opacity', function ( value ) {
		value.bind( function ( to ) {
			root.style.setProperty( '--crrg-hero-overlay-opacity', Math.max( 0, Math.min( 100, parseInt( to, 10 ) || 0 ) ) / 100 );
		} );
	} );

	function text( setting, selector, allowHtml ) {
		api( setting, function ( value ) {
			value.bind( function ( to ) {
				document.querySelectorAll( selector ).forEach( function ( el ) {
					if ( allowHtml ) {
						el.innerHTML = to;
					} else {
						el.textContent = to;
					}
				} );
			} );
		} );
	}

	text( 'topbar_text', '.topbar__text', true );
	text( 'hero_title', '.crrg-hero__title', false );
	text( 'hero_text', '.crrg-hero__text', true );
	text( 'cta_title', '.crrg-cta__title', false );
	text( 'cta_text', '.crrg-cta__text', true );
	text( 'header_cta_text', '.header-cta', false );

	api( 'header_callsign', function ( value ) {
		value.bind( function ( to ) {
			document.querySelectorAll( '.site-callsign' ).forEach( function ( el ) {
				el.textContent = to;
				el.hidden = ! to;
			} );
		} );
	} );
}( wp.customize ) );
