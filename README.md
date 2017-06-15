# WP Bootstrap Navwalker

[![Code Climate](https://codeclimate.com/github/wp-bootstrap/wp-bootstrap-navwalker/badges/gpa.svg)](https://codeclimate.com/github/wp-bootstrap/wp-bootstrap-navwalker)
[![Test Coverage](https://codeclimate.com/github/wp-bootstrap/wp-bootstrap-navwalker/badges/coverage.svg)](https://codeclimate.com/github/wp-bootstrap/wp-bootstrap-navwalker/coverage)
[![Issue Count](https://codeclimate.com/github/wp-bootstrap/wp-bootstrap-navwalker/badges/issue_count.svg)](https://codeclimate.com/github/wp-bootstrap/wp-bootstrap-navwalker)
[![Build Status](https://travis-ci.org/wp-bootstrap/wp-bootstrap-navwalker.svg?branch=master)](https://travis-ci.org/wp-bootstrap/wp-bootstrap-navwalker)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/wp-bootstrap/wp-bootstrap-navwalker/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/wp-bootstrap/wp-bootstrap-navwalker/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/wp-bootstrap/wp-bootstrap-navwalker/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/wp-bootstrap/wp-bootstrap-navwalker/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/wp-bootstrap/wp-bootstrap-navwalker/badges/build.png?b=master)](https://scrutinizer-ci.com/g/wp-bootstrap/wp-bootstrap-navwalker/build-status/master)

A custom WordPress nav walker class to fully implement the Bootstrap 3.0+ navigation style in a custom theme using the WordPress built in menu manager.

## NOTES

This is a utility class that is intended to format your WordPress theme menu with the correct syntax and classes to utilize the Bootstrap dropdown navigation, and does not include the required Bootstrap JS files. You will have to include them manually.

## Installation

Place **wp-bootstrap-navwalker.php** in your WordPress theme folder `/wp-content/your-theme/`

Open your WordPress themes **functions.php** file  `/wp-content/your-theme/functions.php` and add the following code:

```php
// Register Custom Navigation Walker
require_once('wp-bootstrap-navwalker.php');
```

## Usage

Update your `wp_nav_menu()` function in `header.php` to use the new walker by adding a "walker" item to the wp_nav_menu array.

```php
 <?php
            wp_nav_menu( array(
                'menu'              => 'primary',
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
```

Your menu will now be formatted with the correct syntax and classes to implement Bootstrap dropdown navigation.

You will also want to declare your new menu in your `functions.php` file.

```php
register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'THEMENAME' ),
) );
```

Typically the menu is wrapped with additional markup, here is an example of a ` navbar-fixed-top` menu that collapse for responsive navigation.

```php
<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo home_url(); ?>">
                <?php bloginfo('name'); ?>
            </a>
    </div>

        <?php
            wp_nav_menu( array(
                'menu'              => 'primary',
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

This script included the ability to add Bootstrap dividers, dropdown headers, glyphicons and disables links to your menus through the WordPress menu UI.

### Dividers

Simply add a Link menu item with a **URL** of `#` and a **Link Text** or **Title Attribute** of `divider` (case-insensitive so ‘divider’ or ‘Divider’ will both work ) and the class will do the rest.

### Glyphicons

To add an Icon to your link simple place the Glyphicon class name in the links **Title Attribute** field and the class will do the rest. IE `glyphicon-bullhorn`

### Dropdown Headers

Adding a dropdown header is very similar, add a new link with a **URL** of `#` and a **Title Attribute** of `dropdown-header` (it matches the Bootstrap CSS class so it's easy to remember).  set the **Navigation Label** to your header text and the class will do the rest.

### Disabled Links

To set a disabled link simply set the **Title Attribute** to `disabled` and the class will do the rest.

### Changelog

Please see the [Changelog](https://github.com/wp-bootstrap/wp-bootstrap-navwalker/blob/master/CHANGELOG.md).
