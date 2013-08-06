wp-bootstrap-navwalker
======================

A custom WordPress nav walker class to implement the Twitter Bootstrap 2.3.2 (and now 3.0 RC1) (https://github.com/twitter/bootstrap/) navigation style in a custom theme using the WordPress built in menu manager.

![Extras](http://edwardmcintyre.com/pub/github/navwalker-extras-two.jpg)

NOTE
----
This is a utility class that is intended to format your WordPress theme menu with the correct syntax and classes to utilize the Twitter Bootstrap dropdown navigation, and does not include the required Bootstrap JS files. You will have to include them manually. 

Installation
------------
Place **wp_bootstrap_navwalker.php** in your WordPress theme folder `/wp-content/your-theme/`

Open your WordPress themes **functions.php** file  `/wp-content/your-theme/functions.php` and add the following code:

```php
// Register Custom Navigation Walker
require_once('wp_bootstrap_navwalker.php');
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
		'walker' => new wp_bootstrap_navwalker())
	);
?>
```

Your menu will now be formatted with the correct syntax and classes to implement Twitter Bootstrap dropdown navigation. 

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
		'walker' => new wp_bootstrap_navwalker())
	);
?>
```

Boostrap 3 Navbar uses `nav navbar-nav`.

```php
<?php 
	wp_nav_menu( array(
		'menu'		 => 'top_menu',
		'depth'		 => 1,
		'container'	 => false,
		'menu_class' => 'nav navbar-nav',
		'fallback_cb' => 'wp_page_menu',
		//Process nav menu using our custom nav walker
		'walker' => new wp_bootstrap_navwalker())
	);
?>
```

Review options in the Bootstrap docs for more information on nav classes
http://twitter.github.com/bootstrap/components.html#navs

Extras
------------

![Extras](http://edwardmcintyre.com/pub/github/navwalker-extras-two.jpg)

This script included the ability to add Bootstrap dividers and Nav Headers to your menus through the WordPress menu UI. 

######Icons
To add an Icon to your link simple place the full Glyphicon class name in the links **Title Attribute** field and the class will do the rest.
* glyphicons needs to be incuded seporately now

######Dividers
Simply add a Link menu item with a **URL** of `#` and a **Link Text** of `divider` (case-insensitive so ‘divider’ or ‘Divider’ will both work ) and the class will do the rest.

![Divider Example](http://edwardmcintyre.com/pub/github/navwalker-divider.jpg)

You can also add a vertical divider by adding a Link menu item with a **URL** of `#` and a **Link Text** of `divider-vertical`

######Navigation Headers
Adding a navigation header is very similar, add a new link with a **URL** of `#` and a **Link Text** of `nav-header` (it matches the Bootstrap CSS class so it's easy to remember). When the item is added use the **Title Attribute** field to set your header text and the class will do the rest. Boostrap 3 uses a new class name `dropdown-header`. 

Bootstrap 3 uses a new classname `dropdown-headers` for the headers. Currently you can use either or but backwards compatibility will be removed once Bootstrap 3 officially launches.

![Header Example](http://edwardmcintyre.com/pub/github/navwalker-header.jpg)

Changelog
------------
**1.4.3:**
+ Added support for vertical dividers (Thanks to @pattonwebz for the suggestion)

**1.4.2:**
+ Removed redundant code from display_element by using function from parent class (Thanks to @sebakerckhof for the suggestion)

**1.4.1:**
+ Updated class & file names from twitter_bootstrap_nav_walker to wp_bootstrap_navwalker match repository.
+ Licence now GPL-2.0+ to match WordPress.
+ Added a copy of the GPL-2.0+ Licence.
+ Added additional code comments to explain how the extras are processed.

**1.4:**
+ Added support Glyphicons

**1.3:**
+ Added support for nav-header's (Thanks to @nerdworker for the suggestion)

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
