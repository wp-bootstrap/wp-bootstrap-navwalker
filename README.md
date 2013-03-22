wp-bootstrap-navwalker
======================

A custom WordPress nav walker class to implement the Twitter Bootstrap 2.2 (https://github.com/twitter/bootstrap/) navigation style in a custom theme using the WordPress built in menu manager.

NOTE
----
This is a utility class that is intended to format your WordPress theme menu with the correct syntax and classes to utilize the Twitter Bootstrap 2 dropdown navigation, and does not include the required Bootstrap JS files. You will have to include them manually. 

Installation
------------
Place **twitter_bootstrap_nav_walker.php** in your WordPress theme folder `/wp-content/your-theme/`

Open your WordPress themes **functions.php** file  `/wp-content/your-theme/functions.php` and add the following code:

```php
// Register Custom Navigation Walker
require_once('twitter_bootstrap_nav_walker.php');
```

Usage
------------
Update your `wp_nav_menu()` function to use the new walker by adding a "walker" item to the wp_nav_menu array.

```php
<?php 
	wp_nav_menu( array(
		'menu'		 => 'top_menu',
		'depth'		 => 2,
		'container'	 => false,
		'menu_class' => 'nav',
		'fallback_cb' => 'wp_page_menu',
		//Process nav menu using our custom nav walker
		'walker' => new twitter_bootstrap_nav_walker())
	);
?>
```

Your menu will now be formatted with the correct syntax and classes to implement Twitter Bootstrap 2 dropdown navigation. 

To change your menu style add Bootstrap nav class names to the `menu_class` declaration.

```php
<?php 
	wp_nav_menu( array(
		'menu'		 => 'side_menu',
		'depth'		 => 1,
		'container'	 => false,
		'menu_class' => 'nav nav-tabs nav-stacked',
		'fallback_cb' => 'wp_page_menu',
		//Process nav menu using our custom nav walker
		'walker' => new twitter_bootstrap_nav_walker())
	);
?>
```
Review options in the Bootstrap docs for more information on nav classes
http://twitter.github.com/bootstrap/components.html#navs

To add a dropdown menu divider simply add a menu item with the label `divider` (case-insensitive so ‘divider’ or ‘Divider’ will both work ) and the class will do the rest. 

Changelog
------------
**1.2.2:**
+ Fixed double `</li>` issue on divider rows (thanks to @wzub for submitting the issue)

**1.2.1:**
+ Updated caret output logic for Bootstrap 2.2 CSS changes.

**1.2:**
+ Updated the class to work for all Bootstrap menu types. nav-tabs, nav-pills, nav-stacked, nav-list, navbar and dropdowns are all supported.
+ Added ability to add dividers to dropdown menus by adding a menu item with the label of “divider” (case-insensitive) 
+ Added support for multiple dropdown levels (thanks to a Pull Requests from @jmz)
+ Moved dropdown-toggle class declaration from parent `<li>` to `<a>` to match Bootstraps official documentation

**1.1:**
+ Added WordPress Core refrence comments to help understand what functions are overridden
+ Fixed double class declaration on dropdown parents
+ Moved dropdown class declaration from a to parent li

**1.0:**
+ Initial Commit 

Todo
------------
+ Integrate bootstrap accessibility declarations
+ Build demo & tutorial page
+ Build custome widgets/shortcodes for affix menus and page subnav
