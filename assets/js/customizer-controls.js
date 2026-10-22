/**
 * Customizer controls: colour presets and the sortable section list.
 *
 * @global crrgPresets Preset slug => { setting: colour }.
 */
( function ( api, $ ) {
	'use strict';

	var applyingPreset = false;

	api.bind( 'ready', function () {
		var presets = window.crrgPresets || {};
		var firstPreset = Object.keys( presets )[ 0 ];
		var colorSettings = firstPreset ? Object.keys( presets[ firstPreset ] ) : [];

		// Choosing a preset fills every colour.
		api( 'color_preset', function ( preset ) {
			preset.bind( function ( slug ) {
				if ( ! presets[ slug ] ) {
					return;
				}

				applyingPreset = true;
				Object.keys( presets[ slug ] ).forEach( function ( id ) {
					if ( api.has( id ) ) {
						api( id ).set( presets[ slug ][ id ] );
					}
				} );
				applyingPreset = false;
			} );
		} );

		// Editing a single colour switches the preset to "Custom".
		colorSettings.forEach( function ( id ) {
			api( id, function ( setting ) {
				setting.bind( function () {
					if ( ! applyingPreset && api.has( 'color_preset' ) && 'custom' !== api( 'color_preset' ).get() ) {
						api( 'color_preset' ).set( 'custom' );
					}
				} );
			} );
		} );

		// Sliders: keep the number box in step with the slider (which holds
		// the setting link) and offer a reset to the default value.
		$( document ).on( 'input change', '.crrg-range__slider', function () {
			$( this ).siblings( '.crrg-range__number' ).val( this.value );
		} );

		$( document ).on( 'input change', '.crrg-range__number', function ( e ) {
			var $number = $( this );
			var $slider = $number.siblings( '.crrg-range__slider' );
			var min = parseFloat( $slider.attr( 'min' ) );
			var max = parseFloat( $slider.attr( 'max' ) );
			var value = parseFloat( $number.val() );

			if ( isNaN( value ) ) {
				return;
			}
			// Only clamp once the user has finished typing.
			if ( 'change' === e.type ) {
				value = Math.min( max, Math.max( min, value ) );
				$number.val( value );
			}
			if ( value >= min && value <= max ) {
				$slider.val( value ).trigger( 'change' );
			}
		} );

		$( document ).on( 'click', '.crrg-range__reset', function () {
			var $slider = $( this ).siblings( '.crrg-range__slider' );
			$slider.val( $( this ).data( 'default' ) ).trigger( 'change' );
		} );

		// Sortable lists.
		$( '.crrg-sortable' ).each( function () {
			var $list = $( this );
			var $input = $list.siblings( '.crrg-sortable__value' );

			function save() {
				var order = $list.children().map( function () {
					return $( this ).data( 'value' );
				} ).get();

				$input.val( order.join( ',' ) ).trigger( 'change' );
			}

			$list.sortable( {
				axis: 'y',
				handle: '.dashicons-menu',
				update: save
			} );

			$list.on( 'click', '.crrg-sortable__up, .crrg-sortable__down', function () {
				var $button = $( this );
				var $item = $button.closest( '.crrg-sortable__item' );

				if ( $button.hasClass( 'crrg-sortable__up' ) ) {
					$item.prev().before( $item );
				} else {
					$item.next().after( $item );
				}

				save();
				$button.trigger( 'focus' );
			} );
		} );
	} );
}( wp.customize, jQuery ) );
