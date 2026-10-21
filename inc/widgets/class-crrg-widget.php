<?php
/**
 * Base class for the theme widgets: declares fields once and gets the
 * admin form and sanitising for free.
 *
 * @package CRRG
 */

defined( 'ABSPATH' ) || exit;

/**
 * Field-driven widget base.
 */
abstract class CRRG_Widget extends WP_Widget {

	/**
	 * Field definitions: key => array( type, label, default, description?, choices?, rows?, html? ).
	 *
	 * Types: text, textarea, url, email, number, checkbox, select. Textareas
	 * are plain text unless 'html' is true, which allows basic inline HTML.
	 *
	 * @return array
	 */
	abstract protected function fields();

	/**
	 * Instance merged with field defaults.
	 *
	 * @param array $instance Saved instance.
	 * @return array
	 */
	protected function with_defaults( $instance ) {
		$defaults = array();
		foreach ( $this->fields() as $key => $field ) {
			$defaults[ $key ] = isset( $field['default'] ) ? $field['default'] : '';
		}

		return wp_parse_args( (array) $instance, $defaults );
	}

	/**
	 * Print the widget title.
	 *
	 * @param array $args     Sidebar args.
	 * @param array $instance Widget instance.
	 */
	protected function title( $args, $instance ) {
		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		if ( $title ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Split a "a | b | c" textarea into rows of cells.
	 *
	 * @param string $text    Raw text.
	 * @param int    $columns Number of cells per row.
	 * @return array
	 */
	protected function rows( $text, $columns ) {
		$rows = array();

		foreach ( preg_split( '/\r\n|\r|\n/', (string) $text ) as $line ) {
			$line = trim( $line );
			if ( '' === $line ) {
				continue;
			}
			$cells  = array_map( 'trim', explode( '|', $line ) );
			$rows[] = array_pad( array_slice( $cells, 0, $columns ), $columns, '' );
		}

		return $rows;
	}

	/**
	 * Admin form.
	 *
	 * @param array $instance Saved instance.
	 * @return string
	 */
	public function form( $instance ) {
		$instance = $this->with_defaults( $instance );

		foreach ( $this->fields() as $key => $field ) {
			$id    = $this->get_field_id( $key );
			$name  = $this->get_field_name( $key );
			$value = $instance[ $key ];
			$type  = $field['type'];

			echo '<p>';
			if ( 'checkbox' === $type ) {
				printf(
					'<input type="checkbox" class="checkbox" id="%1$s" name="%2$s" value="1" %3$s /> <label for="%1$s">%4$s</label>',
					esc_attr( $id ),
					esc_attr( $name ),
					checked( (bool) $value, true, false ),
					esc_html( $field['label'] )
				);
			} else {
				printf( '<label for="%1$s">%2$s</label>', esc_attr( $id ), esc_html( $field['label'] ) );

				if ( 'textarea' === $type ) {
					printf(
						'<textarea class="widefat" rows="%3$d" id="%1$s" name="%2$s">%4$s</textarea>',
						esc_attr( $id ),
						esc_attr( $name ),
						isset( $field['rows'] ) ? absint( $field['rows'] ) : 4,
						esc_textarea( $value )
					);
				} elseif ( 'select' === $type ) {
					printf( '<select class="widefat" id="%1$s" name="%2$s">', esc_attr( $id ), esc_attr( $name ) );
					foreach ( $field['choices'] as $choice => $label ) {
						printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $choice ), selected( (string) $value, (string) $choice, false ), esc_html( $label ) );
					}
					echo '</select>';
				} else {
					printf(
						'<input class="%5$s" type="%3$s" id="%1$s" name="%2$s" value="%4$s" />',
						esc_attr( $id ),
						esc_attr( $name ),
						esc_attr( $type ),
						esc_attr( $value ),
						'number' === $type ? 'tiny-text' : 'widefat'
					);
				}
			}
			if ( ! empty( $field['description'] ) ) {
				echo '<br /><small class="description">' . esc_html( $field['description'] ) . '</small>';
			}
			echo '</p>';
		}

		return '';
	}

	/**
	 * Sanitise on save.
	 *
	 * @param array $new_instance New values.
	 * @param array $old_instance Previous values.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		foreach ( $this->fields() as $key => $field ) {
			$value = isset( $new_instance[ $key ] ) ? $new_instance[ $key ] : '';

			switch ( $field['type'] ) {
				case 'checkbox':
					$instance[ $key ] = ! empty( $value );
					break;
				case 'number':
					$instance[ $key ] = absint( $value );
					break;
				case 'url':
					$instance[ $key ] = crrg_sanitize_url( $value );
					break;
				case 'email':
					$instance[ $key ] = sanitize_email( $value );
					break;
				case 'select':
					$instance[ $key ] = array_key_exists( $value, $field['choices'] ) ? (string) $value : (string) $field['default'];
					break;
				case 'textarea':
					$instance[ $key ] = empty( $field['html'] )
						? sanitize_textarea_field( $value )
						: wp_kses( $value, crrg_allowed_inline_html() );
					break;
				default:
					$instance[ $key ] = sanitize_text_field( $value );
			}
		}

		return $instance;
	}
}
