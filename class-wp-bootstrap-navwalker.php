<?php
/**
 * WP Bootstrap Navwalker
 *
 * @package WP-Bootstrap-Navwalker
 */

/*
 * Class Name: WP_Bootstrap_Navwalker
 * Plugin Name: WP Bootstrap Navwalker
 * Plugin URI:  https://github.com/wp-bootstrap/wp-bootstrap-navwalker
 * Description: A custom WordPress nav walker class to implement the Bootstrap 3 navigation style in a custom theme using the WordPress built in menu manager.
 * Author: Edward McIntyre - @twittem, WP Bootstrap, William Patton - @pattonwebz
 * Version: 3.0.3
 * Author URI: https://github.com/wp-bootstrap
 * GitHub Plugin URI: https://github.com/wp-bootstrap/wp-bootstrap-navwalker
 * GitHub Branch: master
 * License: GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
*/

/* Check if Class Exists. */
if ( ! class_exists( 'WP_Bootstrap_Navwalker' ) ) {
	/**
	 * WP_Bootstrap_Navwalker class.
	 *
	 * @extends Walker_Nav_Menu
	 */
	class WP_Bootstrap_Navwalker extends Walker_Nav_Menu {

		/**
		 * Start Level.
		 *
		 * @see Walker::start_lvl()
		 * @since 3.0.0
		 *
		 * @access public
		 * @param mixed $output Passed by reference. Used to append additional content.
		 * @param int   $depth (default: 0) Depth of page. Used for padding.
		 * @param array $args (default: array()) Arguments.
		 * @return void
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			$indent = str_repeat( "\t", $depth );
			// find all links with an id in the output.
			preg_match_all( '/(<a.*?id=\"|\')(.*?)\"|\'.*?>/im', $output, $matches );
			// with pointer at end of array check if we got an ID match.
			if ( end( $matches[2] ) ) {
				// build a string to use as aria-labelledby.
				$labledby = 'aria-labelledby="' . end( $matches[2] ) . '"';
			}

			$output .= "\n$indent<ul role=\"menu\" class=\" dropdown-menu\" " . $labledby . ">\n";
		}

		/**
		 * Start El.
		 *
		 * @see Walker::start_el()
		 * @since 3.0.0
		 *
		 * @access public
		 * @param mixed $output Passed by reference. Used to append additional content.
		 * @param mixed $item Menu item data object.
		 * @param int   $depth (default: 0) Depth of menu item. Used for padding.
		 * @param array $args (default: array()) Arguments.
		 * @param int   $id (default: 0) Menu item ID.
		 * @return void
		 */
		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

			$value = '';
			$class_names = $value;
			$classes = empty( $item->classes ) ? array() : (array) $item->classes;
			// Loop through the array and pick out any special classes that need
			// to be added to an element other than the main <li>.
			$extra_link_classes = array();
			$icon_class_string = '';
			foreach ( $classes as $key => $class ) {
				// test if this is a disabled link.
				if ( 'disabled' === $class ) {
					$extra_link_classes[] = 'disabled';
					unset( $classes[ $key ] );
				}
				// test for icon classes - Supports Font Awesome and Glyphicons.
				if ( 'fa' === $class || 'fa-' === substr( $class, 0, 3 ) ) {
					// Because of the abiguity of just 'fa' at the start both
					// 'fa' & 'fa-' are tested for with Font Awesome icons.
					$icon_class_string .= $class . ' ';
					unset( $classes[ $key ] );
				} elseif ( 'glyphicons' === substr( $class, 0, 10 ) ) {
					// This should be a glyphicon icon class.
					$icon_class_string .= $class . ' ';
					unset( $classes[ $key ] );
				}
			}
			$classes[] = 'menu-item-' . $item->ID;
			// BSv4 classname - as of v4-alpha.
			$classes[] = 'nav-item';
			// reasign any filtered classes back to the $classes array.
			$classes = apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args );
			$class_names = join( ' ', $classes );
			if ( $args->has_children ) {
				$class_names .= ' dropdown';
			}
			if ( in_array( 'current-menu-item', $classes, true ) || in_array( 'current-menu-parent', $classes, true ) ) {
				$class_names .= ' active';
			}
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
			$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
			$output .= $indent . '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement"' . $id . $value . $class_names . '>';
			$atts = array();

			if ( empty( $item->attr_title ) ) {
				$atts['title']  = ! empty( $item->title )   ? strip_tags( $item->title ) : '';
			} else {
				$atts['title'] = $item->attr_title;
			}

			$atts['target'] = ! empty( $item->target )	? $item->target	: '';
			$atts['rel']    = ! empty( $item->xfn )		? $item->xfn	: '';
			// If item has_children add atts to a.
			if ( $args->has_children && 0 === $depth && $args->depth > 1 ) {
				$atts['href']   		= '#';
				$atts['data-toggle']	= 'dropdown';
				$atts['aria-haspopup']	= 'true';
				$atts['aria-expanded']	= 'false';
				$atts['class']			= 'dropdown-toggle nav-link';
				$atts['id']				= 'menu-item-dropdown-' . $item->ID;
			} else {
				$atts['href'] 	= ! empty( $item->url ) ? $item->url : '';
				// if we are in a dropdown then the the class .dropdown-item
				// should be used instead of .nav-link.
				if ( $depth > 0 ) {
					$atts['class']	= 'dropdown-item';
				} else {
					$atts['class']	= 'nav-link';
				}
			}
			// Loop through the array of extra link classes plucked from the
			// parent <li>s classes array.
			if ( ! empty( $extra_link_classes ) ) {
				foreach ( $extra_link_classes as $link_class ) {
					if ( ! empty( $link_class ) ) {
						// update $atts with the extra class link.
						$atts['class'] .= ' ' . esc_attr( $link_class );

						// if the modification is a disabled class...
						if ( 'disabled' === $link_class ) {
							// then # the link so it doesn't point anywhere.
							$atts['href'] = '#';
						}
					}
				}
			}
			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );
			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}
			$item_output = $args->before;
			$item_output .= '<a' . $attributes . '>';

			// initiate empty icon var then if we have a string containing icon classes...
			$icon_html = '';
			if ( ! empty( $icon_class_string ) ) {
				// append an <i> with the icon classes to what is output before links.
				$icon_html = '<i class="' . esc_attr( $icon_class_string ) . '" aria-hidden="true"></i> ';
			}
			$item_output .= $args->link_before . $icon_html . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			$item_output .= '</a>';
			$item_output .= $args->after;
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

		}

		/**
		 * Traverse elements to create list from elements.
		 *
		 * Display one element if the element doesn't have any children otherwise,
		 * display the element and its children. Will only traverse up to the max
		 * depth and no ignore elements under that depth.
		 *
		 * This method shouldn't be called directly, use the walk() method instead.
		 *
		 * @see Walker::start_el()
		 * @since 2.5.0
		 *
		 * @access public
		 * @param mixed $element Data object.
		 * @param mixed $children_elements List of elements to continue traversing.
		 * @param mixed $max_depth Max depth to traverse.
		 * @param mixed $depth Depth of current element.
		 * @param mixed $args Arguments.
		 * @param mixed $output Passed by reference. Used to append additional content.
		 * @return null Null on failure with no changes to parameters.
		 */
		public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
			if ( ! $element ) {
				return; }
			$id_field = $this->db_fields['id'];
			// Display this element.
			if ( is_object( $args[0] ) ) {
				$args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] ); }
			parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
		}

		/**
		 * Menu Fallback
		 * =============
		 * If this function is assigned to the wp_nav_menu's fallback_cb variable
		 * and a menu has not been assigned to the theme location in the WordPress
		 * menu manager the function with display nothing to a non-logged in user,
		 * and will add a link to the WordPress menu manager if logged in as an admin.
		 *
		 * @param array $args passed from the wp_nav_menu function.
		 */
		public static function fallback( $args ) {
			if ( current_user_can( 'edit_theme_options' ) ) {

				/* Get Arguments. */
				$container = $args['container'];
				$container_id = $args['container_id'];
				$container_class = $args['container_class'];
				$menu_class = $args['menu_class'];
				$menu_id = $args['menu_id'];

				// initialize var to store fallback html.
				$fallback_output = '';

				if ( $container ) {
					$fallback_output = '<' . esc_attr( $container );
					if ( $container_id ) {
						$fallback_output = ' id="' . esc_attr( $container_id ) . '"';
					}
					if ( $container_class ) {
						$fallback_output = ' class="' . sanitize_html_class( $container_class ) . '"';
					}
					$fallback_output = '>';
				}
				$fallback_output = '<ul';
				if ( $menu_id ) {
					$fallback_output = ' id="' . esc_attr( $menu_id ) . '"'; }
				if ( $menu_class ) {
					$fallback_output = ' class="' . esc_attr( $menu_class ) . '"'; }
				$fallback_output = '>';
				$fallback_output = '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '" title="">' . esc_attr( 'Add a menu', '' ) . '</a></li>';
				$fallback_output = '</ul>';
				if ( $container ) {
					$fallback_output = '</' . esc_attr( $container ) . '>';
				}

				// if $args has 'echo' key and it's true echo, otherwise return.
				if ( array_key_exists( 'echo', $args ) && $args['echo'] ) {
					echo $fallback_output; // WPCS: XSS OK.
				} else {
					return $fallback_output;
				}
			} // End if().
		}
	}
} // End if().
