<?php
/**
 * Value object for nav menu item objects.
 *
 * Created to aid static analysis by PHPStan.
 *
 * @package WordPress
 * @see wp_setup_nav_menu_item()
 */

/**
 * Decorates a menu item (WP_Post) object with the shared navigation menu item properties.
 */
class WP_Nav_Menu_Item {

	/**
	 * The term_id if the menu item represents a taxonomy term.
	 *
	 * @overrides WP_Post
	 * @var int
	 */
	public $ID;

	/**
	 * The title attribute of the link element for this menu item.
	 *
	 * @var string
	 */
	public $attr_title;

	/**
	 * The array of class attribute values for the link element of this menu item.
	 *
	 * @var array
	 */
	public $classes;

	/**
	 * The DB ID of this item as a nav_menu_item object, if it exists (0 if it doesn't exist).
	 *
	 * @var int
	 */
	public $db_id;

	/**
	 * The description of this menu item.
	 *
	 * @var string
	 */
	public $description;

	/**
	 * The DB ID of the nav_menu_item that is this item's menu parent, if any. 0 otherwise.
	 *
	 * @var int
	 */
	public $menu_item_parent;

	/**
	 * The type of object originally represented, such as "category," "post", or "attachment."
	 *
	 * @var string
	 */
	public $object;

	/**
	 * The DB ID of the original object this menu item represents,
	 * e.g. ID for posts and term_id for categories.
	 *
	 * @var int
	 */
	public $object_id;

	/**
	 * The DB ID of the original object's parent object, if any (0 otherwise).
	 *
	 * @overrides WP_Post
	 * @var int
	 */
	public $post_parent;

	/**
	 * A "no title" label if menu item represents a post that lacks a title.
	 *
	 * @overrides WP_Post
	 * @var string
	 */
	public $post_title;

	/**
	 * The target attribute of the link element for this menu item.
	 *
	 * @var string
	 */
	public $target;

	/**
	 * The title of this menu item.
	 *
	 * @var string
	 */
	public $title;

	/**
	 * The family of objects originally represented, such as "post_type" or "taxonomy."
	 *
	 * @var string
	 */
	public $type;

	/**
	 * The singular label used to describe this type of menu item.
	 *
	 * @var string
	 */
	public $type_label;

	/**
	 * The URL to which this menu item points.
	 *
	 * @var string
	 */
	public $url;

	/**
	 * The XFN relationship expressed in the link of this menu item.
	 *
	 * @var string
	 */
	public $xfn;

	/**
	 * Whether the menu item represents an object that no longer exists.
	 *
	 * @var bool
	 */
	public $_invalid; // phpcs:ignore PSR2.Classes.PropertyDeclaration.Underscore

	/**
	 * Whether the menu item represents the active menu item.
	 *
	 * @var bool
	 */
	public $current;

	/**
	 * Whether the menu item represents an parent menu item.
	 *
	 * @var bool
	 */
	public $current_item_parent;

	/**
	 * Whether the menu item represents an ancestor menu item.
	 *
	 * @var bool
	 */
	public $current_item_ancestor;

	/* Copy of WP_Post */

	/**
	 * ID of post author.
	 *
	 * A numeric string, for compatibility reasons.
	 *
	 * @var string
	 */
	public $post_author = 0;

	/**
	 * The post's local publication time.
	 *
	 * @var string
	 */
	public $post_date = '0000-00-00 00:00:00';

	/**
	 * The post's GMT publication time.
	 *
	 * @var string
	 */
	public $post_date_gmt = '0000-00-00 00:00:00';

	/**
	 * The post's content.
	 *
	 * @var string
	 */
	public $post_content = '';

	/**
	 * The post's excerpt.
	 *
	 * @var string
	 */
	public $post_excerpt = '';

	/**
	 * The post's status.
	 *
	 * @var string
	 */
	public $post_status = 'publish';

	/**
	 * Whether comments are allowed.
	 *
	 * @var string
	 */
	public $comment_status = 'open';

	/**
	 * Whether pings are allowed.
	 *
	 * @var string
	 */
	public $ping_status = 'open';

	/**
	 * The post's password in plain text.
	 *
	 * @var string
	 */
	public $post_password = '';

	/**
	 * The post's slug.
	 *
	 * @var string
	 */
	public $post_name = '';

	/**
	 * URLs queued to be pinged.
	 *
	 * @var string
	 */
	public $to_ping = '';

	/**
	 * URLs that have been pinged.
	 *
	 * @var string
	 */
	public $pinged = '';

	/**
	 * The post's local modified time.
	 *
	 * @var string
	 */
	public $post_modified = '0000-00-00 00:00:00';

	/**
	 * The post's GMT modified time.
	 *
	 * @var string
	 */
	public $post_modified_gmt = '0000-00-00 00:00:00';

	/**
	 * A utility DB field for post content.
	 *
	 * @var string
	 */
	public $post_content_filtered = '';

	/**
	 * The unique identifier for a post, not necessarily a URL, used as the feed GUID.
	 *
	 * @var string
	 */
	public $guid = '';

	/**
	 * A field used for ordering posts.
	 *
	 * @var int
	 */
	public $menu_order = 0;

	/**
	 * The post's type, like post or page.
	 *
	 * @var string
	 */
	public $post_type = 'post';

	/**
	 * An attachment's mime type.
	 *
	 * @var string
	 */
	public $post_mime_type = '';

	/**
	 * Cached comment count.
	 *
	 * A numeric string, for compatibility reasons.
	 *
	 * @var string
	 */
	public $comment_count = 0;

	/**
	 * Stores the post object's sanitization level.
	 *
	 * Does not correspond to a DB field.
	 *
	 * @var string
	 */
	public $filter;

	/**
	 * Whether the menu item has children.
	 *
	 * Custom WP Bootstrap Navwalker property.
	 *
	 * @var bool
	 */
	public $has_children;

	/**
	 * Whether the menu item is disabled.
	 *
	 * Custom WP Bootstrap Navwalker property.
	 *
	 * @var bool
	 */
	public $is_disabled;

	/**
	 * Whether to display the menu item's title to screen readers only.
	 *
	 * Custom WP Bootstrap Navwalker property.
	 *
	 * @var bool
	 */
	public $is_sr_only;

	/**
	 * Whether to display the menu item as a dropdown header.
	 *
	 * Custom WP Bootstrap Navwalker property.
	 *
	 * @var bool
	 */
	public $is_dropdown_header;

	/**
	 * Whether to display the menu item as a dropdown divider.
	 *
	 * Custom WP Bootstrap Navwalker property.
	 *
	 * @var bool
	 */
	public $is_dropdown_divider;

	/**
	 * Whether to display the menu item as text-onyl dropdown item.
	 *
	 * Custom WP Bootstrap Navwalker property.
	 *
	 * @var bool
	 */
	public $is_dropdown_item_text;

	/**
	 * Whether to display the menu item as dropdown header/divider
	 * or text-onyl dropdown item.
	 *
	 * Custom WP Bootstrap Navwalker property.
	 *
	 * @var bool
	 */
	public $is_dropdown_menu_content;

	/**
	 * Whether to display the dropdown toggle as split button.
	 * `$is_dropdown_toggle_split` is item-specific.
	 *
	 * Custom WP Bootstrap Navwalker property.
	 *
	 * @var bool
	 */
	public $is_dropdown_toggle_split;

	/**
	 * Whether to display the dropdown toggle as split button.
	 *
	 * Custom WP Bootstrap Navwalker property.
	 *
	 * Merges the item-specific `$is_dropdown_toggle_split` and the
	 * `wp_nav_menu()` argument to display all dropdown toggles as split buttons.
	 *
	 * @var bool
	 */
	public $has_clickable_link;

	/**
	 * Array of icon classes to use on the menu item.
	 *
	 * Custom WP Bootstrap Navwalker property.
	 *
	 * @var bool|array False if no icon classes are provided.
	 */
	public $icon_classes;

	/**
	 * Array of classes to use on the menu item's anchor tag.
	 *
	 * Custom WP Bootstrap Navwalker property.
	 *
	 * @var bool|array False if no icon classes are provided.
	 */
	public $anchor_classes;

	/**
	 * Array of classes to use on the menu item's dropdown menu ul tag.
	 *
	 * Custom WP Bootstrap Navwalker property.
	 *
	 * @var bool|array False if no icon classes are provided.
	 */
	public $dropdown_menu_classes;

	/**
	 * Array of native WP classes to use on the menu item.
	 *
	 * Custom WP Bootstrap Navwalker property.
	 *
	 * @var false|array
	 */
	public $native_classes;

	/**
	 * Array of all custom classes provided by the user.
	 *
	 * Custom WP Bootstrap Navwalker property.
	 *
	 * @var bool|array False if no icon classes are provided.
	 */
	public $custom_classes;

	/**
	 * Whether to append the icon to the menu item's title.
	 *
	 * Custom WP Bootstrap Navwalker property.
	 *
	 * @var null|bool
	 */
	public $icon_append;
}
