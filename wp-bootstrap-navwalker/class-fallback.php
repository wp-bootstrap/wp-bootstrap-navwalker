<?php
/**
 * Fallback class
 *
 * @package WP-Bootstrap-Navwalker
 * @since 5.0.0
 */

namespace WP_Bootstrap\Navwalker;

/**
 * Class used to handle the fallback menu.
 *
 * @since 5.0.0
 */
class Fallback extends Plugin {

	/**
	 * Starts the fallback menu by maybe opening the menu container.
	 *
	 * @access public
	 *
	 * @since 5.0.0
	 *
	 * @uses Utils::get_dropdown_trigger_link_id()
	 * @uses Utils::get_aria_labelledby()
	 *
	 * @param array  $args      An array of `wp_nav_menu()` arguments.
	 * @param string $output    Used to append additional content (passed by reference).
	 * @param bool   $container Whether to wrap the menu in a container (passed by reference).
	 */
	private static function start( $args, &$output, &$container ) {
		if ( $args['container'] ) {
			/**
			 * Filters the list of HTML tags that are valid for use as menu containers.
			 *
			 * @since WP 3.0.0
			 *
			 * @param array $tags The acceptable HTML tags for use as menu containers.
			 *                    Default is array containing 'div' and 'nav'.
			 */
			$allowed_tags = apply_filters( 'wp_nav_menu_container_allowedtags', array( 'div', 'nav' ) );
			if ( is_string( $args['container'] ) && in_array( $args['container'], $allowed_tags, true ) ) {
				$container  = true;
				$cont_class = $args['container_class'] ? ' class="menu-fallback-container ' . esc_attr( $args['container_class'] ) . '"' : ' class="menu-fallback-container"';
				$cont_id    = $args['container_id'] ? ' id="' . esc_attr( $args['container_id'] ) . '"' : '';
				$output    .= '<' . $args['container'] . $cont_id . $cont_class . '>';
			}
		}
	}


	/**
	 * The fallback menu.
	 *
	 * @param array  $args   An array of `wp_nav_menu()` arguments.
	 * @param string $output Used to append additional content (passed by reference).
	 * @return void
	 */
	private static function menu( $args, &$output ) {
		// The fallback menu.
		$menu_class = $args['menu_class'] ? ' class="menu-fallback-menu ' . esc_attr( $args['menu_class'] ) . '"' : ' class="menu-fallback-menu"';
		$menu_id    = $args['menu_id'] ? ' id="' . esc_attr( $args['menu_id'] ) . '"' : '';
		$output    .= '<ul' . $menu_id . $menu_class . '>';
		$output    .= '<li class="nav-item"><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '" class="nav-link">' . esc_html__( 'Add a menu', 'wp-bootstrap-navwalker' ) . '</a></li>';
		$output    .= '</ul>';
	}

	/**
	 * Ends the fallback menu by maybe closing the menu container.
	 *
	 * @access private
	 *
	 * @since 5.0.0
	 *
	 * @uses Utils::get_dropdown_trigger_link_id()
	 * @uses Utils::get_aria_labelledby()
	 *
	 * @param array  $args      An array of `wp_nav_menu()` arguments.
	 * @param string $output    Used to append additional content (passed by reference).
	 * @param bool   $container Whether to wrap the menu in a container.
	 */
	private static function end( $args, &$output, $container ) {
		if ( $container ) {
			$output .= '</' . $args['container'] . '>';
		}
	}

	/**
	 * Displays or retrieves the fallback menu.
	 *
	 * If this function is assigned to the wp_nav_menu's fallback_cb variable
	 * and a menu has not been assigned to the theme location in the WordPress
	 * menu manager the function will display nothing to a non-logged in user,
	 * and will add a link to the WordPress menu manager if logged in as an admin.
	 *
	 * @param array $args An array of `wp_nav_menu()` arguments.
	 * @return string|void String when echo is false.
	 */
	public static function callback( $args ) {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		// Initialize var to store fallback html.
		$output = '';

		// Initialize var to store whether to show a container.
		$container = false;

		self::start( $args, $output, $container );
		self::menu( $args, $output );
		self::end( $args, $output, $container );

		// If $args has 'echo' key and it's true echo, otherwise return.
		if ( array_key_exists( 'echo', $args ) && $args['echo'] ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $output;
		} else {
			return $output;
		}
	}
}
