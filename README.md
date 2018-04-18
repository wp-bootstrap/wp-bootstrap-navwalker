# WP Bootstrap Navwalker

[![Code Climate](https://codeclimate.com/github/wp-bootstrap/wp-bootstrap-navwalker/badges/gpa.svg)](https://codeclimate.com/github/wp-bootstrap/wp-bootstrap-navwalker)
[![Test Coverage](https://codeclimate.com/github/wp-bootstrap/wp-bootstrap-navwalker/badges/coverage.svg)](https://codeclimate.com/github/wp-bootstrap/wp-bootstrap-navwalker/coverage)
[![Issue Count](https://codeclimate.com/github/wp-bootstrap/wp-bootstrap-navwalker/badges/issue_count.svg)](https://codeclimate.com/github/wp-bootstrap/wp-bootstrap-navwalker)
[![Build Status](https://travis-ci.org/wp-bootstrap/wp-bootstrap-navwalker.svg?branch=master)](https://travis-ci.org/wp-bootstrap/wp-bootstrap-navwalker)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/wp-bootstrap/wp-bootstrap-navwalker/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/wp-bootstrap/wp-bootstrap-navwalker/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/wp-bootstrap/wp-bootstrap-navwalker/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/wp-bootstrap/wp-bootstrap-navwalker/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/wp-bootstrap/wp-bootstrap-navwalker/badges/build.png?b=master)](https://scrutinizer-ci.com/g/wp-bootstrap/wp-bootstrap-navwalker/build-status/master)

A custom WordPress Nav Walker Class to fully implement the Bootstrap 4 navigation style in a custom theme using the WordPress built in menu manager.

## NOTES

This is a utility class that is intended to format your WordPress theme menu with the correct syntax and CSS classes to utilize the Bootstrap dropdown navigation. It does not include the required Bootstrap JS and CSS files - you will have to include them manually.

### WordPress.org Theme Compliance

*This walker is fully compliant with all Theme Review guidelines for wordpress.org theme submission.* It requires no modification to be compliant but you can optionally replace the `wp-bootstrap-navwalker` text domain (which appears twice in the `fallback` function) with the text domain of your theme.

### UPGRADE NOTES ###

Between version 3 and version 4 of the walker there have been significant changes to the codebase. Version 4 of the walker is built to work with Bootstrap 4 and has not been tested for backwards compatibility with Bootstrap 3. A separate branch for Bootstrap 3 is maintained here: https://github.com/wp-bootstrap/wp-bootstrap-navwalker/tree/v3-branch

Here is a list of the most notable changes:

- The filename has been changed and prefixed with `class-` to better fit PHP coding standards naming conventions.
	- New Name: `class-wp-bootstrap-navwalker.php`
	- Old Name: `wp-bootstrap-navwalker.php`
- Icon and link modifier handling is now done through the `CSS Classes` menu item input instead of the `Title` input.
- Icon only items are possible using icon classes in combination with the `sr-only` classname.

## Installation

Place **class-wp-bootstrap-navwalker.php** in your WordPress theme folder `/wp-content/your-theme/`

Open your WordPress themes **functions.php** file - `/wp-content/your-theme/functions.php` - and add the following code:

```php
// Register Custom Navigation Walker
require_once get_template_directory() . '/class-wp-bootstrap-navwalker.php';
```

If you encounter errors with the above code use a check like this to return clean errors to help diagnose the problem.

```php
if ( ! file_exists( get_template_directory() . '/class-wp-bootstrap-navwalker.php' ) ) {
	// file does not exist... return an error.
	return new WP_Error( 'class-wp-bootstrap-navwalker-missing', __( 'It appears the class-wp-bootstrap-navwalker.php file may be missing.', 'wp-bootstrap-navwalker' ) );
} else {
	// file exists... require it.
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
	'depth'	          => 2, // 1 = no dropdowns, 2 = with dropdowns.
	'container'       => 'div',
	'container_class' => 'collapse navbar-collapse',
	'container_id'    => 'bs-example-navbar-collapse-1',
	'menu_class'      => 'navbar-nav mr-auto',
	'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
	'walker'          => new WP_Bootstrap_Navwalker(),
) );
```

Your menu will now be formatted with the correct syntax and classes to implement Bootstrap dropdown navigation.

Typically the menu is wrapped with additional markup, here is an example of a ` fixed-top` menu that collapse for responsive navigation at the md breakpoint.

```php
<nav class="navbar navbar-expand-md navbar-light bg-light" role="navigation">
  <div class="container">
	<!-- Brand and toggle get grouped for better mobile display -->
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-controls="bs-example-navbar-collapse-1" aria-expanded="false" aria-label="Toggle navigation">
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

### Making this Walker the Default Walker for Nav Manus

There has been some interest in making this walker the default walker for all menus. That could result in some unexpected situations but it can be achieved by adding this function to your functions.php file.

```php
function prefix_modify_nav_menu_args( $args ) {
	return array_merge( $args, array(
		'walker' => WP_Bootstrap_Navwalker(),
	) );
}
add_filter( 'wp_nav_menu_args', 'prefix_modify_nav_menu_args' );
```
Simply updating the walker may not be enough to get menus working right, you may need to add wrappers or additional classes, you can do that via the above function as well.

### Menu Caching

On some sites generating a large menu that rarely ever changes on every page request is an overhead that you may want to avoid. In those cases I can suggest you look at storing menu results in a transient.

The biggest drawback to caching nav menus with this method is that it cannot easily apply the `.current-menu-item` or the `.active` class to the currently active item as WP decides what is currently active on page load - and since the menu is cached it only knows the active page that it was cached on originally.

You can decide yourself if you want to put up with those drawbacks for the benefit of removing the menu generation time on most page loads. You can follow this article by Dave Clements to see how we cached nav menus that were generated by this walker: https://www.doitwithwp.com/use-transients-speed-wordpress-menus/

Be sure to set the `echo` argument to FALSE in `the wp_nav_menu()` call when doing this so that the results can be stored instead of echoed to the page.

See also:
- https://generatewp.com/how-to-use-transients-to-speed-up-wordpress-menus/
- https://vip-svn.wordpress.com/plugins/cache-nav-menu/cache-nav-menu.php

### Extras

This script included the ability to use Bootstrap nav link mods in your menus through the WordPress menu UI. Disabled links, dropdown headers and dropdown dividers are supported. Additionally icon support is built-in for Glyphicons and Font Awesome (note: you will need to include the icon stylesheets or assets separately).

#### Icons

To add an Icon to your link simply enter Glypicons or Font Awesome class names in the links **CSS Classes** field in the Menu UI and the walker class will do the rest. IE `glyphicons glyphicons-bullhorn` or `fa fa-arrow-left` or `fas fa-arrow-left`.

#### Icon-Only Items

To make an item appear with the icon only apply the bootstrap screen reader class `sr-only` to the item alongside any icon classnames. This will then hide only the text that would appear as the link text.

#### Disabled Links

To set a disabled link simply add `disabled` to the **CSS Classes** field in the Menu UI and the walker class will do the rest. _Note: In addition to adding the .disabled class this will change the link `href` to `#` as well so that it is not a followable link._

#### Dropdown Headers, Dropdown Dividers & Dropdown Item Text.

Headers, dividers and text only items can be added within dropdowns by adding a Custom Link and adding either `dropdown-header`, `dropdown-divider` or `dropdown-item-text` into the **CSS Classes** input. _Note: This will remove the `href` on the item and change it to either a `<span>` for headers or a `<div>` for dividers._

## Changelog

Please see the [Changelog](https://github.com/wp-bootstrap/wp-bootstrap-navwalker/blob/master/CHANGELOG.md).
