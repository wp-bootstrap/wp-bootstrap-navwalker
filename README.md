# WP Bootstrap Navwalker

[![Code Climate](https://codeclimate.com/github/wp-bootstrap/wp-bootstrap-navwalker/badges/gpa.svg)](https://codeclimate.com/github/wp-bootstrap/wp-bootstrap-navwalker)
[![Test Coverage](https://codeclimate.com/github/wp-bootstrap/wp-bootstrap-navwalker/badges/coverage.svg)](https://codeclimate.com/github/wp-bootstrap/wp-bootstrap-navwalker/coverage)
[![Issue Count](https://codeclimate.com/github/wp-bootstrap/wp-bootstrap-navwalker/badges/issue_count.svg)](https://codeclimate.com/github/wp-bootstrap/wp-bootstrap-navwalker)
[![Build Status](https://travis-ci.org/wp-bootstrap/wp-bootstrap-navwalker.svg?branch=master)](https://travis-ci.org/wp-bootstrap/wp-bootstrap-navwalker)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/wp-bootstrap/wp-bootstrap-navwalker/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/wp-bootstrap/wp-bootstrap-navwalker/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/wp-bootstrap/wp-bootstrap-navwalker/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/wp-bootstrap/wp-bootstrap-navwalker/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/wp-bootstrap/wp-bootstrap-navwalker/badges/build.png?b=master)](https://scrutinizer-ci.com/g/wp-bootstrap/wp-bootstrap-navwalker/build-status/master)

A custom WordPress nav walker class to fully implement the Bootstrap 4.0+ navigation style in a custom theme using the WordPress built in menu manager.

## NOTES

This is a utility class that is intended to format your WordPress theme menu with the correct syntax and classes to utilize the Bootstrap dropdown navigation, and does not include the required Bootstrap JS files. You will have to include them manually.

## Installation

Place **class-wp-bootstrap-navwalker.php** in your WordPress theme folder `/wp-content/your-theme/`

Open your WordPress themes **functions.php** file  `/wp-content/your-theme/functions.php` and add the following code:

```php
// Register Custom Navigation Walker
require_once('class-wp-bootstrap-navwalker.php');
```

## Usage

Update your `wp_nav_menu()` function in `header.php` to use the new walker by adding a "walker" item to the wp_nav_menu array.

```php
<?php
wp_nav_menu( array(
    'theme_location'	=> 'primary',
    'depth'				=> 2,
	'container'			=> 'div',
	'container_class'	=> 'collapse navbar-collapse',
	'container_id'		=> 'bs-example-navbar-collapse-1',
	'menu_class'		=> 'navbar-nav mr-auto',
    'fallback_cb'		=> 'WP_Bootstrap_Navwalker::fallback',
    'walker'			=> new WP_Bootstrap_Navwalker())
);
?>
```

Your menu will now be formatted with the correct syntax and classes to implement Bootstrap dropdown navigation.

You will also want to declare your new menu in your `functions.php` file.

```php
	register_nav_menus( array(
    	'primary' => __( 'Primary Menu', 'THEMENAME' ),
	) );
```

Typically the menu is wrapped with additional markup, here is an example of a ` fixed-top` menu that collapse for responsive navigation at the md breakpoint.

```php
<nav class="navbar fixed-top navbar-toggleable-md navbar-light bg-faded" role="navigation">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
	<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-controls="bs-example-navbar-collapse-1" aria-expanded="false" aria-label="Toggle navigation">
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
            'walker'            => new WP_Bootstrap_Navwalker())
        );
        ?>
    </div>
</nav>
```

To change your menu style add Bootstrap nav class names to the `menu_class` declaration.

Review options in the Bootstrap docs for more information on [nav classes](https://getbootstrap.com/components/#nav).


### Displaying the Menu

To display the menu you must associate your menu with your theme location. You can do this by selecting your theme location in the *Theme Locations* list wile editing a menu in the WordPress menu manager.

### Extras

This script included the ability to use Bootstrap nav link mods in your menus through the WordPress menu UI. Currently only disabled links are supported. Additionally icon support is built-in for Glyphicons and font Awesome (note: you will need to include the icon stylesheets or assets separately)

#### Icons

To add an Icon to your link simpley enter Glypicons or Font Awesome class names in the links **Classes** field in the Menu UI and the walker class will do the rest. IE `glyphicons glyphicons-bullhorn` or `fa fa-arrow-left`.

#### Disabled Links

To set a disabled link simply add `disabled` to the **Classes** field in the Menu UI and the walker class will do the rest. Note: _In addition to adding the .disabled class this will change the link `href` to `#` as well so that it is not a followable link._

### Changelog

Please see the [Changelog](https://github.com/wp-bootstrap/wp-bootstrap-navwalker/blob/master/CHANGELOG.md).
