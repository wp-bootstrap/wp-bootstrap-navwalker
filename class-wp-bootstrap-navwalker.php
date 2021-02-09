<?php
/**
 * WP Bootstrap Navwalker
 *
 * @package           WP-Bootstrap-Navwalker
 * @author            Edward McIntyre, William Patton
 * @license           GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       WP Bootstrap Navwalker
 * Plugin URI:        https://github.com/wp-bootstrap/wp-bootstrap-navwalker
 * Description:       A custom WordPress nav walker class to implement the Bootstrap navigation style in a custom theme using the WordPress built in menu manager.
 * Author:            Edward McIntyre - @twittem, WP Bootstrap, William Patton - @pattonwebz
 * Author URI:        https://github.com/wp-bootstrap
 * Version:           5.0.0
 * Requires at least: X.X.X
 * Tested up to:      5.6
 * Requires PHP:      5.6
 * GitHub Plugin URI: https://github.com/wp-bootstrap/wp-bootstrap-navwalker
 * GitHub Branch:     master
 * License:           GPL-3.0-or-later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 */

namespace WP_Bootstrap\Navwalker;

/**
 * Class to load and init the Navwalker classes.
 */
class Plugin {

	/**
	 * The slug used for prefixing hooks.
	 *
	 * @since 5.0.0
	 *
	 * @var string
	 */
	const SLUG = 'wp_bootstrap_navwalker';

	/**
	 * Instantiate the object.
	 *
	 * @access public
	 *
	 * @since 5.0.0
	 */
	public function __construct() {
		$this->includes();
		$this->add_filters();
		$this->add_inline_style_script();
	}

	/**
	 * Include classes.
	 *
	 * @access private
	 *
	 * @since 5.0.0
	 */
	private function includes() {
		$dir = __DIR__ . '/wp-bootstrap-navwalker/';
		require_once $dir . 'class-fallback.php';
		require_once $dir . 'class-filters.php';
		require_once $dir . 'class-setup-args.php';
		require_once $dir . 'class-setup-items.php';
		require_once $dir . 'class-utils.php';
		require_once $dir . 'class-walker.php';
	}

	/**
	 * Add filters used to add Bootstrap markup.
	 *
	 * @access private
	 *
	 * @since 5.0.0
	 */
	private function add_filters() {
		$filters = new Filters();
	}

	/**
	 * Add inline CSS and script for multilevel dropdown support.
	 */
	public function add_inline_style_script() {
		add_action( 'wp_head', __NAMESPACE__ . '\Utils::add_style' );
		add_action( 'wp_footer', __NAMESPACE__ . '\Utils::add_script' );
	}
}

$navwalker = new Plugin();
