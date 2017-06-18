#CHANGELOG

## [3.0.2]

- Remove `<span class="carat">` after parent dropdown items
- Support `echo` arg in fallback menu.
	- credit: @toddlevy
- Add `.active` to parent when a child is current page.
	- credit: @zyberspace

## [3.0.1]

- Fix to correct output of dropdown atts and styles when depth passed to wp_nav_menu is <= 1
	- credit: @chrisgeary92
- Move icon output to a local var instead of modifying and clearing a global object.
- Reassign filtered classes back to $classes array so that updated classes can be accessed later if needed.
	- credit: @lf-jeremy

## [3.0.0]

- Update to work with Bootstrap v4.
	- Added `.nav-item` and `.nav-link` to `<li>` and `<a>` respectively.
- Dropped support for using title attribute to add link modifiers and icons.
- Added support for link modifiers and icons through WP Menu Builder 'classes' input.
	- Currently only 'disabled' link modifier is available.
	- Icon support is for Font-Awesome and Glyphicons icons.

## [2.0.5] - 2016-011-15

- Fixed all reported issues by WP Enforcer.
- Fixed several Code Climate issues.

## [2.0.4]

- Updated fallback function to accept args array from `wp_nav_menu`.

## [2.0.3]

- Included a fallback function that adds a link to the WordPress menu manager if no menu has been assigned to the theme location.

## [2.0.2]

- Small tweak to ensure carets are only displayed on top level dropdowns.

## [2.0.1]

- Added missing active class to active menu items.

## [2.0.0]

- Class was completly re-written using the latest Wordpress 3.6 walker class.
- Now full supports Bootstrap 3.0+
- Tested with wp_debug & the Theme Check plugin.


---
<small>All notable changes to this project will be documented in this file. Please read [Keep a Change Log](http://keepachangelog.com) for more information. This project adheres to [Semantic Versioning](http://semver.org).</small>
