<?php
/**
 * Arguments setup class
 *
 * @package WP-Bootstrap-Navwalker
 * @since 5.0.0
 */

namespace WP_Bootstrap\Navwalker;

/**
 * Class used to handle setting up the menu arguments.
 */
class Setup_Args extends Plugin {

	/**
	 * Which Bootstrap version to use.
	 *
	 * @since 5.0.0
	 *
	 * @var int
	 */
	const BS_VERSION = 4;

	/**
	 * Regluar expressions to match icon classes.
	 *
	 * @since 5.0.0
	 *
	 * @var array
	 */
	const ICON_REGEX = array(
		'bootstrap'   => '^bi-(\S*)?|^bi$',
		'fontawesome' => '^fa-(\S*)?|^fa(s|r|l|b)?(\s?)?$',
		'glyphicon'   => '^glyphicon-(\S*)?|^glyphicon(\s?)$',
	);

	/**
	 * Whether the dropdown trigger link should be clickable.
	 *
	 * @since 5.0.0
	 * @var bool
	 */
	const CLICKABLE = false;

	/**
	 * Whether to drop native WP classes from the HTML markup.
	 *
	 * @since 5.0.0
	 * @var bool
	 */
	const DROP_WP_CLASSES = false;

	/**
	 * Whether to add the `.active` class to the ancestors of the current menu item.
	 *
	 * @since 5.0.0
	 * @var bool
	 */
	const ANCESTORS_ACTIVE = false;

	/**
	 * Retrieve an array of regular expressions to match icon classes.
	 *
	 * @access public
	 *
	 * @since 5.0.0
	 *
	 * @param array $args An array of `wp_nav_menu()` arguments.
	 * @return string[]
	 */
	public static function get_icon_regex( $args ) {
		$slug = parent::SLUG;

		$icon_regex = self::ICON_REGEX;
		if ( isset( $args['icon_regex'] ) ) {
			$icon_regex = (array) $args['icon_regex'];
		}

		/**
		 * Filters the regular expressions for icons.
		 *
		 * @since 5.0.0
		 *
		 * @param string[] $icon_regex Array of regular expressions for icon classes.
		 */
		return (array) apply_filters( "{$slug}_icon_regex", $icon_regex );
	}

	/**
	 * Retrieve whether the dropdown trigger link should be clickable.
	 *
	 * @access public
	 *
	 * @since 5.0.0
	 *
	 * @param array $args An array of `wp_nav_menu()` arguments.
	 * @return bool
	 */
	public static function is_clickable( $args ) {
		if ( $args['bs_version'] < 4 ) {
			return false;
		}

		$slug = parent::SLUG;

		$clickable = self::CLICKABLE;
		if ( isset( $args['clickable'] ) ) {
			$clickable = (bool) $args['clickable'];
		}

		/**
		 * Filters whether the dropdown trigger link should be clickable.
		 *
		 * @since 5.0.0
		 *
		 * @param bool $clickable Whether to make dropdown toggle links clickable or not.
		 */
		return (bool) apply_filters( "{$slug}_clickable", $clickable );

	}

	/**
	 * Retrieve the Bootstrap version to use.
	 *
	 * @access public
	 *
	 * @since 5.0.0
	 *
	 * @param array $args An array of `wp_nav_menu()` arguments.
	 * @return int Major Bootstrap version.
	 */
	public static function bs_version( $args ) {
		$slug = parent::SLUG;

		$version = self::BS_VERSION;
		if ( isset( $args['bs_version'] ) ) {
			$version = (int) $args['bs_version'];
		}

		/**
		 * Filters the Bootstrap version to use.
		 *
		 * @since 5.0.0
		 *
		 * @param int $bs_version Major Bootstrap version.
		 */
		$version = (int) apply_filters( "{$slug}_bs_version", $version );

		if ( $version < 3 | $version > 5 ) {
			return self::BS_VERSION;
		}
		return $version;
	}

	/**
	 * Determine whether to drop native WordPress classes.
	 *
	 * @access public
	 *
	 * @since 5.0.0
	 *
	 * @param array $args An array of `wp_nav_menu()` arguments.
	 * @return bool Whether to drop the classes.
	 */
	public static function drop_wp_classes( $args ) {
		$slug = parent::SLUG;

		if ( isset( $args['drop_wp_classes'] ) ) {
			$drop_wp_classes = $args['drop_wp_classes'];
		} else {
			$drop_wp_classes = self::DROP_WP_CLASSES;
		}

		/**
		 * Filters whether to drop the native WordPress classes.
		 *
		 * @since 5.0.0
		 *
		 * @param bool $drop_wp_classes Whether to drop the classes.
		 */
		return (bool) apply_filters( "{$slug}_drop_wp_classes", $drop_wp_classes );
	}

	/**
	 * Determine whether to add the `.active` class to the current menu item's
	 * ancestors.
	 *
	 * @access public
	 *
	 * @since 5.0.0
	 *
	 * @param array $args An array of `wp_nav_menu()` arguments.
	 * @return bool Whether to add `.active` to item ancestors.
	 */
	public static function ancestors_active( $args ) {
		$slug = parent::SLUG;

		if ( isset( $args['ancestors_active'] ) ) {
			$ancestors_active = (bool) $args['ancestors_active'];
		} else {
			$ancestors_active = self::ANCESTORS_ACTIVE;
		}

		/**
		 * Filters whether to add the `.active`class to the current menu item's
		 * ancestors.
		 *
		 * @since 5.0.0
		 *
		 * @param bool $ancerstors_acitve Whether to add the `.active` class.
		 */
		return (bool) apply_filters( "{$slug}_ancestors_active", $ancestors_active );
	}

	/**
	 * Add the item's dropdown menu classes to the args.
	 *
	 * @access public
	 *
	 * @since 5.0.0
	 *
	 * @param \WP_Nav_Menu_Item $item The current menu item (instance of `WP_Post`).
	 * @param array             $args An array of `wp_nav_menu()` arguments.
	 * @return array
	 */
	public static function add_dropdown_menu_classes( $item, $args ) {
		// Reset dropdown menu classes argument for each item.
		$args[0]->dropdown_menu_classes = false;
		if ( false !== $item->dropdown_menu_classes ) {
			$args[0]->dropdown_menu_classes = $item->dropdown_menu_classes;
		}
		return $args;
	}

}
