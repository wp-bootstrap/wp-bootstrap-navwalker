<?php
/**
 * Data object for displaying navigation menu.
 * Created to aid static analysis.
 *
 * @package WordPress
 * @see wp_nav_menu()
 */

/**
 * Nav menu arguments.
 */
class WP_Nav_Menu_Args {

	/**
	 * Desired menu. Accepts a menu ID, slug, name, or object. Default empty.
	 *
	 * @var int|string|\WP_Term
	 */
	public $menu;

	/**
	 * CSS class to use for the ul element which forms the menu. Default 'menu'.
	 *
	 * @var string
	 */
	public $menu_class;

	/**
	 * The ID that is applied to the ul element which forms the menu.
	 * Default is the menu slug, incremented.
	 *
	 * @var string
	 */
	public $menu_id;

	/**
	 * Whether to wrap the ul, and what to wrap it with. Default 'div'.
	 *
	 * @var string
	 */
	public $container;

	/**
	 * Class that is applied to the container. Default 'menu-{menu slug}-container'.
	 *
	 * @var string
	 */
	public $container_class;

	/**
	 * The ID that is applied to the container. Default empty.
	 *
	 * @var string
	 */
	public $container_id;

	/**
	 * If the menu doesn't exists, a callback function will fire.
	 * Default is 'wp_page_menu'. Set to false for no fallback.
	 *
	 * @var callable|bool
	 */
	public $fallback_cb;

	/**
	 * Text before the link markup. Default empty.
	 *
	 * @var string
	 */
	public $before;

	/**
	 * Text after the link markup. Default empty.
	 *
	 * @var string
	 */
	public $after;

	/**
	 * Text before the link text. Default empty.
	 *
	 * @var string
	 */
	public $link_before;

	/**
	 * Text after the link text. Default empty.
	 *
	 * @var string
	 */
	public $link_after;

	/**
	 * Whether to echo the menu or return it. Default true.
	 *
	 * @var bool
	 */
	public $echo;

	/**
	 * How many levels of the hierarchy are to be included. 0 means all.
	 * Default 0.
	 *
	 * @var int
	 */
	public $depth;

	/**
	 * Instance of a custom walker class. Default empty.
	 *
	 * @var \Walker_Nav_Menu
	 */
	public $walker;

	/**
	 * Theme location to be used. Must be registered with register_nav_menu()
	 * in order to be selectable by the user.
	 *
	 * @var string
	 */
	public $theme_location;

	/**
	 * How the list items should be wrapped. Default is a ul with an id and class.
	 * Uses printf() format with numbered placeholders.
	 *
	 * @var string
	 */
	public $items_wrap;

	/**
	 * Whether to preserve whitespace within the menu's HTML. Accepts 'preserve' or 'discard'.
	 * Default 'preserve'.
	 *
	 * @var string
	 */
	public $item_spacing;
}
