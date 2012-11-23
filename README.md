wp-bootstrap-navwalker
======================

A custom Wordpress nav walker class library to implement the Twitter Bootstrap 2 (https://github.com/twitter/bootstrap/) navigation style using the Wordpress built in menu manager.

NOTE
----
This is a utility class is intended to format your Wordpress theme menu with the correct syntax and classes to utilize the Twitter Bootstrap 2 dropdown navigation, and does not include the dependant Bootstrap JS files You will have to install include them manually. 

Installation
------------
Place **twitter_bootstrap_navbar_walker.php** in your Wordpress theme folder `/wp-content/your-theme/`

Open your Wordpress themes **functions.php** file  `/wp-content/your-theme/functions.php` and add the following code:

```php
// Register Custom Navigation Walker
require_once('twitter_bootstrap_navbar_walker.php');
```

Update your `wp_nav_menu()` function to use our new walker by adding a "walker" item to the wp_nav_menu array.

**Example:**
```php
<?php 
	wp_nav_menu( array(
		'menu' => 'top_menu',
		'depth'		 => 2,
		'container'	 => false,
		'menu_class'	 => 'nav',
		//Process nav menu using our custom nav walker
		'walker' => new twitter_bootstrap_navbar_walker())
	);
?>
```

Your menu will now be formatted with the correct syntax and classes to implement Twitter Bootstrap 2 dropdown navigation. 

Changelog
------------

**twitter_bootstrap_navbar_walker 1.1:**
+ Changed filename from `twitter_bootstrap_nav_walker.php` to `twitter_bootstrap_navbar_walker.php` to avoid when adding new classes to this library
+ Added WordPress Core refrence comments to help understand what functions are overridden
+ Fixed double class declaration on dropdown parents
+ Moved dropdown class declaration from a to parent li