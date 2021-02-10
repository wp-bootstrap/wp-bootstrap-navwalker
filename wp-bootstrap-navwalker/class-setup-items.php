<?php
/**
 * Items setup class
 *
 * @package WP-Bootstrap-Navwalker
 * @since 5.0.0
 */

namespace WP_Bootstrap\Navwalker;

/**
 * Class used to handle setting up the menu items.
 */
class Setup_Items extends Plugin {

	/**
	 * Set up the menu item properties used in our filters to add Bootstrap markup.
	 *
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
	 *
	 * @access public
	 *
	 * @since 5.0.0
	 *
	 * @param \WP_Nav_Menu_Item $item      The current menu item (instance of `WP_Post`).
	 * @param array             $sub_items List of items to continue traversing.
	 * @param int               $max_depth Max depth to traverse.
	 * @param int               $depth     Depth of current element.
	 * @param \WP_Nav_Menu_Args $args      An object of `wp_nav_menu()` arguments.
	 * @return object
	 */
	public static function setup_menu_item_properties( $item, $sub_items, $max_depth, $depth, $args ) {

		// Init properties.
		$item = self::init_item_properties( $item );

		// Whether item has subitems.
		$item->has_children = ! empty( $sub_items[ $item->db_id ] );

		// Whether item has split button dropdown toggler.
		if ( $item->has_children && $args->clickable ) {
			$item->has_clickable_link = true;
		}

		// Separate native WP classes from user-provided classes.
		$item = self::separate_classes( $item );

		// Return early if no custom classes were provided.
		if ( empty( $item->custom_classes ) ) {
			return $item;
		}

		// Classes left to process.
		$classes_left = $item->custom_classes;

		// Set item properties for link modifications.
		$item = self::set_link_mod_properties( $item, $depth, $args, $classes_left );

		// Set item properties for prefixed classes.
		$item = self::set_anchor_property( $item, $classes_left );

		// Set item properties for prefixed classes.
		$item = self::set_dropdown_menu_property( $item, $classes_left );

		// Set item properties for icons.
		$item = self::set_icon_properties( $item, $classes_left, $args );

		// All Navwalker relevant classes have been removed from `$classes_left`.

		// (Maybe) Re-add native classes.
		if ( $args->drop_wp_classes ) {
			$item->native_classes = array();
		}
		$item->classes = $item->native_classes;
		if ( ! empty( $classes_left ) ) {
			$item->classes = array_merge( $item->classes, $classes_left );
		}

		return $item;
	}

	/**
	 * Separate native WP classes from custom user-provided classes.
	 *
	 * @param \WP_Nav_Menu_Item $item The current menu item (instance of `WP_Post`).
	 * @return \WP_Nav_Menu_Item
	 */
	private static function separate_classes( $item ) {
		$match                = preg_grep( '/^(menu-item-?|current-|current_)(\S*)$/i', $item->classes );
		$item->native_classes = $match;
		$item->custom_classes = array_diff( $item->classes, $match );
		return $item;
	}

	/**
	 * Set property for anchor classes.
	 *
	 * @param \WP_Nav_Menu_Item $item         The current menu item (instance of `WP_Post`).
	 * @param array             $classes_left Classes left to process (passed by reference).
	 * @return \WP_Nav_Menu_Item
	 */
	private static function set_anchor_property( $item, $classes_left ) {
		if ( empty( $classes_left ) ) {
			return $item;
		}
		$match = preg_grep( '/^class-anchor-(\S*)$/i', $classes_left );
		if ( ! empty( $match ) ) {
			$item->anchor_classes = array_map( array( __NAMESPACE__ . '\Utils', 'remove_anchor_class_prefix' ), $match );
			$classes_left         = array_diff( $classes_left, $match );
		}
		return $item;
	}

	/**
	 * Set property for dropdown menu classes.
	 *
	 * @param \WP_Nav_Menu_Item $item         The current menu item (instance of `WP_Post`).
	 * @param array             $classes_left Classes left to process (passed by reference).
	 * @return \WP_Nav_Menu_Item
	 */
	private static function set_dropdown_menu_property( $item, $classes_left ) {
		if ( empty( $classes_left ) ) {
			return $item;
		}

		$regex = '/^dropdown-menu-(sm-|md-|lg-|xl-|xll-)?(start|end|left|right)|^class-dropdown-menu-(\S*)$/i';

		$match = preg_grep( $regex, $classes_left );
		if ( ! empty( $match ) ) {
			// We did a case-insesitive match. Make class names lowercase.
			$classes = array_map( 'strtolower', $match );

			// Remove the prefix 'class-dropdown-menu-'.
			$callable = __NAMESPACE__ . '\Utils::remove_dropdown_menu_class_prefix';
			$classes  = array_map( $callable, $classes );

			// Set property.
			$item->dropdown_menu_classes = $classes;

			// Remove processed classes from classes left to process.
			$classes_left = array_diff( $classes_left, $match );
		}
		return $item;
	}

	/**
	 * Set item properties for link modifications.
	 *
	 * @param \WP_Nav_Menu_Item $item         The current menu item (instance of `WP_Post`).
	 * @param int               $depth        Depth of the current item.
	 * @param \WP_Nav_Menu_Args $args         An object of `wp_nav_menu()` arguments.
	 * @param array             $classes_left Classes left to process (passed by reference).
	 * @return object
	 */
	private static function set_link_mod_properties( $item, $depth, $args, &$classes_left ) {
		if ( empty( $classes_left ) ) {
			return $item;
		}

		// Link modifications.
		$link_mods = array(
			'dropdown-divider',
			'dropdown-header',
			'dropdown-item-text',
			'dropdown-toggle-split',
			'sr-only',
			'disabled',
		);

		// Set item properties for linkmods.
		$link_mod_classes = array_intersect( $link_mods, $classes_left );
		if ( empty( $link_mod_classes ) ) {
			return $item;
		}
		foreach ( $link_mod_classes as $key => $class ) {
			$property          = 'is_' . str_replace( '-', '_', $class );
			$item->{$property} = true;
		}

		if ( $item->is_dropdown_toggle_split && $item->has_children ) {
			if ( 3 !== $args->bs_version ) {
				$item->has_clickable_link = true;
			}
		}

		$item = Utils::sanitize_dropdown_menu_content( $item, $depth );
		$item = Utils::sanitize_is_disabled( $item );

		// Remove classes already processed.
		$classes_left = array_diff( $classes_left, $link_mod_classes );

		return $item;
	}

	/**
	 * Set item properties for icons.
	 *
	 * @param \WP_Nav_Menu_Item $item         The current menu item (instance of `WP_Post`).
	 * @param array             $classes_left Classes left to process (passed by reference).
	 * @param \WP_Nav_Menu_Args $args         An object of `wp_nav_menu()` arguments.
	 * @return object
	 */
	private static function set_icon_properties( $item, &$classes_left, $args ) {
		if ( empty( $classes_left || $item->is_dropdown_divider || empty( $args->icon_regex ) ) ) {
			return $item;
		}

		$icon_regex = '/' . implode( '|', $args->icon_regex ) . '/i';

		$match = preg_grep( $icon_regex, $classes_left );
		if ( ! empty( $match ) ) {
			$classes_left       = array_diff( $classes_left, $match );
			$item->icon_classes = $match;
		}
		if ( ! empty( $classes_left ) ) {
			foreach ( $classes_left as $key => $class ) {
				if ( 'icon-append' === $class ) {
					$item->icon_append = true;
					unset( $classes_left[ $key ] );
				}
			}
		}

		return $item;
	}

	/**
	 * Initialise the item porperties used in the filters.
	 *
	 * @access private
	 *
	 * @since 5.0.0
	 *
	 * @param \WP_Nav_Menu_Item $item The current menu item (instance of `WP_Post`).
	 * @return \WP_Nav_Menu_Item
	 */
	private static function init_item_properties( $item ) {
		$item->has_children             = false;
		$item->is_disabled              = false;
		$item->is_sr_only               = false;
		$item->is_dropdown_menu_content = false;
		$item->is_dropdown_header       = false;
		$item->is_dropdown_divider      = false;
		$item->is_dropdown_item_text    = false;
		$item->is_dropdown_toggle_split = false;
		$item->has_clickable_link       = false;
		$item->icon_classes             = false;
		$item->anchor_classes           = false;
		$item->dropdown_menu_classes    = false;
		$item->native_classes           = false;
		$item->custom_classes           = false;
		return $item;
	}
}
