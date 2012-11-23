wp-bootstrap-navwalker
======================

A custom Wordpress nav walker class to implement the Twitter Bootstrap 2 (https://github.com/twitter/bootstrap/) navigation style in your custom theme using the Wordpress built in menu manager.

NOTE
----
This is a utility class is intended to format your Wordpress theme menu with the correct syntax and classes to utilize the Twitter Bootstrap 2 dropdown navigation, and does not include the dependant Bootstrap JS files You will have to install include them manually. 

Installation
------------
Place **twitter_bootstrap_nav_walker.php** in your Wordpress theme folder `/wp-content/your-theme/`

Open your Wordpress themes **functions.php** file  `/wp-content/your-theme/functions.php` and add the following code:

```php
// Register Custom Navigation Walker
require_once('twitter_bootstrap_nav_walker.php');
```

Useage
------------
Update your `wp_nav_menu()` function to use our new walker by adding a "walker" item to the wp_nav_menu array.

```php
<?php 
	wp_nav_menu( array(
		'menu'		 => 'top_menu',
		'depth'		 => 2,
		'container'	 => false,
		'menu_class' => 'nav',
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
		//Process nav menu using our custom nav walker
		'walker' => new twitter_bootstrap_nav_walker())
	);
?>
```
Review options in the Bootstrap docs for more information on nav classes
http://twitter.github.com/bootstrap/components.html#navs

To add a dropdown menu divider simply add a menu item with the label `divider` (case-Insensitive so ‘divider’ or ‘Divider’ will both work ) and the class will do the rest. 

Changelog
------------
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