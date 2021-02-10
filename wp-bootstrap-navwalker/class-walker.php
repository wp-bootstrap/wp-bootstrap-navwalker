<?php
/**
 * Walker Class
 *
 * @package WP-Bootstrap-Navwalker
 * @since 5.0.0 Added namespace and renamed class.
 * @since 1.0.0
 */

namespace WP_Bootstrap\Navwalker;

/**
 * Class used to add Bootstrap markup via extending the `Walker_Nav_Menu` class.
 *
 * @link https://developer.wordpress.org/reference/classes/walker_nav_menu/
 */
class Walker extends \Walker_Nav_Menu {

	/**
	 * Starts the list before the elements are added.
	 *
	 * @access public
	 *
	 * @since 5.0.0 Removed code duplication.
	 * @since 1.0.0
	 *
	 * @link https://developer.wordpress.org/reference/classes/walker_nav_menu/start_lvl/
	 *
	 * @uses Utils::get_dropdown_trigger_link_id()
	 * @uses Utils::get_aria_labelledby()
	 *
	 * @param string            $output Used to append additional content (passed by reference).
	 * @param int               $depth  Depth of menu item. Used for padding.
	 * @param \WP_Nav_Menu_Args $args   An object of `wp_nav_menu()` arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		/*
		 * Get aria-labelledby attribute.
		 *
		 * The `.dropdown-menu` container needs to have a labelledby
		 * attribute which points to it's trigger link.
		 */
		$labelledby = Utils::get_aria_labelledby( $output );

		// Append start level to output.
		parent::start_lvl( $output, $depth, $args );

		// Insert aria-labelledby attribute.
		$output = Utils::add_aria_labelledby( $labelledby, $output );
	}

	/**
	 * Traverse elements to create list from elements.
	 *
	 * Display one element if the element doesn't have any children otherwise,
	 * display the element and its children. Will only traverse up to the max
	 * depth and no ignore elements under that depth. It is possible to set the
	 * max depth to include all depths, see walk() method.
	 *
	 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
	 *
	 * @since 5.0.0 Code duplication removed.
	 * @since 1.0.0
	 *
	 * @link https://developer.wordpress.org/reference/classes/walker/display_element/
	 *
	 * @uses Utils::setup_menu_item_properties()
	 * @uses Utils::setup_item_specific_args()
	 *
	 * @param \WP_Nav_Menu_Item $item      The current menu item (instance of `WP_Post`).
	 * @param array             $sub_items List of elements to continue traversing (passed by reference).
	 * @param int               $max_depth Max depth to traverse.
	 * @param int               $depth     Depth of current element.
	 * @param array             $args      An array of `wp_nav_menu()` arguments.
	 * @param string            $output    Used to append additional content (passed by reference).
	 */
	public function display_element( $item, &$sub_items, $max_depth, $depth, $args, &$output ) {
		if ( ! is_object( $item ) ) {
			return;
		}

		// Add item properties used in our filters.
		$item = Setup_Items::setup_menu_item_properties( $item, $sub_items, $max_depth, $depth, $args[0] );

		// Add arguments used in our filters.
		$args = Setup_Args::add_dropdown_menu_classes( $item, $args );

		parent::display_element( $item, $sub_items, $max_depth, $depth, $args, $output );
	}
}
