# WP Bootstrap Navwalker

[![Code Climate](https://codeclimate.com/github/wp-bootstrap/wp-bootstrap-navwalker/badges/gpa.svg)](https://codeclimate.com/github/wp-bootstrap/wp-bootstrap-navwalker)
[![Test Coverage](https://codeclimate.com/github/wp-bootstrap/wp-bootstrap-navwalker/badges/coverage.svg)](https://codeclimate.com/github/wp-bootstrap/wp-bootstrap-navwalker/coverage)
[![Issue Count](https://codeclimate.com/github/wp-bootstrap/wp-bootstrap-navwalker/badges/issue_count.svg)](https://codeclimate.com/github/wp-bootstrap/wp-bootstrap-navwalker)
[![Build Status](https://travis-ci.org/wp-bootstrap/wp-bootstrap-navwalker.svg?branch=master)](https://travis-ci.org/wp-bootstrap/wp-bootstrap-navwalker)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/wp-bootstrap/wp-bootstrap-navwalker/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/wp-bootstrap/wp-bootstrap-navwalker/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/wp-bootstrap/wp-bootstrap-navwalker/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/wp-bootstrap/wp-bootstrap-navwalker/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/wp-bootstrap/wp-bootstrap-navwalker/badges/build.png?b=master)](https://scrutinizer-ci.com/g/wp-bootstrap/wp-bootstrap-navwalker/build-status/master)

**This code in the main repo branch is undergoing a big shakeup to bring it in line with recent standards and to merge and test the backlog of PRs I have left for too long. Please use the v4.3.0 tag for stable version while this process happens. https://github.com/wp-bootstrap/wp-bootstrap-navwalker/releases/tag/v4.3.0**

A custom WordPress Nav Walker Class to fully implement the Bootstrap 4 navigation style in a custom theme using the WordPress built in menu manager.

## NOTES

This is a utility class that is intended to format your WordPress theme menu with the correct syntax and CSS classes to utilize the Bootstrap dropdown navigation. It does not include the required Bootstrap JS and CSS files - you will have to include them manually.

### WordPress.org Theme Compliance

*This walker is fully compliant with all Theme Review guidelines for wordpress.org theme submission.* It requires no modification to be compliant but you can optionally replace the `wp-bootstrap-navwalker` text domain (which appears twice in the `fallback` function) with the text domain of your theme.

### Upgrade Notes

Between version 3 and version 4 of the walker there have been significant changes to the codebase. Version 4 of the walker is built to work with Bootstrap 4 and has not been tested for backwards compatibility with Bootstrap 3. A separate branch for Bootstrap 3 is maintained here: <https://github.com/wp-bootstrap/wp-bootstrap-navwalker/tree/v3-branch>

Here is a list of the most notable changes:

- The filename has been changed and prefixed with `class-` to better fit PHP coding standards naming conventions.
  - New Name: `class-wp-bootstrap-navwalker.php`
  - Old Name: `wp-bootstrap-navwalker.php`
- Icon and link modifier handling is now done through the `CSS Classes` menu item input instead of the `Title` input.
- Icon only items are possible using icon classes in combination with the `sr-only` classname.

## Installation

Place **class-wp-bootstrap-navwalker.php** in your WordPress theme folder `/wp-content/themes/your-theme/`

Open your WordPress themes **functions.php** file - `/wp-content/themes/your-theme/functions.php` - and add the following code:

```php
/**
 * Register Custom Navigation Walker
 */
function register_navwalker(){
	require_once get_template_directory() . '/class-wp-bootstrap-navwalker.php';
}
add_action( 'after_setup_theme', 'register_navwalker' );
```

If you encounter errors with the above code use a check like this to return clean errors to help diagnose the problem.

```php
if ( !file_exists( get_template_directory() . '/class-wp-bootstrap-navwalker.php' ) ) {
	// file does not exist, return an error.
	function our_error() {
        	return new WP_Error( 'broke', __( 'class-wp-bootstrap-navwalker.php file may be missing', 'wp-bootstrap-navwalker' ) );
    	}
	$return = our_error();
    if( is_wp_error( $return ) ) {
        echo $return->get_error_message();
    }
} else {
	// file exists, require it.
	require_once get_template_directory() . '/class-wp-bootstrap-navwalker.php';
}
```

You will also need to declare a new menu in your `functions.php` file if one doesn't already exist.

```php
register_nav_menus( array(
    'primary' => __( 'Primary Menu', 'THEMENAME' ),
) );
```

## Usage

Add or update any `wp_nav_menu()` functions in your theme (often found in `header.php`) to use the new walker by adding a `'walker'` item to the wp_nav_menu args array.

```php
wp_nav_menu( array(
    'theme_location'  => 'primary',
    'depth'           => 2, // 1 = no dropdowns, 2 = with dropdowns.
    'container'       => 'div',
    'container_class' => 'collapse navbar-collapse',
    'container_id'    => 'bs-example-navbar-collapse-1',
    'menu_class'      => 'navbar-nav mr-auto',
    'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
    'walker'          => new WP_Bootstrap_Navwalker(),
) );
```

Your menu will now be formatted with the correct syntax and classes to implement Bootstrap dropdown navigation.

Typically the menu is wrapped with additional markup, here is an example of a `fixed-top` menu that collapse for responsive navigation at the md breakpoint.

```php
<nav class="navbar navbar-expand-md navbar-light bg-light" role="navigation">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-controls="bs-example-navbar-collapse-1" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'your-theme-slug' ); ?>">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="#">Navbar</a>
        <?php
        wp_nav_menu( array(
            'theme_location'    => 'primary',
            'depth'             => 2,
            'container'         => 'div',
            'container_class'   => 'collapse navbar-collapse',
            'container_id'      => 'bs-example-navbar-collapse-1',
            'menu_class'        => 'nav navbar-nav',
            'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
            'walker'            => new WP_Bootstrap_Navwalker(),
        ) );
        ?>
    </div>
</nav>
```

To change your menu style add Bootstrap nav class names to the `menu_class` declaration.

Review options in the Bootstrap docs for more information on [nav classes](https://getbootstrap.com/components/#nav).

### Displaying the Menu

To display the menu you must associate your menu with your theme location. You can do this by selecting your theme location in the *Theme Locations* list while editing a menu in the WordPress menu manager.

### Making this Walker the Default Walker for Nav Menus

There has been some interest in making this walker the default walker for all menus. That could result in some unexpected situations but it can be achieved by adding this function to your functions.php file.

```php
function prefix_modify_nav_menu_args( $args ) {
    return array_merge( $args, array(
        'walker' => new WP_Bootstrap_Navwalker(),
    ) );
}
add_filter( 'wp_nav_menu_args', 'prefix_modify_nav_menu_args' );
```

Simply updating the walker may not be enough to get menus working right, you may need to add wrappers or additional classes, you can do that via the above function as well.

### Usage with Bootstrap 5

Bootstrap 5 uses namespaced data attributes. All `data` attributes now include `bs` as an infix. The new attributes work just like the old ones. Here’s the menu toggle button from the example above with the renamed data attributes.

```php
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#bs-example-navbar-collapse-1" aria-controls="bs-example-navbar-collapse-1" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'your-theme-slug' ); ?>">
    <span class="navbar-toggler-icon"></span>
</button>
```

The walker also adds a data attribute for dropdown toggles via the `start_el()` method. Paste this to your functions.php to make the walker use the infixed data attibute.

```php
add_filter( 'nav_menu_link_attributes', 'prefix_bs5_dropdown_data_attribute', 20, 3 );
/**
 * Use namespaced data attribute for Bootstrap's dropdown toggles.
 *
 * @param array    $atts HTML attributes applied to the item's `<a>` element.
 * @param WP_Post  $item The current menu item.
 * @param stdClass $args An object of wp_nav_menu() arguments.
 * @return array
 */
function prefix_bs5_dropdown_data_attribute( $atts, $item, $args ) {
    if ( is_a( $args->walker, 'WP_Bootstrap_Navwalker' ) ) {
        if ( array_key_exists( 'data-toggle', $atts ) ) {
            unset( $atts['data-toggle'] );
            $atts['data-bs-toggle'] = 'dropdown';
        }
    }
    return $atts;
}
```

### Menu Caching

On some sites generating a large menu that rarely ever changes on every page request is an overhead that you may want to avoid. In those cases I can suggest you look at storing menu results in a transient.

The biggest drawback to caching nav menus with this method is that it cannot easily apply the `.current-menu-item` or the `.active` class to the currently active item as WP decides what is currently active on page load - and since the menu is cached it only knows the active page that it was cached on originally.

You can decide yourself if you want to put up with those drawbacks for the benefit of removing the menu generation time on most page loads. You can follow this article by Dave Clements to see how we cached nav menus that were generated by this walker: <https://www.doitwithwp.com/use-transients-speed-wordpress-menus/>

Be sure to set the `echo` argument to FALSE in `the wp_nav_menu()` call when doing this so that the results can be stored instead of echoed to the page.

See also:

- <https://generatewp.com/how-to-use-transients-to-speed-up-wordpress-menus/>
- <https://vip-svn.wordpress.com/plugins/cache-nav-menu/cache-nav-menu.php>

### Extras

This script included the ability to use Bootstrap nav link mods in your menus through the WordPress menu UI. Disabled links, dropdown headers and dropdown dividers are supported. Additionally icon support is built-in for Glyphicons and Font Awesome (note: you will need to include the icon stylesheets or assets separately).

#### Icons

To add an Icon to your link simply enter Glyphicons or Font Awesome class names in the links **CSS Classes** field in the Menu UI and the walker class will do the rest. IE `glyphicons glyphicons-bullhorn` or `fa fa-arrow-left` or `fas fa-arrow-left`.

#### Icon-Only Items

To make an item appear with the icon only apply the bootstrap screen reader class `sr-only` to the item alongside any icon classnames. This will then hide only the text that would appear as the link text.

#### Disabled Links

To set a disabled link simply add `disabled` to the **CSS Classes** field in the Menu UI and the walker class will do the rest. _Note: In addition to adding the .disabled class this will change the link `href` to `#` as well so that it is not a follow-able link._

#### Dropdown Headers, Dropdown Dividers & Dropdown Item Text

Headers, dividers and text only items can be added within dropdowns by adding a Custom Link and adding either `dropdown-header`, `dropdown-divider` or `dropdown-item-text` into the **CSS Classes** input. _Note: This will remove the `href` on the item and change it to either a `<span>` for headers or a `<div>` for dividers._

### Missing Edit Shortcut in Customizer Preview

According to the documentation for [`wp_nav_menu()`](https://developer.wordpress.org/reference/functions/wp_nav_menu/) one has to provide an instance of the custom walker class in order to apply the custom walker to the menu. As the instance is not [JSON serializable](https://make.wordpress.org/core/2015/07/29/fast-previewing-changes-to-menus-in-the-customizer/) this will cause the menu edit shortcut to not appear in the Customizer preview. To fix this do the following:
1. Provide the class name string instead of the class instance as value for the 'walker' key in the array of wp_nav_menu's arguments,
```diff
wp_nav_menu( array(
    'theme_location'  => 'primary',
    'depth'           => 2, // 1 = no dropdowns, 2 = with dropdowns.
    'container'       => 'div',
    'container_class' => 'collapse navbar-collapse',
    'container_id'    => 'bs-example-navbar-collapse-1',
    'menu_class'      => 'navbar-nav mr-auto',
    'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
-    'walker'          => new WP_Bootstrap_Navwalker(),
+    'walker'          => 'WP_Bootstrap_Navwalker',
) );
```
2. re-add the class instance by adding this filter to your `functions.php`
```php
function slug_provide_walker_instance( $args ) {
    if ( isset( $args['walker'] ) && is_string( $args['walker'] ) && class_exists( $args['walker'] ) ) {
        $args['walker'] = new $args['walker'];
    }
    return $args;
}
add_filter( 'wp_nav_menu_args', 'slug_provide_walker_instance', 1001 );
```

## Changelog

Please see the [Changelog](https://github.com/wp-bootstrap/wp-bootstrap-navwalker/blob/master/CHANGELOG.md).
