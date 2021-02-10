<?php
/**
 * Filter Class
 *
 * @package WP-Bootstrap-Navwalker
 * @since 5.0.0
 */

namespace WP_Bootstrap\Navwalker;

/**
 * Class used to add Bootstrap markup via filters.
 */
class Filters extends Plugin {

	/**
	 * Add filters.
	 *
	 * @access public
	 *
	 * @since 5.0.0
	 */
	public function __construct() {
		add_filter( 'wp_nav_menu_args', array( $this, 'add_all_filters' ), 10, 1 );
	}

	/**
	 * Add filters if the walker is active.
	 *
	 * @access public
	 *
	 * @since 5.0.0
	 *
	 * @param array $args An array of `wp_nav_menu()` arguments.
	 *
	 * @return array
	 */
	public function add_all_filters( $args ) {
		// Only hook filters if the navigation menu uses this walker.
		if ( is_a( $args['walker'], __NAMESPACE__ . '\Walker' ) ) {
			add_filter( 'nav_menu_css_class', array( $this, 'set_menu_css_class' ), 20, 4 );
			add_filter( 'nav_menu_item_args', array( $this, 'set_item_description' ), 20, 3 );
			add_filter( 'nav_menu_item_title', array( $this, 'set_item_title' ), 20, 3 );
			add_filter( 'nav_menu_item_title', array( $this, 'add_icon' ), 20, 2 );
			add_filter( 'nav_menu_item_title', array( $this, 'add_caret' ), 20, 4 );
			add_filter( 'nav_menu_link_attributes', array( $this, 'set_link_attributes_parent' ), 20, 4 );
			add_filter( 'nav_menu_link_attributes', array( $this, 'set_link_attributes_childless' ), 20, 4 );
			add_filter( 'nav_menu_link_attributes', array( $this, 'set_link_attributes_general' ), 20, 3 );
			add_filter( 'nav_menu_submenu_css_class', array( $this, 'set_submenu_css_class' ), 20, 2 );
			add_filter( 'walker_nav_menu_start_el', array( $this, 'append_split_button_toggle' ), 20, 4 );
			add_filter( 'walker_nav_menu_start_el', array( $this, 'set_dropdown_menu_content_output' ), 20, 4 );
			add_filter( 'wp_nav_menu_args', array( $this, 'set_nav_menu_args' ), 20, 1 );
		}
		return $args;
	}

	/**
	 * Set the item's description.
	 *
	 * Note Bootstrap does not support item descriptions.
	 * Hence, styling has to be added by the user.
	 *
	 * @access public
	 *
	 * @since 5.0.0
	 *
	 * @param \WP_Nav_Menu_Args $args  An object of `wp_nav_menu()` arguments.
	 * @param \WP_Nav_Menu_Item $item  The current menu item (instance of `WP_Post`).
	 * @param int               $depth Depth of menu item.
	 * @return \WP_Nav_Menu_Args
	 */
	public function set_item_description( $args, $item, $depth ) {
		$args->link_after = '';
		if ( 0 === $depth && property_exists( $item, 'description' ) && $item->description ) {
			$args->link_after = sprintf(
				'<p class="menu-item-description" aria-labelledby="%s">%s</p>',
				'menu-item-title-' . (int) $item->ID,
				wp_kses_post( $item->description )
			);
		}
		return $args;
	}

	/**
	 * Add Bootstrap CSS class to submenu ul tag.
	 *
	 * @access public
	 *
	 * @since 5.0.0
	 *
	 * @param string[]          $classes Array of the CSS classes that are applied to the menu <ul> element.
	 * @param \WP_Nav_Menu_Args $args    An object of `wp_nav_menu()` arguments.
	 * @return string[]
	 */
	public function set_submenu_css_class( $classes, $args ) {
		$classes[] = 'dropdown-menu';
		if ( $args->dropdown_menu_classes ) {
			$classes = array_merge( $classes, $args->dropdown_menu_classes );
		}
		return $classes;
	}

	/**
	 * Add Bootstrap CSS classes to nav menu items.
	 *
	 * @access public
	 *
	 * @since 5.0.0
	 *
	 * @param string[]          $classes Array of the CSS classes that are applied to the menu item's <li> element.
	 * @param \WP_Nav_Menu_Item $item    The current menu item (instance of `WP_Post`).
	 * @param \WP_Nav_Menu_Args $args    An object of `wp_nav_menu()` arguments.
	 * @param int               $depth   Depth of menu item.
	 * @return string[]
	 */
	public function set_menu_css_class( $classes, $item, $args, $depth ) {
		if ( $args->drop_wp_classes ) {
			foreach ( $classes as $key => $class ) {
				if ( 'menu-item-' . $item->ID === $class ) {
					unset( $classes[ $key ] );
				}
			}
		}

		if ( 3 > $args->bs_version ) {
			$classes[] = 'nav-item';
		}

		if ( $item->has_children ) {
			if ( $depth > 0 ) {
				$classes[] = 'dropdown-submenu';
			} else {
				$classes[] = 'dropdown';
			}
			if ( $item->has_clickable_link ) {
				$classes[] = 'btn-group flex-wrap';
			}
		} elseif ( $item->is_dropdown_divider && 3 === $args->bs_version ) {
			$classes[] = 'divider';
		}

		return $classes;
	}

	/**
	 * Add Bootstrap markup specific to nav links with children.
	 *
	 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
	 *
	 * @access public
	 *
	 * @todo No aria-haspopup="true" in BS 5 docs. Check!
	 * @since 5.0.0
	 *
	 * @uses Utils::shim()
	 *
	 * @param array             $atts {
	 *  The HTML attributes applied to the menu item's <a> element, empty strings are ignored.
	 *
	 *  @type string $title        The title attribute.
	 *  @type string $target       The target attribute.
	 *  @type string $rel          The rel attribute.
	 *  @type string $href         The href attribute.
	 *  @type string $aria_current The aria-current attribute.
	 * }
	 * @param \WP_Nav_Menu_Item $item  The current menu item (instance of `WP_Post`).
	 * @param \WP_Nav_Menu_Args $args  An object of `wp_nav_menu()` arguments.
	 * @param int               $depth Depth of menu item.
	 * @return array
	 */
	public function set_link_attributes_parent( $atts, $item, $args, $depth ) {
		if ( ! $item->has_children || 1 === $args->depth ) {
			return $atts;
		}

		$toggle_attr = Utils::shim( 'data-toggle-attr', $args->bs_version );

		if ( ! $item->has_clickable_link & $depth < $args->depth - 1 ) {
			$atts['href']          = '#';
			$atts[ $toggle_attr ]  = 'dropdown';
			$atts['aria-haspopup'] = 'true';
			$atts['aria-expanded'] = 'false';
			$atts['class'][]       = 'dropdown-toggle';
			$atts['role']          = 'button';
		}
		$atts['id']      = 'menu-item-dropdown-toggle-' . $item->ID;
		$atts['class'][] = 'nav-link';

		return $atts;
	}

	/**
	 * Add Bootstrap markup specific to nav links with children.
	 *
	 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 *
	 * @access public
	 *
	 * @since 5.0.0
	 *
	 * @param array             $atts {
	 *  The HTML attributes applied to the menu item's <a> element, empty strings are ignored.
	 *
	 *  @type string $title        The title attribute.
	 *  @type string $target       The target attribute.
	 *  @type string $rel          The rel attribute.
	 *  @type string $href         The href attribute.
	 *  @type string $aria_current The aria-current attribute.
	 * }
	 * @param \WP_Nav_Menu_Item $item  The current menu item (instance of `WP_Post`).
	 * @param \WP_Nav_Menu_Args $args  An object of `wp_nav_menu()` arguments.
	 * @param int               $depth Depth of menu item.
	 * @return array
	 */
	public function set_link_attributes_childless( $atts, $item, $args, $depth ) {
		if ( $item->has_children & $depth > 0 ) {
			return $atts;
		}

		if ( $depth > 0 ) {
			$atts['class'][] = 'dropdown-item';
		} else {
			$atts['class'][] = 'nav-link';
		}

		return $atts;
	}

	/**
	 * Add Bootstrap markup to nav links.
	 *
	 * @access public
	 *
	 * @todo No aria-haspopup="true" in BS 5 docs. Check!
	 * @since 5.0.0
	 *
	 * @uses Utils::shim()
	 *
	 * @param array             $atts {
	 *  The HTML attributes applied to the menu item's <a> element, empty strings are ignored.
	 *
	 *  @type string $title        The title attribute.
	 *  @type string $target       The target attribute.
	 *  @type string $rel          The rel attribute.
	 *  @type string $href         The href attribute.
	 *  @type string $aria_current The aria-current attribute.
	 * }
	 * @param \WP_Nav_Menu_Item $item The current menu item (instance of `WP_Post`).
	 * @param \WP_Nav_Menu_Args $args An object of `wp_nav_menu()` arguments.
	 * @return array
	 */
	public function set_link_attributes_general( $atts, $item, $args ) {
		if ( $item->is_disabled ) {
			$atts['class'][]       = 'disabled';
			$atts['href']          = '#';
			$atts['tabindex']      = -1;
			$atts['aria-disabled'] = true;
		}

		if ( $item->anchor_classes ) {
			foreach ( $item->anchor_classes as $class ) {
				$atts['class'][] = $class;
			}
		}

		// Add active class.
		if ( $item->current ) {
			$atts['class'][] = 'active';
		} elseif ( $args->ancestors_active && $item->current_item_ancestor ) {
			$atts['class'][] = 'active';
		}

		if ( isset( $atts['class'] ) ) {
			$atts['class'] = implode( ' ', $atts['class'] );
		}

		return $atts;
	}

	/**
	 * Retrieve the HTML markup for the dropdown menu content item.
	 *
	 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
	 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
	 *
	 * @access public
	 *
	 * @since 5.0.0
	 *
	 * @param string            $item_output The menu item's starting HTML output.
	 * @param \WP_Nav_Menu_Item $item        The current menu item (instance of `WP_Post`).
	 * @param int               $depth       Depth of menu item.
	 * @param \WP_Nav_Menu_Args $args        An object of `wp_nav_menu()` arguments.
	 * @return string
	 */
	public function set_dropdown_menu_content_output( $item_output, $item, $depth, $args ) {
		if ( ! $item->is_dropdown_menu_content ) {
			return $item_output;
		}

		$title = '';
		if ( ! $item->is_dropdown_divider ) {
			/**
			 * Filters a menu item's title.
			 *
			 * @since WP 4.4.0
			 *
			 * @param string   $title The menu item's title.
			 * @param WP_Post  $item  The current menu item.
			 * @param stdClass $args  An object of wp_nav_menu() arguments.
			 * @param int      $depth Depth of menu item. Used for padding.
			 */
			$title = apply_filters( 'nav_menu_item_title', $item->title, $item, $args, $depth );
		}

		if ( $item->is_dropdown_divider ) {
			$item_output = Utils::get_dropdown_divider( $args );
		} elseif ( $item->is_dropdown_header ) {
			$item_output = Utils::get_dropdown_header( $title );
		} else {
			$item_output = Utils::get_dropdown_item_text( $title );
		}

		return $item_output;
	}

	/**
	 * Append the dropdown toggle for split button dropfown toggles.
	 *
	 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 *
	 * @access public
	 *
	 * @since 5.0.0
	 *
	 * @uses Utils::shim()
	 *
	 * @param string            $item_output The menu item's starting HTML output.
	 * @param \WP_Nav_Menu_Item $item        The current menu item (instance of `WP_Post`).
	 * @param int               $depth Depth of menu item.
	 * @param \WP_Nav_Menu_Args $args  An object of `wp_nav_menu()` arguments.
	 * @return string
	 */
	public function append_split_button_toggle( $item_output, $item, $depth, $args ) {
		if ( ! $item->has_clickable_link || 1 === (int) $args->depth ) {
			return $item_output;
		}

		$slug        = parent::SLUG;
		$sr_class    = Utils::shim( 'screen-reader-class', $args->bs_version );
		$toggle_attr = Utils::shim( 'data-toggle-attr', $args->bs_version );

		/**
		 * Filters the classes fot the split button dropdown toggle.
		 *
		 * @since 5.0.0
		 *
		 * @param array $classes Array of classes.
		 */
		$classes = (array) apply_filters(
			"{$slug}_split_button_toggle_classes",
			array(
				'dropdown-toggle',
				'dropdown-toggle-split',
				'nav-link',
			)
		);
		if ( $item->current ) {
			$classes[] = 'active';
		} elseif ( $args->ancestors_active && $item->current_item_ancestor ) {
			$classes[] = 'active';
		}

		$classes = implode( ' ', $classes );

		$sr_text = __( 'Toggle Dropdown', 'wp-bootstrap-navwalker' );

		/**
		 * Filters the screen reader text for the split button dropdown toggle.
		 *
		 * @param string $sr_text Screen reader text.
		 */
		$sr_text = apply_filters( "{$slug}_split_button_toggle_sr_text", $sr_text );

		$toggle = sprintf(
			'<a href="#" class="%s" %s="dropdown" aria-haspopup="true" aria-expanded="false" role="button">%s</a>',
			esc_attr( $classes ),
			$toggle_attr,
			'<span class="' . $sr_class . '">' . esc_html( $sr_text ) . '</span>'
		);

		return $item_output . $toggle;
	}

	/**
	 * Wrap the title in a <span> tag with corresponding classes.
	 *
	 * @access public
	 *
	 * @since 5.0.0
	 *
	 * @uses Utils::shim()
	 *
	 * @param string            $title The menu item's title.
	 * @param \WP_Nav_Menu_Item $item  The current menu item (instance of `WP_Post`).
	 * @param \WP_Nav_Menu_Args $args  An object of `wp_nav_menu()` arguments.
	 * @return string
	 */
	public function set_item_title( $title, $item, $args ) {
		if ( $item->is_dropdown_menu_content ) {
			// `set_dropdown_menu_content_output()` handels this case.
			return $title;
		}

		$sr_class = Utils::shim( 'screen-reader-class', $args->bs_version );

		$item_title_id = '';
		if ( property_exists( $item, 'description' ) && $item->description ) {
			$item_title_id = 'id="menu-item-title-' . $item->ID . '" ';
		}

		// Wrap title in <span> and add .sr-only/visually-hidden if neccessary.
		if ( $item->is_sr_only ) {
			$title = '<span ' . $item_title_id . 'class="menu-item-title ' . $sr_class . '">' . $title . '</span>';
		} else {
			$title = '<span ' . $item_title_id . 'class="menu-item-title">' . $title . '</span>';
		}

		return $title;
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
	public function add_icon( $title, $item ) {
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
	 * Append Bootstrap 3 caret to the title.
	 *
	 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
	 *
	 * @access public
	 *
	 * @since 5.0.0
	 *
	 * @param string            $title The menu item's title.
	 * @param \WP_Nav_Menu_Item $item  The current menu item (instance of `WP_Post`).
	 * @param \WP_Nav_Menu_Args $args  An object of `wp_nav_menu()` arguments.
	 * @param int               $depth Depth of menu item.
	 * @return string
	 */
	public function add_caret( $title, $item, $args, $depth ) {
		if ( 3 === $args->bs_version ) {
			if ( $item->has_children && 0 === $depth && 1 !== $args->depth ) {
				$title .= ' <span class="caret"></span>';
			}
		}
		return $title;
	}

	/**
	 * Set the arguments for the navigation menu.
	 *
	 * @access public
	 *
	 * @since 5.0.0
	 *
	 * @param array $args An array of `wp_nav_menu()` arguments.
	 * @return array
	 */
	public function set_nav_menu_args( $args ) {
		$args['bs_version']       = Setup_Args::bs_version( $args );
		$args['drop_wp_classes']  = Setup_Args::drop_wp_classes( $args );
		$args['clickable']        = Setup_Args::is_clickable( $args );
		$args['icon_regex']       = Setup_Args::get_icon_regex( $args );
		$args['ancestors_active'] = Setup_Args::ancestors_active( $args );
		return $args;
	}

}
