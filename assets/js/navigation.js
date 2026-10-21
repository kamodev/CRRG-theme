/**
 * Primary menu dropdowns and the mobile navigation panel.
 *
 * - Sub-menu toggle buttons open/close dropdowns for keyboard and touch users.
 * - Escape closes the open dropdown or the mobile panel.
 * - The mobile panel traps focus while open and restores it on close.
 */
( function () {
	'use strict';

	var breakpoint = parseInt( window.getComputedStyle( document.documentElement ).getPropertyValue( '--crrg-nav-breakpoint' ), 10 ) || 1280;
	var desktopQuery = window.matchMedia( '(min-width: ' + breakpoint + 'px)' );

	/* Sub-menus ------------------------------------------------------- */

	function setOpen( item, open ) {
		var toggle = item.querySelector( ':scope > .submenu-toggle' );

		item.classList.toggle( 'is-open', open );
		item.classList.remove( 'is-dismissed' );

		if ( toggle ) {
			toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		}
	}

	function closeSiblings( item ) {
		var parent = item.parentElement;

		if ( ! parent ) {
			return;
		}

		Array.prototype.forEach.call( parent.children, function ( sibling ) {
			if ( sibling !== item && sibling.classList.contains( 'is-open' ) ) {
				setOpen( sibling, false );
			}
		} );
	}

	function closeAll( menu ) {
		menu.querySelectorAll( '.is-open' ).forEach( function ( item ) {
			setOpen( item, false );
		} );
	}

	document.addEventListener( 'click', function ( event ) {
		var toggle = event.target.closest( '.submenu-toggle' );

		if ( toggle ) {
			var item = toggle.parentElement;
			var open = ! item.classList.contains( 'is-open' );

			if ( open ) {
				closeSiblings( item );
			}
			setOpen( item, open );
			return;
		}

		// Clicking outside the desktop menu closes its dropdowns.
		document.querySelectorAll( '.primary-menu' ).forEach( function ( menu ) {
			if ( ! menu.contains( event.target ) ) {
				closeAll( menu );
			}
		} );
	} );

	document.querySelectorAll( '.main-navigation .primary-menu' ).forEach( function ( menu ) {
		menu.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' !== event.key ) {
				return;
			}

			var item = event.target.closest( '.menu-item-has-children' );

			// Move focus out of the sub-menu back to its parent item.
			if ( item && item.querySelector( '.sub-menu' ).contains( event.target ) ) {
				var link = item.querySelector( ':scope > a' );
				if ( link ) {
					link.focus();
				}
			}

			if ( item ) {
				setOpen( item, false );
				item.classList.add( 'is-dismissed' );
			}
		} );

		menu.addEventListener( 'focusout', function ( event ) {
			var item = event.target.closest( '.menu-item-has-children' );

			if ( item && ! item.contains( event.relatedTarget ) ) {
				setOpen( item, false );
			}
		} );

		menu.addEventListener( 'mouseleave', function () {
			menu.querySelectorAll( '.is-dismissed' ).forEach( function ( item ) {
				item.classList.remove( 'is-dismissed' );
			} );
		} );
	} );

	/* Mobile panel ---------------------------------------------------- */

	var panel = document.getElementById( 'mobile-navigation' );
	var toggles = document.querySelectorAll( '.menu-toggle' );

	if ( ! panel || ! toggles.length ) {
		return;
	}

	var backdrop = document.querySelector( '.mobile-navigation-backdrop' );
	var closeButton = panel.querySelector( '.mobile-navigation__close' );
	var header = document.getElementById( 'masthead' );
	var isDropdown = document.body.classList.contains( 'mobile-menu-dropdown' );
	var lastToggle = null;
	var hideTimer = null;

	function focusable() {
		return Array.prototype.filter.call(
			panel.querySelectorAll( 'a[href], button:not([disabled]), input, select, textarea' ),
			function ( el ) {
				return el.offsetParent !== null;
			}
		);
	}

	function openPanel( toggle ) {
		lastToggle = toggle;
		window.clearTimeout( hideTimer );

		if ( isDropdown && header ) {
			var top = Math.max( 0, header.getBoundingClientRect().bottom );
			panel.style.setProperty( '--crrg-mobile-nav-top', top + 'px' );
			if ( backdrop ) {
				backdrop.style.setProperty( '--crrg-mobile-nav-top', top + 'px' );
			}
		}

		panel.hidden = false;
		if ( backdrop ) {
			backdrop.hidden = false;
		}

		// Force a reflow so the transition runs.
		void panel.offsetWidth;

		panel.classList.add( 'is-open' );
		if ( backdrop ) {
			backdrop.classList.add( 'is-visible' );
		}
		document.body.classList.add( 'mobile-nav-open' );

		toggles.forEach( function ( t ) {
			t.setAttribute( 'aria-expanded', 'true' );
		} );

		var items = focusable();
		if ( items.length ) {
			( isDropdown ? items[ 0 ] : closeButton || items[ 0 ] ).focus();
		}
	}

	function closePanel( restoreFocus ) {
		if ( ! panel.classList.contains( 'is-open' ) ) {
			return;
		}

		panel.classList.remove( 'is-open' );
		if ( backdrop ) {
			backdrop.classList.remove( 'is-visible' );
		}
		document.body.classList.remove( 'mobile-nav-open' );

		toggles.forEach( function ( t ) {
			t.setAttribute( 'aria-expanded', 'false' );
		} );

		hideTimer = window.setTimeout( function () {
			panel.hidden = true;
			if ( backdrop ) {
				backdrop.hidden = true;
			}
		}, 300 );

		if ( restoreFocus !== false && lastToggle ) {
			lastToggle.focus();
		}
	}

	toggles.forEach( function ( toggle ) {
		toggle.addEventListener( 'click', function () {
			if ( panel.classList.contains( 'is-open' ) ) {
				closePanel();
			} else {
				openPanel( toggle );
			}
		} );
	} );

	if ( closeButton ) {
		closeButton.addEventListener( 'click', function () {
			closePanel();
		} );
	}

	if ( backdrop ) {
		backdrop.addEventListener( 'click', function () {
			closePanel();
		} );
	}

	panel.addEventListener( 'click', function ( event ) {
		// Following an in-page link closes the panel.
		var link = event.target.closest( 'a[href^="#"]' );
		if ( link ) {
			closePanel( false );
		}
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( ! panel.classList.contains( 'is-open' ) ) {
			return;
		}

		if ( 'Escape' === event.key ) {
			closePanel();
			return;
		}

		if ( 'Tab' !== event.key ) {
			return;
		}

		var items = focusable();
		if ( ! items.length ) {
			return;
		}

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

	var onBreakpoint = function ( event ) {
		if ( event.matches ) {
			closePanel( false );
		}
	};

	if ( desktopQuery.addEventListener ) {
		desktopQuery.addEventListener( 'change', onBreakpoint );
	} else if ( desktopQuery.addListener ) {
		desktopQuery.addListener( onBreakpoint );
	}
}() );
