/**
 * Sticky header state, search overlay and back-to-top button.
 */
( function () {
	'use strict';

	var body = document.body;
	var root = document.documentElement;
	var header = document.getElementById( 'masthead' );
	var backToTop = document.querySelector( '.back-to-top' );

	/* Header height & sticky state ------------------------------------ */

	var isSticky = body.classList.contains( 'has-sticky-header' );
	var isTransparent = body.classList.contains( 'has-transparent-header' );
	var stickAt = 0;

	function measure() {
		if ( ! header ) {
			return;
		}

		var wasStuck = header.classList.contains( 'is-stuck' );
		header.classList.remove( 'is-stuck' );

		root.style.setProperty( '--crrg-header-height', header.offsetHeight + 'px' );

		var topbar = document.querySelector( '.topbar' );
		if ( topbar ) {
			root.style.setProperty( '--crrg-topbar-height', topbar.offsetHeight + 'px' );
		}

		var top = header.getBoundingClientRect().top + window.scrollY;
		var adminBar = document.getElementById( 'wpadminbar' );
		var offset = adminBar && 'fixed' === window.getComputedStyle( adminBar ).position ? adminBar.offsetHeight : 0;

		// A transparent header only becomes solid once the hero is scrolled past its height.
		stickAt = isTransparent ? top + header.offsetHeight : Math.max( 0, top - offset );

		if ( wasStuck ) {
			header.classList.add( 'is-stuck' );
		}
	}

	function onScroll() {
		var y = window.scrollY;

		if ( header && isSticky ) {
			header.classList.toggle( 'is-stuck', y > stickAt );
		}

		if ( backToTop ) {
			backToTop.classList.toggle( 'is-visible', y > 600 );
		}
	}

	measure();
	onScroll();

	var ticking = false;
	window.addEventListener(
		'scroll',
		function () {
			if ( ticking ) {
				return;
			}
			ticking = true;
			window.requestAnimationFrame( function () {
				onScroll();
				ticking = false;
			} );
		},
		{ passive: true }
	);

	var resizeTimer = null;
	window.addEventListener( 'resize', function () {
		window.clearTimeout( resizeTimer );
		resizeTimer = window.setTimeout( function () {
			measure();
			onScroll();
		}, 150 );
	} );

	window.addEventListener( 'load', function () {
		measure();
		onScroll();
	} );

	/* Back to top ----------------------------------------------------- */

	if ( backToTop ) {
		backToTop.addEventListener( 'click', function ( event ) {
			event.preventDefault();
			window.scrollTo( { top: 0, behavior: window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ? 'auto' : 'smooth' } );

			var skip = document.querySelector( '.skip-link' );
			if ( skip ) {
				skip.focus( { preventScroll: true } );
			}
		} );
	}

	/* Search overlay -------------------------------------------------- */

	var overlay = document.getElementById( 'search-overlay' );
	var searchToggles = document.querySelectorAll( '.search-toggle' );
	var lastSearchToggle = null;

	if ( ! overlay || ! searchToggles.length ) {
		return;
	}

	function openSearch( toggle ) {
		lastSearchToggle = toggle;
		overlay.hidden = false;
		searchToggles.forEach( function ( t ) {
			t.setAttribute( 'aria-expanded', 'true' );
		} );

		var field = overlay.querySelector( '.search-field' );
		if ( field ) {
			field.focus();
		}
	}

	function closeSearch() {
		if ( overlay.hidden ) {
			return;
		}

		overlay.hidden = true;
		searchToggles.forEach( function ( t ) {
			t.setAttribute( 'aria-expanded', 'false' );
		} );

		if ( lastSearchToggle ) {
			lastSearchToggle.focus();
		}
	}

	searchToggles.forEach( function ( toggle ) {
		toggle.addEventListener( 'click', function () {
			if ( overlay.hidden ) {
				openSearch( toggle );
			} else {
				closeSearch();
			}
		} );
	} );

	overlay.addEventListener( 'click', function ( event ) {
		if ( event.target === overlay || event.target.closest( '.search-overlay__close' ) ) {
			closeSearch();
		}
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Escape' === event.key ) {
			closeSearch();
		}
	} );

	// Keep keyboard focus inside the overlay while it is open.
	overlay.addEventListener( 'keydown', function ( event ) {
		if ( 'Tab' !== event.key ) {
			return;
		}

		var items = overlay.querySelectorAll( 'input, button' );
		var first = items[ 0 ];
		var last = items[ items.length - 1 ];

		if ( event.shiftKey && document.activeElement === first ) {
			event.preventDefault();
			last.focus();
		} else if ( ! event.shiftKey && document.activeElement === last ) {
			event.preventDefault();
			first.focus();
		}
	} );
}() );
