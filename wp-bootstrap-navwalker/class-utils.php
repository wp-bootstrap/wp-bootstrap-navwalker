<?php
/**
 * Utility Class
 *
 * @package WP-Bootstrap-Navwalker
 * @since 5.0.0
 */

namespace WP_Bootstrap\Navwalker;

/**
 * Class used to ...
 */
class Utils extends Plugin {

	/**
	 * Shims for different Bootstrap versions.
	 *
	 * @since 5.0.0
	 *
	 * @var array
	 */
	const SHIM = array(
		'screen-reader-class' => array(
			'v3' => 'sr-only',
			'v4' => 'sr-only',
			'v5' => 'visually-hidden',
		),
		'data-toggle-attr'    => array(
			'v3' => 'data-toggle',
			'v4' => 'data-toggle',
			'v5' => 'data-bs-toggle',
		),
		'divider-class'       => array(
			'v3' => 'divider',
			'v4' => 'dropdown-divider',
			'v5' => 'dropdown-divider',
		),
	);

	/**
	 * Retrieve a shim.
	 *
	 * @access public
	 *
	 * @since 5.0.0
	 *
	 * @param string $name Name of shim.
	 * @param int    $bs_version Major Bootstrap version.
	 * @return string|bool
	 */
	public static function shim( $name, $bs_version ) {
		$version = 'v' . (int) $bs_version;
		if ( isset( self::SHIM[ $name ][ $version ] ) ) {
			return self::SHIM[ $name ][ $version ];
		}
		return false;
	}

	/**
	 * Sanitize dropdown menu content properties.
	 *
	 * At depth = 0 there can't be dropdown items and hence can't be dropdown
	 * menu contents. If the item is a parent and the dropdown  toggle is not
	 * separated from the link, the item must be a dropdown toggle and hence
	 * can't be a dropdown content.
	 *
	 * @access private
	 *
	 * @since 5.0.0
	 *
	 * @param \WP_Nav_Menu_Item $item  The current menu item (instance of `WP_Post`).
	 * @param int               $depth Depth of the current item.
	 * @return \WP_Nav_Menu_Item
	 */
	public static function sanitize_dropdown_menu_content( $item, $depth ) {
		if ( 0 === $depth || ( $item->has_children && ! $item->has_clickable_link ) ) {
			/*
			 * At depth = 0 there can't be dropdown items and hence can't be
			 * dropdown menu contents. If the item is a parent and the dropdown
			 * toggle is not separated from the link, the item must be a
			 * dropdown toggle and hence can't be a dropdown content.
			 */
			$item->is_dropdown_header       = false;
			$item->is_dropdown_divider      = false;
			$item->is_dropdown_item_text    = false;
			$item->is_dropdown_menu_content = false;
		}
		return $item;
	}

	/**
	 * Sanitize the is_disabled property.
	 *
	 * Dropdown menu content cannot be disabled.
	 *
	 * @access private
	 *
	 * @since 5.0.0
	 *
	 * @param \WP_Nav_Menu_Item $item  The current menu item (instance of `WP_Post`).
	 * @return \WP_Nav_Menu_Item
	 */
	public static function sanitize_is_disabled( $item ) {
		if ( $item->is_dropdown_header
			|| $item->is_dropdown_divider
			|| $item->is_dropdown_item_text ) {
			$item->is_dropdown_menu_content = true;

			$item->is_disabled = false;
		}
		return $item;
	}

	/**
	 * Build `aria-labelledby` attribute.
	 *
	 * @param string $output HTML markup for the menu created so far.
	 * @return string
	 */
	public static function get_aria_labelledby( $output ) {
		$trigger_link_id = self::get_dropdown_trigger_link_id( $output );
		return ' aria-labelledby="' . $trigger_link_id . '"';
	}

	/**
	 * Add `aria-labelledby` attribute to dropdown menu ul tag.
	 *
	 * @param string $labelledby The `aria-labelledby` attribute.
	 * @param string $output     HTML markup for the menu created so far (passed by reference).
	 */
	public static function add_aria_labelledby( $labelledby, $output ) {
		return self::str_lreplace( '>', $labelledby . '>', $output );
	}

	/**
	 * Get the id of the latest link with an id that was added to the output.
	 *
	 * @access private
	 * @since 5.0.0 Was coded into `start_lvl()` prior to 5.0.0.
	 *
	 * @param string $output Used to append additional content (passed by reference).
	 * @return string
	 */
	private static function get_dropdown_trigger_link_id( $output ) {
		// Find all links with an id in the output.
		preg_match_all( '/(<a.*?id=\"|\')(.*?)\"|\'.*?>/im', $output, $matches );
		// With pointer at end of array check if we got an ID match.
		if ( end( $matches[2] ) ) {
			return esc_attr( end( $matches[2] ) );
		}
		return '';
	}

	/**
	 * Replace last occurance of a substring within a string.
	 *
	 * @access public
	 * @since 5.0.0
	 *
	 * @param string $search  The value being searched for, otherwise known as the needle.
	 * @param string $replace The replacement value that replaces found search values.
	 * @param string $string  The string being searched and replaced on, otherwise known as the haystack.
	 * @return string
	 */
	private static function str_lreplace( $search, $replace, $string ) {
		$strrpos = strrpos( $string, $search );

		if ( false !== $strrpos ) {
			$string = substr_replace( $string, $replace, $strrpos, strlen( $search ) );
		}

		return $string;
	}

	/**
	 * Remove prefix from custom user-provided class.
	 *
	 * @param string $prefix Prefix for custom user-provided class.
	 * @param string $class  Custom user-provided class.
	 * @return string
	 */
	private static function remove_class_prefix( $prefix, $class ) {
		return str_replace( $prefix, '', $class );
	}

	/**
	 * Remove the prefix 'class-anchor-' from a custom user-provided class.
	 *
	 * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
	 *
	 * @param string $class Custom user-provided class.
	 * @return string
	 */
	public static function remove_anchor_class_prefix( $class ) {
		return self::remove_class_prefix( 'class-anchor-', $class );
	}

	/**
	 * Remove the prefix 'class-dropdown-menu-' from a custom user-provided class.
	 *
	 * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
	 *
	 * @param string $class Custom user-provided class.
	 * @return string
	 */
	public static function remove_dropdown_menu_class_prefix( $class ) {
		return self::remove_class_prefix( 'class-dropdown-menu-', $class );
	}

	/**
	 * Retrieve the HTML markup for the dropdown divider.
	 *
	 * @access public
	 *
	 * @since 5.0.0
	 *
	 * @uses Utils::shim()
	 *
	 * @param \WP_Nav_Menu_Args $args An object of `wp_nav_menu()` arguments.
	 * @return string
	 */
	public static function get_dropdown_divider( $args ) {
		return sprintf(
			'<hr class="%s">',
			self::shim( 'dropdown-divider', $args->bs_version )
		);
	}

	/**
	 * Retrieve the HTML markup for the dropdown header.
	 *
	 * @access public
	 *
	 * @since 5.0.0
	 *
	 * @param string $title The post title.
	 * @return string
	 */
	public static function get_dropdown_header( $title ) {
		/*
		 * For a header use a span with the `.h6` class instead of a real
		 * header tag so that it doesn't confuse screen readers.
		 */
		return sprintf(
			'<span class="dropdown-header h6">%s</span>',
			$title
		);
	}

	/**
	 * Retrieve the HTML markup for the dropdown item text.
	 *
	 * @access public
	 *
	 * @since 5.0.0
	 *
	 * @param string $title The post title.
	 * @return string
	 */
	public static function get_dropdown_item_text( $title ) {
		return sprintf(
			'<span class="dropdown-item-text">%s</span>',
			$title
		);
	}

	/**
	 * Prepend or append icon to title.
	 *
	 * @access public
	 *
	 * @since 5.0.0
	 *
	 * @param string            $title The menu item's title.
	 * @param \WP_Nav_Menu_Item $item  The current menu item (instance of `WP_Post`).
	 * @return string
	 */
	public static function add_icon( $title, $item ) {
		if ( ! $item->icon_classes ) {
			return $title;
		}

		$before_title = '';
		$after_title  = '';

		$icon_html = '<i class="' . esc_attr( implode( ' ', $item->icon_classes ) ) . '" aria-hidden="true"></i>';

		if ( isset( $item->icon_append ) ) {
			$before_title = '';
			$after_title  = '&nbsp;' . $icon_html;
		} else {
			$before_title = $icon_html . '&nbsp;';
			$after_title  = '';
		}

		return $before_title . $title . $after_title;
	}

	/**
	 * Styles to display menu items of depth greater than 2.
	 */
	public static function add_style() {
		?>
		<style>
		.dropdown-toggle-split {
			text-align: left;
			margin-left: .25rem!important;
		}
		.sub-menu.dropdown-menu {
			width: 100%;
		}
		.dropdown-submenu {
			position: relative;
		}
		.dropdown-submenu a::after {
			transform: rotate(-90deg);
			position: absolute;
			right: 6px;
			top: .8em;
		}
		.dropdown-submenu .dropdown-menu {
			top: 0;
			left: 100%;
			margin-left: .1rem;
			margin-right: .1rem;
		}
		</style>
		<?php
	}

	/**
	 * Script to display menu items of depth greater than 2.
	 *
	 * Should work for Bootstrap v4+
	 */
	public static function add_script() {
		?>
		<script>
		(function($) {
			var $subMenu, $dropdownMenu,
				dropdownMenuPT, dropdownMenuBTW, dropdownMenuMT;
			$('.navbar .dropdown-menu a.dropdown-toggle').on('click', function(e) {
				if (!$(this).next().hasClass('show')) {
					$(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
				}
				var $subMenu = $(this).next(".dropdown-menu");
				$subMenu.toggleClass('show');
				$(this).parents('li.dropdown.show').on('hidden.bs.dropdown', function(e) {
					$('.dropdown-submenu .show').removeClass("show");
				});
				return false;
			});
			$dropdownMenu = $('.navbar .sub-menu.dropdown-menu');
			if ($dropdownMenu.length>0) {
				dropdownMenuPT = $dropdownMenu.css('padding-top').match(/\d+/);
				dropdownMenuBTW = $dropdownMenu.css('border-top-width').match(/\d+/);
				dropdownMenuMT = parseFloat(dropdownMenuPT) + parseFloat(dropdownMenuBTW);
				$dropdownMenu.css('margin-top', '-'+dropdownMenuMT+'px');
			}
		}(jQuery));
		</script>
		<?php
	}

}

